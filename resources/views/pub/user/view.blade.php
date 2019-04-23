<?php
/** @var $user \App\Models\User */
/** @var $article \App\Models\Article */
?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div id="profile" class="row justify-content-center">
            <div class="col-md-12 user-profile-head">
                <div class="col-md-5">
                    <img src="{{ $user->getAvatarUrl() }}">
                </div>
                <div class="col-md-5 details">
                    <br/>
                    <p><b>Name</b>: {{ $user->name }}</p>
                    <p><b>Academic degree</b>: {{ $user->academic_degree }}</p>
                    <p><b>Institution</b>: {{ $user->institution }}</p>
                    <p><b>Specialization</b>: {{ $user->specialization }}</p>
                    @if($isCurentAuthenticatedUser)
                        <a href="{{ route('user.edit') }}"><button class="btn btn-primary">Edit</button></a>
                    @endif
                </div>
            </div>
            <div class="col-md-12 user-profile-cards">
                <div id="languages" class="col-md-5 col-no-padding card">
                    <div class="card-header">Languages</div>
                    <div class="card-body">
                        @foreach($user->languages as $language)
                            <div class="language-item">
                                {{ $language->getPublicName() }} {{ $language->getPublicType() }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div id="publications" class="col-md-5 col-no-padding card">
                    <div class="card-header">Publications</div>
                    <div class="card-body">
                        @foreach($user->articles()->limit(10)->get() as $article)
                            @if ($article->details->isNotEmpty())
                                <div class="language-item">
                                    <a href="{{ route('article.show', [$article->id]) }}">
                                        {{ $article->details->first()->title }}
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
