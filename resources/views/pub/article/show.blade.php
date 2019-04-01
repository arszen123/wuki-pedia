<?php
/**
 * @var \App\Models\Article $article
 */
?>
@extends('layouts.app')

@section('content')
    <div class="container">
        @foreach($availableLanguages as $lang)
            <a class="btn btn-primary" href="{{ route('article.show', ['id' => $article->id, 'language' => $lang]) }}">
                {{ \HTML::languageByCode($lang) }}
            </a>
        @endforeach
        <div class="card">
            <div class="card-header">{{ $articleDetails->title }} @auth @if($article->details->isNotEmpty()) <a href="{{ route('article.edit', [$article->id]) }}" style="float:right">Edit</a> @endif @endauth</div>
            <div class="card-body">{!! $articleDetails->context !!}</div>
            <div class="card-footer">
                Language: {{ \HTML::languageByCode($articleDetails->lang_id) }} <br>
                Created: {{ $article->created_at }} <br>
                Last pdated at {{ $article->updated_at }} <br>
                Author: {{ $article->author->name }} <br>
                Tags: {{ \HTML::listTags($articleDetails->tag) }}<br/>
                Participants: {!! \HTML::listParticipants($participants) !!}
            </div>
        </div>
    </div>
@endsection