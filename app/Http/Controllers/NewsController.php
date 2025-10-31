<?php

namespace App\Http\Controllers;

use App\Services\NewsService;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function fetchHeadlines()
    {
        $category = request('category', 'general');
        $language = request('language', 'en');
        $limit = request('limit', 3);

        try {
            $news = $this->newsService->get_top_headlines($category, $language, $limit);

            if ($news) {
                return response()->json([
                    'success' => true,
                    'news' => $news
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'News data not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch news data: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => false,
            'message' => 'News API temporarily disabled'
        ], 503);
    }
}
