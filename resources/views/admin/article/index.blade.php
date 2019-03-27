<?php
/**
 * @var $userArticles \App\Models\Article[]
 */
$col = empty($userPendingArticles) ? 12 : 6;
?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card col-md-{{ $col }} col-no-padding" style="display: inline-block">
            <div class="card-header">
                My articles
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($userArticles as $article)
                        @if($article->details->isNotEmpty())
                        <li class="list-group-item">
                            <div>
                            <h3>{{ $article->details->first()->title }}</h3>
                            <p>{{ $article->updated_at->format('Y-m-d') }}</p>
                            </div>
                            <div>
                                <a href="{{ route('article.statistic', [$article->id]) }}"><button class="btn btn-primary">Statistic</button></a>
                                <a href="{{ route('article.edit', [$article->id]) }}"><button class="btn btn-primary">Edit</button></a>
                                <a href="{{ route('article.mod.requests', [$article->id]) }}"><button class="btn btn-primary">Modification Requests</button></a>
                            </div>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        @if(!empty($userPendingArticles))
            <div class="card col-md-5 col-no-padding" style="display: inline-block">
                <div class="card-header">
                    My pending articles
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($userPendingArticles as $article)
                            <li class="list-group-item">
                                <div>
                                    <h3><a href="{{ route('article.history.view', [$article->history_id]) }}">{{ $article->title }}</a></h3>
                                    <p>{{ $article->created_at }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
@endsection