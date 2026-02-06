<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    use Illuminate\Support\Facades\DB;
    $shortcut = DB::table('seo')->where('key', 'shortcut')->first();
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/theme/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/theme/css/icon-font.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/theme/css/splide-core.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/theme/css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/theme/css/responsive.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="{{ asset('assets/js/jquery-3.2.1.min.js') }}"></script>
    @stack('header')
    @yield('metadata')
    @yield('styles')
    <script>
        var urlSearch = '{{route('search')}}';
        var csrf_token = '{{ csrf_token() }}';
        var urlRegister = '{{route('auth-register')}}';
        var urlLogin = '{{route('auth-login')}}';

        function handleCallbackCheckAuthIsDone(callBack) {
            const user = '{{Auth::check()}}';

            if (!user) {
                $(document).ready(function () {
                    $('#auth').on(AUTHEN_DONE_EVENT, function () {
                        callBack && typeof callBack === 'function' && callBack();
                    });
                });
            } else {
                @if (Auth::check())
                    sessionStorage.setItem('user', JSON.stringify({
                        name: '{{Auth::user()->name}}',
                        avatar: '{{Auth::user()->avatar}}',
                    }));
                    callBack();
                @endif
            }
        }
    </script>
</head>

<body>
    <script src="{{asset('assets/theme/js/cookie.js')}}"></script>
    <script src="{{asset('assets/theme/js/common.js')}}"></script>
    <script src="{{asset('assets/theme/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/theme/js/splide.min.js')}}"></script>
    <script>
        function checkDarkModeConfig() {
            const lightMode = localStorage.getItem('lm');
            if (lightMode === 'true') {
                document.body.classList.remove('darkmode');
                $('.input-dark-mode').prop('checked', false);
            } else {
                document.body.classList.add('darkmode');
                $('.input-dark-mode').prop('checked', true);
            }
        }

        function toggleDarkModeConfig(mode) {
            const darkMode = mode !== undefined ? mode : $('body').hasClass('darkmode');
            if (!darkMode) {
                document.body.classList.add('darkmode');
            } else {
                document.body.classList.remove('darkmode');
            }

            localStorage.setItem('lm', darkMode);
            checkDarkModeConfig();
        }
        checkDarkModeConfig();
    </script>
    @yield('main_content')
    <script src="{{asset('assets/theme/js/custom.js')}}"></script>
    @yield('scripts')
</body>

</html>