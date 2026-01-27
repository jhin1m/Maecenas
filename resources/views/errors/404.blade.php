@extends('users.layout.main')

@section('content')
<div class="container notfound-page">
    <div class="d-flex flex-column justify-content-center align-items-center pt-5 pb-5">
        <img src="/assets/images/404.png" alt="404" width="528" height="412">
        <p class="mt-3 text-center">Không tìm thấy trang? Tìm truyện hay nhất <a href="/tim-kiem" class="text-decoration-underline color">tại đây</a>.</p>
    </div>
</div>
@endsection
