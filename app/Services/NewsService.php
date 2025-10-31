<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NewsService
{
    protected $key;
    protected $url;

    public function __construct()
    {
        $this->key = config('services.news.key');
        $this->url = config('services.news.URL');
    }

    public function get_top_headlines(string $category = 'general', string $lang = 'en', int $max = 3)
    {
        $response = Http::get(
            "{$this->url}top-headlines",
            [
                'category' => $category,
                'lang' => $lang,
                'max' => $max,
                'apikey' => $this->key,
            ]
        );

        if ($response->successful()) {
            $articles = $response->json('articles', []);

            return array_map(function ($article) {
                return [
                    'title' => $article['title'] ?? null,
                    'description' => $article['description'] ?? null,
                    'url' => $article['url'] ?? null,
                    'image' => $article['image'] ?? null,
                    'published_at' => $article['publishedAt'] ?? null,
                    'source' => $article['source']['name'] ?? 'Unknown',
                ];
            }, $articles);
        }

        return [];
    }
}
