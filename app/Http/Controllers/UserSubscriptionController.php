<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\Article;
use App\Models\BrowsingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserSubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:subscriber']);
    }

    public function dashboard()
    {
        $user = auth()->user();
        $subscribedThemes = $user->themeSubscriptions()
            ->with(['articles' => function($query) {
                $query->latest()->take(5);
            }])
            ->get();

        // Fetch browsing history with related articles
        $recentlyViewed = $user->browsingHistory()
        ->with('article.theme') // Eager load article and its theme
        ->latest('last_visited_at') // Order by last visited date
        ->take(5) // Limit to 5 most recent entries
        ->get();
        $stats = [
            'subscriptions' => $user->themeSubscriptions()->count(),
            'articles_read' => $user->viewedArticles()->count(),
            'comments_made' => $user->comments()->count(),
            'ratings_given' => $user->ratings()->count(),
        ];

        return view('user.dashboard', compact('subscribedThemes', 'recentlyViewed', 'stats'));
    }

    public function index()
    {
        $subscriptions = auth()->user()->themeSubscriptions()
            ->with(['responsible'])
            ->withCount(['articles' => function($query) {
                $query->where('status', 'published')
                      ->where('is_active', true);
            }])
            ->get();

        $recommendedThemes = Theme::whereNotIn('id', auth()->user()->themeSubscriptions->pluck('id'))
            ->where('is_active', true)
            ->withCount(['articles' => function($query) {
                $query->where('status', 'published')
                      ->where('is_active', true);
            }])
            ->take(4)
            ->get();

        return view('user.subscriptions', compact('subscriptions', 'recommendedThemes'));
    }

    public function history(Request $request)
    {
        $query = auth()->user()->browsingHistory()
            ->with(['article.theme', 'article.author'])
            ->latest('last_visited_at');

        if ($request->has('theme')) {
            $query->whereHas('article', function($q) use ($request) {
                $q->where('theme_id', $request->theme);
            });
        }

        if ($request->has('date')) {
            $query->whereDate('last_visited_at', $request->date);
        }

        $history = $query->paginate(20);
        $themes = Theme::pluck('name', 'id');

        return view('user.history', compact('history', 'themes'));
    }

    public function stats()
    {
        $readingStats = BrowsingHistory::where('user_id', auth()->id())
            ->join('articles', 'browsing_history.article_id', '=', 'articles.id')
            ->join('themes', 'articles.theme_id', '=', 'themes.id')
            ->select('themes.name', DB::raw('COUNT(*) as views'))
            ->groupBy('themes.id', 'themes.name')
            ->get();

        $ratings = auth()->user()->ratings()
            ->with('article.theme')
            ->latest()
            ->take(10)
            ->get();

        $favoriteThemes = auth()->user()->themeSubscriptions()
            ->withCount(['articles' => function($query) {
                $query->whereHas('browsingHistory', function($q) {
                    $q->where('user_id', auth()->id());
                });
            }])
            ->orderByDesc('articles_count')
            ->take(5)
            ->get();

        $user = auth()->user();
        
        $monthlyStats = $user->viewedArticles()
            ->selectRaw('YEAR(article_user.created_at) as year, MONTH(article_user.created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $themeStats = $user->viewedArticles()
            ->join('themes', 'articles.theme_id', '=', 'themes.id')
            ->selectRaw('themes.name, COUNT(*) as count')
            ->groupBy('themes.id', 'themes.name')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        return view('user.stats', compact('readingStats', 'ratings', 'favoriteThemes', 'monthlyStats', 'themeStats'));
    }

    public function toggleSubscription(Theme $theme)
    {
        if (auth()->user()->themeSubscriptions->contains($theme)) {
            auth()->user()->themeSubscriptions()->detach($theme);
            $message = 'Successfully unsubscribed from theme';
        } else {
            auth()->user()->themeSubscriptions()->attach($theme);
            $message = 'Successfully subscribed to theme';
        }

        return back()->with('success', $message);
    }
   
}
