@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach($topArticles as $article)
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
            @endforeach
        </div>
        <div class="col-md-2 user-cards justify-content-center ">
            <p>Top users:</p>
            @foreach($topUsers as $user)
                <a href="#">
                    <div class="col-md-12 user-card">
                        {{ $user->name }} <span class="badge badge-primary">{{ $user->badge }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
