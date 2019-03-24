@extends('layouts.app')

@section('content')
    <div class="container">
            <div id="select-language">
                @foreach($languages as $key => $lang)
                    <a href="/" data-language="{{ $key }}">{{ \HTML::languageByCode($key) }}</a><br/>
                @endforeach
            </div>
    </div>
@endsection