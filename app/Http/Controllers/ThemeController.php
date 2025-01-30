<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    public function index()
    {
        $themes = Theme::query()
            ->withCount(['articles', 'subscribers'])
            ->with('responsible')
            ->where('is_active', true)
            ->latest()
            ->paginate(12);
        
        return view('themes.index', compact('themes'));
        
    }
   
    

    public function show(Theme $theme)
    {
        $query = $theme->articles()->with(['author']);

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

        $isSubscribed = auth()->check() ? auth()->user()->isSubscribedTo($theme) : false;
        $canModerate = auth()->check() ? auth()->user()->canModerateTheme($theme) : false;

        return view('themes.show', compact('theme', 'articles', 'isSubscribed', 'canModerate'));
    }

    public function subscribe(Theme $theme)
    {
        if (!auth()->user()->isSubscriber()) {
            return back()->with('error', 'Only subscribers can subscribe to themes');
        }

        auth()->user()->themeSubscriptions()->attach($theme);
        return back()->with('success', 'Successfully subscribed to theme');
    }

    public function unsubscribe(Theme $theme)
    {
        auth()->user()->themeSubscriptions()->detach($theme);
        return back()->with('success', 'Successfully unsubscribed from theme');
    }

    public function manage()
    {
        $this->authorize('manage-themes');
        
        $themes = Theme::with(['responsible', 'subscribers'])
                      ->withCount(['articles', 'subscribers'])
                      ->get();
                      
        return view('themes.manage', compact('themes'));
    }
}
