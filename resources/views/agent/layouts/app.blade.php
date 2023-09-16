<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/simplebar/simplebar.min.css') }}">
    @endpush
    @include('includes.head')
</head>

<body>
    @include('agent.includes.navbar')
    <div class="dash">
        <aside class="dash-sidebar">
            <div class="overlay"></div>
            <div class="dash-sidebar-inner" data-simplebar>
                <div class="dash-sidebar-container">
                    <div class="dash-sidebar-menu">
                        <div class="dash-sidebar-links">
                            <div class="dash-sidebar-links-items">
                                <div
                                    class="dash-sidebar-link {{ request()->segment(2) == 'tickets' ? 'current' : '' }}">
                                    <a href="{{ route('agent.tickets.index') }}" class="dash-sidebar-link-anchor">
                                        <div class="dash-sidebar-link-icon">
                                            <i class="fa-solid fa-inbox"></i>
                                        </div>
                                        <span class="dash-sidebar-link-text">{{ lang('Tickets', 'tickets') }}</span>
                                    </a>
                                </div>
                                <div
                                    class="dash-sidebar-link {{ request()->segment(2) == 'settings' ? 'current' : '' }}">
                                    <a href="{{ route('agent.settings.index') }}" class="dash-sidebar-link-anchor">
                                        <div class="dash-sidebar-link-icon">
                                            <i class="fa fa-cog"></i>
                                        </div>
                                        <span class="dash-sidebar-link-text">{{ lang('Settings', 'settings') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        <div class="content">
            <div class="dash-contain">
                <div class="mb-4">
                    <div class="row row-cols-auto align-items-center justify-content-between g-2">
                        <div class="col">
                            <h2>@yield('title')</h2>
                            @include('partials.breadcrumb')
                        </div>
                        @hasSection('back')
                            <div class="col">
                                <a href="@yield('back')" class="btn btn-outline-secondary btn-md">
                                    <i
                                        class="fa-solid fa-arrow-{{ config('app.direction') == 'rtl' ? 'right' : 'left' }} me-2"></i>
                                    {{ lang('Back', 'tickets') }}
                                </a>
                            </div>
                        @endif
                        @if (request()->routeIs('agent.notifications.index'))
                            <div class="col">
                                <a href="{{ route('agent.notifications.read.all') }}"
                                    class="btn btn-outline-primary btn-md action-confirm me-2"><i
                                        class="fa-regular fa-bookmark me-2"></i>{{ lang('Mark All as Read', 'notifications') }}</a>
                                <form action="{{ route('agent.notifications.destroy.all') }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger action-confirm btn-md"><i
                                            class="fa-regular fa-trash-can me-2"></i>{{ lang('Delete All Read', 'notifications') }}</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
                @yield('content')
            </div>
            <div class="footer mt-auto bg-white text-muted small py-3">
                <div class="dash-container">
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <p class="mb-0">&copy; <span data-year></span>
                                {{ $settings->general->site_name }} - {{ lang('All rights reserved') }}.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('includes.config')
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/simplebar/simplebar.min.js') }}"></script>
    @endpush
    @include('includes.scripts')
</body>

</html>
