@extends('admin.layouts.grid')
@section('title', admin_lang('Tickets'))
@section('link', route('admin.tickets.create'))
@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6 col-xxl-6">
            <div class="vironeer-counter-card bg-primary">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ admin_lang('Opened tickets') }}</p>
                    <p class="vironeer-counter-card-number">{{ $counters['opened_tickets'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xxl-6">
            <div class="vironeer-counter-card bg-danger">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-regular fa-circle-xmark"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ admin_lang('Closed tickets') }}</p>
                    <p class="vironeer-counter-card-number">{{ $counters['closed_tickets'] }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="custom-card card">
        <div class="card-header p-3 border-bottom-small">
            <form class="multiple-select-search-form" action="{{ request()->url() }}" method="GET">
                <div class="row g-3">
                    <div class="col-12">
                        <input type="text" name="search" class="form-control"
                            placeholder="{{ admin_lang('Search...') }}" value="{{ request()->input('search') ?? '' }}">
                    </div>
                    <div class="col-12 col-lg-3">
                        <select name="user" class="form-select selectpicker" title="{{ admin_lang('User') }}"
                            data-live-search="true">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ $user->id == request('user') ? 'selected' : '' }}>
                                    {{ $user->getName() }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-lg-3">
                        <select name="department" class="form-select selectpicker" title="{{ admin_lang('Department') }}"
                            data-live-search="true">
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ $department->id == request('department') ? 'selected' : '' }}>
                                    {{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-lg-2">
                        <select name="priority" class="form-select selectpicker" title="{{ admin_lang('Priority') }}">
                            @foreach (\App\Models\Ticket::getPriorityOptions() as $key => $value)
                                <option value="{{ $key }}" {{ $key == request('priority') ? 'selected' : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-lg-2">
                        <select name="status" class="form-select selectpicker" title="{{ admin_lang('Status') }}">
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                {{ admin_lang('Opened') }}
                            </option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>
                                {{ admin_lang('Closed') }}</option>
                        </select>
                    </div>
                    <div class="col">
                        <button class="btn btn-primary w-100"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="col">
                        <a href="{{ route('admin.tickets.index') }}"
                            class="btn btn-secondary w-100">{{ admin_lang('Reset') }}</a>
                    </div>
                </div>
            </form>
        </div>
        <div>
            @if ($tickets->count() > 0)
                <div class="table-responsive">
                    <table class="vironeer-normal-table table w-100">
                        <thead>
                            <tr>
                                <th>{{ admin_lang('ID') }}</th>
                                <th>{{ admin_lang('Subject') }}</th>
                                <th>{{ admin_lang('User') }}</th>
                                <th>{{ admin_lang('Department') }}</th>
                                <th>{{ admin_lang('Priority') }}</th>
                                <th class="text-center">{{ admin_lang('Status') }}</th>
                                <th class="text-center">{{ admin_lang('Created date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}"><i
                                                class="fa-solid fa-ticket me-1"></i>#{{ $ticket->id }}</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-dark">
                                            {{ shorterText($ticket->subject, 50) }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.members.users.edit', $ticket->user->id) }}"
                                            class="text-dark">
                                            <i class="fa fa-user me-1"></i>
                                            {{ $ticket->user->getName() }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.departments.edit', $ticket->department->id) }}"
                                            class="text-dark">
                                            {{ $ticket->department->name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $ticket->getPriority() }}
                                    </td>
                                    <td class="text-center">
                                        @if ($ticket->isOpened())
                                            <span class="badge bg-primary">{{ admin_lang('Opened') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ admin_lang('Closed') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ dateFormat($ticket->created_at) }}</td>
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
                                                        href="{{ route('admin.tickets.show', $ticket->id) }}"><i
                                                            class="fas fa-eye me-2"></i>{{ admin_lang('View') }}</a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.tickets.destroy', $ticket->id) }}"
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
    {{ $tickets->links() }}
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
