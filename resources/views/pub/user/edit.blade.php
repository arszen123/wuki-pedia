<?php
/** @var $user \App\Models\User */
?>
@extends('layouts.app')

@section('content')
    <div class="container justify-content-center">
        <div class="card">
            <div class="card-header">{{ __('Edit')  }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.update') }}" enctype="multipart/form-data">
                    @csrf

                    <image-input :prefill="'{{ $user->getAvatarUrl() }}'"></image-input>

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control{{ $errors->has('Name') ? ' is-invalid' : '' }}" name="name" value="{{ old('title', $user->name) }}" required autofocus>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="academic_degree" class="col-md-4 col-form-label text-md-right">{{ __('Academic degree') }}</label>

                        <div class="col-md-6">
                            <input id="academic_degree" type="text" class="form-control{{ $errors->has('academic_degree') ? ' is-invalid' : '' }}" name="academic_degree" value="{{ old('academic_degree', $user->academic_degree) }}" required>

                            @if ($errors->has('academic_degree'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('academic_degree') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="institution" class="col-md-4 col-form-label text-md-right">{{ __('Academic degree') }}</label>

                        <div class="col-md-6">
                            <input id="institution" type="text" class="form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}" name="institution" value="{{ old('institution', $user->institution) }}" required>

                            @if ($errors->has('institution'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('institution') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="specialization" class="col-md-4 col-form-label text-md-right">{{ __('Specialization') }}</label>

                        <div class="col-md-6">
                            <input id="specialization" type="text" class="form-control{{ $errors->has('specialization') ? ' is-invalid' : '' }}" name="specialization" value="{{ old('specialization', $user->specialization) }}" required>

                            @if ($errors->has('specialization'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('specialization') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <languages-form-element :languages="{{ $user->languages->toJson() }}"></languages-form-element>

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
