@extends('admin.layouts.form')
@section('title', admin_lang('Footer Menu'))
@section('container', 'container-max-lg')
@section('link', route('admin.footer-menu.create'))
@if ($footerMenuLinks->count() == 0)
    @section('btn_action', 'disabled')
@endif
@section('content')
    @if ($footerMenuLinks->count() > 0)
        <form id="vironeer-submited-form" action="{{ route('admin.footer-menu.sort') }}" method="POST">
            @csrf
            <input name="ids" id="ids" hidden>
        </form>
        <div class="card mb-3">
            <ul class="vironeer-sort-menu custom-list-group list-group list-group-flush">
                @foreach ($footerMenuLinks as $footerMenuLink)
                    <li class="list-group-item d-flex justify-content-between align-items-center"
                        data-id="{{ $footerMenuLink->id }}">
                        <div class="item-title">
                            <span class="vironeer-navigation-handle me-2 text-muted"><i
                                    class="fas fa-arrows-alt"></i></span>
                            <span>{{ $footerMenuLink->name }}</span>
                        </div>
                        <div class="buttons">
                            <a href="{{ route('admin.footer-menu.edit', $footerMenuLink->id) }}"
                                class="vironeer-edit-footer-menu btn btn-blue btn-sm me-2"><i class="fa fa-edit"></i></a>
                            <form class="d-inline" action="{{ route('admin.footer-menu.destroy', $footerMenuLink->id) }}"
                                method="POST">
                                @method('DELETE')
                                @csrf
                                <button class="vironeer-able-to-delete btn btn-danger btn-sm"><i
                                        class="far fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                @include('admin.partials.empty', ['size' => 180])
            </div>
        </div>
    @endif
    @if ($footerMenuLinks->count() > 0)
        @push('styles_libs')
            <link href="{{ asset('assets/vendor/libs/jquery/jquery-ui.min.css') }}" />
        @endpush
        @push('scripts_libs')
            <script src="{{ asset('assets/vendor/libs/jquery/jquery-ui.min.js') }}"></script>
        @endpush
    @endif
@endsection
