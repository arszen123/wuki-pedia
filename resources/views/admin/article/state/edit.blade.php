@extends('layouts.app')

@section('content')
    <div class="container justify-content-center">
        <div class="card">
            <div class="card-header">{{ __('Article [:langauge]', ['langauge' => \HTML::languageByCode($articleDetailsHistory->lang_id)]) }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route('article.state.update', [$local->id]) }}">
                    @csrf
                    <input type="hidden" name="language" value="{{ $articleDetailsHistory->lang_id }}" >
                    <div class="form-group row">
                        <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>

                        <div class="col-md-6">
                            <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title', $articleDetailsHistory->title) }}" required autofocus>

                            @if ($errors->has('title'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="justify-content-center" style="display: flex">
                        <div class="col-md-4">
                            <h3>Local</h3>
                            <div>{!! $local->context !!}</div>
                        </div>
                        <div class="col-md-4">
                            <h3>Remote</h3>
                            <div>{!! $remote->context !!}</div></div>
                        <div class="col-md-4">
                            <h3>Base</h3>
                            <div>{!! $base->context !!}</div></div>
                    </div>
                    <div class="form-group">
                        <label for="context" class="col-form-label text-md-right justify-content-center">{{ __('Context') }}</label>

                        <div class="col-md-12">
                            <textarea id="context" name="context" style="height: 50%">{{ old('context', $articleDetailsHistory->context) }}</textarea>
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
                            <input id="tag" name="tag" class="form-control" value="{{ old('tag', \HTML::listTags($articleDetailsHistory->tag, ',')) }}">
                            @if ($errors->has('tag'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('tag') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="state" class="col-form-label text-md-right justify-content-center">{{ __('State') }}</label>

                        <div class="col-md-12">
                            <select id="context" name="state" class="form-control">
                                @foreach($states as $state)
                                    <option value="{{ $state }}" {{ old('state', $articleDetailsHistory->state) === $state ? 'selected' : '' }}>{{ $state }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('state'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('state') }}</strong>
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