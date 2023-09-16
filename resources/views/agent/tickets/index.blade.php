@extends('agent.layouts.app')
@section('title', lang('Tickets', 'tickets'))
@section('content')
    <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2 g-3">
        <div class="col">
            <div class="counter">
                <div class="card-v">
                    <div class="counter-icon">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                    <div class="counter-meta">
                        <h3 class="mb-2">{{ lang('Opened Tickets', 'tickets') }}</h3>
                        <p class="mb-0 fs-4">{{ number_format($counters['opened_tickets']) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="counter counter-danger">
                <div class="card-v">
                    <div class="counter-icon">
                        <i class="fa-regular fa-circle-xmark"></i>
                    </div>
                    <div class="counter-meta">
                        <h3 class="mb-2">{{ lang('Closed Tickets', 'tickets') }}</h3>
                        <p class="mb-0 fs-4">{{ number_format($counters['closed_tickets']) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-5">
        <div class="row row-cols-1 g-4">
            <div class="col">
                <div class="row row-cols-auto justify-content-between align-items-center g-2">
                    <div class="col">
                        <h4 class="mb-0">{{ lang('All Tickets', 'tickets') }}</h4>
                    </div>
                    <div class="col">
                        @if (request('search') || request('status_opened') || request('status_closed') || request('priority'))
                            <a href="{{ route('agent.tickets.index') }}" class="btn btn-outline-danger btn-md me-2"><i
                                    class="fa-solid fa-filter-circle-xmark me-2"></i>{{ lang('Clear', 'tickets') }}</a>
                        @endif
                        <button class="btn btn-outline-primary btn-md" data-bs-toggle="modal"
                            data-bs-target="#ticketsFilter">
                            <i class="fa fa-filter me-2"></i>
                            {{ lang('Filter', 'tickets') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="dash-table d-none d-lg-block">
                    <div class="table-responsive-xl">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ lang('ID', 'tickets') }}</th>
                                    <th class="text-start">{{ lang('Subject', 'tickets') }}</th>
                                    <th class="text-center">{{ lang('Priority', 'tickets') }}</th>
                                    <th class="text-center">{{ lang('Status', 'tickets') }}</th>
                                    <th>{{ lang('Created Date', 'tickets') }}</th>
                                    <th class="text-end"></th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @forelse ($tickets as $ticket)
                                    <tr>
                                        <td>
                                            <a
                                                href="{{ route('agent.tickets.show', $ticket->id) }}">#{{ $ticket->id }}</a>
                                        </td>
                                        <td class="text-start">
                                            <a href="{{ route('agent.tickets.show', $ticket->id) }}" class="text-dark">
                                                {{ shorterText($ticket->subject, 50) }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{ $ticket->getPriority() }}
                                        </td>
                                        <td class="text-center">
                                            @if ($ticket->isOpened())
                                                <span class="badge bg-primary">{{ lang('Opened', 'tickets') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ lang('Closed', 'tickets') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ dateFormat($ticket->created_at) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('agent.tickets.show', $ticket->id) }}"
                                                class="btn btn-secondary"><i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <p class="mb-0 p-3">{{ lang('No tickets found', 'tickets') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-v p-0">
                    <ol class="list-group list-group-flush d-block d-lg-none">
                        @foreach ($tickets as $ticket)
                            <a href="{{ route('agent.tickets.show', $ticket->id) }}"
                                class="list-group-item d-flex justify-content-between align-items-start p-3">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">#{{ $ticket->id }}</div>
                                    <div>{{ shorterText($ticket->subject, 40) }}</div>
                                    <small class="text-muted">{{ dateFormat($ticket->created_at) }}</small>
                                </div>
                                @if ($ticket->isOpened())
                                    <span class="badge bg-primary">{{ lang('Opened', 'tickets') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ lang('Closed', 'tickets') }}</span>
                                @endif
                            </a>
                        @endforeach
                    </ol>
                </div>

                {{ $tickets->links() }}
            </div>
        </div>
    </div>
    <div class="modal fade" id="ticketsFilter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-header border-0 p-0 mb-4">
                    <h5 class="modal-title" id="exampleModalLabel">{{ lang('Tickets Filter', 'tickets') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <form id="filterForm" action="{{ route('agent.tickets.index') }}" method="GET">
                        <div class="row row-cols-1 g-3">
                            <div class="col">
                                <div class="form-search">
                                    <input type="text" name="search" class="form-control form-control-md"
                                        placeholder="{{ lang('Type to Search...', 'tickets') }}"
                                        value="{{ request('search') }}" />
                                    <div class="icon">
                                        <i class="fa fa-search"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label">{{ lang('Priority', 'tickets') }}</label>
                                <select name="priority" class="form-select form-select-md">
                                    @foreach (\App\Models\Ticket::getPriorityOptions() as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ $key == request('priority') ? 'selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label">{{ lang('Tickets Status', 'tickets') }}</label>
                                <div class="row row-cols-2 g-2">
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="status_opened"
                                                id="ticketStatus1" {{ request('status_opened') ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="ticketStatus1">{{ lang('Opened Tickets', 'tickets') }}</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="status_closed"
                                                id="ticketStatus2" {{ request('status_closed') ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="ticketStatus2">{{ lang('Closed Tickets', 'tickets') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 p-0 mt-4">
                    <button type="button" class="btn btn-outline-danger"
                        data-bs-dismiss="modal">{{ lang('Close', 'tickets') }}</button>
                    <button form="filterForm" class="btn btn-primary">{{ lang('Filter', 'tickets') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
