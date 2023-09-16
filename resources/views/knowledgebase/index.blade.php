@extends('layouts.docs')
@section('title', lang('Knowledge base', 'knowledgebase'))
@section('content')
    <h2 class="doc-section-title">{{ lang('Knowledge base', 'knowledgebase') }}</h2>
    @if ($categories->count() > 0)
        <div class="row row-cols-1 row-cols-lg-1 row-cols-xl-2 justify-content-center g-4">
            @foreach ($categories as $category)
                <div class="col">
                    <div class="border p-5 rounded-2 h-100">
                        <div class="categories">
                            <div class="categories-header d-flex align-items-center">
                                <a href="{{ route('knowledgebase.category', $category->slug) }}">
                                    <img class="categories-img me-3 flex-shrink-0" src="{{ asset($category->icon) }}"
                                        alt="{{ $category->name }}" />
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
                                    <a href="{{ route('knowledgebase.article', $article->slug) }}" class="category">
                                        <i class="fa-regular fa-file-lines"></i>
                                        <span>{{ $article->title }}</span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="categories-footer">
                                <a href="{{ route('knowledgebase.category', $category->slug) }}">
                                    <span>{{ lang('View All', 'knowledgebase') }}</span>
                                    <i
                                        class="fa fa-arrow-{{ config('app.direction') == 'rtl' ? 'left' : 'right' }}  ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        @include('partials.empty')
    @endif
@endsection
