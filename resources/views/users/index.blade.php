@extends('users.layout.main')
@section('metadata')
{!! SEO::generate() !!}
{!! $metaHtml ?? '' !!}
@endsection
@section('content')
<div id="main-content">
    <section id="manga-slider" class="container">
        <div class="slide-single splide" role="group" aria-label="Banner">
            <div class="splide__track">
                <div class="splide__list">
                    <div class="splide__slide">
                        <div class="sl-wrapper">
                            <img class="bg-slide" data-splide-lazy="/assets/images/bg-slide1.png" alt="banner" />
                            <div class="mb-left mb-banner">
                                <a href="/the-loai/manga" aria-label="Nhật Bản"><img class="slide-img" data-splide-lazy="/assets/images/mb-11.png" alt="" /></a>
                            </div>
                            <div class="mb-mid__banner">
                                <div class="sm-tag">Truyện<span>Nhật Bản</span></div>
                                <div class="mb-mid mb-banner">
                                    <a href="/the-loai/manga" aria-label="Nhật Bản"><img class="slide-img" data-splide-lazy="/assets/images/mb-12.png" alt="" /></a>
                                </div>
                            </div>
                            <div class="mb-right mb-banner">
                                <a href="/the-loai/manga" aria-label="Nhật Bản"><img class="slide-img" data-splide-lazy="/assets/images/mb-13.png" alt="" /></a>
                            </div>
                        </div>
                    </div>
                    <div class="splide__slide dc-comics">
                        <div class="sl-wrapper">
                            <img class="lzl bg-slide" data-splide-lazy="/assets/images/bg-slide2.png" alt="banner" />
                            <div class="mb-left mb-banner">
                                <a href="/the-loai/dc-comics" aria-label="DC Comics"><img class="slide-img" data-splide-lazy="/assets/images/mb-21.png" alt="" /></a>
                            </div>
                            <div class="mb-mid__banner">
                                <div class="sm-tag">Truyện<span>DC COMICS</span></div>
                                <div class="mb-mid mb-banner">
                                    <a href="/the-loai/dc-comics" aria-label="DC Comics"><img class="slide-img" data-splide-lazy="/assets/images/mb-22.png" alt="" /></a>
                                </div>
                            </div>
                            <div class="mb-right mb-banner">
                                <a href="/the-loai/dc-comics" aria-label="DC Comics"><img class="slide-img" data-splide-lazy="/assets/images/mb-23.png" alt="" /></a>
                            </div>
                        </div>
                    </div>

                    <div class="splide__slide kor-comics">
                        <div class="sl-wrapper">
                            <img class="lzl bg-slide" data-splide-lazy="/assets/images/bg-slide3.png" alt="banner" />
                            <div class="mb-left mb-banner">
                                <a href="/the-loai/manhwa" aria-label="Hàn Quốc"><img class="slide-img" data-splide-lazy="/assets/images/mb-31.png" alt="" /></a>
                            </div>
                            <div class="mb-mid__banner">
                                <div class="sm-tag">Truyện<span>Hàn Quốc</span></div>
                                <div class="mb-mid mb-banner">
                                    <a href="/the-loai/manhwa" aria-label="Hàn Quốc"><img class="slide-img" data-splide-lazy="/assets/images/mb-32.png" alt="" /></a>
                                </div>
                            </div>
                            <div class="mb-right mb-banner">
                                <a href="/the-loai/manhwa" aria-label="Hàn Quốc"><img class="slide-img lzl" data-splide-lazy="/assets/images/mb-33.png" alt="" /></a>
                            </div>
                        </div>
                    </div>
                    <div class="splide__slide marvel-comics">
                        <div class="sl-wrapper">
                            <img class="lzl bg-slide" data-splide-lazy="/assets/images/bg-slide4.png" alt="banner" />
                            <div class="mb-left mb-banner">
                                <a href="/the-loai/marvel-comics" aria-label="Marvel Comics"><img class="slide-img" data-splide-lazy="/assets/images/mb-41.png" alt="" /></a>
                            </div>
                            <div class="mb-mid__banner">
                                <div class="sm-tag">
                                    Truyện<span>MARVEL COMICS</span>
                                </div>
                                <div class="mb-mid mb-banner">
                                    <a href="/the-loai/marvel-comics" aria-label="Marvel Comics"><img class="slide-img" data-splide-lazy="/assets/images/mb-42.png" alt="" /></a>
                                </div>
                            </div>
                            <div class="mb-right mb-banner">
                                <a href="/the-loai/marvel-comics" aria-label="Marvel Comics"><img class="slide-img" data-splide-lazy="/assets/images/mb-43.png" alt="" /></a>
                            </div>
                        </div>
                    </div>
                    <div class="splide__slide chi-comics">
                        <div class="sl-wrapper">
                            <img class="lzl bg-slide" data-splide-lazy="/assets/images/bg-slide5.png" alt="banner" />
                            <div class="mb-left mb-banner">
                                <a href="/the-loai/manhua" aria-label="Trung Quốc"><img class="slide-img" data-splide-lazy="/assets/images/mb-51.png" alt="" /></a>
                            </div>
                            <div class="mb-mid__banner">
                                <div class="sm-tag">Truyện<span>TRUNG QUỐC</span></div>
                                <div class="mb-mid mb-banner">
                                    <a href="/the-loai/manhua" aria-label="Trung Quốc"><img class="slide-img" data-splide-lazy="/assets/images/mb-52.png" alt="" /></a>
                                </div>
                            </div>
                            <div class="mb-right mb-banner">
                                <a href="/the-loai/manhua" aria-label="Trung Quốc"><img class="slide-img" data-splide-lazy="/assets/images/mb-53.png" alt="" /></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="splide__arrows">
                <button class="splide__arrow splide__arrow--prev">
                    <i class="icon-arrow-left"></i>
                </button>
                <button class="splide__arrow splide__arrow--next">
                    <i class="icon-arrow-right"></i>
                </button>
            </div>

        </div>
        <h1 class="main-title">{{env('APP_NAME')}} Chính Thức - Đọc truyện tranh miễn phí</h1>
    </section>

    <section id="manga-trend" class="container">
        <div class="m-trend splide splide-navtop">
            <div class="group-title justify-content-between">
                <h2 class="m-title title">Top thịnh hành<span class="sub">Truyện được mọi người yêu thích.</span></h2>
                <div class="splide__arrows position-relative">
                    <button class="splide__arrow splide__arrow--prev">
                        <i class="icon-arrow-left"></i>
                    </button>
                    <button class="splide__arrow splide__arrow--next">
                        <i class="icon-arrow-right"></i>
                    </button>
                </div>
            </div>
            <div class="splide__track">
                <div class="splide__list">

                    @foreach ($comicsMostViews as $item)
                    <div class="m-post splide__slide">
                        @if ($loop->iteration == 1)
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="65" viewBox="0 0 32 65" fill="none">
                            <path d="M19.0979 1H31V64H15.3353V16.6879V15.4342L14.1129 15.713L4.15686 17.984L1.20327 6.04715L19.0979 1Z" stroke="#FFD35A" stroke-width="2" />
                        </svg>
                        @elseif ($loop->iteration == 2)
                        <svg xmlns="http://www.w3.org/2000/svg" width="59" height="77" viewBox="0 0 59 77" fill="none">
                            <path d="M28.222 60.9129H58V76H1V61.7008L28.5152 40.3718L28.5152 40.3719L28.5257 40.3636C35.4513 34.8455 38.4874 31.1186 38.4874 25.9551C38.4874 23.3328 37.5702 21.1313 35.8852 19.593C34.2107 18.0641 31.874 17.2767 29.1805 17.2767C23.8926 17.2767 19.7986 20.2982 14.2688 26.609L1.80485 16.0778C5.5601 11.3021 9.27649 7.61654 13.6656 5.08384C18.2679 2.42817 23.6804 1 30.778 1C38.672 1 45.294 3.39823 49.9277 7.45726C54.5512 11.5074 57.2545 17.2599 57.2545 24.1166V24.3329C57.2545 30.193 55.7684 34.5832 52.9681 38.4997C50.1403 42.4547 45.943 45.9704 40.4167 50.0176L27.6427 59.0979L25.0892 60.9129H28.222Z" stroke="#DF7861" stroke-width="2" fill="none"></path>
                        </svg>
                        @elseif ($loop->iteration == 3)
                        <svg xmlns="http://www.w3.org/2000/svg" width="62" height="77" viewBox="0 0 62 77" fill="none">
                            <path d="M34.493 15.9314H6.23944V1H59.4718V14.5263L41.5971 29.6483L40.0529 30.9547L42.0285 31.3885C47.3297 32.5524 52.0756 34.5961 55.485 37.8364C58.8655 41.0493 61 45.5011 61 51.6569V51.8725C61 59.177 57.9949 65.1782 52.9682 69.3705C47.9265 73.5754 40.7983 76 32.5282 76C18.3855 76 8.5626 71.2301 1.39151 63.6584L13.3236 51.8697C18.7877 57.0622 24.5127 60.098 31.5458 60.098C34.7551 60.098 37.4519 59.3062 39.3693 57.7937C41.3116 56.2616 42.3697 54.053 42.3697 51.4412V51.2255C42.3697 48.4351 41.0569 46.184 38.6697 44.6776C36.3331 43.2032 33.0093 42.4608 28.9261 42.4608H20.7334L18.0507 32.9002L35.1577 17.6784L37.1212 15.9314H34.493Z" stroke="#596FB7" stroke-width="2" fill="none"></path>
                        </svg>
                        @elseif ($loop->iteration == 4)
                        <svg xmlns="http://www.w3.org/2000/svg" width="71" height="76" viewBox="0 0 71 76" fill="none">
                            <path d="M40.2342 1H60.2485V45.7078V46.7078H61.2485H70V60.339H61.2485H60.2485V61.339V75H41.6497V61.339V60.339H40.6497H4.29656L1.09028 46.8334L40.2342 1ZM21.5878 45.1704L20.1985 46.8156H22.3519H40.6497H41.6497V45.8156V24.1475V21.4134L39.8857 23.5023L21.5878 45.1704Z" stroke="#2B4992" stroke-width="2" fill="none"></path>
                        </svg>
                        @elseif ($loop->iteration == 5)
                        <svg xmlns="http://www.w3.org/2000/svg" width="61" height="76" viewBox="0 0 61 76" fill="none">
                            <path d="M8.82308 1H56.6998V16.0308H24.1658H23.2208L23.1674 16.9743L22.5287 28.2572L22.4339 29.9304L23.9514 29.2192C27.248 27.6742 30.6166 26.6527 35.4503 26.6527C41.9302 26.6527 48.0753 28.468 52.5801 32.1729C57.058 35.8556 60 41.4721 60 49.2829V49.4958C60 57.5223 56.9491 63.8603 51.7612 68.2084C46.5538 72.5728 39.1089 75 30.2339 75C17.7096 75 8.84675 70.8077 1.38783 64.0996L11.937 51.7464C17.8052 56.5415 23.463 59.437 29.9145 59.437C33.4693 59.437 36.4554 58.5773 38.5746 56.8911C40.7215 55.1829 41.8796 52.7028 41.8796 49.7087V49.4958C41.8796 46.475 40.6206 44.0241 38.4401 42.3551C36.2882 40.708 33.3122 39.874 29.9145 39.874C25.3101 39.874 21.4454 41.4103 17.9741 43.4455L6.78235 37.2179L8.82308 1Z" stroke="#2B4992" stroke-width="2" fill="none"></path>
                        </svg>
                        @elseif ($loop->iteration == 6)
                        <svg xmlns="http://www.w3.org/2000/svg" width="62" height="78" viewBox="0 0 62 78" fill="none">
                            <path d="M49.8393 21.5048C45.4437 18.356 41.1133 16.25 35.1834 16.25C30.4544 16.25 26.8636 18.0017 24.3539 21.0624C21.8767 24.0834 20.523 28.2915 20.0294 33.1124L19.7956 35.3966L21.6272 34.012C25.5004 31.0844 30.1185 28.4286 37.2215 28.4286C43.6142 28.4286 49.5642 30.4534 53.9011 34.2358C58.2214 38.0038 61 43.5648 61 50.7857V51C61 59.0681 57.7732 65.541 52.5912 70.0106C47.395 74.4925 40.1824 77 32.1799 77C21.6919 77 15.1968 73.9802 9.93161 68.7211C4.5933 63.3889 1 55.2108 1 41.3571V41.1429C1 29.611 3.80854 19.5674 9.40831 12.4298C14.9821 5.32525 23.3968 1 34.8616 1C44.8812 1 51.629 3.51789 58.4943 8.48979L49.8393 21.5048ZM31.3218 62.8214C34.8107 62.8214 37.6755 61.7033 39.6738 59.7673C41.6718 57.8316 42.7266 55.1518 42.7266 52.1786V51.9643C42.7266 48.9747 41.6067 46.2943 39.5558 44.3653C37.5069 42.4383 34.5947 41.3214 31.1073 41.3214C27.6257 41.3214 24.7617 42.4082 22.7609 44.3166C20.7584 46.2265 19.7024 48.8826 19.7024 51.8571V52.0714C19.7024 55.0595 20.8211 57.764 22.8677 59.7187C24.9141 61.6732 27.8273 62.8214 31.3218 62.8214Z" stroke="#2B4992" stroke-width="2" fill="none"></path>
                        </svg>
                        @elseif ($loop->iteration == 7)
                        <svg xmlns="http://www.w3.org/2000/svg" width="58" height="75" viewBox="0 0 58 75" fill="none">
                            <path d="M3.45179 74L35.7311 17.9634L36.5946 16.4643H34.8646H1V1H57V15.3819L24.1635 74H3.45179Z" stroke="#2B4992" stroke-width="2" fill="none"></path>
                        </svg>
                        @elseif ($loop->iteration == 8)
                        <svg xmlns="http://www.w3.org/2000/svg" width="61" height="78" viewBox="0 0 61 78" fill="none">
                            <path d="M13.1021 38.5187L14.9451 37.7292L13.2058 36.7319C10.3772 35.1101 7.96868 33.1556 6.26629 30.6653C4.57259 28.1877 3.54167 25.1276 3.54167 21.2238V21.0083C3.54167 9.72941 14.1566 1 30.5 1C46.8434 1 57.4583 9.72941 57.4583 21.0083V21.2238C57.4583 25.1276 56.4274 28.1877 54.7337 30.6653C53.0313 33.1556 50.6228 35.1101 47.7942 36.7319L46.1085 37.6985L47.8749 38.5085C51.5494 40.1932 54.5706 42.2622 56.6742 44.9847C58.7639 47.6892 60 51.1052 60 55.5912V55.8066C60 62.1855 57.0056 67.4436 51.8399 71.1402C46.6503 74.8539 39.245 77 30.5 77C21.7602 77 14.3538 74.8024 9.16141 71.0738C3.98892 67.3595 1 62.1255 1 55.9144V55.6989C1 51.3 2.11523 47.8531 4.14127 45.1008C6.17572 42.337 9.18594 40.1961 13.1021 38.5187ZM30.5 32.8895C33.4627 32.8895 35.9933 31.9597 37.7942 30.3108C39.6006 28.6569 40.6076 26.3371 40.6076 23.7017V23.4862C40.6076 18.6669 36.5974 14.5138 30.5 14.5138C24.4206 14.5138 20.3924 18.5425 20.3924 23.3785V23.5939C20.3924 26.2276 21.3978 28.5709 23.1991 30.2507C24.9972 31.9275 27.5289 32.8895 30.5 32.8895ZM30.5 63.3785C34.1269 63.3785 37.0476 62.3552 39.0789 60.6206C41.1177 58.8796 42.1962 56.4726 42.1962 53.8674V53.6519C42.1962 50.8986 40.9511 48.5652 38.8393 46.9463C36.7464 45.342 33.8429 44.4641 30.5 44.4641C27.1571 44.4641 24.2536 45.342 22.1607 46.9463C20.0489 48.5652 18.8038 50.8986 18.8038 53.6519V53.8674C18.8038 56.4726 19.8823 58.8796 21.9211 60.6206C23.9524 62.3552 26.8731 63.3785 30.5 63.3785Z" stroke="#2B4992" stroke-width="2" fill="none"></path>
                        </svg>
                        @endif

                        <div class="p-thumb flex-shrink-0">
                            <a title="{{$item->name}}" href="{{route('detail', ['slug' => $item->slug])}}">
                                <span class="img-poster">
                                    <img class="lzl" data-src="{{$item->thumbnail}}" rel="nofollow" data-original="{{$item->thumbnail}}" alt="{{$item->name}}" src="/assets/images/pre-load1.png" width="100%" height="100%">
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
                                    <span>
                                        {{number_format($item->rating, 1)}}
                                    </span>
                                </div>

                                <span class="num-view">{{\Illuminate\Support\Number::abbreviate($item->view_total)}} lượt xem</span>

                            </div>
                            <ul class="list-chaps">
                                @foreach ($item->chapters->take(-2) as $chapter)
                                    <li class="chapter">
                                        <a data-id="{{$chapter->id}}" href="{{route('showRead', ['slug' => $item->slug, 'chapter' => $chapter->slug])}}" title="{{$chapter->name}}">
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
        </div>
    </section>

    <div class="container">
        <div class="row">
            <div class="col-12 col-xl-8">
                <section id="manga-new_update">
                    <div class="list-manga splide">
                        <div class="group-title">
                            <h2 class="m-title title">Mới cập nhật
                                <span class="sub">Truyện mới được cập nhật.</span>
                            </h2>

                            <div class="swiper-btn__group">
                                <a href="/new?page=2" class="view-all">Xem tất cả</a>
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
                                @foreach ($comicsUpdated as $item)
                                <div class="m-post splide__slide">

                                    <div class="p-thumb flex-shrink-0">
                                        <a title="{{$item->name}}" href="{{route('detail', ['slug' => $item->slug])}}">
                                            <span class="img-poster">
                                                <img class="lzl" data-src="{{$item->thumbnail}}" rel="nofollow" data-original="{{$item->thumbnail}}" alt="{{$item->name}}" src="/assets/images/pre-load1.png" width="100%" height="100%">
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
                                            <span class="num-view">{{\Illuminate\Support\Number::abbreviate($item->view_total)}} lượt xem</span>
                                        </div>
                                        <ul class="list-chaps">
                                            @foreach ($item->chapters->take(-2) as $chapter)
                                                <li class="chapter">
                                                    <a data-id="{{$chapter->id}}" href="{{route('showRead', ['slug' => $item->slug, 'chapter' => $chapter->slug])}}" title="{{$chapter->name}}">
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

                        <ul class="pagination">
                            <li class="active" data-page="1">
                                <a href="/">1</a>
                            </li>

                            <li class="" data-page="2">
                                <a href="{{route('showNewUpdate', ['page' => 2])}}">2</a>
                            </li>

                            <li class="" data-page="3">
                                <a href="{{route('showNewUpdate', ['page' => 3])}}">3</a>
                            </li>
                        </ul>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-4">
                <section id="top-follow">
                    <h2 class="m-title title">Top theo dõi<span class="sub">Truyện mới được cập nhật.</span></h2>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="" class="nav-link active" id="day-tab" data-bs-toggle="tab" data-bs-target="#day" role="tab" aria-controls="day" aria-selected="true">Ngày</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="" class="nav-link" id="week-tab" data-bs-toggle="tab" data-bs-target="#week" role="tab" aria-controls="week" aria-selected="false">Tuần</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="" class="nav-link" id="month-tab" data-bs-toggle="tab" data-bs-target="#month" role="tab" aria-controls="month" aria-selected="false">Tháng</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="TopFollow">
                        <div class="tab-pane fade show active" id="day" role="tabpanel" aria-labelledby="day-tab">
                            <ul class="list-unstyled">
                                @foreach ($comicsDaily as $item)
                                <li>

                                    <div class="p-thumb flex-shrink-0">
                                        <a title="{{$item->name}}" href="{{route('detail', ['slug' => $item->slug])}}">
                                            <span class="img-poster">
                                                <img class="lzl" data-src="{{$item->thumbnail}}" rel="nofollow" data-original="{{$item->thumbnail}}" alt="{{$item->name}}" src="/assets/images/pre-load1.png" width="100%" height="100%">
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

                                            <span class="num-view">{{\Illuminate\Support\Number::abbreviate($item->view_total)}} lượt xem</span>

                                        </div>
                                        <ul class="list-chaps">
                                            @isset($item->lastChapter)
                                                <li class="chapter">
                                                    <a data-id="{{$item->lastChapter->id}}" href="{{route('showRead', ['slug' => $item->slug, 'chapter' => $item->lastChapter->slug])}}" title="{{$item->lastChapter->name}}">
                                                        Chapter {{$item->lastChapter->name}}<span>{{$item->lastChapter->created_at->diffForHumans()}}</span>
                                                    </a>
                                                </li>
                                            @endisset
                                        </ul>
                                    </div>

                                    <span class="rank rank-{{$loop->iteration}}">{{sprintf('%02d', $loop->iteration)}}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="week" role="tabpanel" aria-labelledby="week-tab">
                            <ul class="list-unstyled">
                                @foreach ($comicsWeekly as $item)
                                <li>

                                    <div class="p-thumb flex-shrink-0">
                                        <a title="{{$item->name}}" href="{{route('detail', ['slug' => $item->slug])}}">
                                            <span class="img-poster">
                                                <img class="lzl" data-src="{{$item->thumbnail}}" rel="nofollow" data-original="{{$item->thumbnail}}" alt="{{$item->name}}" src="/assets/images/pre-load1.png" width="100%" height="100%">
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

                                            <span class="num-view">{{\Illuminate\Support\Number::abbreviate($item->view_total)}} lượt xem</span>

                                        </div>
                                        <ul class="list-chaps">
                                            @isset($item->lastChapter)
                                                <li class="chapter">
                                                    <a data-id="{{$item->lastChapter->id}}" href="{{route('showRead', ['slug' => $item->slug, 'chapter' => $item->lastChapter->slug])}}" title="{{$item->lastChapter->name}}">
                                                        Chapter {{$item->lastChapter->name}}<span>{{$item->lastChapter->created_at->diffForHumans()}}</span>
                                                    </a>
                                                </li>
                                            @endisset
                                        </ul>
                                    </div>

                                    <span class="rank rank-{{$loop->iteration}}">{{sprintf('%02d', $loop->iteration)}}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="month" role="tabpanel" aria-labelledby="month-tab">
                            <ul class="list-unstyled">
                                @foreach ($comicsMonthly as $item)
                                <li>
                                    <div class="p-thumb flex-shrink-0">
                                        <a title="{{$item->name}}" href="{{route('detail', ['slug' => $item->slug])}}">
                                            <span class="img-poster">
                                                <img class="lzl" data-src="{{$item->thumbnail}}" rel="nofollow" data-original="{{$item->thumbnail}}" alt="{{$item->name}}" src="/assets/images/pre-load1.png" width="100%" height="100%">
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

                                            <span class="num-view">{{\Illuminate\Support\Number::abbreviate($item->view_total)}} lượt xem</span>

                                        </div>
                                        <ul class="list-chaps">
                                            @isset($item->lastChapter)
                                                <li class="chapter">
                                                    <a data-id="{{$item->lastChapter->id}}" href="{{route('showRead', ['slug' => $item->slug, 'chapter' => $item->lastChapter->slug])}}" title="{{$item->lastChapter->name}}">
                                                        Chapter {{$item->lastChapter->name}}<span>{{$item->lastChapter->created_at->diffForHumans()}}</span>
                                                    </a>
                                                </li>
                                            @endisset
                                        </ul>
                                    </div>

                                    <span class="rank rank-{{$loop->iteration}}">{{sprintf('%02d', $loop->iteration)}}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <section id="m-finish" class="container">
        <div class="m-suggest splide splide-navtop">
            <div class="group-title">
                <h2 class="m-title title">ĐÃ HOÀN THÀNH<span class="sub">Gợi ý theo sở thích của bạn.</span></h2>
                <div class="swiper-btn__group">

                    <a href="{{route('showCompleted')}}" class="view-all">Xem tất cả</a>

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
                    @foreach ($completedComics as $item)
                    <div class="m-post horizontal splide__slide">

                        <div class="p-thumb flex-shrink-0">
                            <a title="{{$item->name}}" href="{{route('detail', ['slug' => $item->slug])}}">
                                <span class="img-poster">
                                    <img class="lzl" data-src="{{$item->thumbnail}}" rel="nofollow" data-original="{{$item->thumbnail}}" alt="{{$item->name}}" src="/assets/images/pre-load1.png" width="100%" height="100%">
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
                                @isset($item->lastChapter)
                                    <li class="chapter">
                                        <a data-id="{{$item->lastChapter->id}}" href="{{route('showRead', ['slug' => $item->slug, 'chapter' => $item->lastChapter->slug])}}" title="{{$item->lastChapter->name}}">
                                            Chapter {{$item->lastChapter->name}}<span>{{$item->lastChapter->created_at->diffForHumans()}}</span>
                                        </a>
                                    </li>
                                @endisset
                            </ul>
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="container">
        <div class="row">
            <div class="col-12 col-xl-3">
                <div class="banner-cmt d-none d-xl-block">
                    <img class="" src="/assets/images/banner-cmt.png" alt="Banner bình luận">
                </div>
            </div>
            <div class="col-12 col-xl-9">
                <div class="top-comments splide">
                    <h3 class="title">Bình luận mới nhất</h3>
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach ($newComments as $item)
                            <li class="splide__slide">
                                <div class="tc-item">
                                    <div class="tc-v">
                                        <div class="tc-header">
                                            <div class="user-avatar"><img alt="" rel="nofollow" src="{{$item->user->avatar}}" /></div>
                                            <div class="info">
                                                <div class="user-name">{{$item->user->name}}</div>
                                                <span class="tc-time">{{$item->created_at->diffForHumans()}}</span>
                                            </div>
                                        </div>
                                        <div class="tc-thumb">
                                            <a class="tc-thumbnail" title="{{$item->comic->name}}" href="{{route('detail', ['slug' => $item->comic->slug])}}">
                                                <img class="lzl" data-src="{{$item->comic->thumbnail}}" rel="nofollow" alt="{{$item->comic->name}}" src="/assets/images/pre-load1.png" width="100%" height="100%">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="cmt-description">
                                        {{$item->content}}
                                    </div>
                                    <div class="tc-footer">
                                        <a href="{{route('detail', ['slug' => $item->comic->slug])}}" class="tc-name">{{$item->comic->name}}</a>
                                    </div>
                                    <a class="tc-link" href="{{route('detail', ['slug' => $item->comic->slug])}}#cmt-{{$item->id}}"></a>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="manga-feature_genres" class="container">
        <h2 class="m-title title">Thể loại nổi bật<span class="sub">Các thể loại tại {{env('APP_NAME')}}</span></h2>
        <div class="top-genres splide">
            <div class="splide__track">
                <div class="splide__list">
                    @php
                        $images = [
                            'action',
                            'romance',
                            'comedy',
                            'fantasy',
                        ]
                    @endphp
                    @foreach ($categories as $item)
                    <div class="g-item splide__slide">
                        <div class="genre-content">
                            <a href="{{route('showCategory', ['slug' => $item->slug])}}" class="g-link" aria-label="{{$item->name}}">
                                <h3 class="g-name">
                                    <a href="{{route('showCategory', ['slug' => $item->slug])}}">{{$item->name}}
                                        <i class="icon-login"></i>
                                    </a>
                                </h3>
                                <div class="g-thumb">

                                    <img src="/assets/images/{{$images[$loop->iteration - 1]}}.png" alt="{{$item->name}}" />

                                    <div class="num-series"><strong>{{$item->comics_count}}</strong>bộ truyện</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="325" height="234" viewBox="0 0 325 234" fill="none" class="bg-cat">
                                    <path d="M0 222.626V11.5503C0 6.83781 5 4.49225e-05 14 4.49225e-05L44.5 0C53.5 0 65 4.27481 65 15.5749C65 26.875 57 25.5543 47.8528 31.15C38.7055 36.7456 23.9264 51.2352 77.7607 49.4022C94.5912 48.8291 97.6994 36.0883 97.6994 21.9063C97.6994 7.7244 115.644 3.35107e-05 139.571 3.35107e-05H291.104C305.061 3.351e-05 325 3.43323e-05 325 18.3306V37.2279C325 49.1427 305.061 53.0683 293.098 53.0683H230.5C195.399 53.0683 189.417 90.2899 230.5 90.2899H279.141C315.031 90.2899 325 98.5387 325 113.203V175.883C325 193.297 309.049 197.88 281.135 197.88H189.417C159.509 197.88 160.628 215.015 147 226.406C134.938 236.488 123.62 233.624 41.8712 233.624C17.9448 233.624 0 232.707 0 222.626Z" fill="#596FB7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="blog-section" class="container">
        <div class="blog-home splide splide-navtop">
            <div class="group-title">
                <h3 class="m-title title">Tin tức<span class="sub">Bài viết mới nhất từ cộng đồng.</span></h3>
                <div class="swiper-btn__group">
                    <a href="{{route('showNews')}}" class="view-all">Xem tất cả</a>
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
                    @foreach ($blogs as $item)
                    <div class="splide__slide">
                        <div class="item-card">
                            <div class="item-image">
                                <a href="{{route('showBlog', ['slug' => $item->slug])}}" class="item-link" alt=""></a>
                                <img src="{{$item->image}}" alt="{{$item->title}}">
                            </div>
                            <div class="item-content">
                                <h3 class="item-title"><a href="{{route('showBlog', ['slug' => $item->slug])}}" alt="">{{$item->title}}</a></h3>
                                <div class="item-description">{!! Str::limit((strip_tags($item->content)), 100) !!}</div>
                                <div class="item-info">

                                    <span class="author">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M8.10646 7.74732C8.08646 7.74732 8.07313 7.74732 8.05313 7.74732C8.01979 7.74065 7.97313 7.74065 7.93313 7.74732C5.99979 7.68732 4.53979 6.16732 4.53979 4.29398C4.53979 2.38732 6.09313 0.833984 7.99979 0.833984C9.90646 0.833984 11.4598 2.38732 11.4598 4.29398C11.4531 6.16732 9.98646 7.68732 8.12646 7.74732C8.11979 7.74732 8.11313 7.74732 8.10646 7.74732ZM7.99979 1.83398C6.64646 1.83398 5.5398 2.94065 5.5398 4.29398C5.5398 5.62732 6.57979 6.70065 7.90646 6.74732C7.93979 6.74065 8.03313 6.74065 8.1198 6.74732C9.42646 6.68732 10.4531 5.61398 10.4598 4.29398C10.4598 2.94065 9.35313 1.83398 7.99979 1.83398Z" fill="#787978" />
                                            <path d="M8.11307 15.0327C6.80641 15.0327 5.49307 14.6993 4.49974 14.0327C3.57307 13.4193 3.06641 12.5793 3.06641 11.666C3.06641 10.7527 3.57307 9.90602 4.49974 9.28602C6.49974 7.95935 9.73974 7.95935 11.7264 9.28602C12.6464 9.89935 13.1597 10.7393 13.1597 11.6527C13.1597 12.566 12.6531 13.4127 11.7264 14.0327C10.7264 14.6993 9.41974 15.0327 8.11307 15.0327ZM5.05307 10.126C4.41307 10.5527 4.06641 11.0993 4.06641 11.6727C4.06641 12.2393 4.41974 12.786 5.05307 13.206C6.71307 14.3193 9.51307 14.3193 11.1731 13.206C11.8131 12.7793 12.1597 12.2327 12.1597 11.6593C12.1597 11.0927 11.8064 10.546 11.1731 10.126C9.51307 9.01935 6.71307 9.01935 5.05307 10.126Z" fill="#787978" />
                                        </svg>{{$item->user->name}}
                                    </span>

                                    <span class="date">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M8.00008 15.1667C4.50675 15.1667 1.66675 12.3267 1.66675 8.83333C1.66675 5.34 4.50675 2.5 8.00008 2.5C11.4934 2.5 14.3334 5.34 14.3334 8.83333C14.3334 12.3267 11.4934 15.1667 8.00008 15.1667ZM8.00008 3.5C5.06008 3.5 2.66675 5.89333 2.66675 8.83333C2.66675 11.7733 5.06008 14.1667 8.00008 14.1667C10.9401 14.1667 13.3334 11.7733 13.3334 8.83333C13.3334 5.89333 10.9401 3.5 8.00008 3.5Z" fill="#787978" />
                                            <path d="M8 9.16732C7.72667 9.16732 7.5 8.94065 7.5 8.66732V5.33398C7.5 5.06065 7.72667 4.83398 8 4.83398C8.27333 4.83398 8.5 5.06065 8.5 5.33398V8.66732C8.5 8.94065 8.27333 9.16732 8 9.16732Z" fill="#787978" />
                                            <path d="M10 1.83398H6C5.72667 1.83398 5.5 1.60732 5.5 1.33398C5.5 1.06065 5.72667 0.833984 6 0.833984H10C10.2733 0.833984 10.5 1.06065 10.5 1.33398C10.5 1.60732 10.2733 1.83398 10 1.83398Z" fill="#787978" />
                                        </svg>
                                        {{$item->created_at->format('d/m/Y')}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts')
<script src="{{asset('assets/theme/js/splide-extension-grid.min.js')}}"></script>
<script src="{{asset('assets/theme/js/suggest.js')}}"></script>
<script src="{{asset('assets/theme/js/index.js')}}"></script>
@endsection
