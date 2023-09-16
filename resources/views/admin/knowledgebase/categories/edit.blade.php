@extends('admin.layouts.form')
@section('section', admin_lang('Knowledge base'))
@section('title', $category->name)
@section('container', 'container-max-lg')
@section('back', route('admin.knowledgebase.categories.index'))
@section('content')
    <div class="mb-3">
        <a class="btn btn-outline-secondary" href="{{ route('knowledgebase.category', $category->slug) }}" target="_blank"><i
                class="fa fa-eye me-2"></i>{{ admin_lang('Preview') }}</a>
    </div>
    <form id="vironeer-submited-form" action="{{ route('admin.knowledgebase.categories.update', $category->id) }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body p-4">
                <div class="vironeer-file-preview-box mb-3 bg-light p-4 text-center">
                    <div class="file-preview-box mb-3">
                        <img id="filePreview" src="{{ asset($category->icon) }}" class="rounded-3" width="80px">
                    </div>
                    <button id="selectFileBtn" type="button" class="btn btn-secondary mb-2"><i
                            class="fas fa-camera me-2"></i>{{ admin_lang('Choose Icon') }}</button>
                    <input id="selectedFileInput" type="file" name="icon" accept="image/png, image/jpg, image/jpeg"
                        hidden>
                    <small class="text-muted d-block">{{ admin_lang('Allowed (PNG, JPG, JPEG)') }}</small>
                    <small class="text-muted d-block">{{ admin_lang('Image will be resized into (150x150)') }}</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Category name') }} </label>
                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" required />
                </div>
                <div class="mb-0">
                    <label class="form-label">{{ admin_lang('Slug') }} </label>
                    <div class="input-group vironeer-input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{ url('knowledgebase/categories/') }}/</span>
                        </div>
                        <input type="text" name="slug" class="form-control" value="{{ $category->slug }}" required />
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
