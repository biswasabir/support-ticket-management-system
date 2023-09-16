@extends('layouts.docs')
@section('section', lang('Knowledge base', 'knowledgebase'))
@section('title', $article->title)
@section('content')
    <h2 class="doc-section-title">{{ $article->title }}</h2>
    <div class="doc-section-content">
        {!! $article->body !!}
    </div>
    <div class="mt-5">
        <div class="article-feedback row row-cols-auto justify-content-center flex-nowrap g-3">
            <div class="col flex-shrink-1">
                <button class="react-btn" data-slug="{{ $article->slug }}" data-action="1">
                    <i class="fa-solid fa-thumbs-up"></i>
                    {{ lang('Helpful', 'knowledgebase') }}
                </button>
            </div>
            <div class="col flex-shrink-1">
                <button class="react-btn" data-slug="{{ $article->slug }}" data-action="2">
                    <i class="fa-solid fa-thumbs-down"></i>
                    {{ lang('Not Helpful', 'knowledgebase') }}
                </button>
            </div>
        </div>
    </div>
@endsection
