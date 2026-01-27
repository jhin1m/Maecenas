@extends("users.layout.main")
@section('metadata')
{!! SEO::generate() !!}
{!! $metaHtml ?? '' !!}
@endsection
@section('content')
<div class="container">
    <div class="page-breadcrumb">
        <span class="item"><a href="/">Trang chủ</a></span>
        <span class="item breadcrumb_last" aria-current="page">Thể loại</span>
    </div>

    @foreach ($categories as $category)
    <section class="mb-3">
        <div class="splide m-suggest splide-navtop">
            <div class="group-title">
                <h2 class="m-title title">{{$category->name}}<span class="sub">Gợi ý theo sở thích của bạn.</span></h2>
                <div class="swiper-btn__group">
                    <a href="{{route('showCategory', ['slug' => $category->slug])}}" class="view-all">Xem tất cả</a>
                    <div class="splide__arrows position-relative">
                        <button class="splide__arrow splide__arrow--prev">
                            <i class="icon-arrow-left"></i>
                        </button>
                        <button class="splide__arrow splide__arrow--next">
                            <i class="icon-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="splide__track">
                <div class="splide__list">
                    @foreach ($category->comics->take(10) as $comic)
                    <div class="m-post horizontal splide__slide">
                        <div class="p-thumb flex-shrink-0">
                            <a title="{{$comic->name}}" href="{{route('detail', ['slug' => $comic->slug])}}">
                                <span class="img-poster">
                                    <img class="lzl" data-src="{{$comic->thumbnail}}" rel="nofollow" data-original="{{$comic->thumbnail}}" alt="{{$comic->name}}" src="/assets/images/pre-load1.png" width="100%" height="100%">
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

                            </div>
                            <ul class="list-chaps">

                                <li class="chapter">
                                    @if ($comic->chapters->count() > 0)
                                    <a data-id="{{$comic->chapters->take(-1)->first()->id}}" href="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $comic->chapters->take(-1)->first()->slug])}}" title="Chapter #{{$comic->chapters->take(-1)->first()->name}}">
                                        Chapter #{{$comic->chapters->take(-1)->first()->name}}<span>{{$comic->chapters->take(-1)->first()->created_at->diffForHumans()}}</span>
                                    </a>
                                    @endif
                                </li>

                            </ul>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endforeach
</div>

<script src="{{asset('assets/theme/js/suggest.js')}}"></script>
@endsection
