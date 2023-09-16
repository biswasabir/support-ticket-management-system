@extends('admin.layouts.grid')
@section('section', admin_lang('Members'))
@section('title', admin_lang('Agents'))
@section('link', route('admin.members.agents.create'))
@section('content')
    <div class="custom-card card">
        <div class="card-header p-3 border-bottom-small">
            <form class="multiple-select-search-form" action="{{ request()->url() }}" method="GET">
                <div class="row g-3">
                    <div class="col-12 col-lg-10">
                        <input type="text" name="search" class="form-control" placeholder="{{ admin_lang('Search...') }}"
                            value="{{ request()->input('search') ?? '' }}">
                    </div>
                    <div class="col">
                        <button class="btn btn-primary w-100"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="col">
                        <a href="{{ route('admin.members.agents.index') }}"
                            class="btn btn-secondary w-100">{{ admin_lang('Reset') }}</a>
                    </div>
                </div>
            </form>
        </div>
        <div>
            @if ($agents->count() > 0)
                <div class="table-responsive">
                    <table class="vironeer-normal-table table w-100">
                        <thead>
                            <tr>
                                <th class="tb-w-3x">#</th>
                                <th class="tb-w-20x">{{ admin_lang('Agent details') }}</th>
                                <th class="tb-w-7x">{{ admin_lang('Departments') }}</th>
                                <th class="tb-w-3x text-center">{{ admin_lang('Added date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agents as $agent)
                                <tr>
                                    <td>{{ $agent->id }}</td>
                                    <td>
                                        <div class="vironeer-user-box">
                                            <a class="vironeer-user-avatar"
                                                href="{{ route('admin.members.agents.edit', $agent->id) }}">
                                                <img src="{{ $agent->getAvatar() }}" class="rounded-circle"
                                                    alt="{{ $agent->getName() }}" />
                                            </a>
                                            <div>
                                                <a class="text-reset"
                                                    href="{{ route('admin.members.agents.edit', $agent->id) }}">{{ $agent->getName() }}</a>
                                                <p class="text-muted mb-0">{{ $agent->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($agent->departments->count() > 0)
                                            <div class="row g-2">
                                                @foreach ($agent->departments as $department)
                                                    <div class="col-auto">
                                                        <a href="{{ route('admin.departments.edit', $department->id) }}">
                                                            <span class="badge bg-secondary">{{ $department->name }}</span>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span>--</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ dateFormat($agent->created_at) }}</td>
                                    <td>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                                aria-expanded="true">
                                                <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-sm-end"
                                                data-popper-placement="bottom-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.members.agents.edit', $agent->id) }}"><i
                                                            class="fas fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.members.agents.destroy', $agent->id) }}"
                                                        method="POST">
                                                        @csrf @method('DELETE')
                                                        <button class="vironeer-able-to-delete dropdown-item text-danger"><i
                                                                class="far fa-trash-alt me-2"></i>{{ admin_lang('Delete') }}</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                @include('admin.partials.empty', ['size' => 180])
            @endif
        </div>
    </div>
    {{ $agents->links() }}
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
