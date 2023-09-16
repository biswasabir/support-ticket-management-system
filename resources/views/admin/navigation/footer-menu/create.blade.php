@extends('admin.layouts.form')
@section('section', admin_lang('Footer menu'))
@section('title', admin_lang('New Link'))
@section('container', 'container-max-lg')
@section('back', route('admin.footer-menu.index'))
@section('content')
    <div class="card">
        <div class="card-body p-4">
            <form id="vironeer-submited-form" action="{{ route('admin.footer-menu.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Name') }} </label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">{{ admin_lang('Link') }} </label>
                    <input type="link" name="link" class="form-control" placeholder="/" required>
                </div>
            </form>
        </div>
    </div>
@endsection
