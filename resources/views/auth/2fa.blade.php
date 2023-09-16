@extends('layouts.auth')
@section('title', lang('2Fa Verification', 'auth'))
@section('content')
    <div class="sign">
        <div class="card-v">
            <div class="mb-4">
                <h3>{{ lang('2Fa Verification', 'auth') }}</h3>
                <p class="text-muted">{{ lang('Please enter the OTP code to continue', 'auth') }}</p>
            </div>
            <form action="{{ route('2fa.verify') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="text" name="otp_code" class="form-control form-control-md input-numeric" maxlength="6"
                        required placeholder="• • • • • •" autofocus>
                </div>
                <button class="btn btn-primary btn-md w-100">{{ lang('Continue', 'auth') }}</button>
            </form>
        </div>
    </div>
@endsection
