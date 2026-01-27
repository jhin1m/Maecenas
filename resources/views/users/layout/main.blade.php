@extends('users.main')
@section('main_content')
    <div class="wrapper">
        @include('users.layout.header')
        <div id="vote_noti" style="bottom: -100px;">
            <p>Cảm ơn bạn đã nhận xét truyện</p>
            <img src="/assets/images/img-vote-noti.png" width="64" height="92" alt="">
        </div>
        <main>
            @yield('content')
        </main>
        @include('users.layout.footer')
    </div>
@endsection
