@extends('admin.layouts.grid')
@section('section', admin_lang('Members'))
@section('title', admin_lang('Users'))
@section('link', route('admin.members.users.create'))
@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6 col-xxl-6">
            <div class="vironeer-counter-card bg-c3">
                <div class="vironeer-counter-card-icon">
                    <i class="fa fa-users"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ admin_lang('Active Users') }}</p>
                    <p class="vironeer-counter-card-number">{{ $counters['active'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xxl-6">
            <div class="vironeer-counter-card bg-c5">
                <div class="vironeer-counter-card-icon">
                    <i class="fa fa-ban"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ admin_lang('Banned Users') }}</p>
                    <p class="vironeer-counter-card-number">{{ $counters['banned'] }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="custom-card card">
        <div class="card-header p-3 border-bottom-small">
            <form class="multiple-select-search-form" action="{{ request()->url() }}" method="GET">
                <div class="row g-3">
                    <div class="col-12 col-lg-8">
                        <input type="text" name="search" class="form-control"
                            placeholder="{{ admin_lang('Search...') }}" value="{{ request()->input('search') ?? '' }}">
                    </div>
                    <div class="col-12 col-lg-2">
                        <select name="status" class="form-select selectpicker" title="{{ admin_lang('Status') }}">
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                {{ admin_lang('Active') }}
                            </option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                {{ admin_lang('Banned') }}</option>
                        </select>
                    </div>
                    <div class="col">
                        <button class="btn btn-primary w-100"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="col">
                        <a href="{{ route('admin.members.users.index') }}"
                            class="btn btn-secondary w-100">{{ admin_lang('Reset') }}</a>
                    </div>
                </div>
            </form>
        </div>
        <div>
            @if ($users->count() > 0)
                <div class="table-responsive">
                    <table class="vironeer-normal-table table w-100">
                        <thead>
                            <tr>
                                <th class="tb-w-3x">#</th>
                                <th class="tb-w-20x">{{ admin_lang('User details') }}</th>
                                <th class="tb-w-7x">{{ admin_lang('IP Address') }}</th>
                                <th class="tb-w-3x text-center">{{ admin_lang('Email status') }}</th>
                                <th class="tb-w-3x text-center">{{ admin_lang('Account status') }}</th>
                                <th class="tb-w-3x text-center">{{ admin_lang('Registred date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="vironeer-user-box">
                                            <a class="vironeer-user-avatar"
                                                href="{{ route('admin.members.users.edit', $user->id) }}">
                                                <img src="{{ $user->getAvatar() }}" class="rounded-circle"
                                                    alt="{{ $user->getName() }}" />
                                            </a>
                                            <div>
                                                <a class="text-reset"
                                                    href="{{ route('admin.members.users.edit', $user->id) }}">{{ $user->getName() }}</a>
                                                <p class="text-muted mb-0">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ demoMode() ? admin_lang('Hidden in demo') : $user->ip_address ?? '--' }}</td>
                                    <td class="text-center">
                                        @if ($user->isVerified())
                                            <span class="badge bg-girl">{{ admin_lang('Verified') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ admin_lang('Unverified') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($user->isActive())
                                            <span class="badge bg-success">{{ admin_lang('Active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ admin_lang('Banned') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ dateFormat($user->created_at) }}</td>
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
                                                        href="{{ route('admin.tickets.create', 'user=' . $user->id) }}"><i
                                                            class="fas fa-plus me-2"></i>{{ admin_lang('New ticket') }}</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.tickets.index', 'user=' . $user->id) }}"><i
                                                            class="fa-solid fa-inbox me-2"></i>{{ admin_lang('View tickets') }}</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.members.users.edit', $user->id) }}"><i
                                                            class="fas fa-edit me-2"></i>{{ admin_lang('Edit details') }}</a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.members.users.destroy', $user->id) }}"
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
    {{ $users->links() }}
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
