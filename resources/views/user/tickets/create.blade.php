@extends('user.layouts.app')
@section('title', lang('New ticket', 'tickets'))
@section('back', route('user.tickets.index'))
@section('content')
    <div class="card-v">
        <form action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <label class="form-label">{{ lang('Subject', 'tickets') }}</label>
                    <input type="text" name="subject" class="form-control form-control-md" value="{{ old('subject') }}"
                        autofocus required>
                </div>
                <div class="col-lg-6">
                    <label class="form-label">{{ lang('Department', 'tickets') }}</label>
                    <select name="department" class="form-select form-select-md" required>
                        <option value="" disabled selected>{{ lang('Choose', 'tickets') }}</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ old('department') == $department->id ? 'selected' : '' }}>{{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6">
                    <label class="form-label">{{ lang('Priority', 'tickets') }}</label>
                    <select name="priority" class="form-select form-select-md" required>
                        @foreach (\App\Models\Ticket::getPriorityOptions() as $key => $value)
                            <option value="{{ $key }}" {{ old('priority') == $key ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-12">
                    <label class="form-label">{{ lang('Description', 'tickets') }}</label>
                    <textarea name="description" class="form-control" rows="10" required>{{ old('description') }}</textarea>
                </div>
                <div class="col-lg-12">
                    <div class="attachments">
                        <div class="attachment-box-1">
                            <label class="form-label">{{ lang('Attachments', 'tickets') }}
                                ({{ $settings->tickets->file_types }}) </label>
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form-control form-control-md">
                                <button id="addAttachment" class="btn btn-outline-secondary" type="button">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary btn-md">{{ lang('Submit', 'tickets') }}</button>
        </form>
    </div>
    @push('top_scripts')
        <script>
            "use strict";
            const ticketsConfig = {!! json_encode([
                'max_file' => $settings->tickets->max_files,
                'max_files_error' => str(lang('Max {max} files can be uploaded', 'tickets'))->replace(
                    '{max}',
                    $settings->tickets->max_files,
                ),
            ]) !!}
        </script>
    @endpush
@endsection
