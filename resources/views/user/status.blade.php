@extends('layouts.app')

@section('content')
    <h2>Mes Articles</h2>

    <div class="articles-container">
        @foreach ($articles as $article)
            <div class="article-card">
                <h3>{{ $article->title }}</h3>
                <p><strong>Thème :</strong> {{ $article->theme->name }}</p>
                <p><strong>Publié par :</strong> {{ $article->author->name }}</p>
                <p><strong>Date :</strong> {{ $article->created_at->format('M d, Y') }}</p>
                
                <span class="status 
                    @if($article->status == 'published') published
                    @elseif($article->status == 'rejected') rejected
                    @else pending @endif">
                    {{ ucfirst($article->status) }}
                </span>

                <br><br>
                <a href="{{ route('articles.show', $article->id) }}" class="view-button">Voir</a>
            </div>
        @endforeach
    </div>

    {{ $articles->links() }} {{-- Pagination --}}
@endsection
<style>
    .articles-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 20px;
}

.article-card {
    width: 300px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 15px;
    text-align: center;
}

.article-card h3 {
    font-size: 18px;
    margin-bottom: 10px;
}

.article-card p {
    font-size: 14px;
    color: #555;
    margin: 5px 0;
}

.status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: bold;
}

.published {
    background-color: #d4edda;
    color: #155724;
}

.rejected {
    background-color: #f8d7da;
    color: #721c24;
}

.pending {
    background-color: #fff3cd;
    color: #856404;
}

.view-button {
    display: inline-block;
    padding: 8px 15px;
    margin-top: 10px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.view-button:hover {
    background-color: #0056b3;
}

</style>