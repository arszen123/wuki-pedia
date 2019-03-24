<div class="col-md-5 article-card">
    <div class="article-title">
        <a href="{{ route('article.show', [$article->id]) }}">{{ $article->title }}</a>
    </div>
    <div class="article-details">
        <p><b>Author:</b> {{ $article->author }}</p>
        <p><b>Created:</b> {{ $article->created_at }}</p>
        <p><b>Language:</b> {{ \HTML::languageByCode($article->lang_id) }}</p>
    </div>
</div>