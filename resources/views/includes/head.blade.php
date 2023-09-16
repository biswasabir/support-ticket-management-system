@php
$description = $__env->yieldContent('description') ? $__env->yieldContent('description') : $settings->seo->description ?? '';
$ogImage = $__env->yieldContent('og_image') ? $__env->yieldContent('og_image') : asset($settings->media->social_image);
@endphp
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="theme-color" content="{{ $settings->colors->primary_color }}">
<meta name="language" content="{{ app()->getLocale() }}">
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $settings->seo->keywords ?? '' }}">
<meta property="og:site_name" content="{{ $settings->general->site_name }}">
<meta property="og:locale" content="{{ app()->getLocale() }}">
<meta property="og:locale:alternate" content="{{ app()->getLocale() }}">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ pageTitle($__env) }}">
<meta property="og:description" content="{{ $description }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ pageTitle($__env) }}">
<meta name="twitter:description" content="{{ $description }}">
<meta property="og:image:height" content="600">
<meta property="og:image:width" content="316">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta name="twitter:image:src" content="{{ $ogImage }}">
<title>{{ pageTitle($__env) }}</title>
<link rel="icon" href="{{ asset($settings->media->favicon) }}">
@include('includes.styles')
