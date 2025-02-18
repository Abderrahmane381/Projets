<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function show(Article $article)
{
    $messages = comment::with('user') // Récupère les messages 
        ->where('article_id', $article->id) // Filtre les messages associes a l'article specifique
        ->orderBy('created_at', 'asc') // Trie les messages par date de creation
        ->get(); // Récupère tous les messages correspondants aux critères

    return view('chat.comments', compact('article', 'messages')); // Retourne la vue avec l'article et les messages
}

    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500'
        ]);

        $autoApprove = auth()->user()->isEditor() || 
                      (auth()->user()->isThemeResponsible() && 
                       $article->theme->responsible_id === auth()->id());

        $message = Comment::create([
            'article_id' => $article->id,
            'user_id' => auth()->id(),
            'content' => $validated['message'],
            'is_approved' => $autoApprove
        ]);

        return back()->with('success', 
            $autoApprove ? 'Message sent' : 'Commentaire est bien enregistre'
        );
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment->update($validated);

        return back()->with('success', 'Comment updated successfully.');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }
    public function showArticleComments(Article $article)
    {
        // Récupérer les commentaires de l'article, avec éventuellement une pagination
        $comments = $article->comments()->paginate(10);

        return view('theme-responsible.comments.article', compact('article', 'comments'));
    }

}

