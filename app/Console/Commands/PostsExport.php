<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class PostsExport extends Command
{
    // Def of artisan command and possible options
    protected $signature = 'posts:export
        {--path= : Chemin du Json (exemple : storage/export/posts.json)}
        {--published-only : N’exporter que les posts publiés}
        {--since= : Exporter les posts créés depuis YYYY-MM-DD}
        {--limit= : limiter le nombre de posts}
        {--columns= : id, slug, title, body, published, created_at, updated_at}';

    // Description in php artisan list
    protected $description = 'Exporter les posts au format JSON (filtres et colonnes configurables)';

    public function handle(): int
    {
        // exit way : option or default with timestamp
        $path = $this->option('path') ?: ('export/posts-' . now()->format('Ymd_His') . '.json');

        // columns to export
        $cols = $this->option('columns')
            ? array_map('trim', explode(',', $this->option('columns')))
            : ['id', 'slug', 'title', 'body', 'published', 'created_at', 'updated_at'];

        // beginning of the request
        $q = Post::query()->orderBy('id');

        // if option published-only true, filter only by published posts
        if ($this->option('published-only')) {
            $q->where('published', true);
        }

        // If the --since option is given → filter based on the date provided
        if ($since = $this->option('since')) {
            try {
                $dt = Carbon::parse($since)->startOfDay();
                $q->where('created_at', '>=', $dt);
            } catch (\Throwable) {
                // If the date is invalid → error message and stop
                $this->error("Date invalide pour --since: $since");
                return self::INVALID;
            }
        }

        // If the --limit option is given → the number of results is limited
        if ($limit = $this->option('limit')) {
            $q->limit((int)$limit);
        }

        // Retrieving results with selected columns
        $rows = $q->get($cols)
            ->map(function (Post $p) use ($cols) {
            // required columns retrieved
            $row = $p->only($cols);

            // Force the “published” field to a Boolean if present
            if (array_key_exists('published', $row)) {
                $row['published'] = (bool) $row['published'];
            }

            // Convert dates to ISO8601 format if present
            foreach (['created_at', 'updated_at'] as $key) {
                if (array_key_exists($key, $row) && !is_null($row[$key])){
                    $row[$key] = ($row[$key] instanceof Carbon)
                        ? $row[$key]->toIso8601String()
                        : Carbon::parse($row[$key])->toIso8601String();
                }
            }

            return $row; // returns a clean array of a post
            })
            ->values() // reindex the keys
            ->all(); // array returned

        // Attempt to write to a JSON file
        try {
            Storage::disk('local')->put(
                $path,
                json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
            );
        } catch (\JsonException $e) {
            // If JSON encoding problem → error
            $this->error('Erreur JSON : ' . $e->getMessage());
            return self::FAILURE;
        }

        // success message with the file path and number of exported posts
        $this->info('Export OK : ' . storage_path("app/$path"));
        $this->line('Total : ' . count($rows) . ' post(s).');

        return self::SUCCESS;
        // ending of the request with status = success
    }
}
