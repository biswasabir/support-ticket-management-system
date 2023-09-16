@extends('layouts.auth')
@section('title', lang('Reset Password', 'auth'))
@section('content')
    <div class="sign">
        <div class="card-v">
            <div class="mb-4">
                <h3>{{ lang('Reset Password', 'auth') }}</h3>
                <p class="text-muted"> {{ lang('Enter a new password to continue.', 'auth') }}</p>
            </div>
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-3">
                    <label class="form-label">{{ lang('Email address', 'forms') }}</label>
                    <input type="email" name="email" class="form-control form-control-md" value="{{ $email }}"
                        readonly />
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
                <button class="btn btn-primary btn-md w-100">{{ lang('Reset', 'auth') }}</button>
            </form>
        </div>
    </div>
@endsection
