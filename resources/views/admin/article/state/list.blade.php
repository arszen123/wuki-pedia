<?php
/**
 * @var $articleDetailsHistories \App\Models\ArticleDetailsHistory[]
 */
?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                My articles
            </div>
            <div class="card-body">
                @if(!$articleDetailsHistories->isEmpty())
                    <ul class="list-group">
                        @foreach($articleDetailsHistories as $articleDetailsHistory)
                            <li class="list-group-item">
                                <div>
                                    <h3>{{ $articleDetailsHistory->title }}</h3>
                                    <p>{{ $articleDetailsHistory->updated_at->format('Y-m-d') }}</p>
                                    <a class="btn btn-primary" href="{{ route('article.state.edit', [$articleDetailsHistory->id]) }}">View</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    No modification request!
                @endif
            </div>
        </div>
    </div>
@endsection