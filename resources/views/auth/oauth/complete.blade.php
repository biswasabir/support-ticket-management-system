@extends('layouts.auth')
@section('title', lang('Complete your information', 'auth'))
@section('content')
    <div class="sign">
        <div class="card-v">
            <div class="mb-4">
                <h3>{{ lang('Complete your information', 'auth') }}</h3>
                <p class="text-muted">{{ lang('Some information is missing that you have to set.', 'auth') }}</p>
            </div>
            <form action="{{ route('oauth.data.complete') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ lang('Email address', 'forms') }}</label>
                    <input type="email" name="email" class="form-control form-control-md"
                        value="{{ auth()->user()->email }}" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ lang('Password', 'forms') }}</label>
                    <input type="password" name="password" class="form-control form-control-md" minlength="8" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ lang('Confirm password', 'forms') }}</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-md" minlength="8"
                        required>
                </div>
                <x-captcha />
                <button class="btn btn-primary btn-md w-100">{{ lang('Continue', 'auth') }}</button>
            </form>
        </div>
    </div>
@endsection
