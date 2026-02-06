@php
    use Illuminate\Support\Facades\Cache;
    use App\Models\Category;
    $categoriesHeader = Cache::remember('categoriesHeader', 3600, function () {
        return Category::all();
    });
@endphp

<header class="header" id="header">
    <div class="container">
        <div class="main-header d-flex align-items-center">
            <a id="mobile_menu" class="d-flex d-xl-none" data-bs-toggle="offcanvas" href="#menumobile"
                aria-label="Menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M21 7.75H3C2.59 7.75 2.25 7.41 2.25 7C2.25 6.59 2.59 6.25 3 6.25H21C21.41 6.25 21.75 6.59 21.75 7C21.75 7.41 21.41 7.75 21 7.75Z"
                        fill="#1E201E"></path>
                    <path
                        d="M21 12.75H3C2.59 12.75 2.25 12.41 2.25 12C2.25 11.59 2.59 11.25 3 11.25H21C21.41 11.25 21.75 11.59 21.75 12C21.75 12.41 21.41 12.75 21 12.75Z"
                        fill="#1E201E"></path>
                    <path
                        d="M21 17.75H3C2.59 17.75 2.25 17.41 2.25 17C2.25 16.59 2.59 16.25 3 16.25H21C21.41 16.25 21.75 16.59 21.75 17C21.75 17.41 21.41 17.75 21 17.75Z"
                        fill="#1E201E"></path>
                </svg>
            </a>
            <a class="d-flex d-xl-none menu-random" href="{{route('random')}}">
                <img alt="" src="/assets/images/random.png" width="24" height="24" />
            </a>
            <a class="logo" title="Truyện tranh online" href="/">
                <img class="logo-light" alt="Đọc truyện tranh miễn phí tại {{env('APP_NAME')}}" src="/logo.png"
                    width="150" height="54" />
                <img class="logo-dark" alt="Đọc truyện tranh miễn phí tại {{env('APP_NAME')}}" src="/logo.png"
                    width="150" height="54" />
            </a>

            <ul class="nav navbar-nav flex-row flex-wrap main-menu d-none d-xl-flex">
                <li class="">
                    <a href="{{route('random')}}">Random</a>
                </li>
                <li class="">
                    <a href="{{route('hot')}}">Hot nhất</a>
                </li>
                <li class="has-sub">
                    <a href="{{route('showCategory')}}" class="sub-toggle" aria-expanded="false">Thể loại
                        <i class="icon-arrow-down-1"></i>
                    </a>
                    <div class="dropdown-menu">
                        @foreach ($categoriesHeader as $category)
                            <span><a class="dropdown-item"
                                    href="{{route('showCategoryBySlug', ['slug' => $category->slug])}}">{{$category->name}}</a></span>
                        @endforeach
                    </div>
                </li>
                <li>
                    <a href="{{route('showNews')}}">Tin tức</a>
                </li>
            </ul>

            <div class="header-right d-flex align-items-center justify-content-end ms-auto">
                <a href="#" class="toggle-formsearch ms-auto d-flex d-xl-none" aria-label="Search">
                    <i class="icon-search-normal"></i>
                </a>
                <!-- Search Modal -->
                <form class="d-xl-flex form-search" id="form-search" action="{{route('showSearch')}}">
                    <input class="form-control" type="text" placeholder="Tìm kiếm" aria-label="Tìm kiếm" />
                    <a href="{{route('showSearch')}}" class="i-filter"> Nâng cao </a>
                    <i class="icon-search-normal"></i>
                    <button type="button" class="s-clear">
                        <i class="icon-close-circle"></i>
                    </button>
                    <div class="nav search-result-wrapper" id="search-suggest" style="display: none">
                        <p>Gợi ý cho bạn</p>
                        <div class="tab-content">
                            <ul class="result list-unstyled"></ul>
                            <a href="{{route('showSearch')}}" class="view-all">
                                Xem toàn bộ kết quả
                            </a>
                        </div>
                    </div>
                    <div class="overlay"></div>
                </form>

                <script>
                    const listMangaElem = $("#form-search .result").first();

                    function renderMatchedTitle(search, title) {
                        const collator = new Intl.Collator(undefined, {
                            sensitivity: "base",
                        });
                        const searchWords = search.toLowerCase().split(/\s+/);
                        const titleLower = title.toLowerCase();

                        // Try to match the full search term first
                        const fullMatchIndex = titleLower.indexOf(
                            search.toLowerCase(),
                        );
                        if (fullMatchIndex !== -1) {
                            const matched = title.slice(
                                fullMatchIndex,
                                fullMatchIndex + search.length,
                            );
                            return (
                                title.slice(0, fullMatchIndex) +
                                `<span class="color">${matched}</span>` +
                                title.slice(fullMatchIndex + search.length)
                            );
                        }

                        // If full search term is not matched, fallback to individual words
                        const matches = searchWords.map((word) => {
                            let index = -1;
                            for (
                                let i = 0;
                                i <= titleLower.length - word.length;
                                i++
                            ) {
                                if (
                                    collator.compare(
                                        titleLower.slice(i, i + word.length),
                                        word,
                                    ) === 0
                                ) {
                                    index = i;
                                    break;
                                }
                            }

                            // If no match is found, return the original title
                            if (index === -1) {
                                return { index: -1, matched: null };
                            }

                            const matched = title.slice(index, index + word.length);
                            // return title.slice(0, index) + `<span class="color">${matched}</span>` + title.slice(index + search.length);
                            return { index, matched };
                        });

                        let result = title;
                        for (const matched of matches) {
                            if (matched.matched) {
                                result = result.replace(
                                    matched.matched,
                                    `<span class="color">${matched.matched}</span>`,
                                );
                            }
                        }
                        return result;
                    }

                    function appendRecommendMangas(mangas, keyword) {
                        listMangaElem.empty();
                        listMangaElem.append(
                            mangas.map((manga) => {
                                const title = manga.name;
                                const posterPath = manga.thumbnail;
                                const slug = manga.slug;
                                return `<li>
                                <div class="p-thumb flex-shrink-0">
                                    <a title="${title}" href="${slug}" rel="nofollow">
                                        <img
                                            class="img-poster"
                                            data-original="${posterPath}"
                                            alt="${title}"
                                            src="${posterPath}"
                                        />
                                    </a>
                                </div>
                                <div class="p-content flex-grow-1">
                                    <h3 class="m-name">
                                        <a href="${manga.url}">${renderMatchedTitle(keyword, manga.name)}</a>
                                    </h3>

                                    <div class="group-star">
                                        <div class="list-chaps">
                                            ${manga.last_chapter &&
                                    `<span class="chapter">
                                                    <a data-id="${manga.last_chapter.id}" href="${manga.url}/${manga.last_chapter.slug}" title="${manga.last_chapter.name}">
                                                        Chapter ${manga.last_chapter.name}
                                                    </a>
                                                </span>`
                                    }
                                        </div>
                                    </div>
                                </div>
                            </li>`;
                            }),
                        );
                    }

                    $("#form-search input").on(
                        "keyup",
                        debounce(function () {
                            const keyword = $(this).val();
                            $.ajax({
                                method: "GET",
                                url: `/api/mangas/search?keyword=${keyword}`,
                                success: function (res) {
                                    if (res.status && res.data) {
                                        const mangas = res.data;
                                        appendRecommendMangas(mangas, keyword);
                                        $("#search-suggest .tab-content > a").attr(
                                            "href",
                                            `{{route('showSearch')}}?keyword=${keyword}`,
                                        );
                                    } else {
                                        alert("search error");
                                    }
                                },
                                error: function (err) {
                                    alert("search error: " + err);
                                },
                            });
                        }, 200),
                    );

                    $(document).ready(function () {
                        // Toggle Search Form Mobile
                        const forms = $(".form-search");
                        const btnToggleSearchForms = $(".toggle-formsearch");

                        if (btnToggleSearchForms.length) {
                            // Add a click event listener to each button
                            btnToggleSearchForms.each(function (index) {
                                $(this).on("click", function () {
                                    const form = forms.eq(index);
                                    form.toggleClass("active-mobile");
                                    form.find("input").focus();
                                    form.find(".search-result-wrapper").show();
                                });
                            });
                        }

                        // Show search result wrapper when input is focused
                        forms.find("input").on("focus", function () {
                            $(this)
                                .closest(".form-search")
                                .find(".search-result-wrapper")
                                .show();
                        });

                        // Hide search result wrapper when clicking outside
                        $(document).on("click", function (event) {
                            if (
                                !$(event.target).closest(
                                    ".form-search, a.toggle-formsearch, .search-result-wrapper",
                                ).length
                            ) {
                                $(".search-result-wrapper").hide();
                                forms.removeClass("active-mobile");
                            }
                        });
                        $(".form-search .overlay").on("click", function () {
                            $(this)
                                .closest(".form-search")
                                .find(".search-result-wrapper")
                                .hide();
                            $(this)
                                .closest(".form-search")
                                .removeClass("active-mobile");
                        });
                    });
                </script>

                <div class="d-none d-xl-block">
                    <label class="switch">
                        <input class="input-dark-mode" type="checkbox" />
                        <div class="slider round">
                            <div class="sun-moon">
                                <svg id="moon-dot-1" class="moon-dot" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <svg id="moon-dot-2" class="moon-dot" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <svg id="moon-dot-3" class="moon-dot" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <svg id="light-ray-1" class="light-ray" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <svg id="light-ray-2" class="light-ray" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <svg id="light-ray-3" class="light-ray" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>

                                <svg id="cloud-1" class="cloud-dark" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <svg id="cloud-2" class="cloud-dark" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <svg id="cloud-3" class="cloud-dark" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <svg id="cloud-4" class="cloud-light" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <svg class="cloud-light cloud-5" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <svg class="cloud-light cloud-6" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                            </div>
                            <div class="stars">
                                <svg class="star star-1" viewBox="0 0 20 20">
                                    <path
                                        d="M 0 10 C 10 10,10 10 ,0 10 C 10 10 , 10 10 , 10 20 C 10 10 , 10 10 , 20 10 C 10 10 , 10 10 , 10 0 C 10 10,10 10 ,0 10 Z">
                                    </path>
                                </svg>
                                <svg class="star star-2" viewBox="0 0 20 20">
                                    <path
                                        d="M 0 10 C 10 10,10 10 ,0 10 C 10 10 , 10 10 , 10 20 C 10 10 , 10 10 , 20 10 C 10 10 , 10 10 , 10 0 C 10 10,10 10 ,0 10 Z">
                                    </path>
                                </svg>
                                <svg class="star star-3" viewBox="0 0 20 20">
                                    <path
                                        d="M 0 10 C 10 10,10 10 ,0 10 C 10 10 , 10 10 , 10 20 C 10 10 , 10 10 , 20 10 C 10 10 , 10 10 , 10 0 C 10 10,10 10 ,0 10 Z">
                                    </path>
                                </svg>
                                <svg class="star star-4" viewBox="0 0 20 20">
                                    <path
                                        d="M 0 10 C 10 10,10 10 ,0 10 C 10 10 , 10 10 , 10 20 C 10 10 , 10 10 , 20 10 C 10 10 , 10 10 , 10 0 C 10 10,10 10 ,0 10 Z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </label>
                </div>
                <div hidden="hidden" class="noti" id="box-noti">
                    <a href="javascript:void(0)" class="btn-noti">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M12 10.5195C11.59 10.5195 11.25 10.1795 11.25 9.76945V6.43945C11.25 6.02945 11.59 5.68945 12 5.68945C12.41 5.68945 12.75 6.02945 12.75 6.43945V9.76945C12.75 10.1895 12.41 10.5195 12 10.5195Z"
                                fill="#2B4992"></path>
                            <path
                                d="M12.0208 20.3502C9.44084 20.3502 6.87084 19.9402 4.42084 19.1202C3.51084 18.8202 2.82084 18.1702 2.52084 17.3502C2.22084 16.5302 2.32084 15.5902 2.81084 14.7702L4.08084 12.6502C4.36084 12.1802 4.61084 11.3002 4.61084 10.7502V8.65023C4.61084 4.56023 7.93084 1.24023 12.0208 1.24023C16.1108 1.24023 19.4308 4.56023 19.4308 8.65023V10.7502C19.4308 11.2902 19.6808 12.1802 19.9608 12.6502L21.2308 14.7702C21.7008 15.5502 21.7808 16.4802 21.4708 17.3302C21.1608 18.1802 20.4808 18.8302 19.6208 19.1202C17.1708 19.9502 14.6008 20.3502 12.0208 20.3502ZM12.0208 2.75023C8.76084 2.75023 6.11084 5.40023 6.11084 8.66023V10.7602C6.11084 11.5702 5.79084 12.7402 5.37084 13.4302L4.10084 15.5602C3.84084 15.9902 3.78084 16.4502 3.93084 16.8502C4.08084 17.2502 4.42084 17.5502 4.90084 17.7102C9.50084 19.2402 14.5608 19.2402 19.1608 17.7102C19.5908 17.5702 19.9208 17.2502 20.0708 16.8302C20.2308 16.4102 20.1808 15.9502 19.9508 15.5602L18.6808 13.4402C18.2608 12.7502 17.9408 11.5802 17.9408 10.7702V8.67023C17.9308 5.40023 15.2808 2.75023 12.0208 2.75023Z"
                                fill="#2B4992"></path>
                            <path
                                d="M11.9999 22.9003C10.9299 22.9003 9.87992 22.4603 9.11992 21.7003C8.35992 20.9403 7.91992 19.8903 7.91992 18.8203H9.41992C9.41992 19.5003 9.69992 20.1603 10.1799 20.6403C10.6599 21.1203 11.3199 21.4003 11.9999 21.4003C13.4199 21.4003 14.5799 20.2403 14.5799 18.8203H16.0799C16.0799 21.0703 14.2499 22.9003 11.9999 22.9003Z"
                                fill="#2B4992"></path>
                        </svg>
                        <span class="badge-noti">1</span>
                    </a>
                    <div class="noti-content list-unstyled no-scrollbar"></div>
                    <div class="overlay-noti"></div>
                </div>
                @if (!auth()->check())
                    <div class="nav-account list-inline" id="not-loggin">
                        <span class="login-link d-none d-xl-flex">
                            <button class="btn btn-login" rel="nofollow" data-bs-toggle="modal"
                                data-bs-target="#loginModal">
                                <span>Đăng nhập</span>
                            </button>
                        </span>

                        <a id="avatar" class="d-flex d-xl-none user-avatar-img" data-bs-toggle="modal"
                            data-bs-target="#loginModal">
                            <img class="" alt="Avatar" src="/assets/images/no-avatar.png" style="object-fit: contain" />
                        </a>
                    </div>
                @else
                    <div class="dropdown nav-account" id="has-login">
                        <button class="dropdown-toggle" type="button" id="menuAccount" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img id="avatar-temp-header" class="avatar-temp user-avatar-img"
                                src="{{auth()->user()->avatar}}" alt="{{auth()->user()->name}}"
                                style="background-color: rgb(120, 121, 120);">
                            <span id="username" class="d-none d-xl-block">
                                {{auth()->user()->name}}
                            </span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="menuAccount">
                            <span>
                                <a class="dropdown-item" href="{{route('profile')}}">
                                    Tài khoản</a>
                            </span>
                            <span>
                                <a class="dropdown-item" href="{{route('showHistory')}}">
                                    Truyện đang đọc</a>
                            </span>
                            <span>
                                <a class="dropdown-item" href="{{route('showFollow')}}">
                                    Truyện đã lưu</a>
                            </span>
                            <span>
                                <a id="logout" class="dropdown-item" href="{{route('logout')}}">
                                    Đăng xuất</a>
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>

<div class="offcanvas offcanvas-start" tabindex="-1" id="menumobile" aria-labelledby="offcanvasLabel">
    <div class="offcanvas-body">
        <div class="off-header">
            <div class="">
                <label class="switch">
                    <input class="input-dark-mode" type="checkbox" />
                    <div class="slider round">
                        <div class="sun-moon">
                            <svg class="moon-dot moon-dot-1" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="50"></circle>
                            </svg>
                            <svg class="moon-dot moon-dot-2" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="50"></circle>
                            </svg>
                            <svg class="moon-dot moon-dot-3" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="50"></circle>
                            </svg>
                            <svg class="light-ray light-ray-1" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="50"></circle>
                            </svg>
                            <svg class="light-ray light-ray-2" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="50"></circle>
                            </svg>
                            <svg class="light-ray light-ray-3" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="50"></circle>
                            </svg>

                            <svg class="cloud-dark cloud-1" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="50"></circle>
                            </svg>
                            <svg class="cloud-dark cloud-2" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="50"></circle>
                            </svg>
                            <svg class="cloud-dark cloud-3" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="50"></circle>
                            </svg>
                            <svg class="cloud-light cloud-4" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="50"></circle>
                            </svg>
                            <svg class="cloud-light cloud-5" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="50"></circle>
                            </svg>
                            <svg class="cloud-light cloud-6" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="50"></circle>
                            </svg>
                        </div>
                        <div class="stars">
                            <svg class="star star-1" viewBox="0 0 20 20">
                                <path
                                    d="M 0 10 C 10 10,10 10 ,0 10 C 10 10 , 10 10 , 10 20 C 10 10 , 10 10 , 20 10 C 10 10 , 10 10 , 10 0 C 10 10,10 10 ,0 10 Z">
                                </path>
                            </svg>
                            <svg class="star star-2" viewBox="0 0 20 20">
                                <path
                                    d="M 0 10 C 10 10,10 10 ,0 10 C 10 10 , 10 10 , 10 20 C 10 10 , 10 10 , 20 10 C 10 10 , 10 10 , 10 0 C 10 10,10 10 ,0 10 Z">
                                </path>
                            </svg>
                            <svg class="star star-3" viewBox="0 0 20 20">
                                <path
                                    d="M 0 10 C 10 10,10 10 ,0 10 C 10 10 , 10 10 , 10 20 C 10 10 , 10 10 , 20 10 C 10 10 , 10 10 , 10 0 C 10 10,10 10 ,0 10 Z">
                                </path>
                            </svg>
                            <svg class="star star-4" viewBox="0 0 20 20">
                                <path
                                    d="M 0 10 C 10 10,10 10 ,0 10 C 10 10 , 10 10 , 10 20 C 10 10 , 10 10 , 20 10 C 10 10 , 10 10 , 10 0 C 10 10,10 10 ,0 10 Z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </label>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            <i class="icon-close-circle"></i>

        </div>
        <ul class="nav navbar-nav flex-wrap main-menu">
            <li class="@if (Route::currentRouteName() == 'home') active @endif">
                <a href="{{route('home')}}">Trang chủ</a>
            </li>
            <li class="@if (Route::currentRouteName() == 'hot') active @endif">
                <a href="{{route('hot')}}">Hot nhất</a>
            </li>
            <li class="@if (Route::currentRouteName() == 'showNews') active @endif">
                <a href="{{route('showNews')}}">Tin tức</a>
            </li>
            <li class="has-sub @if (Route::currentRouteName() == 'showCategory') active @endif">
                <a href="{{route('showCategory')}}" class="sub-toggle" aria-expanded="false">Thể loại
                </a>
                <div class="dropdown-menu">
                    @foreach ($categoriesHeader as $category)
                        <span>
                            <a class="dropdown-item" href="{{route('showCategoryBySlug', ['slug' => $category->slug])}}">
                                {{$category->name}}
                            </a>
                        </span>
                    @endforeach
                </div>
            </li>
        </ul>
    </div>
</div>
<script>
    $('.dropdown-item').on('click', function (e) {
        window.location.href = $(e.target).attr('href');
    });
    checkDarkModeConfig()
</script>
@if (!auth()->check())
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Đăng nhập</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="icon-close-circle"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="d-flex flex-column align-items-center justify-content-center g_id_signin"
                        style="min-height: 200px" action="{{route('auth-login')}}" method="POST">
                        @csrf
                        @if (session('error'))
                            <div class="alert alert-danger text-center mb-3 w-100">{{session('error')}}</div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success text-center mb-3 w-100">{{session('success')}}</div>
                        @endif

                        <a href="{{route('loginGoogle')}}" class="google btn" id="google-authen-btn">
                            <ion-icon name="logo-google"></ion-icon> Login with
                            Google
                        </a>

                        <div class="form-group w-100">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Nhập email"
                                required value="{{old('email')}}">
                        </div>
                        <div class="form-group w-100">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Mật khẩu" required>
                        </div>
                        <div class="form-group w-100">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Nhớ mật khẩu</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-3">Đăng nhập</button>
                    </form>
                    <div class="text-center reg-acc">
                        Chưa có tài khoản?
                        <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" class="color">Đăng kí ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        @if (session('error'))
            $(document).ready(function () {
                $('#loginModal').modal('show');
            });
        @endif

        @if (session('success'))
            $(document).ready(function () {
                $('#loginModal').modal('show');
            });
        @endif
    </script>

    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Đăng ký</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="icon-close-circle"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="d-flex flex-column align-items-center justify-content-center g_id_signin"
                        style="min-height: 200px" action="{{route('auth-register')}}" method="POST">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger text-center mb-3 w-100">
                                @foreach ($errors->all() as $error)
                                    <p>{{$error}}</p>
                                @endforeach
                            </div>
                        @endif

                        <a href="{{route('loginGoogle')}}" class="google btn" id="google-authen-btn">
                            <ion-icon name="logo-google"></ion-icon> Đăng ký với
                            Google
                        </a>

                        <div class="form-group w-100">
                            <label for="name" class="form-label">Tên</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nhập tên" required
                                value="{{old('name')}}">
                        </div>
                        <div class="form-group w-100">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Nhập email"
                                required value="{{old('email')}}">
                        </div>
                        <div class="form-group w-100">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Mật khẩu" required>
                        </div>
                        <div class="form-group w-100">
                            <label for="password_confirmation" class="form-label">Nhập lại mật khẩu</label>
                            <input type="password" class="form-control" name="password_confirmation"
                                id="password_confirmation" placeholder="Nhập lại mật khẩu" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-3">Đăng ký</button>
                    </form>
                    <div class="text-center reg-acc">
                        Đã có tài khoản?
                        <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="color">Đăng nhập ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        @if ($errors->any())
            $(document).ready(function () {
                $('#registerModal').modal('show');
            });
        @endif
    </script>
@endif