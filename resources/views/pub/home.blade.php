@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div id="search" class="form-group row">

                <div class="col-md-6">
                    <input id="tag" name="search" type="text" class="form-control" value="{{ $tags }}">
                </div>
                <div class="col-md-6">
                    <button id="do-search" class="btn btn-primary">Search</button>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            @foreach($topArticles as $article)
                @include($articlePartial, ['article' => $article])
            @endforeach
        </div>
        <div class="col-md-2 user-cards justify-content-center ">
            <p>Top users:</p>
            @foreach($topUsers as $user)
                <a href="{{ route('user.view', [$user->id]) }}">
                    <div class="col-md-12 user-card">
                        {{ $user->name }} <span class="badge badge-primary">{{ $user->badge }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
