<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class PostsExport extends Command
{
    protected $signature = 'posts:export
        {--path= : Chemin du Json (exemple : storage/export/posts.json)}
        {--published-only : N’exporter que les posts publiés}
        {--since= : Exporter les posts créés depuis YYYY-MM-DD}
        {--limit= : limiter le nombre de posts}
        {--columns= : id, slug, title, body, published, created_at, updated_at)}';

    protected $description = 'Exporter les posts au format JSON (filtres et colonnes configurables)';

    public function handle(): int
    {
        $path = $this->option('path') ?: ('export/posts-' . now()->format('Ymd_His') . '.json');
        $cols = $this->option('columns')
            ? array_map('trim', explode(',', $this->option('columns')))
            : ['id', 'slug', 'title', 'body', 'published', 'created_at', 'updated_at'];
        $q = Post::query()->orderBy('id');

        if ($this->option('published-only')) {
            $q->where('published', true);
        }

        if ($since = $this->option('since')) {
            try {
                $dt = Carbon::parse($since)->startOfDay();
                $q->where('created_at', '>=', $dt);
            } catch (\Throwable $e) {
                $this->error("Date invalide pour --since: {$since}");
                return self::INVALID;
            }
        }
        if ($limit = $this->option('limit')) {
            $q->limit((int)$limit);
        }

        $rows = $q->get($cols)
            ->map(function (Post $p) use ($cols) {
            $row = $p->only($cols);

            if (array_key_exists('published', $row)) {
                $row['published'] = (bool) $row['published'];
            }

            foreach (['created_at', 'updated_at'] as $key) {
                if (array_key_exists($key, $row) && !is_null($row[$key])){
                    $row[$key] = ($row[$key] instanceof Carbon)
                        ? $row[$key]->toIso8601String()
                        : Carbon::parse($row[$key])->toIso8601String();
                }
            }

            return $row;
            })
            ->values()
            ->all();

        try {
            Storage::disk('local')->put(
                $path,
                json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
            );
        } catch (\JsonException $e) {
            $this->error('Erreur JSON : ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info('Export OK : ' . storage_path("app/{$path}"));
        $this->line('Total : ' . count($rows) . 'post(s).');

        return self::SUCCESS;
    }
}
