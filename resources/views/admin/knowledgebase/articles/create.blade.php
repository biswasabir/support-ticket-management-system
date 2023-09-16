@extends('admin.layouts.form')
@section('section', admin_lang('Knowledge base'))
@section('title', admin_lang('New Article'))
@section('back', route('admin.knowledgebase.articles.index'))
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.knowledgebase.articles.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card p-2 mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Title') }} </label>
                            <input type="text" name="title" id="create_slug" class="form-control"
                                value="{{ old('title') }}" required autofocus />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Slug') }} </label>
                            <div class="input-group vironeer-input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ url('knowledgebase/articles/') }}/</span>
                                </div>
                                <input type="text" name="slug" id="show_slug" class="form-control"
                                    value="{{ old('slug') }}" required />
                            </div>
                        </div>
                        <div class="ckeditor-lg mb-0">
                            <label class="form-label">{{ admin_lang('Body') }} </label>
                            <textarea name="body" rows="10" class="form-control ckeditor">{{ old('content') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card p-2 mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Categories') }} </label>
                            <select name="categories[]" class="form-select form-select-lg selectpicker"
                                data-live-search="true" title="{{ admin_lang('Choose') }}" multiple required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ admin_lang('Short description') }} </label>
                            <textarea name="short_description" rows="6" class="form-control"
                                placeholder="{{ admin_lang('50 to 200 character at most') }}" required>{{ old('short_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @push('top_scripts')
        <script>
            "use strict";
            let GET_SLUG_URL = "{{ route('admin.knowledgebase.articles.slug') }}";
        </script>
    @endpush
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/ckeditor/ckeditor.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/ckeditor/plugins/uploadAdapterPlugin.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
