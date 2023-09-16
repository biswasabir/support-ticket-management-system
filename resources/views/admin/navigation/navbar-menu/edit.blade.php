@extends('admin.layouts.form')
@section('section', admin_lang('Navbar menu'))
@section('title', $navbarMenu->name)
@section('container', 'container-max-lg')
@section('back', route('admin.navbar-menu.index'))
@section('content')
    <div class="card">
        <div class="card-body p-4">
            <form id="vironeer-submited-form" action="{{ route('admin.navbar-menu.update', $navbarMenu->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Name') }} </label>
                    <input type="text" name="name" class="form-control" value="{{ $navbarMenu->name }}" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">{{ admin_lang('Link') }} </label>
                    <input type="link" name="link" class="form-control" placeholder="/"
                        value="{{ $navbarMenu->link }}" required>
                </div>
            </form>
        </div>
    </div>
@endsection
