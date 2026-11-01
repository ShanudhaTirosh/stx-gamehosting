<?php

namespace BrandedHosting\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use BrandedHosting\Platform\Services\KnowledgeBaseService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    public function __construct(
        protected KnowledgeBaseService $kbService
    ) {}

    /**
     * Knowledge Base Index & Search.
     */
    public function index(Request $request): View
    {
        $categories = $this->kbService->getCategories();
        $query = $request->query('q', '');
        $searchResults = !empty($query) ? $this->kbService->search($query) : [];

        return view('hosting-platform::storefront.knowledgebase.index', [
            'categories' => $categories,
            'query' => $query,
            'searchResults' => $searchResults,
        ]);
    }

    /**
     * Category view.
     */
    public function category(string $slug): View
    {
        $categories = $this->kbService->getCategories();
        if (!isset($categories[$slug])) {
            abort(404, 'Category not found.');
        }

        return view('hosting-platform::storefront.knowledgebase.category', [
            'category' => $categories[$slug],
            'categorySlug' => $slug,
            'allCategories' => $categories,
        ]);
    }

    /**
     * Show a specific knowledge base article.
     */
    public function article(string $categorySlug, string $articleSlug): View
    {
        $article = $this->kbService->getArticle($categorySlug, $articleSlug);
        if (!$article) {
            abort(404, 'Article not found.');
        }

        $allCategories = $this->kbService->getCategories();

        return view('hosting-platform::storefront.knowledgebase.article', [
            'article' => $article,
            'category' => $allCategories[$categorySlug] ?? null,
            'allCategories' => $allCategories,
        ]);
    }

    /**
     * JSON API search for live autocomplete.
     */
    public function apiSearch(Request $request): JsonResponse
    {
        $query = (string) $request->query('q', '');
        $results = $this->kbService->search($query);

        return response()->json([
            'query' => $query,
            'count' => count($results),
            'results' => $results,
        ]);
    }
}
