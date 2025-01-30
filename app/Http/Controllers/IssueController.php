<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.role:editor')->except(['index', 'show']);
    }

    public function index()
{
    $issues = Issue::with('theme')
        ->when(auth()->guest(), function($query) {
            // Si l'utilisateur est un invité, afficher uniquement les problèmes publics
            return $query->where('is_public', true);
        })
        ->when(auth()->check(), function($query) {
            $user = auth()->user();

            // Vérification pour les abonnés
            if ($user->hasRole('subscriber')) {
            
                    return $query->where('is_public', true);
                
            }

            // Vérification pour les responsables de thème et les éditeurs
            if ($user->hasRole('responsible') || $user->hasRole('editor')) {
                // Les responsables de thèmes et les éditeurs peuvent voir tous les problèmes
                return $query;
            }
        })
        ->where('is_active', true)
        ->orderBy('publication_date', 'desc')
        ->paginate(12);

    return view('issues.index', compact('issues'));
}


    public function show(Issue $issue)
    {
        if (!$issue->is_public && auth()->guest()) {
            abort(403, 'This issue is not publicly available.');
        }

        $articles = $issue->articles()
            ->where('status', 'published')
            ->where('is_active', true)
            ->get();

        return view('issues.show', compact('issue', 'articles'));
    }

    public function manage()
    {
        $issues = Issue::with(['theme', 'articles'])
            ->withCount('articles')
            ->orderBy('publication_date', 'desc')
            ->paginate(15);

        return view('issues.manage', compact('issues'));
    }

    public function create()
    {
        return view('issues.create');
    }

    public function togglePublic(Issue $issue)
    {
        $issue->update(['is_public' => !$issue->is_public]);
        return back()->with('success', 'Issue visibility updated successfully');
    }

    public function toggleActive(Issue $issue)
    {
        $issue->update(['is_active' => !$issue->is_active]);
        return back()->with('success', 'Issue status updated successfully');
    }
}
