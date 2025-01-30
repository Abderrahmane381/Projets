<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy', 'rate']);
    }

    public function index()
    {
        $query = Article::with(['author', 'theme']);

        if (auth()->check() && auth()->user()->hasAnyRole(['editor', 'theme_responsible'])) {
            // Show all articles except drafts for editors and theme responsibles
            $query->where('is_active', true)
                  ->whereNotIn('status', ['draft']);
        } else {
            // Show only published articles for others
            $query->where('status', 'published')
                  ->where('is_active', true);
        }

        $articles = $query->latest()->paginate(12);

        return view('articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        // Allow viewing if:
        // 1. Article is published and active
        // 2. User is logged in and is the author
        // 3. User is an editor
        // 4. User is the theme responsible for this article's theme
        if (!($article->status === 'published' && $article->is_active) && 
            !(auth()->check() && (
                auth()->id() === $article->author_id ||
                auth()->user()->hasRole('editor') ||
                (auth()->user()->hasRole('theme_responsible') && 
                 auth()->id() === $article->theme->responsible_id)
            ))
        ) {
            abort(404);
        }

        if (auth()->check()) {
            auth()->user()->browsingHistory()->updateOrCreate(
                ['article_id' => $article->id],
                ['last_visited_at' => now()]
            )->increment('visit_count');
        }

        $userRating = auth()->check() ? 
            $article->ratings()->where('user_id', auth()->id())->value('rating') : 
            null;

        $recommendations = auth()->check() ? 
            auth()->user()->getRecommendedArticles() : 
            Article::where('theme_id', $article->theme_id)
                ->where('id', '!=', $article->id)
                ->where('status', 'published')
                ->where('is_active', true)
                ->take(3)
                ->get();

        $article->load(['comments.user' => function ($query) {
            $query->select('id', 'name');
        }]);

        return view('articles.show', compact('article', 'userRating', 'recommendations'));
    }

    public function create(Request $request)
    {
        $themes = Theme::where('is_active', true)->get();
        $selectedTheme = $request->get('theme');

        return view('articles.create', compact('themes', 'selectedTheme'));
    }

    public function store(Request $request)
    {
        try {
            if (!auth()->check()) {
                throw new \Exception('You must be logged in to create articles.');
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'theme_id' => 'required|exists:themes,id'
            ]);

            $article = Article::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'theme_id' => $validated['theme_id'],
                'author_id' => auth()->id(),
                'status' => Article::STATUS_PENDING_REVIEW,
                'is_active' => true
            ]);

            return redirect()->route('articles.show', $article)
                ->with('success', 'Article created successfully');

        } catch (\Exception $e) {
            Log::error('Article creation failed: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to create article: ' . $e->getMessage());
        }
    }

    public function edit(Article $article)
    {
        if (!(auth()->id() === $article->author_id || 
            auth()->user()->hasRole('editor') ||
            (auth()->user()->hasRole('theme_responsible') && 
             auth()->id() === $article->theme->responsible_id)
        )) {
            abort(403);
        }

        $themes = Theme::where('is_active', true)->get();
        return view('articles.edit', compact('article', 'themes'));
    }

    public function update(Request $request, Article $article)
    {
        if (!(auth()->id() === $article->author_id || 
            auth()->user()->hasRole('editor') ||
            (auth()->user()->hasRole('theme_responsible') && 
             auth()->id() === $article->theme->responsible_id)
        )) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'theme_id' => 'required|exists:themes,id'
        ]);

        $article->update($validated);

        return redirect()->route('articles.show', $article)
            ->with('success', 'Article updated successfully');
    }

    public function rate(Request $request, Article $article)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5'
        ]);

        $article->ratings()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['rating' => $validated['rating']]
        );

        return back()->with('success', 'Rating submitted successfully');
    }

    public function destroy(Article $article)
    {
        if (!(auth()->id() === $article->author_id || 
            auth()->user()->hasRole('editor') ||
            (auth()->user()->hasRole('theme_responsible') && 
             auth()->id() === $article->theme->responsible_id)
        )) {
            abort(403);
        }

        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Article deleted successfully');
    }
    public function myArticles()
    {
    $user = auth()->user(); // Récupérer l'utilisateur connecté

    $articles = $user->articles() // Récupérer les articles de l'abonné
        ->with(['theme']) // Charger le thème associé
        ->latest() // Trier du plus récent au plus ancien
        ->paginate(15); // Pagination

    return view('user.status', compact('articles'));
    }

}
