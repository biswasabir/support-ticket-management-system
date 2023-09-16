@extends('layouts.app')
@section('title', $settings->seo->title ?? '')
@section('content')
    <header class="header">
        <div class="header-shape">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path class="shape-fill" fill-opacity="1"
                    d="M0,288L80,266.7C160,245,320,203,480,197.3C640,192,800,224,960,218.7C1120,213,1280,171,1360,149.3L1440,128L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z">
                </path>
            </svg>
        </div>
        <div class="wrapper" style="background-image: url({{ asset($settings->media->header_pattern) }});">
            <div class="container">
                <div class="wrapper-content">
                    <div class="wrapper-container">
                        <div class="col-xl-8 mx-auto">
                            <h1 class="header-title" data-aos="fade-right" data-aos-duration="1000">
                                {{ lang('How We Can Help You?', 'home page') }}
                            </h1>
                            <p class="header-text" data-aos="fade-right" data-aos-duration="1000">
                                {{ lang('Start searching to find answers, or check our knowledge base', 'home page') }}
                            </p>
                            @if ($settings->actions->knowledgebase_status)
                                <div class="header-search search" data-aos="fade-up" data-aos-duration="1000">
                                    <form action="{{ route('knowledgebase.search.page') }}" method="GET">
                                        <div class="search-input">
                                            <button class="search-icon">
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <input type="text" name="q"
                                                placeholder="{{ lang('Ask a Question or Enter a Keyword', 'knowledgebase') }}" />
                                        </div>
                                        <div class="search-results" data-simplebar>
                                            <div></div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @if ($settings->actions->knowledgebase_status && $categories->count() > 0)
        <div class="section">
            <div class="container">
                <div class="section-header">
                    <div class="section-title mb-4">
                        <h3 class="mb-0">{{ lang('Knowledge Base', 'home page') }}</h3>
                        <div class="section-title-divider"></div>
                    </div>
                    <p class="section-text col-lg-7 text-muted mx-auto mb-0">
                        {{ lang('Knowledge base description', 'home page') }}
                    </p>
                </div>
                <div class="section-body">
                    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 justify-content-center g-4">
                        @foreach ($categories as $category)
                            <div class="col" data-aos="fade-up" data-aos-duration="1000">
                                <div class="card-v h-100">
                                    <div class="categories">
                                        <div class="categories-header d-flex align-items-center">
                                            <a href="{{ route('knowledgebase.category', $category->slug) }}">
                                                <img class="categories-img me-3 flex-shrink-0"
                                                    src="{{ asset($category->icon) }}" alt="{{ $category->name }}" />
                                            </a>
                                            <div class="categories-meta">
                                                <a href="{{ route('knowledgebase.category', $category->slug) }}">
                                                    <h5 class="categories-title text-dark mb-1">{{ $category->name }}</h5>
                                                </a>
                                                <p class="categories-topics mb-0 text-muted small">
                                                    @if ($category->articles_count == 1)
                                                        {{ str(lang('{count} Topic', 'knowledgebase'))->replace('{count}', $category->articles_count) }}
                                                    @else
                                                        {{ str(lang('{count} Topics', 'knowledgebase'))->replace('{count}', $category->articles_count) }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="categories-body">
                                            @foreach ($category->articles as $article)
                                                <a href="{{ route('knowledgebase.article', $article->slug) }}"
                                                    class="category">
                                                    <i class="fa-regular fa-file-lines"></i>
                                                    <span>{{ $article->title }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                        <div class="categories-footer">
                                            <a href="{{ route('knowledgebase.category', $category->slug) }}">
                                                <span>{{ lang('View All', 'knowledgebase') }}</span>
                                                <i
                                                    class="fa fa-arrow-{{ config('app.direction') == 'rtl' ? 'left' : 'right' }} ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (
        !auth()->user() ||
            (auth()->user() &&
                auth()->user()->isUser()))
        <div class="section pt-0">
            <div class="container">
                <div class="section-body">
                    <div class="card-v p-5" data-aos="zoom-in" data-aos-duration="1000">
                        <div class="row justify-content-between align-items-center g-4">
                            <div class="col-12 col-lg-6 text-center text-lg-start">
                                <h4 class="mb-3">{{ lang('Still no luck? We can help!', 'home page') }}</h4>
                                <p class="text-muted mb-0">
                                    {{ lang('Open a ticket and we will contact you back as soon as possible.', 'home page') }}
                                </p>
                            </div>
                            <div class="col-12 col-lg-auto d-flex justify-content-center">
                                <a href="{{ auth()->user() ? route('user.tickets.create') : route('login') }}"
                                    class="btn btn-primary btn-lg">
                                    {{ lang('Open a ticket', 'home page') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/aos/aos.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/simplebar/simplebar.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/aos/aos.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/simplebar/simplebar.min.js') }}"></script>
    @endpush
@endsection
