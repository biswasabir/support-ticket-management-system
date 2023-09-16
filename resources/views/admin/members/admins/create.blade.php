@extends('admin.layouts.form')
@section('section', admin_lang('Members'))
@section('title', admin_lang('New Admin'))
@section('back', route('admin.members.admins.index'))
@section('container', 'container-max-lg')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ admin_lang('Admin details') }}
        </div>
        <div class="card-body p-4">
            <form id="vironeer-submited-form" action="{{ route('admin.members.admins.store') }}" method="POST">
                @csrf
                <div class="row g-3 mb-2">
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('First Name') }} </label>
                        <input type="firstname" name="firstname" class="form-control form-control-lg"
                            value="{{ old('firstname') }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Last Name') }} </label>
                        <input type="lastname" name="lastname" class="form-control form-control-lg"
                            value="{{ old('lastname') }}" required>
                    </div>
                    <div class="col-lg-12">
                        <label class="form-label">{{ admin_lang('E-mail Address') }} </label>
                        <input type="email" name="email" class="form-control form-control-lg"
                            value="{{ old('email') }}" required>
                    </div>
                    <div class="col-lg-12">
                        <label class="form-label">{{ admin_lang('Password') }} </label>
                        <div class="input-group">
                            <input id="randomPasswordInput" type="text" class="form-control form-control-lg"
                                name="password" required>
                            <button id="copy-btn" class="btn btn-secondary" type="button"
                                data-clipboard-target="#randomPasswordInput"><i class="far fa-clone"></i></button>
                            <button id="randomPasswordBtn" class="btn btn-secondary" type="button"><i
                                    class="fa-solid fa-rotate me-2"></i>{{ admin_lang('Generate') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection
