@extends("users.layout.main")
@php
    $currentQuery = request()->all();
@endphp

@section('metadata')
    <title>{{$currentQuery['keyword'] ?? ''}} - Đọc truyện {{$currentQuery['keyword'] ?? ''}} mới nhất | {{env('APP_NAME')}}</title>
    <meta property="og:title" content="Truyện {{$currentQuery['keyword'] ?? ''}} mới nhất, truyện full chap đầy đủ. Thông tin về {{$currentQuery['keyword'] ?? ''}} hay cập nhật mới nhất tại {{env('APP_NAME')}}">
    <meta name="keywords" content="{{$currentQuery['keyword'] ?? ''}},{{env('APP_NAME')}},đọc truyện,truyện tranh,truyện full">
@endsection

@section('content')
<div class="search-wrapper">
    <div class="container">
        <div class="page-breadcrumb">
            <span class="item"><a href="/">Trang chủ</a></span>
            <span class="item breadcrumb_last" aria-current="page">Tìm kiếm</span>
        </div>

        <div class="row flex-lg-row-reverse">
            <div class="col-lg-4">
                <div class="sidebar mb-3">
                    <span class="title">Bộ lọc</span>

                    <div class="form-search-normal">
                        <form class="form-search">
                            <input class="form-control" type="text" placeholder="Tìm kiếm tên truyện" aria-label="Tìm kiếm" value="{{$currentQuery['keyword'] ?? ''}}">
                            <i class="icon-search-normal"></i>
                        </form>
                        <a href="" class="fsn-icon d-lg-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M10.9404 22.65C10.4604 22.65 9.99039 22.53 9.55039 22.29C8.67039 21.8 8.14039 20.91 8.14039 19.91V14.61C8.14039 14.11 7.81039 13.36 7.50039 12.98L3.76039 9.01998C3.13039 8.38998 2.65039 7.30998 2.65039 6.49998V4.19998C2.65039 2.59998 3.86039 1.34998 5.40039 1.34998H18.6004C20.1204 1.34998 21.3504 2.57998 21.3504 4.09998V6.29998C21.3504 7.34998 20.7204 8.53998 20.1304 9.12998L15.8004 12.96C15.3804 13.31 15.0504 14.08 15.0504 14.7V19C15.0504 19.89 14.4904 20.92 13.7904 21.34L12.4104 22.23C11.9604 22.51 11.4504 22.65 10.9404 22.65ZM5.40039 2.84998C4.70039 2.84998 4.15039 3.43998 4.15039 4.19998V6.49998C4.15039 6.86998 4.45039 7.58998 4.83039 7.96998L8.64039 11.98C9.15039 12.61 9.65039 13.66 9.65039 14.6V19.9C9.65039 20.55 10.1004 20.87 10.2904 20.97C10.7104 21.2 11.2204 21.2 11.6104 20.96L13.0004 20.07C13.2804 19.9 13.5604 19.36 13.5604 19V14.7C13.5604 13.63 14.0804 12.45 14.8304 11.82L19.1104 8.02998C19.4504 7.68998 19.8604 6.87998 19.8604 6.28998V4.09998C19.8604 3.40998 19.3004 2.84998 18.6104 2.84998H5.40039Z" fill="#473D98"></path>
                                <path d="M5.99968 10.75C5.85968 10.75 5.72968 10.71 5.59968 10.64C5.24968 10.42 5.13968 9.95002 5.35968 9.60002L10.2897 1.70002C10.5097 1.35002 10.9697 1.24002 11.3197 1.46002C11.6697 1.68002 11.7797 2.14002 11.5597 2.49002L6.62968 10.39C6.48968 10.62 6.24968 10.75 5.99968 10.75Z" fill="#473D98"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="list-settings">
                        <div id="dd-sort" class="dropdown">
                            <a href="" class="dropdown-toggle" id="dropdownZoom" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>Sắp xếp</span><i class="icon-arrow-down-1"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownZoom">
                                <div class="list-mode">
                                    <span><a data-value="view_desc" class="dropdown-item" href="#">Lượt xem giảm dần</a></span>
                                    <span><a data-value="view_asc" class="dropdown-item" href="#">Lượt
                                            xem tăng dần</a></span>
                                    <span><a data-value="udpated_at_date_desc" class="dropdown-item" href="#">Ngày cập nhật giảm dần</a></span>
                                    <span><a data-value="udpated_at_date_asc" class="dropdown-item" href="#">Ngày cập nhật tăng dần</a></span>
                                    <span><a data-value="created_at_date_desc" class="dropdown-item" href="#">Ngày đăng giảm dần</a></span>
                                    <span><a data-value="created_at_date_asc" class="dropdown-item" href="#">Ngày đăng tăng dần</a></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <span>Thể loại:</span>
                            <div class="list-genres">
                                @foreach ($categories as $category)
                                <span data-value="{{$category->id}}" class="  ">
                                    #{{$category->name}}
                                </span>
                                @endforeach
                            </div>
                        </div>

                        <a class="btn btn-filter" href="#">
                            <span>Tìm kiếm</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="group-title">
                    <div class="only-title">
                        <h1 class="m-title title">{{$currentQuery['keyword'] ?? ''}}</h1>
                        <h2 class="sub">Kết quả có chứa từ "<strong class="color">{{$currentQuery['keyword'] ?? ''}}</strong>"</h2>
                    </div>

                    <span>Có <strong class="color">{{$comics->total()}}</strong> kết quả liên quan</span>
                </div>

                <div class="search-result">
                    <div class="row">
                        @foreach ($comics as $comic)
                        <div class="m-post col-md-6">

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
                                    <span class="num-view">{{\Illuminate\Support\Number::abbreviate($comic->view_total)}} lượt xem</span>
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

                    {{$comics->appends($currentQuery)->links('vendor.pagination.custom')}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    const listMangasElem = $('.search-wrapper .col-lg-8');
const filterWrapper = $('.search-wrapper');
const pageElem = $('.search-wrapper .search-result .pagination');
const countPage = $('.search-wrapper .search-result .pagination').attr(
    'data-count-page',
);
const url = new URL(window.location.href);
let currentPage = Number(url.searchParams.get('page')) || 1;

async function handleFillChossenData() {
    const genreElements = filterWrapper.find('.list-genres>span');
    const chosenGenreIds = url.searchParams.get('genreIds')?.split(',') || [];
    for (let i = 0; i < genreElements.length; i++) {
        const genreId = $(genreElements[i]).attr('data-value');
        if (genreId && chosenGenreIds.includes(genreId)) {
            $(genreElements[i]).addClass('active');
        }
    }

    const chosenCategoryIds =
        url.searchParams.get('categoryIds')?.split(',') || [];
    const categoryElements = filterWrapper.find('.list-cats .form-check input');
    let allCate = true;
    for (let i = 0; i < categoryElements.length; i++) {
        const cateId = $(categoryElements[i]).attr('value');
        if (cateId && chosenCategoryIds.includes(cateId)) {
            $(categoryElements[i]).prop('checked', true);
        } else if (cateId) {
            allCate = false;
        }
    }
    if (allCate) {
        filterWrapper.find('#cat--all').prop('checked', true);
    }

    const chosenOrder = url.searchParams.get('orderBy');
    if (chosenOrder) {
        const orderElements = filterWrapper.find(
            '#dd-sort .dropdown-menu .dropdown-item',
        );
        for (const orderElement of orderElements) {
            if ($(orderElement).attr('data-value') === chosenOrder) {
                var selectedText = $(orderElement).text();
                var selectedValue = $(orderElement).data('value');
                var dropdown = $(orderElement).closest('.dropdown');
                var dropdownToggle = dropdown.find('.dropdown-toggle');
                var iconElement = dropdownToggle.find('i');
                var subElement = dropdownToggle.find('sub');

                if (subElement.length) {
                    subElement.text(selectedText);
                } else {
                    var newSubElement = $('<sub>').text(selectedText);
                    iconElement.before(newSubElement);
                }

                dropdown.attr('data-value', selectedValue);
            }
        }
    }
}
handleFillChossenData();

async function handleSearchAdvance(isMovePage) {
    const categoryIds = filterWrapper
        .find('.list-cats')
        .find('input:checkbox:checked')
        .map(function () {
            return parseInt($(this).val());
        })
        .get();
    const genreIds = filterWrapper
        .find('.list-genres>span.active')
        .map(function () {
            return parseInt($(this).attr('data-value'));
        })
        .get();

    const orderBy =
        filterWrapper.find('#dd-sort').attr('data-value') || undefined;
    const keyword = filterWrapper.find('input').val();

    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });

    currentPage = isMovePage ? currentPage : 1;

    url.searchParams.set('page', currentPage);

    if (keyword) {
        url.searchParams.set('keyword', keyword);
    } else {
        url.searchParams.delete('keyword');
    }
    if (categoryIds.length) {
        url.searchParams.set(
            'categoryIds',
            categoryIds.filter((c) => !isNaN(c)).join(','),
        );
    } else {
        url.searchParams.delete('categoryIds');
    }
    if (genreIds.length) {
        url.searchParams.set('genreIds', genreIds.join(','));
    } else {
        url.searchParams.delete('genreIds');
    }
    if (orderBy) {
        url.searchParams.set('orderBy', orderBy);
    } else {
        url.searchParams.delete('orderBy');
    }

    window.location.href = url.href;

    // const res = await searchAdvance(
    //     keyword,
    //     currentPage,
    //     categoryIds,
    //     genreIds,
    //     // countChapter,
    //     orderBy,
    // );

    // if (res) {
    //     listMangasElem.empty().append(res);
    //     observeNewImages();
    // }
}

$('a.btn-filter').on('click', async function (e) {
    e.preventDefault();
    await handleSearchAdvance(false);
});

// Pagination
filterWrapper.on('click', '.pagination > li', async function (e) {
    e.preventDefault();
    if ($(this).hasClass('active')) {
        return;
    }
    let dataPage = parseInt($(this).attr('data-page'));
    const isNextPage = !dataPage;
    const isPrevPage = dataPage == -1;
    if (isNextPage && countPage <= currentPage) {
        return;
    }

    if (isPrevPage && currentPage <= 1) {
        return;
    }
    currentPage = isNextPage
        ? currentPage + 1
        : isPrevPage
          ? currentPage - 1
          : dataPage;

    url.searchParams.set('page', currentPage);
    window.location.href = url.href;
});

$('.form-search-normal > .form-search').on('submit', function (e) {
    e.preventDefault();
    handleSearchAdvance(false);
});

$('#view-all-tags').on('click', function (e) {
    e.preventDefault();
    $(this).attr('style', 'display: none !important');
    $(this)
        .parent()
        .find('.list-genres > span')
        .each((i, e) => {
            $(e).removeClass('d-none');
        });
});
</script>
@endsection
