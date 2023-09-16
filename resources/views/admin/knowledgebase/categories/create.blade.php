@extends('admin.layouts.form')
@section('section', admin_lang('Knowledge base'))
@section('title', admin_lang('New category'))
@section('container', 'container-max-lg')
@section('back', route('admin.knowledgebase.categories.index'))
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.knowledgebase.categories.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body p-4">
                <div class="vironeer-file-preview-box mb-3 bg-light p-4 text-center">
                    <div class="file-preview-box mb-3 d-none">
                        <img id="filePreview" src="#" class="rounded-3" width="80px">
                    </div>
                    <button id="selectFileBtn" type="button" class="btn btn-secondary mb-2"><i
                            class="fas fa-camera me-2"></i>{{ admin_lang('Choose Icon') }}</button>
                    <input id="selectedFileInput" type="file" name="icon" accept="image/png, image/jpg, image/jpeg"
                        hidden required>
                    <small class="text-muted d-block">{{ admin_lang('Allowed (PNG, JPG, JPEG)') }}</small>
                    <small class="text-muted d-block">{{ admin_lang('Image will be resized into (150x150)') }}</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Category name') }} </label>
                    <input type="text" name="name" id="create_slug" class="form-control" value="{{ old('name') }}"
                        required autofocus />
                </div>
                <div class="mb-0">
                    <label class="form-label">{{ admin_lang('Slug') }} </label>
                    <div class="input-group vironeer-input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{ url('knowledgebase/categories/') }}/</span>
                        </div>
                        <input type="text" name="slug" id="show_slug" class="form-control" value="{{ old('slug') }}"
                            required />
                    </div>
                </div>
            </div>
        </div>
    </form>
    @push('top_scripts')
        <script>
            "use strict";
            let GET_SLUG_URL = "{{ route('admin.knowledgebase.categories.slug') }}";
        </script>
    @endpush
@endsection
