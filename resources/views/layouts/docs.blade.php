<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/simplebar/simplebar.min.css') }}">
    @endpush
    @include('includes.head')
</head>

<body class="body-doc">
    <div class="nav-bar">
        <div class="custom-container">
            <div class="nav-bar-container">
                <div class="sidebar-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset($settings->media->logo_dark) }}" alt="{{ $settings->general->site_name }}" />
                </a>
                <div class="nav-search search">
                    <form action="{{ route('knowledgebase.search.page') }}" method="GET">
                        <div class="search-input">
                            <button class="search-input-icon">
                                <i class="fa fa-search"></i>
                            </button>
                            <input type="text" name="q"
                                placeholder="{{ lang('Ask a Question or Enter a Keyword', 'knowledgebase') }}" />
                            <div class="search-close">
                                <i class="fa fa-times"></i>
                            </div>
                        </div>
                        <div class="search-results" data-simplebar>
                            <div></div>
                        </div>
                    </form>
                </div>
                <div class="nav-bar-menu">
                    <div class="overlay"></div>
                    <div class="nav-bar-links">
                        <div class="nav-bar-menu-header">
                            <a class="nav-bar-menu-close ms-auto">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                        @foreach ($navbarMenuLinks as $navbarMenuLink)
                            @if ($navbarMenuLink->children->count() > 0)
                                <div class="drop-down" data-dropdown data-dropdown-position="top">
                                    <div class="drop-down-btn">
                                        <span>{{ $navbarMenuLink->name }}</span>
                                        <i class="fa fa-angle-down ms-2"></i>
                                    </div>
                                    <div class="drop-down-menu">
                                        @foreach ($navbarMenuLink->children as $child)
                                            <a href="{{ $child->link }}" class="drop-down-item">
                                                <span>{{ $child->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ $navbarMenuLink->link }}" class="link">
                                    <div class="link-title">
                                        <span>{{ $navbarMenuLink->name }}</span>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                        @guest
                            <a href="{{ route('login') }}" class="link-btn">
                                <button class="btn btn-secondary">{{ lang('Sign In', 'auth') }}</button>
                            </a>
                            @if ($settings->actions->registration_status)
                                <a href="{{ route('register') }}" class="link-btn">
                                    <button class="btn btn-primary">{{ lang('Sign Up', 'auth') }}</button>
                                </a>
                            @endif
                        @endguest
                    </div>
                </div>
                <div class="nav-bar-actions">
                    @auth
                        @include('partials.user-menu')
                    @endauth
                    <div class="search-btn d-block d-lg-none me-3">
                        <i class="fa fa-search"></i>
                    </div>
                    <div class="nav-bar-menu-btn">
                        <i class="fa-solid fa-bars-staggered fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-doc">
        <div class="custom-container flex-grow-1">
            <div class="page-container">
                <aside class="page-sidebar" data-simplebar>
                    <div class="page-sidebar-inner">
                        @foreach ($categories as $category)
                            <div class="page-links">
                                <h6
                                    class="page-links-title {{ request()->segment(3) == $category->slug ? 'active' : '' }}">
                                    <a
                                        href="{{ route('knowledgebase.category', $category->slug) }}">{{ $category->name }}</a>
                                </h6>
                                @foreach ($category->articles as $article)
                                    <a href="{{ route('knowledgebase.article', $article->slug) }}"
                                        class="page-links-item {{ request()->segment(3) == $article->slug ? 'active' : '' }}">
                                        <i class="fa-regular fa-file-lines me-1"></i>
                                        {{ $article->title }}
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </aside>
                <div class="page-body">
                    <div class="wrapper">
                        <div class="doc-section">
                            <nav aria-label="breadcrumb mb-2">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item capitalize"><a href="{{ route('home') }}">Home</a></li>
                                    <?php $segments = ''; ?>
                                    @foreach (request()->segments() as $segment)
                                        <?php $segments .= '/' . $segment; ?>
                                        <li
                                            class="breadcrumb-item capitalize @if (request()->segment(count(request()->segments())) == $segment) active @endif">
                                            @if (request()->segment(count(request()->segments())) != $segment)
                                                <a href="{{ url($segments) }}">{{ ucfirst($segment) }}</a>
                                            @else
                                                {{ ucfirst($segment) }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </nav>
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('includes.footer', ['container' => 'custom-container'])
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/simplebar/simplebar.min.js') }}"></script>
    @endpush
    @include('includes.config')
    @include('includes.scripts')
</body>

</html>
