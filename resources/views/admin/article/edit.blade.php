@extends('layouts.app')

@section('content')
    <div class="container justify-content-center">
        <div class="card">
            <div class="card-header">{{ __('Select article language:') }}</div>
            <div class="card-body">
                <form action="{{ route('article.edit', [$article->id]) }}">
                    <div class="form-group row">
                        <label for="language" class="col-md-4 col-form-label text-md-right">{{ __('Language') }}</label>

                        <div class="col-md-6">
                            <select id="language" name="language" class="form-control">
                                @foreach($languages as $key => $lang)
                                    {{-- isoName, nativeName --}}
                                    <option value="{{ $key }}" {{ $key === old('language', $articleDetails->lang_id) ?'selected':'' }}>{{ $lang['isoName'] }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('language'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('language') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Select') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">{{ __('Article [:langauge]', ['langauge' => \HTML::languageByCode($articleDetails->lang_id)]) }}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('article.update', [$article->id]) }}">
                    @csrf
                    <input type="hidden" name="base_id" value="{{ $base_id }}" >
                    <input type="hidden" name="language" value="{{ $articleDetails->lang_id }}" >
                    <div class="form-group row">
                        <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>

                        <div class="col-md-6">
                            <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title', $articleDetails->title) }}" required autofocus>

                            @if ($errors->has('title'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="context" class="col-form-label text-md-right justify-content-center">{{ __('Context') }}</label>

                        <div class="col-md-12">
                            <textarea id="context" name="context" style="height: 50%">{{ old('context', $articleDetails->context) }}</textarea>
                            @if ($errors->has('context'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('context') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="tag" class="col-md-4 col-form-label text-md-right">{{ __('Tag') }}</label>

                        <div class="col-md-6">
                            <input id="tag" name="tag" class="form-control" value="{{ old('tag', \HTML::listTags($articleDetails->tag, ',')) }}">
                            @if ($errors->has('tag'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('tag') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection