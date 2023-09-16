@extends('admin.layouts.form')
@section('section', admin_lang('Members'))
@section('title', admin_lang('Admin') . ' | ' . $admin->getName())
@section('back', route('admin.members.admins.index'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.members.admins.update', $admin->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card custom-card mb-3">
            <div class="card-header">{{ admin_lang('Actions') }}</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-lg-4">
                        <label class="form-label">{{ admin_lang('Two-Factor Authentication') }} </label>
                        <input id="2faCheckbox" type="checkbox" name="google2fa_status" data-toggle="toggle"
                            data-on="{{ admin_lang('Active') }}" data-off="{{ admin_lang('Disabled') }}"
                            {{ $admin->has2fa() ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
        </div>
        <div class="card custom-card">
            <div class="card-header">{{ admin_lang('Account details') }}</div>
            <div class="card-body p-4">
                <div class="row align-items-center mb-4">
                    <div class="col-auto">
                        <img id="filePreview" src="{{ $admin->getAvatar() }}" alt="{{ $admin->getName() }}"
                            class="rounded-circle border" width="80px" height="80px">
                    </div>
                    <div class="col-auto">
                        <button id="selectFileBtn" type="button" class="btn btn-secondary btn-sm"><i
                                class="fas fa-camera me-2"></i>{{ admin_lang('Choose Image') }}</button>
                        <input id="selectedFileInput" type="file" name="avatar"
                            accept="image/png, image/jpg, image/jpeg" hidden>
                    </div>
                </div>
                <div class="row g-3 mb-2">
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('First Name') }} </label>
                        <input type="firstname" name="firstname" class="form-control form-control-lg"
                            value="{{ $admin->firstname }}">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Last Name') }} </label>
                        <input type="lastname" name="lastname" class="form-control form-control-lg"
                            value="{{ $admin->lastname }}">
                    </div>
                    <div class="col-lg-12">
                        <label class="form-label">{{ admin_lang('E-mail Address') }} </label>
                        <input type="email" name="email" class="form-control form-control-lg"
                            value="{{ $admin->email }}" required>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
