@extends('layouts.docs')
@section('section', lang('Knowledge base', 'knowledgebase'))
@section('title', lang('Categories', 'knowledgebase'))
@section('content')
    <h2 class="doc-section-title">{{ lang('Categories', 'knowledgebase') }}</h2>
    @if ($categories->count() > 0)
        <div class="row row-cols-1 row-cols-lg-2 g-0 mt-0">
            @foreach ($categories as $category)
                <a href="{{ route('knowledgebase.category', $category->slug) }}" class="doc-category">
                    <div class="row row-cols-auto flex-nowrap g-4 align-items-center">
                        <div class="col flex-shrink-0">
                            <div class="mt-2">
                                <img src="{{ asset($category->icon) }}" alt="{{ $category->name }}" width="50px"
                                    height="50px">
                            </div>
                        </div>
                        <div class="col flex-shrink-1">
                            <h5 class="mb-1">{{ $category->name }}</h5>
                            <p class="text-muted">
                                @if ($category->articles_count == 1)
                                    {{ str(lang('{count} Topic', 'knowledgebase'))->replace('{count}', $category->articles_count) }}
                                @else
                                    {{ str(lang('{count} Topics', 'knowledgebase'))->replace('{count}', $category->articles_count) }}
                                @endif
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        {{ $categories->links() }}
    @else
        @include('partials.empty')
    @endif
@endsection
