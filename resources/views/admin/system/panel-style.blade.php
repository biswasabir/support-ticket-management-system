@extends('admin.layouts.form')
@section('section', admin_lang('System'))
@section('title', admin_lang('Panel Style'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.system.panel-style') }}" method="POST">
        @csrf
        <div class="card mb-3">
            <div class="card-header">
                {{ admin_lang('Colors') }}
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach ($settings->system->colors as $key => $value)
                        <div class="col-lg-6 col-xl-4">
                            <label class="form-label">{{ ucfirst(str($key)->replace('_', ' ')) }} </label>
                            <div class="colorpicker">
                                <input type="text" name="system[colors][{{ $key }}]"
                                    class="form-control coloris" value="{{ $value }}" required>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/coloris/coloris.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/coloris/coloris.min.js') }}"></script>
    @endpush
@endsection
