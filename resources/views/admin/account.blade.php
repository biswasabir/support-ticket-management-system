@extends('admin.layouts.grid')
@section('title', admin_lang('Account details'))
@section('container', 'container-max-lg')
@section('content')
    <div class="details mb-4">
        <form action="{{ route('admin.account.details') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card custom-card">
                <div class="card-header">
                    <span>{{ admin_lang('Personal details') }}</span>
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <div class="col-auto">
                            <img id="filePreview" src="{{ $user->getAvatar() }}" alt="{{ $user->getName() }}"
                                class="rounded-circle border" width="80px" height="80px">
                        </div>
                        <div class="col-auto">
                            <button id="selectFileBtn" type="button" class="btn btn-secondary btn-sm"><i
                                    class="fas fa-camera me-2"></i>{{ admin_lang('Choose Image') }}</button>
                            <input id="selectedFileInput" type="file" name="avatar"
                                accept="image/png, image/jpg, image/jpeg" hidden>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-lg-6">
                            <label class="form-label">{{ admin_lang('First Name') }} </label>
                            <input type="firstname" class="form-control" name="firstname" value="{{ $user->firstname }}"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">{{ admin_lang('Last Name') }} </label>
                            <input type="lastname" class="form-control" name="lastname" value="{{ $user->lastname }}"
                                required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ admin_lang('Email Address') }} </label>
                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                    </div>
                    <button class="btn btn-primary">{{ admin_lang('Save Changes') }}</button>
                </div>
            </div>
        </form>
    </div>
    <div class="password mb-4">
        <form id="vironeer-submited-form" action="{{ route('admin.account.password') }}" method="POST">
            @csrf
            <div class="card custom-card">
                <div class="card-header">
                    <span>{{ admin_lang('Password') }}</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">{{ admin_lang('Password') }} </label>
                        <input type="password" class="form-control" name="current-password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ admin_lang('New Password') }} </label>
                        <input type="password" class="form-control" name="new-password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ admin_lang('Confirm New Password') }} </label>
                        <input type="password" class="form-control" name="new-password_confirmation" required>
                    </div>
                    <button class="btn btn-primary">{{ admin_lang('Save Changes') }}</button>
                </div>
            </div>
        </form>
    </div>
    <div class="2fa">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>{{ admin_lang('2Factor Authentication') }}</span>
                @if (!$user->google2fa_status)
                    <span class="badge bg-danger">{{ admin_lang('Disabled') }}</span>
                @else
                    <span class="badge bg-success">{{ admin_lang('Enabled') }}</span>
                @endif
            </div>
            <div class="card-body">
                <p>{{ admin_lang('Two-factor authentication (2FA) strengthens access security by requiring two methods (also referred to as factors) to verify your identity. Two-factor authentication protects against phishing, social engineering, and password brute force attacks and secures your logins from attackers exploiting weak or stolen credentials.') }}
                </p>
                @if (!$user->google2fa_status)
                    <div class="row g-3 mb-3">
                        <div class="col-lg-6">
                            <div class="border h-100 text-center p-4">
                                <div class="mb-2">
                                    {!! $qrCode !!}
                                </div>
                                <div class="input-group mb-3">
                                    <input id="input-link" type="text" class="form-control form-control-md"
                                        value="{{ $user->google2fa_secret }}" readonly>
                                    <button id="copy-btn" class="btn btn-secondary" data-clipboard-target="#input-link"><i
                                            class="far fa-clone"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="border h-100 p-4">
                                <form action="{{ route('admin.account.2fa.enable') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">{{ admin_lang('OTP Code') }} </label>
                                        <input type="text" name="otp_code" class="form-control input-numeric"
                                            placeholder="••••••" maxlength="6" required>
                                    </div>
                                    <button class="btn btn-primary w-100">{{ admin_lang('Enable') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mb-3">
                        <div class="col-lg-6 m-auto">
                            <div class="border h-100 p-4">
                                <form action="{{ route('admin.account.2fa.disable') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">{{ admin_lang('OTP Code') }} </label>
                                        <input type="text" name="otp_code" class="form-control input-numeric"
                                            placeholder="••••••" maxlength="6" required>
                                    </div>
                                    <button class="btn btn-danger w-100">{{ admin_lang('Disable') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                <p class="mb-2">
                    {{ admin_lang('To use the two factor authentication, you have to install a Google Authenticator compatible app. Here are some that are currently available:') }}
                </p>
                <li class="mb-1"><a target="_blank"
                        href="https://apps.apple.com/us/app/google-authenticator/id388497605">{{ admin_lang('Google Authenticator for iOS') }}</a>
                </li>
                <li class="mb-1"><a target="_blank"
                        href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en&gl=US">{{ admin_lang('Google Authenticator for Android') }}</a>
                </li>
                <li class="mb-1"><a target="_blank"
                        href="https://apps.apple.com/us/app/microsoft-authenticator/id983156458">{{ admin_lang('Microsoft Authenticator for iOS') }}</a>
                </li>
                <li class="mb-1"><a target="_blank"
                        href="https://play.google.com/store/apps/details?id=com.azure.authenticator&hl=en_US&gl=US">{{ admin_lang('Microsoft Authenticator for Android') }}</a>
                </li>
            </div>
        </div>
    </div>
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection
