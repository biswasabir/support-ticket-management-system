@extends('admin.layouts.form')
@section('section', admin_lang('Tickets'))
@section('title', admin_lang('New Ticket'))
@section('back', route('admin.tickets.index'))
@section('content')
    <div class="card">
        <div class="card-body p-4">
            <form id="vironeer-submited-form" action="{{ route('admin.tickets.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row g-4 mb-2">
                    <div class="col-lg-12">
                        <label class="form-label">{{ admin_lang('Subject') }}</label>
                        <input type="text" name="subject" class="form-control form-control-lg"
                            value="{{ old('subject') }}" autofocus required>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label">{{ admin_lang('User') }}</label>
                        <select name="user" class="form-select form-select-lg selectpicker" data-live-search="true"
                            title="{{ admin_lang('Choose') }}" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('user') == $user->id || request('user') == $user->id ? 'selected' : '' }}>
                                    {{ $user->getName() }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label">{{ admin_lang('Department') }}</label>
                        <select name="department" class="form-select form-select-lg selectpicker" data-live-search="true"
                            title="{{ admin_lang('Choose') }}" required>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department') == $department->id ? 'selected' : '' }}>{{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label">{{ admin_lang('Priority') }}</label>
                        <select name="priority" class="form-select form-select-lg" required>
                            @foreach (\App\Models\Ticket::getPriorityOptions() as $key => $value)
                                <option value="{{ $key }}" {{ old('priority') == $key ? 'selected' : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <label class="form-label">{{ admin_lang('Description') }}</label>
                        <textarea name="description" class="form-control" rows="10" required>{{ old('description') }}</textarea>
                    </div>
                    <div class="col-lg-12">
                        <div class="attachments">
                            <div class="attachment-box-1">
                                <label class="form-label">{{ admin_lang('Attachments') }}</label>
                                <div class="input-group">
                                    <input type="file" name="attachments[]" class="form-control form-control-lg">
                                    <button id="addAttachment" class="btn btn-outline-secondary" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
