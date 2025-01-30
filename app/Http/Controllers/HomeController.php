<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\Article;
use App\Models\Issue;

class HomeController extends Controller
{
    public function index()
    {
        $featuredThemes = Theme::withCount(['articles', 'subscribers'])
            ->where('is_active', true)
            ->take(3)
            ->get();

        $latestArticles = Article::with(['author', 'theme'])
            ->where('status', 'published')
            ->where('is_active', true)
            ->latest()
            ->take(3)
            ->get();

        $latestIssue = Issue::with('articles')
            ->where('is_active', true)
            ->where('is_public', true)
            ->latest('publication_date')
            ->first();

        return view('home', compact('featuredThemes', 'latestArticles', 'latestIssue'));
    }
}
