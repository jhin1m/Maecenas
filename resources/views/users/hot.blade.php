@extends("users.layout.main")
@section('metadata')
{!! SEO::generate() !!}
{!! $metaHtml ?? '' !!}
@endsection
@section('content')
<div class="container">
    <div class="page-breadcrumb">
        <span class="item"><a href="/">Trang chủ</a></span>
        <span class="item breadcrumb_last" aria-current="page">Truyện hot nhất</span>
    </div>

    <section class="mb-3">
        <div>
            <div class="group-title">
                <div class="only-title">
                    <h1 class="m-title title">Truyện hot nhất</h1>
                    <h2 class="sub">Danh sách truyện Hot nhất được gợi ý</h2>
                </div>
            </div>

            <ul class="nav nav-tabs nav-account" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="{{route('hot', ['type' => 'all'])}}" class="nav-link active">All</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{route('hot', ['type' => 'day'])}}" class="nav-link false">Ngày</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{route('hot', ['type' => 'week'])}}" class="nav-link false">Tuần</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{route('hot', ['type' => 'month'])}}" class="nav-link false">Tháng</a>
                </li>
            </ul>

            <div class="list-managas row">
                @foreach ($comics as $comic)
                <div class="m-post col-md-6 col-xl-4">

                    <div class="p-thumb flex-shrink-0">
                        <a title="{{$comic->name}}" href="{{route('detail', ['slug' => $comic->slug])}}">
                            <span class="img-poster">
                                <img class="lzl" data-src="{{$comic->thumbnail}}" rel="nofollow" data-original="{{$comic->thumbnail}}" alt="{{$comic->name}}" width="100%" height="100%">
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
                            <span class="num-view">{{ Number::abbreviate($comic->view_total) }} lượt xem</span>
                        </div>
                        <ul class="list-chaps">
                            @foreach ($comic->chapters->take(-2) as $chapter)
                            <li class="chapter">
                                <a data-id="{{$chapter->id}}" href="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $chapter->slug])}}" title="{{$chapter->name}}">
                                    Chapter {{$chapter->name}}<span>{{$chapter->created_at->diffForHumans()}}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

@endsection
