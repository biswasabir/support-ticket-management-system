@extends('layouts.docs')
@section('section', lang('Knowledge base', 'knowledgebase'))
@section('title', $category->name)
@section('content')
    <h2 class="doc-section-title">
        <img src="{{ asset($category->icon) }}" alt="{{ $category->name }}" width="40px" height="40px">
        <span class="ms-2">{{ $category->name }}</span>
    </h2>
    @if ($articles->count() > 0)
        <div class="row row-cols-1 g-0 mt-0">
            @foreach ($articles as $article)
                <a href="{{ route('knowledgebase.article', $article->slug) }}" class="doc-category">
                    <div class="row row-cols-auto flex-nowrap g-4">
                        <div class="col flex-shrink-0">
                            <div class="mt-2">
                                <i class="fa fa-file-alt fa-3x"></i>
                            </div>
                        </div>
                        <div class="col flex-shrink-1">
                            <h5 class="mb-1">{{ $article->title }}</h5>
                            <p class="mb-0 text-muted">{{ $article->short_description }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        {{ $articles->links() }}
    @else
        @include('partials.empty')
    @endif
@endsection
