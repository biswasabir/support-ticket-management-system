<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/simplebar/simplebar.min.css') }}">
    @endpush
    @include('includes.head')
</head>

<body>
    @include('user.includes.navbar')
    <div class="section py-5">
        <div class="container">
            <div class="section-header text-start">
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
                    @if (request()->routeIs('user.tickets.index'))
                        <div class="col">
                            <a href="{{ route('user.tickets.create') }}" class="btn btn-primary btn-md">
                                <i class="fa fa-plus me-1"></i>
                                {{ lang('New Ticket', 'tickets') }}
                            </a>
                        </div>
                    @endif
                    @if (request()->routeIs('user.notifications.index'))
                        <div class="col">
                            <a href="{{ route('user.notifications.read.all') }}"
                                class="btn btn-outline-primary btn-md action-confirm me-2"><i
                                    class="fa-regular fa-bookmark me-2"></i>{{ lang('Mark All as Read', 'notifications') }}</a>
                            <form action="{{ route('user.notifications.destroy.all') }}" method="POST"
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
            <div class="section-body">
                @yield('content')
            </div>
        </div>
    </div>
    @include('includes.footer')
    @include('includes.config')
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/simplebar/simplebar.min.js') }}"></script>
    @endpush
    @include('includes.scripts')
</body>

</html>
