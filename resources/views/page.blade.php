@extends('layouts.app')
@section('title', $page->title)
@section('description', $page->short_description)
@section('header', true)
@section('content')
    <div class="section">
        <div class="container">
            <div class="section-body">
                <div class="card-v">
                    @if ($page->slug != 'contact-us')
                        {!! $page->body !!}
                    @else
                        <form action="{{ route('page.contact.send', $page->slug) }}" method="POST">
                            @csrf
                            <div class="row row-cols-1 row-cols-lg-3 g-3 mb-3">
                                <div class="col">
                                    <label class="form-label">{{ lang('Name', 'contact') }}</label>
                                    <input type="text" name="name" class="form-control form-control-md"
                                        value="{{ auth()->user()? auth()->user()->getName(): '' }}" required>
                                </div>
                                <div class="col">
                                    <label class="form-label">{{ lang('Email', 'contact') }}</label>
                                    <input type="email" name="email" class="form-control form-control-md"
                                        value="{{ auth()->user() ? auth()->user()->email : '' }}" required>
                                </div>
                                <div class="col">
                                    <label class="form-label">{{ lang('Subject', 'contact') }}</label>
                                    <input type="text" name="subject" class="form-control form-control-md"
                                        value="{{ old('subject') }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ lang('Message', 'contact') }}</label>
                                <textarea class="form-control" name="message" rows="8" required>{{ old('message') }}</textarea>
                            </div>
                            <x-captcha />
                            <button class="btn btn-primary btn-md">{{ lang('Send', 'contact') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
