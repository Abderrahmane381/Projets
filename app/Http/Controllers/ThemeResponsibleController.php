<?php

namespace App\Http\Controllers;


use App\Models\Theme;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ThemeResponsibleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:theme_responsible']);
    }

    public function dashboard()
    {
        $themes = auth()->user()->responsibleThemes()
            ->withCount(['subscribers', 'articles'])
            ->get();

        $themeIds = $themes->pluck('id');

        $stats = [
            'total_subscribers' => $themes->sum('subscribers_count'),
            'total_articles' => $themes->sum('articles_count'),
            'themes_count' => $themes->count(),
            'pending_articles' => Article::whereIn('theme_id', $themeIds)
                ->where('status', 'pending_review')
                ->count()
        ];

        $pending_reviews = Article::whereIn('theme_id', $themeIds)
            ->where('status', 'pending_review')
            ->count();

        $pendingArticles = Article::whereIn('theme_id', $themeIds)
            ->where('status', 'pending_review')
            ->with(['author', 'theme'])
            ->get();

        return view('theme-responsible.dashboard', compact('themes', 'stats', 'pending_reviews', 'pendingArticles'));
    }

    public function manageArticles(Theme $theme)
    {
        if (! auth()->user()->canModerateTheme($theme)) {
            abort(403, 'You are not authorized to manage this theme.');
        }

        $articles = $theme->articles()
            ->with(['author'])
            ->latest()
            ->paginate(15);

        return view('theme-responsible.articles.index', compact('theme', 'articles'));
    }

    public function reviewArticle(Article $article)
    {
        

        return view('theme-responsible.articles.review', compact('article'));
    }

    public function updateArticleStatus(Request $request, Article $article)
    {
       

        $validated = $request->validate([
            'status' => 'required|in:published,rejected',
            'feedback' => 'required|string|min:10'
        ]);

        $article->update([
            'status' => $validated['status'],
            'feedback' => $validated['feedback']
        ]);

        return redirect()->route('theme-responsible.articles.index', $article->theme)
            ->with('success', 'Article review completed successfully');
    }

    public function moderateComments(Theme $theme)
    {
        if (! auth()->user()->canModerateTheme($theme)) {
            abort(403, 'You are not authorized to moderate comments for this theme.');
        }

        $comments = Comment::whereHas('article', function($query) use ($theme) {
            $query->where('theme_id', $theme->id);
        })->with(['user', 'article'])
          ->latest()
          ->paginate(20);

        return view('theme-responsible.comments.moderate', compact('theme', 'comments'));
    }

    public function toggleCommentApproval(Comment $comment)
    {
        $this->authorize('moderate', $comment);

        $comment->update(['is_approved' => !$comment->is_approved]);

        return back()->with('success', 'Comment status updated successfully');
    }

    public function themeStatistics(Theme $theme)
    {
        if (!auth()->user()->canModerateTheme($theme)) {
            abort(403);
        }

        $statistics = [
            'articles' => [
                'total' => $theme->articles()->count(),
                'published' => $theme->articles()->where('status', 'published')->count(),
                'pending' => $theme->articles()->where('status', 'pending_review')->count()
            ],
            'subscribers' => $theme->subscribers()->count(),
            'comments' => Comment::whereHas('article', function($query) use ($theme) {
                $query->where('theme_id', $theme->id);
            })->count(),
            'average_rating' => DB::table('ratings')
                ->join('articles', 'ratings.article_id', '=', 'articles.id') // Fixed join syntax
                ->where('articles.theme_id', $theme->id)
                ->avg('ratings.rating') ?? 0,
            'monthly_data' => [
                'articles' => $theme->articles()
                    ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                    ->whereYear('created_at', date('Y'))
                    ->groupBy('month')
                    ->get()
            ]
        ];

        return view('theme-responsible.statistics', compact('theme', 'statistics'));
    }

    public function createArticle(Theme $theme)
    {
        if (! auth()->user()->canModerateTheme($theme)) {
            abort(403, 'You are not authorized to create articles for this theme.');
        }

        return view('theme-responsible.articles.create', compact('theme'));
    }
    public function updateStatus(Theme $theme, Article $article)
    {
        if ($article->theme_id !== $theme->id || ! auth()->user()->canModerateTheme($theme)) {
            abort(403, 'You are not authorized to manage this theme.');
        }

        $article->update([
            'status' => request('status')
        ]);

        return back()->with('success', 'Status updated successfully');
    }

    public function destroyArticle(Theme $theme, Article $article)
    {

        if ($article->theme_id !== $theme->id || ! auth()->user()->canModerateTheme($theme)) {
            abort(403, 'You are not authorized to manage this theme.');
        }

        $article->delete();
        return back()->with('success', 'Article deleted successfully');
    }

    public function storeArticle(Request $request, Theme $theme)
    {
        if (! auth()->user()->canModerateTheme($theme)) {
            abort(403, 'You are not authorized to create articles for this theme.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,pending_review'
        ]);

        $article = $theme->articles()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'author_id' => auth()->id()
        ]);

        return redirect()->route('theme-responsible.articles.index', $theme)
            ->with('success', 'Article created successfully');
    }

    public function deleteComment(Comment $comment)
    {
        // Check if user can delete this comment (must be from their theme)
        if (! auth()->user()->canModerateTheme($comment->article->theme)) {
            abort(403, 'You are not authorized to delete comments for this theme.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully');
    }
    public function index(Request $request, $theme = null)
    {
        dd($request->route('theme'));
        if ($request->query('theme') === 'all') {
            $articles = Article::all(); // Tous les articles
        }   else {
                $articles = Article::where('theme_id', $theme)->get();
            }

        return view('theme-responsible.articles.index', compact('articles'));
    }
    public function ViewSubscriptions(Theme $theme)
    {
        // Vérification manuelle de l'accès
        if ($theme->responsible_id !== auth()->id()) {
            abort(403, "Accès non autorisé à ce thème");
        }

        $subscriptions = $theme->subscribers()
                             ->with('roles')
                             ->get();

        return view('theme-responsible.subscriptions', compact('theme', 'subscriptions'));
    }

    public function updateRole(Theme $theme, User $user)
    {
        // Double vérification de sécurité
        if ($theme->responsible_id !== auth()->id()) {
            abort(403);
        }

        // Vérifier que l'utilisateur est bien abonné au thème
        if (!$theme->subscribers()->where('user_id', $user->id)->exists()) {
            abort(404, "Utilisateur non trouvé dans les abonnés");
        }

        $user->syncRoles([request('role')]);
        
        return back()->with('success', 'Rôle mis à jour');
    }

    public function destroy(Theme $theme, User $user)
    {
        if ($theme->responsible_id !== auth()->id()) {
            abort(403);
        }

        // Supprimer l'abonnement
        $theme->subscribers()->detach($user->id);
        
        return back()->with('success', 'Abonnement résilié');
    }
}



