@extends('admin.layouts.form')
@section('section', admin_lang('Settings'))
@section('title', admin_lang('Translates'))
@section('content')
    <div class="note note-warning d-flex">
        <div class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div>
            <strong>{{ admin_lang('Important!') }}</strong><br>
            <small>{{ admin_lang('There are some words that should not be translated that start with some tags or are inside a tag') }}
                <strong>{{ admin_lang(':value, :seconds, :min, ::max, {username}') }}</strong>
                {{ admin_lang('etc...') }}</small>
        </div>
    </div>
    <form id="vironeer-submited-form" action="{{ route('admin.settings.translates.update') }}" method="POST">
        @csrf
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Language name') }}</label>
                        <select name="language" class="form-select selectpicker" data-live-search="true"
                            title="{{ admin_lang('Language') }}" required>
                            @foreach (\App\Partials\Languages::all() as $key => $value)
                                <option value="{{ $key }}" {{ app()->getLocale() == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Direction') }}</label>
                        <select name="direction" class="form-select">
                            <option value="ltr" {{ config('app.direction') == 'ltr' ? 'selected' : '' }}>
                                {{ admin_lang('LTR') }}</option>
                            <option value="rtl" {{ config('app.direction') == 'rtl' ? 'selected' : '' }}>
                                {{ admin_lang('RTL') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card translate-card">
            <div class="card-header">
                <ul class="nav nav-pills card-header-tabs">
                    @foreach ($groups as $group)
                        <li class="nav-item">
                            <a class="nav-link {{ $active == $group ? 'active' : '' }}"
                                href="{{ route('admin.settings.translates.group', $group) }}">{{ str_replace('-', ' ', $group) }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body my-1">
                <input type="hidden" name="group" value="{{ $active }}">
                @if (is_array($translates) && count($translates) > 0)
                    @foreach ($translates as $key1 => $value1)
                        @if (is_array($value1))
                            <h2 class="header">{{ $key1 }}</h2>
                            @foreach ($value1 as $key2 => $value2)
                                <div class="vironeer-translate-box">
                                    <label
                                        class="form-label text-muted">{{ ucfirst(str_replace('_', ' ', $key1)) }}</label>
                                    <div class="vironeer-translated-item d-block d-lg-flex bd-highlight align-items-center">
                                        <div class="flex-grow-1 bd-highlight">
                                            <textarea id="autosizeInput" class="vironeer-translate-key translate-fields form-control" rows="1" readonly>{{ $defaultLanguage[$key1][$key2] }}</textarea>
                                        </div>
                                        <div class="pe-3 ps-3 bd-highlight text-center text-success d-none d-lg-block"><i
                                                class="fas fa-chevron-right"></i></div>
                                        <div class="flex-grow-1 bd-highlight">
                                            <textarea id="autosizeInput" name="translates[{{ $key1 }}][{{ $key2 }}]"
                                                class="translate-fields form-control" rows="1" placeholder="{{ $value2 }}">{{ $value2 }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="vironeer-translate-box">
                                <label class="form-label text-muted">{{ ucfirst(str_replace('_', ' ', $key1)) }}</label>
                                <div class="vironeer-translated-item d-block d-lg-flex bd-highlight align-items-center">
                                    <div class="flex-grow-1 bd-highlight">
                                        <textarea id="autosizeInput" class="vironeer-translate-key translate-fields form-control" rows="1" readonly>{{ $defaultLanguage[$key1] }}</textarea>
                                    </div>
                                    <div class="pe-3 ps-3 bd-highlight text-center text-success d-none d-lg-block"><i
                                            class="fas fa-chevron-right"></i></div>
                                    <div class="flex-grow-1 bd-highlight">
                                        <textarea id="autosizeInput" name="translates[{{ $key1 }}]" class="translate-fields form-control"
                                            rows="1" placeholder="{{ $value1 }}">{{ $value1 }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="text-center">
                        <p class="mb-0 text-muted">{{ admin_lang('No translations found') }}</p>
                    </div>
                @endif

            </div>
        </div>
    </form>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/autosize/autosize.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
    @push('scripts')
        <script>
            autosize($('textarea'));
        </script>
    @endpush
@endsection
