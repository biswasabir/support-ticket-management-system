@extends('admin.layouts.application')
@section('title', admin_lang('Dashboard'))
@section('content')
    @if (!$settings->smtp->status)
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ admin_lang('SMTP is not enabled, set it now to be able to recover the password and use all the features that needs to send an email.') }}
            <a href="{{ route('admin.settings.smtp.index') }}">{{ admin_lang('Take Action') }}</a>
        </div>
    @endif
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-4 col-xxl-4">
            <div class="vironeer-counter-card bg-c-8">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-check-double"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ admin_lang('Total tickets') }}</p>
                    <p class="vironeer-counter-card-number">{{ $counters['total_tickets'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xxl-4">
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
        <div class="col-12 col-lg-4 col-xxl-4">
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
        <div class="col-12 col-lg-4 col-xxl">
            <div class="vironeer-counter-card bg-c1">
                <div class="vironeer-counter-card-icon">
                    <i class="fa fa-users"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ admin_lang('Total Users') }}</p>
                    <p class="vironeer-counter-card-number">{{ $counters['total_users'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xxl">
            <div class="vironeer-counter-card bg-c-5">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-users-line"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ admin_lang('Total Agents') }}</p>
                    <p class="vironeer-counter-card-number">{{ $counters['total_agents'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xxl">
            <div class="vironeer-counter-card bg-c-9">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ admin_lang('Total Admins') }}</p>
                    <p class="vironeer-counter-card-number">{{ $counters['total_admins'] }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-12 col-lg-8 col-xxl-8">
            <div class="card">
                <div class="vironeer-box chart-bar">
                    <div class="vironeer-box-header">
                        <p class="vironeer-box-header-title large mb-0">{{ admin_lang('Users Statistics For This Week') }}
                        </p>
                        <div class="vironeer-box-header-action ms-auto">
                            <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-sm-end">
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.members.users.index') }}">{{ admin_lang('View All') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="vironeer-box-body">
                        <div class="chart-bar">
                            <canvas height="380" id="dashboard-users-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xxl-4">
            <div class="card vhp-460">
                <div class="vironeer-box v2">
                    <div class="vironeer-box-header mb-3">
                        <p class="vironeer-box-header-title large mb-0">{{ admin_lang('Recently registered') }}</p>
                        <div class="vironeer-box-header-action ms-auto">
                            <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-sm-end">
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.members.users.index') }}">{{ admin_lang('View All') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="vironeer-box-body">
                        <div class="vironeer-random-lists">
                            @forelse ($users as $user)
                                <div class="vironeer-random-list">
                                    <div class="vironeer-random-list-cont">
                                        <a class="vironeer-random-list-img" href="#">
                                            <img src="{{ $user->getAvatar() }}" />
                                        </a>
                                        <div class="vironeer-random-list-info">
                                            <div>
                                                <a class="vironeer-random-list-title fs-exact-14"
                                                    href="{{ route('admin.members.users.edit', $user->id) }}">
                                                    {{ $user->getName() }}
                                                </a>
                                                <p class="vironeer-random-list-text mb-0">
                                                    {{ $user->created_at->diffforhumans() }}
                                                </p>
                                            </div>
                                            <div class="vironeer-random-list-action d-none d-lg-block">
                                                <a href="{{ route('admin.members.users.edit', $user->id) }}"
                                                    class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                @include('admin.partials.empty', ['size' => 120])
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="vironeer-box chart-bar">
                    <div class="vironeer-box-header">
                        <p class="vironeer-box-header-title large mb-0">
                            {{ admin_lang('Tickets Statistics For This Month') }}
                        </p>
                        <div class="vironeer-box-header-action ms-auto">
                            <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-sm-end">
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.tickets.index') }}">{{ admin_lang('View All') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="vironeer-box-body">
                        <div class="chart-bar">
                            <canvas height="460" id="dashboard-tickets-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('top_scripts')
        <script>
            "use strict";
            const chartsConfig = {!! json_encode([
                'users' => [
                    'title' => admin_lang('Users'),
                    'labels' => $charts['users']['labels'],
                    'data' => $charts['users']['data'],
                    'max' => $charts['users']['max'],
                ],
                'tickets' => [
                    'title' => admin_lang('Tickets'),
                    'labels' => $charts['tickets']['labels'],
                    'data' => $charts['tickets']['data'],
                    'max' => $charts['tickets']['max'],
                ],
            ]) !!};
        </script>
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/chartjs/chart.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/admin/js/charts.js') }}"></script>
    @endpush
@endsection
