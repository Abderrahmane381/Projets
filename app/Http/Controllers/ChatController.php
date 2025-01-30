<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Article $article)
    {
        $messages = Chat::with('user')
            ->where('article_id', $article->id)
            ->where('is_approved', true)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chat.show', compact('article', 'messages'));
    }

    public function sendMessage(Request $request, Article $article)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500'
        ]);

        $autoApprove = auth()->user()->isEditor() || 
                      (auth()->user()->isThemeResponsible() && 
                       $article->theme->responsible_id === auth()->id());

        $message = Chat::create([
            'article_id' => $article->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_approved' => $autoApprove
        ]);

        return back()->with('success', 
            $autoApprove ? 'Message sent' : 'Message sent and waiting for approval'
        );
    }

    public function moderate(Article $article)
    {
        $this->authorize('moderate-chat', $article);

        $messages = Chat::with('user')
            ->where('article_id', $article->id)
            ->where('is_approved', false)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chat.moderate', compact('article', 'messages'));
    }

    public function approve(Chat $message)
    {
        $this->authorize('moderate-chat', $message->article);
        
        $message->update(['is_approved' => true]);
        return back()->with('success', 'Message approved');
    }

    public function delete(Chat $message)
    {
        $this->authorize('moderate-chat', $message->article);
        
        $message->delete();
        return back()->with('success', 'Message deleted');
    }
}
