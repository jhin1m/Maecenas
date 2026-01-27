@extends("users.layout.main")
@section('metadata')
{!! SEO::generate() !!}
{!! $metaHtml ?? '' !!}
@endsection
@section('content')
<div class="container">
    <div class="page-breadcrumb">
        <span class="item"><a href="/">Trang chủ</a></span>
        <span class="item breadcrumb_last" aria-current="page">Thể loại - {{$category->name}}</span>
    </div>

    <section class="mb-3">
        <div class="group-title">
            <div class="only-title">
                <h1 class="m-title title">Truyện tranh {{$category->name}}</h1>
            </div>
        </div>

        <div class="list-genre">
            @foreach ($comics as $item)
            <div class="m-post horizontal">
                <div class="p-thumb flex-shrink-0">
                    <a title="{{$item->name}}" href="{{route('detail', ['slug' => $item->slug])}}">
                        <span class="img-poster">
                            <img class="lzl" data-src="{{$item->thumbnail}}" rel="nofollow" data-original="{{$item->thumbnail}}" alt="{{$item->name}}" width="100%" height="100%">
                        </span>
                    </a>
                </div>
                <div class="p-content flex-grow-1">
                    <h3 class="m-name">
                        <a href="{{route('detail', ['slug' => $item->slug])}}">{{$item->name}}</a>
                    </h3>
                    <div class="group-star">
                        <div class="m-star">
                            <span class="star-rating">
                                <span style="width: {{$item->rating * 20}}%;"></span>
                            </span>
                            <span>{{number_format($item->rating, 1)}}</span>
                        </div>
                    </div>
                    <ul class="list-chaps">

                        @foreach ($item->chapters->take(-1)->reverse() as $chap)
                        <li class="chapter">
                            <a data-id="{{$chap->id}}" href="{{route('showRead', ['slug' => $item->slug, 'chapter' => $chap->slug])}}" title="Chapter #{{$chap->name}}">
                                Chapter #{{$chap->name}}<span>{{$chap->created_at->diffForHumans()}}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
        {{$comics->links('vendor.pagination.custom')}}
    </section>
</div>
@endsection
