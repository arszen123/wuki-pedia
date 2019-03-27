@extends('layouts.app')

@section('content')
    <div class="container justify-content-center col-md-12">
        <div class="col-md-12">
            <div id="search" class="form-group row">

                <div class="col-md-6">
                    <input id="tag" name="search" type="text" class="form-control" value="{{ $tags }}">
                </div>
                <div class="col-md-6">
                    <button id="do-suggestion-search" class="btn btn-primary">Search</button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Suggested articles
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($articles as $article)
                        <li class="list-group-item">
                            <div>
                                <h3><a href="{{ route('article.state.edit', [$article->history_id]) }}">{{ $article->title }}</a></h3>
                                <p>{{ $article->created_at }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection