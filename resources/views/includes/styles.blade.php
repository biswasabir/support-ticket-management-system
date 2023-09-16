@if (config('app.direction') == 'rtl')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/bootstrap.rtl.min.css') }}">
@else
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/bootstrap.min.css') }}">
@endif
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/fontawesome/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.min.css') }}">
@stack('styles_libs')
@frontColors
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
@if (config('app.direction') == 'rtl')
    <link rel="stylesheet" href="{{ asset('assets/css/app-rtl.css') }}">
@endif
@stack('styles')
@customStyle
