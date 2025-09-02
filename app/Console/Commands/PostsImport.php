<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostsImport extends Command
{
    protected $signature = 'post:import
        {path : Chemin du JSON à importer (exemple : storage/exports/posts.json}
        {--update : mettre à jour si le slug existe déjà (sinon, saute)}
        {--dry-run : Simuler sans écrire}
        {--only: Colonnes CSV à prendre en compte (exemple : title,body, published, slug)}';

    protected $description = 'Importer des posts depuis un JSON (upsert par slug, dry-run possible';

    public function handle(): int
    {
        $pathArg = $this->argument('path');
        $only = $this->option('only')
            ? array_map('trim', explode(',', $this->option('only')))
            : null;
        $json = null;
        if (is_file($pathArg)) {
            $json = file_get_contents($pathArg);
            $displayPath = $pathArg;
        } else {
            if (!Storage::disk('local')->exists($pathArg)) {
                $this->error("Fichier introuvable : {pathArg}");
                return self::INVALID;
            }
            $json = Storage::disk('local')->get($pathArg);
            $displayPath = storage_path("app/{$pathArg}");
        }

        try {
            $rows = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
            if (!is_array($rows)) {
                $this->error('JSON invalide: racine non-tableau.');
                return self::INVALID;
            }
        } catch (\JsonException $e) {
            $this->error('Erreur JSON : ' . $e->getMessage());
            return self::INVALID;
        }

        $dryRun = (bool)$this->option('dry-run');
        $allowUpdate = (bool)$this->option('update');
        $created = $updated = $skipped = $errors = 0;

        foreach ($rows as $i => $row) {
            try {
                if (!is_array($row)) {
                    $this->line("skip #{$i} (entrée non-objet)");
                    $skipped++;
                    continue;
                }

                if ($only) {
                    $row = array_intersect_key($row, array_flip($only));
                }

                $title = trim((string)($row['title'] ?? ''));
                $body = array_key_exists('body', $row) ? (string)($row['body'] ?? '') : null;
                $published = array_key_exists('published', $row) && (bool)$row['published'];

                $slug = isset($row['slug']) && $row['slug'] !== ''
                    ? Str::slug((string)$row['slug'])
                    : Str::slug($title);

                if ($slug === '') {
                    $slug = 'post';
                }

                $base = $slug;
                $j = 1;
                while (Post::where('slug', $slug)->exists() &&
                    !($allowUpdate && Post::where('slug', $slug)->exists())) {
                    $slug = $base . '-' . ($j++);
                }
                $existing = Post::where('slug', $slug)->first();

                $data = [
                    'title' => $title !== '' ? $title : 'Sans titre',
                    'body' => $body,
                    'published' => $published,
                    'slug' => $slug,
                ];

                if ($existing) {
                    if ($allowUpdate) {
                        if ($dryRun) {
                            $this->line("[dry-run] update {$slug}");
                            $updated++;
                        } else {
                            $existing->update($data);
                            $this->line("update {$slug}");
                            $updated++;
                        }
                    } else {
                        $this->line("skip {$slug} (existe déjà)");
                        $skipped++;
                    }
                } else {
                    if ($dryRun) {
                        $this->line("[dry-run] create {$slug}");
                        $created++;
                    } else {
                        Post::create($data);
                        $this->line("create {$slug}");
                        $created++;
                    }
                }
            } catch (\Throwable $e) {
                $this->error("Erreur ligne #{$i}: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("Import terminé depuis : {$displayPath}");
        $this->line("Créés: {$created} | Mis à jour: {$updated} | Ignorés: {$skipped} | Erreurs: {$errors}");

        return $errors ? self::FAILURE : self::SUCCESS;
    }
}
