@extends("users.layout.main")
@section('metadata')
    <title>Truyện đã đọc</title>
    <meta property="og:title" content="Truyện đã đọc">
    <meta property="robots" content="noindex, nofollow">
@endsection
@section('content')
<div id="main-content" class="container">
    <div class="page-breadcrumb">
        <span class="item"><a href="/">Trang chủ</a></span>
        <span class="item breadcrumb_last" aria-current="page">Lịch sử</span>
    </div>
    <ul class="nav nav-tabs nav-account" role="tablist">
        <li class="nav-item" role="presentation">
            <a href="{{route('profile')}}" class="nav-link ">Thông tin</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="{{route('showHistory')}}" class="nav-link active">Đang đọc</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="{{route('showFollow')}}" class="nav-link" id="mg-save-tab">Đã lưu</a>
        </li>
    </ul>

    <div class="tab-content" id="TopFollow">
        <div class="tab-pane fade active show" id="mg-continue">
            <div class="list-managas row list-current-managas">
                @foreach ($comics as $comic)
                @if ($comic->slug && $comic->conti)
                <div class="m-post col-md-6 col-xl-4">
                    <div class="p-thumb flex-shrink-0">
                        <a title="{{$comic->name}}" href="{{route('detail', ['slug' => $comic->slug])}}" rel="nofollow">
                            <span class="img-poster">
                                <img class="lzl" data-src="{{$comic->thumbnail}}" data-original="{{$comic->thumbnail}}" alt="{{$comic->name}}" width="100%" height="100%">
                            </span>
                        </a>
                    </div>
                    <div class="p-content flex-grow-1">
                        <h3 class="m-name">
                            <a href="{{route('detail', ['slug' => $comic->slug])}}">{{$comic->name}}</a>
                        </h3>
                        <div class="group-star">
                            <div class="m-star">
                                <span class="star-rating">
                                    <span style="width: {{$comic->rating * 20}}%;"></span>
                                </span>
                                <span>{{number_format($comic->rating, 1)}}</span>
                            </div>
                            <span class="num-view">{{\Illuminate\Support\Number::abbreviate($comic->view_total)}} lượt xem</span>
                        </div>
                        <ul class="list-chaps">
                            <li class="chapter">
                                <a href="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $comic->conti->slug])}}">Chapter #{{$comic->conti->name}}</a>
                            </li>
                        </ul>

                    </div>
                    <a href="#" class="s-clear"><i class="icon-close-circle clear-reading-manga-btn" data-id="{{$comic->id}}"></i></a>
                </div>
                @endif
                @endforeach
            </div>

            {{$comics->links('vendor.pagination.custom')}}
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $('.clear-reading-manga-btn').click(function() {
            var id = $(this).data('id');
            $.ajax({
                url: '{{route('removeHistory')}}',
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    window.location.reload();
                }
            });
        });
    </script>
@endsection
