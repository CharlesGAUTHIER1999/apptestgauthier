<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostsImport extends Command
{
    // Definition of the artisan command and its options
    protected $signature = 'post:import
        {path : Path to the JSON file to import (example: storage/exports/posts.json}
        {--update : Update if the slug already exists (otherwise, skip)}
        {--dry-run : Simulate without writing to the database}
        {--only: Columns to take into account (example: title,body,published,slug)}';

    // Description shown in `php artisan list`
    protected $description = 'Import posts from a JSON file (upsert by slug, dry-run supported)';

    public function handle(): int
    {
        // Get the "path" argument (JSON file path)
        $pathArg = $this->argument('path');

        // Columns to take into account, only if the --only option is provided
        $only = $this->option('only')
            ? array_map('trim', explode(',', $this->option('only')))
            : null;

        // Check if the path is a real system file
        if (is_file($pathArg)) {
            $json = file_get_contents($pathArg); // direct read
            $displayPath = $pathArg;
        } else {
            // Otherwise, look in Laravel's "local" storage disk
            if (!Storage::disk('local')->exists($pathArg)) {
                $this->error("File not found: {pathArg}");
                return self::INVALID;
            }
            $json = Storage::disk('local')->get($pathArg);
            $displayPath = storage_path("app/$pathArg");
        }

        // Decode the JSON
        try {
            $rows = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

            // Check that the JSON root is actually an array
            if (!is_array($rows)) {
                $this->error('Invalid JSON: root is not an array.');
                return self::INVALID;
            }
        } catch (\JsonException $e) {
            $this->error('JSON error: ' . $e->getMessage());
            return self::INVALID;
        }

        // Boolean options
        $dryRun = (bool)$this->option('dry-run'); // if true, only simulate
        $allowUpdate = (bool)$this->option('update'); // if true, update existing slugs

        // Counters for the summary
        $created = $updated = $skipped = $errors = 0;

        // Iterate over JSON entries
        foreach ($rows as $i => $row) {
            try {
                // If the entry is not an array → skip it
                if (!is_array($row)) {
                    $this->line("skip #$i (non-object entry)");
                    $skipped++;
                    continue;
                }

                // If --only is defined → keep only the requested columns
                if ($only) {
                    $row = array_intersect_key($row, array_flip($only));
                }

                // Normalize fields
                $title = trim((string)($row['title'] ?? ''));
                $body = array_key_exists('body', $row) ? (string)($row['body'] ?? '') : null;
                $published = array_key_exists('published', $row) && $row['published'];

                // Generate the slug: either provided or based on the title
                $slug = isset($row['slug']) && $row['slug'] !== ''
                    ? Str::slug((string)$row['slug'])
                    : Str::slug($title);

                // If the slug is empty → fallback value
                if ($slug === '') {
                    $slug = 'post';
                }

                // Handle slug duplicates (slug-1, slug-2, etc.)
                $base = $slug;
                $j = 1;
                while (Post::where('slug', $slug)->exists() &&
                    !($allowUpdate && Post::where('slug', $slug)->exists())) {
                    $slug = $base . '-' . ($j++);
                }

                // Check if a post already exists with this slug
                $existing = Post::where('slug', $slug)->first();

                // Normalized data for insert/update
                $data = [
                    'title' => $title !== '' ? $title : 'Untitled',
                    'body' => $body,
                    'published' => $published,
                    'slug' => $slug,
                ];

                // Case: post already exists
                if ($existing) {
                    if ($allowUpdate) { // update allowed
                        if ($dryRun) { // simulation only
                            $this->line("[dry-run] update $slug");
                        } else { // actual update
                            $existing->update($data);
                            $this->line("update $slug");
                        }
                        $updated++;
                    } else {
                        // Otherwise, skip the post
                        $this->line("skip $slug (already exists)");
                        $skipped++;
                    }
                } else {
                    // Case: post does not exist yet
                    if ($dryRun) {
                        $this->line("[dry-run] create $slug");
                    } else {
                        Post::create($data); // insert new post
                        $this->line("create $slug");
                    }
                    $created++;
                }
            } catch (\Throwable $e) {
                // On error for a given row
                $this->error("Error line #$i: " . $e->getMessage());
                $errors++;
            }
        }

        // Import summary
        $this->info("Import finished from: $displayPath");
        $this->line("Created: $created | Updated: $updated | Skipped: $skipped | Errors: $errors");

        // Return success or failure depending on errors
        return $errors ? self::FAILURE : self::SUCCESS;
    }
}
