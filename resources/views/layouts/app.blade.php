<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @include('includes.head')
</head>

<body>
    @include('includes.navbar')
    @hasSection('header')
        <header class="header">
            <div class="wrapper wrapper-page"
                style="background-image: url({{ asset($settings->media->header_pattern) }});">
                <div class="container">
                    <div class="wrapper-content">
                        <div class="wrapper-container">
                            <h1 class="page-title">
                                @yield('title')
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    @endif
    @yield('content')
    @include('includes.footer')
    @include('includes.config')
    @include('includes.scripts')
</body>

</html>
