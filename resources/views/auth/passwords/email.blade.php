@extends('layouts.auth')
@section('title', lang('Reset Password', 'auth'))
@section('content')
    <div class="sign">
        <div class="card-v">
            <div class="mb-4">
                <h3>{{ lang('Reset Password', 'auth') }}</h3>
                <p class="text-muted">{{ lang('reset password description', 'auth') }}</p>
            </div>
            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ lang('Email address', 'forms') }}</label>
                    <input type="email" name="email" class="form-control form-control-md" value="{{ old('email') }}"
                        required />
                </div>
                <x-captcha />
                <button class="btn btn-primary btn-md w-100">{{ lang('Reset', 'auth') }}</button>
            </form>
        </div>
    </div>
@endsection
