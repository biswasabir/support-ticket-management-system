@extends('admin.layouts.grid')
@section('section', admin_lang('System'))
@section('title', admin_lang('Plugins'))
@section('upload_modal', admin_lang('Upload'))
@section('content')
    <div class="custom-card card">
        <table id="datatable" class="table w-100">
            <thead>
                <tr>
                    <th class="tb-w-1x">#</th>
                    <th class="tb-w-3x">{{ admin_lang('Logo') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Name') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Version') }}</th>
                    <th class="tb-w-3x">{{ admin_lang('Status') }}</th>
                    <th class="tb-w-7x">{{ admin_lang('Added at') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plugins as $plugin)
                    <tr class="item">
                        <td>{{ $plugin->id }}</td>
                        <td>
                            @if ($plugin->action_text)
                                <a href="{{ adminUrl($plugin->action_link) }}">
                                    <img src="{{ asset($plugin->logo) }}" alt="{{ $plugin->name }}" height="35">
                                </a>
                            @else
                                <img src="{{ asset($plugin->logo) }}" alt="{{ $plugin->name }}" height="35">
                            @endif
                        </td>
                        <td>
                            @if ($plugin->action_text)
                                <a href="{{ url($plugin->action_link) }}" class="text-dark">
                                    {{ $plugin->name }}
                                </a>
                            @else
                                <span>{{ $plugin->name }}</span>
                            @endif
                        </td>
                        <td><span class="badge bg-dark">{{ $plugin->version }}</span></td>
                        <td>
                            @if ($plugin->status)
                                <span class="badge bg-success">{{ admin_lang('Active') }}</span>
                            @else
                                <span class="badge bg-danger">{{ admin_lang('Disabled') }}</span>
                            @endif
                        </td>
                        <td>{{ dateFormat($plugin->created_at) }}</td>
                        <td>
                            @if ($plugin->action_text)
                                <div class="text-end">
                                    <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                        aria-expanded="true">
                                        <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                        @if ($plugin->action_text)
                                            <li>
                                                <hr class="dropdown-divider" />
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ adminUrl($plugin->action_link) }}"><i
                                                        class="fas fa-external-link-alt me-2"></i>{{ $plugin->action_text }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModallLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <form id="addNewForm" action="{{ route('admin.system.plugins.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Plugin Purchase Code') }} </label>
                            <input type="text" name="purchase_code" class="form-control form-control-lg"
                                placeholder="{{ admin_lang('Purchase code') }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">{{ admin_lang('Plugin Files (Zip)') }} </label>
                            <input type="file" name="plugin_files" class="form-control form-control-lg" accept=".zip"
                                required>
                        </div>
                        <div class="row justify-content-center g-3">
                            <div class="col-12 col-lg">
                                <button type="button" class="btn btn-secondary btn-lg w-100" data-bs-dismiss="modal"
                                    aria-label="Close">{{ admin_lang('Close') }}</button>
                            </div>
                            <div class="col-12 col-lg">
                                <button class="btn btn-primary btn-lg w-100">{{ admin_lang('Upload') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
