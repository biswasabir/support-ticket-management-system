@extends('admin.layouts.form')
@section('title', admin_lang('New department'))
@section('container', 'container-max-lg')
@section('back', route('admin.departments.index'))
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.departments.store') }}" method="POST">
        @csrf
        <div class="card p-2">
            <div class="card-body">
                <div class="col-lg-4 mb-3">
                    <label class="form-label">{{ admin_lang('Status') }} </label>
                    <input type="checkbox" name="status" data-toggle="toggle" checked>
                </div>
                <div class="mb-0">
                    <label class="form-label">{{ admin_lang('Name') }} </label>
                    <input type="text" name="name" class="form-control form-control-lg" value="{{ old('name') }}"
                        placeholder="{{ admin_lang('Enter department name') }}" required autofocus />
                </div>
            </div>
        </div>
    </form>
@endsection
