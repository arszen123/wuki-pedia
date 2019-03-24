<div class="col-md-5 article-card">
    <div class="article-title">
        <a href="{{ route('article.show', [$article->id]) }}">{{ $article->title }}</a>
    </div>
    <div class="article-details">
        <p><b>Updated:</b> {{ $article->updated_at }}</p>
    </div>
</div>