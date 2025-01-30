<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Theme;
use App\Models\Article;
use App\Models\Issue;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:editor');
    }

    public function dashboard()
    {
        $stats = [
            'users' => [
                'total' => User::count(),
                'subscribers' => User::role('subscriber')->count(),
                'theme_responsibles' => User::role('theme_responsible')->count(),
                'editors' => User::role('editor')->count()
            ],
            'themes' => [
                'total' => Theme::count(),
                'active' => Theme::where('is_active', true)->count()
            ],
            'articles' => [
                'total' => Article::count(),
                'published' => Article::where('status', 'published')->count(),
                'pending' => Article::where('status', 'submitted')->count()
            ],
            'issues' => [
                'total' => Issue::count(),
                'public' => Issue::where('is_public', true)->count(),
                'active' => Issue::where('is_active', true)->count()
            ]
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $users = User::withCount(['articles', 'themeSubscriptions'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function themes()
    {
        $themes = Theme::withCount('articles')->paginate(15);
        return view('admin.themes.index', compact('themes'));
    }

    public function articles()
    {
        $articles = Article::with(['author', 'theme'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.articles.index', compact('articles'));
    }

    public function issues()
    {
        $issues = Issue::with(['theme', 'articles'])
            ->withCount('articles')
            ->orderBy('publication_date', 'desc')
            ->paginate(15);

        return view('admin.issues.index', compact('issues'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:guest,subscriber,theme_responsible,editor',
            'is_active' => 'boolean'
        ]);

        $user->syncRoles([$validated['role']]);
        $user->update(['is_active' => $validated['is_active'] ?? $user->is_active]);
        
        return back()->with('success', 'User updated successfully');
    }

    public function toggleTheme(Theme $theme)
    {
        $theme->update(['is_active' => !$theme->is_active]);
        return back()->with('success', 'Theme status updated successfully');
    }

    public function toggleArticle(Article $article)
    {
        $article->update(['is_active' => !$article->is_active]);
        return back()->with('success', 'Article status updated successfully');
    }

    public function toggleIssue(Issue $issue)
    {
        $issue->update(['is_active' => !$issue->is_active]);
        return back()->with('success', 'Issue status updated successfully');
    }

    public function toggleUser(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated successfully');
    }
}
