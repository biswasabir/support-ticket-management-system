@extends('layouts.auth')
@section('title', lang('Sign In', 'auth'))
@section('content')
    <div class="sign">
        <div class="card-v">
            <div class="sign-header mb-4">
                <h4>{{ lang('Sign In', 'auth') }}</h4>
                <p class="text-muted mb-0">{{ lang('Enter your account details to sign in', 'auth') }}</p>
            </div>
            <div class="sign-body">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ lang('Email address', 'forms') }}</label>
                        <input type="email" name="email" class="form-control form-control-md" value="{{ old('email') }}"
                            required />
                    </div>
                    <div class="mb-3">
                        <div class="mb-2">
                            <div class="row row-cols-auto justify-content-between align-items-center g-2">
                                <div class="col">
                                    <label class="form-label mb-0">{{ lang('Password', 'forms') }}</label>
                                </div>
                                <div class="col">
                                    <a href="{{ route('password.request') }}" class="d-block">
                                        {{ lang('Forgot Your Password?', 'auth') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <input type="password" name="password" class="form-control form-control-md" required />
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">{{ lang('Remember Me', 'auth') }}</label>
                        </div>
                    </div>
                    <x-captcha />
                    <button class="btn btn-primary btn-md w-100">{{ lang('Sign In', 'auth') }}</button>
                </form>
                <x-oauth-buttons />
            </div>
        </div>
    </div>
@endsection
