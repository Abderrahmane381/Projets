<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Theme;
use App\Models\User;
use App\Models\Issue;
use Illuminate\Http\Request;

class EditorController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.role:editor');
    }

    public function dashboard()
    {
        $stats = [
            'users' => [
                'total' => User::count(),
                'subscribers' => User::whereHas('roles', function($query) {
                    $query->where('name', 'subscriber');
                })->count(),
                'theme_responsibles' => User::whereHas('roles', function($query) {
                    $query->where('name', 'theme_responsible');
                })->count()
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
                'public' => Issue::where('is_public', true)->count()
            ]
        ];

        return view('editor.dashboard', compact('stats'));
    }

    public function manageUsers()
    {
        $users = User::withCount(['articles', 'themeSubscriptions'])->paginate(15);
        return view('editor.users.index', compact('users'));
    }

    public function updateUserRole(User $user, Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:guest,subscriber,theme_responsible,editor'
        ]);

        $user->update(['role' => $validated['role']]);
        return back()->with('success', 'User role updated successfully');
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated successfully');
    }
    public function destroyuser(User $user)
    {
    $user->delete();
    return redirect()->route('editor.users.index')->with('success', 'User deleted successfully.');
    }


    public function reviewArticles()
    {
        $articles = Article::with(['author', 'theme'])
                         ->where('status', 'submitted')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        return view('editor.articles.review', compact('articles'));
    }

    

    public function createIssue()
    {
        $publishedArticles = Article::query()
            ->where('status', 'published')
            ->where('is_active', true)
            ->whereNull('issue_id')  // Only get articles not already in an issue
            ->with('theme')  // Eager load theme relationship
            ->orderByDesc('created_at')
            ->get();

        if ($publishedArticles->isEmpty()) {
            return redirect()->route('editor.issues.index')
                ->with('error', 'No published articles available to create an issue.');
        }

        return view('editor.issues.create', compact('publishedArticles'));
    }

    public function storeIssue(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'articles' => 'required|array|min:1',
            'articles.*' => 'exists:articles,id',
            'is_public' => 'boolean'
        ]);

        $issue = Issue::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'is_public' => $request->has('is_public'),
            'is_active' => true
        ]);

        $issue->articles()->attach($validated['articles']);

        return redirect()->route('editor.issues.index')
            ->with('success', 'Issue created successfully');
    }

    public function manageIssues()
    {
        $issues = Issue::with('articles')
            ->latest()
            ->paginate(10);
            
        return view('editor.issues.index', compact('issues'));
    }

    public function editIssue(Issue $issue)
    {
        $publishedArticles = Article::where(function ($query) use ($issue) {
                $query->where('status', 'published')
                    ->where('is_active', true)
                    ->whereNull('issue_id')
                    ->orWhereIn('id', $issue->articles->pluck('id'));
            })
            ->with(['author', 'theme'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('editor.issues.edit', compact('issue', 'publishedArticles'));
    }

    public function updateIssue(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_public' => 'boolean',
            'published_at' => 'nullable|date',
            'articles' => 'required|array|min:1',
            'articles.*' => 'exists:articles,id'
        ]);

        $issue->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'is_public' => $validated['is_public'] ?? false,
            'published_at' => $validated['published_at'] ?? null // Évite l'erreur si absent
        ]);

        $issue->articles()->sync($validated['articles']);

        return redirect()->route('editor.issues.index')
            ->with('success', 'Issue updated successfully');
    }

    public function destroyIssue(Issue $issue)
    {
        $issue->delete();

        return redirect()->route('editor.issues.index')
            ->with('success', 'Issue deleted successfully');
    }

    public function manageThemes()
    {
        $themes = Theme::withCount(['articles', 'subscribers'])
            ->orderBy('name')
            ->paginate(10);
        return view('editor.themes.index', compact('themes'));
    }

    public function createArticle()
    {
        $themes = Theme::where('is_active', true)->get();
        return view('editor.articles.create', compact('themes'));
    }

    public function storeArticle(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'theme_id' => 'required|exists:themes,id',
            'content' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $article = Article::create([
            'title' => $validated['title'],
            'theme_id' => $validated['theme_id'],
            'content' => $validated['content'],
            'is_active' => $validated['is_active'] ?? false,
            'status' => 'published',
            'author_id' => auth()->id()
        ]);

        return redirect()->route('editor.articles.review')
            ->with('success', 'Article created successfully');
    }

    public function createTheme()
    {
        $themeResponsibles = User::whereHas('roles', function($query) {
            $query->where('name', 'theme_responsible');
        })->get();
        
        return view('editor.themes.create', compact('themeResponsibles'));
    }

    public function createUser()
    {
        $roles = ['guest', 'subscriber', 'theme_responsible', 'editor'];
        return view('editor.users.create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:guest,subscriber,theme_responsible,editor',
            'is_active' => 'boolean'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'is_active' => $validated['is_active'] ?? false
        ]);

        return redirect()->route('editor.users.index')
            ->with('success', 'User created successfully');
    }

    public function storeTheme(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'theme_responsible_id' => 'required|exists:users,id', // Assurez-vous que l'utilisateur existe
            'is_active' => 'required|boolean', // true ou false
        ]);

        // Création du nouveau thème
        $theme = new Theme();
        $theme->name = $validatedData['name'];
        $theme->description = $validatedData['description'];
        $theme->responsible_id = $validatedData['theme_responsible_id'];
        $theme->is_active = $validatedData['is_active'];
        $theme->save();

        // Rediriger avec un message de succès
        return redirect()->route('editor.themes.index')->with('success', 'Le thème a été créé avec succès.');
    }

    public function edit($id)
    {
    // Récupérer le thème avec l'ID spécifié
    $theme = Theme::findOrFail($id);

    // Retourner la vue d'édition avec les données du thème
    return view('editor.themes.edit', compact('theme'));
    }


    public function update(Request $request, $id)
    {
        $theme = Theme::find($id);
        $theme->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'is_active' => $request->has('is_active') ? $request->input('is_active') : $theme->is_active,
            'theme_responsible_id'=> $request->input('theme_responsible_id')
        ]);
       // Rediriger vers la page d'édition ou une autre page après la mise à jour réussie
        return redirect()->route('editor.themes.index')->with('success', 'Thème mis à jour avec succès');
    }
    public function destroy($id)
    {
        // Trouver le thème à supprimer par son ID
        $theme = Theme::findOrFail($id);
    
        // Supprimer le thème de la base de données
        $theme->delete();
    
        // Rediriger vers la page d'index des thèmes avec un message de succès
        return redirect()->route('editor.themes.index')->with('success', 'Thème supprimé avec succès');
    }
    public function index()
    {
        // Récupérer tous les articles avec leurs thèmes et auteurs
        $articles = Article::with(['theme', 'author'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('editor.articles.index', compact('articles'));
    }

    public function updateStatus(Request $request, Article $article)
    {
        // Vérifie si l'utilisateur est un éditeur
        if (!auth()->user()->hasRole('editor')) {
            abort(403, 'Accès interdit');
        }

        $request->validate([
            'status' => 'required|in:submitted,approved,rejected,published',
        ]);

        $article->update(['status' => $request->status]);

        return redirect()->route('editor.articles.index')->with('success', 'Article status updated.');
    }

    public function destroyarticle(Article $article)
    {
        // Vérifie si l'utilisateur est un éditeur
        if (!auth()->user()->hasRole('editor')) {
            abort(403, 'Accès interdit');
        }

        $article->delete();

        return redirect()->route('editor.articles.index')->with('success', 'Article deleted successfully.');
    }



}
