<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @include('includes.head')
</head>

<body>
    @include('includes.navbar')
    <div class="section my-auto">
        <div class="container">
            <div class="section-body">
                @yield('content')
            </div>
        </div>
    </div>
    @include('includes.footer')
    @include('includes.config')
    @include('includes.scripts')
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}')
            @endforeach
        </script>
    @elseif(session('status'))
        <script>
            toastr.success('{{ session('status') }}')
        </script>
    @elseif(session('resent'))
        <script>
            toastr.success('{{ lang('Link has been resend Successfully', 'auth') }}')
        </script>
    @endif
</body>

</html>
