@extends('users.main')
@section('metadata')
{!! SEO::generate() !!}
{!! $metaHtml ?? '' !!}
@endsection
@section('main_content')
<div class="wrapper">
    <main>
        <div class="reading-page">
            <header class="reading-header" id="readingHeader">
                <div class="r-left">
                    <a href="/" class="logo">
                        <img src="/favicon.png" width="48" alt="Logo" class="" style="min-width: 48px;">
                    </a>
                    <h1 class="manga-name">
                        Đọc <a href="{{route('detail', ['slug' => $comic->slug])}}">{{$comic->name}}</a> - Chapter {{$chapterSelected->name}}
                    </h1>
                </div>

                <div class="r-right">
                    <a href="" class="report m-0" data-bs-toggle="modal" data-bs-target="#ReportModal">
                        <i class="icon-info-circle"></i>
                    </a>

                    <a href="#" class="show-setting d-flex" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSetting">
                        <i class="icon-setting-2"></i>
                    </a>

                </div>
                <div class="r-navigation navigation navi-chap position-relative">
                    <button type="button" class="navi prev @isset($comic->prevChap)  @else disabled @endisset" onclick="handlePrevChapter()" title="Chapter Trước">
                        <i class="icon-arrow-left"></i>
                    </button>

                    <div id="dd-chapters" class="dropdown">
                        <a href="" class="dropdown-toggle" id="dropdownChaps" data-bs-toggle="dropdown" aria-expanded="false">
                            <span>
                                Chapter {{$chapterSelected->name}}
                            </span><i class="icon-arrow-down-1"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownChaps">
                            <form class="form-search" id="form-search-chap">
                                <input class="form-control" type="text" placeholder="Tìm kiếm" aria-label="Tìm kiếm">
                                <i class="icon-search-normal"></i>
                            </form>
                            <div class="list-chap">
                                @foreach ($comic->chapters->reverse() as $chapter)
                                <span class="l-chapter @if (request()->route('chapter') == $chapter->slug) selected @endif">
                                    <a class="dropdown-item" data-value="Chapter #{{$chapter->name}}" href="{{route('showRead', ['slug' => $comic->slug, 'chapter' => $chapter->slug])}}" title="Chapter #{{$chapter->name}}">
                                        Chapter {{$chapter->name}}
                                    </a>
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="tl-trans dropdown">
                        <a href="" class="dropdown-toggle" data-bs-toggle="dropdown">
                            <span>Server</span>
                            <i class="icon-arrow-down-1"></i>
                        </a>
                        <div class="dropdown-menu">
                            @foreach ($servers as $server)
                            <span class="{{$server->id == $chapterSelected->id ? 'active' : ''}}">
                                <span class="dropdown-item" data-value="{{$server->server}}" href="">{{$server->server}}</span>
                            </span>
                            @endforeach
                        </div>
                    </div>

                    <button type="button" class="navi next @isset($comic->nextChap)  @else disabled @endisset" onclick="handleNextChapter()" title="Chapter Sau">
                        <i class="icon-arrow-right"></i>
                    </button>
                </div>
                <span class="reading-header-btn"><i class="icon-arrow-down-1"></i></span>
            </header>

            <div id="manga-images" data-mode="default">
                <div class="main-images text-center position-relative" id="read-chaps">
                    @foreach ($chapterSelected->images as $image)
                    <div id="img-id-{{$image->id}}" class="mi-item" data-id="{{$image->id}}">
                        <div class="loaded i-right">
                            <img class="reading-img lozad" data-src="{{$image->link}}">
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="navi-bottom navi-chap navigation">
                    <button type="button" class="navi prev @isset($comic->prevChap)  @else disabled @endisset" onclick="handlePrevChapter()" title="Chapter Trước">
                        <i class="icon-arrow-left"></i>
                        <span>Chapter Trước</span>
                    </button>
                    <button type="button" class="navi next @isset($comic->nextChap)  @else disabled @endisset" onclick="handleNextChapter()" title="Chapter Sau">
                        <span>Chapter Sau</span>
                        <i class="icon-arrow-right"></i>
                    </button>
                </div>

                <div class="container comment-chapter">
                    <x-comment :data="$comments" />
                </div>
            </div>
        </div>
    </main>
    <div href="javascript:void(0)" id="back-to-top" style="display: flex; cursor: pointer; opacity: 1; transition: opacity 0.3s;">
        <i class="icon-arrow-up"></i>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasSetting" aria-labelledby="offcanvasSettingLabel" style="visibility: hidden;" aria-hidden="true">
    <div class="offcanvas-body">
        <div class="offcanvas-header">
            <button class="btn-close" type="button" data-bs-dismiss="offcanvas" aria-label="Close"><i class="icon-close-circle"></i></button>
            <h4 class="title">
                Cài đặt
            </h4>
        </div>
        <div class="list-settings">

            <div class="reading-mode disabled">
                <div id="dd-mode" class="dropdown">
                    <a href="" class="dropdown-toggle" id="dropdownMode" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>Chế độ đọc</span><sub>Dọc</sub>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMode">
                        <div class="list-mode">
                            <span><a class="dropdown-item" data-mode="default" data-value="dọc" href="#">Dọc</a></span>
                            <span><a class="dropdown-item" data-mode="horizon-single" data-value="Ngang 1 trang" href="#">Ngang 1 trang</a></span>
                            <span><a class="dropdown-item" data-mode="horizon-double" data-value="Ngang 2 trang" href="#">Ngang 2 trang</a></span>
                        </div>
                    </div>
                </div>

            </div>

            <div id="lightmode" class="dropdown">
                <a href="" class="dropdown-toggle" id="dropdownLightmode" data-bs-toggle="dropdown" aria-expanded="false">
                    <span>LightMode</span><sub>Tối</sub>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownLightmode">
                    <div class="list-mode">
                        <span><a class="dropdown-item dl-mode" data-value="false" href="#">Sáng</a></span>
                        <span><a class="dropdown-item dl-mode" data-value="true" href="#">Tối</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ReportModal" tabindex="-1" aria-labelledby="ReportModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ReportModalLabel">Báo cáo lỗi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="icon-close-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="report-form">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="report-reason_5" data-reason="Có yếu tố phá hoại (đăng chap troll, dịch bậy, v.v.)">
            <label class="form-check-label" for="report-reason_5">Có yếu tố phá hoại (đăng chap troll, dịch bậy, v.v.)</label></div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="report-reason_4" data-reason="Thiếu ảnh bìa chap nếu có">
            <label class="form-check-label" for="report-reason_4">Thiếu ảnh bìa chap nếu có</label></div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="report-reason_3" data-reason="Ảnh bị lỗi">
            <label class="form-check-label" for="report-reason_3">Ảnh bị lỗi</label></div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="report-reason_2" data-reason="Số vol/chap bị sai số/thiếu">
            <label class="form-check-label" for="report-reason_2">Số vol/chap bị sai số/thiếu</label></div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="report-reason_1" data-reason="Chap bị lặp lại">
            <label class="form-check-label" for="report-reason_1">Chap bị lặp lại</label></div>
                    <textarea class="form-control form-control-textarea" name="content" maxlength="3000" placeholder="Gặp vấn đề khác xin điền ở đây..."></textarea>
                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#otp-modal">Báo cáo</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    async function postComment(mangaId, chapterId, content, parentCommentId) {
        const response = await $.ajax({
            type: 'POST',
            xhrFields: { withCredentials: true },
            url:
                '/comments' +
                (!!parentCommentId ? `/${parentCommentId}/reply` : ''),
            contentType: 'application/json',
            data: JSON.stringify({ mangaId, chapterId, content, _token: '{{ csrf_token() }}' }),
            dataType: 'json',
        }).catch(() => {
            return null;
        });

        if (response && response.status === 200) {
            return response.data;
        }

        return null;
    }

    async function getComment(mangaId, chapterId, page, pageSize, orderBy) {
        const url = new URL('{{route('getComments')}}');

        url.searchParams.append('mangaId', mangaId);
        if (chapterId) {
            url.searchParams.append('chapterId', chapterId);
        }

        if (page) {
            url.searchParams.append('page', page);
        }

        if (pageSize) {
            url.searchParams.append('pageSize', pageSize);
        }

        if (orderBy) {
            url.searchParams.append('orderBy[]', orderBy);
        }

        const response = await $.ajax({
            type: 'GET',
            xhrFields: { withCredentials: true },
            url: url.toString(),
            contentType: 'application/json',
        }).catch(() => {
            return null;
        });

        if (response && response.status === 200) {
            return response.data;
        }

        return null;
    }

    async function likeComment(commentId) {
        const response = await $.ajax({
            type: 'PATCH',
            xhrFields: { withCredentials: true },
            url: `/comments/${commentId}/like`,
            contentType: 'application/json',
            data: JSON.stringify({ _token: '{{ csrf_token() }}' }),
            dataType: 'json',
        }).catch(() => {
            return null;
        });

        if (response && response.status === 200) {
            return response.data;
        }

        return null;
    }
</script>
<script type="text/javascript" src="/nettruyen/lozad.min.js"></script>
<script type="text/javascript">
    const observer = lozad();
    observer.observe();
    window.isChapter = true;
</script>
<script>
    var id_item = '{{$comic->id}}';
    var chapter_id = '{{$chapterSelected->id}}';
    var table = "comic";
</script>
<script type="text/javascript" src="/assets/js/tinymce.min.js"></script>
<script type="text/javascript" src="/assets/js/comment.min.js"></script>
<script>
    $(document).ready(function() {
        @if (auth()->check())
            setTimeout(function() {
                $.ajax({
                    url: "{{route('upExp')}}",
                    type: 'POST',
                    data: {
                        id: '{{auth()->user()->id}}' ?? null,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }, 60000);
        @endif
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var readingHeader = document.querySelector('#readingHeader');
        var readingHeaderBtn = document.querySelector('.reading-header-btn');
        var lastScrollTop = 15;
        var headerHeight = readingHeader.offsetHeight;

        // Initially hide the header
        readingHeader.style.top = `-${headerHeight}px`;

        window.addEventListener('scroll', function() {
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > lastScrollTop) {
                // Scrolling down
                readingHeader.style.top = `-${headerHeight}px`;
                readingHeaderBtn.style.opacity = '1';
                readingHeaderBtn.style.visibility = 'visible';
            } else if (scrollTop === 0) {
                // At the top of the page
                readingHeaderBtn.style.opacity = '0';
                readingHeaderBtn.style.visibility = 'hidden';
            }

            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // Ensure lastScrollTop is not negative
        });

        if (readingHeader) {
            readingHeader.addEventListener('click', function () {
                readingHeader.classList.toggle('expanded');
            });
        }

    });

    var selectedZoom = 'Dọc';
    var selectedMode = 'default';
    // Data reading mode
    $(document).ready(function() {
        $('#dd-mode .dropdown-item').on('click', function(e) {
            selectedMode = $(this).data('mode');

            $('#manga-images').attr('data-mode', selectedMode);

            if (selectedMode !== 'default') {
                $('#manga-images').addClass('style-horizontal');
                $('.comment-chapter').addClass('d-none');
            } else {
                $('#manga-images').removeClass('style-horizontal');
                $('.comment-chapter').removeClass('d-none');
            }
        });

        $('#dd-zoom .dropdown-item').on('click', function(e) {
            selectedZoom = $(this).data('value');

            if (selectedZoom !== 'Dọc') {
                $('#manga-images').addClass('full-horizontal');
            } else {
                $('#manga-images').removeClass('full-horizontal');
            }
        });
    });

    function updateActiveItems() {
        var activeItems = $('.mi-item.active');
        var mode = $('#manga-images').attr('data-mode');

        var firstActiveItem = activeItems.first();

        $('.set-pages #dropdownPages span').text(
            $(`.chose-img-${$(firstActiveItem).attr('data-id')}`).text(),
        );

        if (mode === 'horizon-double') {
            // Đảm bảo có hai phần tử active
            if (activeItems.length === 1) {
                var nextItem = activeItems.next('.mi-item');
                if (nextItem.length) {
                    nextItem.addClass('active');
                }
            } else if (activeItems.length === 0) {
                // Nếu không có phần tử nào active, đặt hai phần tử đầu tiên là active
                $('.mi-item').slice(0, 2).addClass('active');
            } else if (activeItems.length > 2) {
                activeItems.slice(2).removeClass('active');
            }
        } else {
            // Chỉ giữ một phần tử active
            if (activeItems.length > 1) {
                activeItems.slice(1).removeClass('active');
            }
        }
    }

    function handleNextImage() {
        var activeItems = $('.mi-item.active');
        var lastActiveItem = activeItems.last();
        var nextItem = lastActiveItem.next('.mi-item');
        updateSelectedPage();
        if (nextItem.length) {
            activeItems.removeClass('active');
            nextItem.addClass('active');
            updateActiveItems();
            return true
        } else {
            return false
        }
    }

    function handlePrevImage() {
        var activeItems = $('.mi-item.active');
        var firstActiveItem = activeItems.first();
        var prevItem = firstActiveItem.prev('.mi-item');

        if (prevItem.length) {
            activeItems.removeClass('active');

            var mode = $('#manga-images').attr('data-mode');

            if (mode === 'horizon-double') {
                prevItem = prevItem.prev('.mi-item');
            }
            prevItem.addClass('active');

            updateActiveItems();
            return true
        } else {
            return false
        }
    }

    function handlePrevImageThenChapter(){
        const rMode = $('#manga-images').attr('data-mode');

        if (rMode === 'default') {
            return;
            // handlePrevChapter()
        }

        if(!handlePrevImage()){
            handlePrevChapter(true)
        }
    }

    function handleNextImageThenChapter(){
        const rMode = $('#manga-images').attr('data-mode');
        if (rMode === 'default') {
            return;
            // handleNextChapter()
        }

        if(!handleNextImage()){
            handleNextChapter()
        }
    }

    function handlePrevChapter(last = false) {
        @if ($comic->prevChap)
            window.location.href = "{{route('showRead', ['slug' => $comic->slug, 'chapter' => $comic->prevChap->slug])}}?lastImg=true";
        @else
            return;
        @endif
    }

    function handleNextChapter() {
        @if ($comic->nextChap)
            window.location.href = "{{route('showRead', ['slug' => $comic->slug, 'chapter' => $comic->nextChap->slug])}}";
        @else
            return;
        @endif
    }

    // Reading mode horizontal
    $(document).ready(function() {
        // Đặt mi-item đầu tiên là active mặc định
        $('.mi-item').first().addClass('active');

        // Sự kiện click cho nút prev
        $('.navi.prev').on('click', function() {
            handlePrevImage();
        });

        // Sự kiện click cho nút next
        $('.navi.next').on('click', function() {
            handleNextImage();
        });

        // Cập nhật trạng thái active khi trang được tải
        updateActiveItems();

        // Cập nhật trạng thái active khi data-mode thay đổi
        $('#dd-mode .dropdown-item').on('click', function(e) {
            e.preventDefault();

            var selectedMode = $(this).data('mode');

            // Cập nhật thuộc tính data-mode của #manga-images
            $('#manga-images').attr('data-mode', selectedMode);
            $('#dd-mode sub').text($(this).text());

            localStorage.setItem(
                'reading-mode',
                JSON.stringify({
                    mode: selectedMode,
                    label: $(this).text(),
                }),
            );

            // Cập nhật trạng thái active cho các mi-item
            updateActiveItems();
        });
    });

    const readingMode = localStorage.getItem('reading-mode');
    if (readingMode && !mangaDetail.isWebtoon) {
        const modes = $('#dd-mode .dropdown-item');
        for (const mode of modes) {
            if ($(mode).text() === JSON.parse(readingMode).label) {
                $(document).ready(function() {
                    $(mode).trigger('click');
                    handleShowLastImage()
                });
                break;
            }
        }
    }

    // handle press key arrow
    $(window).on('keyup', function(e) {
        if (selectedMode === 'default') { // chế độ dọc
            if (e.key === 'ArrowLeft') {
                handlePrevChapter();
            } else if (e.key === 'ArrowRight') {
                handleNextChapter();
            }
        } else { // chế độ ngang
            if (e.key === 'ArrowUp') {
                handlePrevChapter();
            } else if (e.key === 'ArrowDown') {
                handleNextChapter();
            } else if (e.key === 'ArrowLeft') {
                handlePrevImage();
            } else if (e.key === 'ArrowRight') {
                handleNextImage();
            }
        }
    });


    function handleClearWindowHref() {
        var url = document.location.href;
        const newUrl = new URL(url);
        if (newUrl.searchParams.has('lastImg')) {
            newUrl.searchParams.delete('lastImg');
            window.history.pushState({}, '', newUrl);
        }
    }

    function handleShowLastImage() {
        const newUrl = new URL(document.location.href);
        if(newUrl.searchParams.get('lastImg')){
            var allItem = $('.mi-item');
            allItem.removeClass('active');
            var lastItem = $('.mi-item').last();
            lastItem.addClass('active');
            updateActiveItems();
        }
        handleClearWindowHref()
    }

    $(document).ready(function() {
        handleShowLastImage()
    });

    $(document).ready(function() {
        // Thêm class selected cho chapter hiện tại
        $(`.l-chapter a[data-value="Chapter {{$chapterSelected->name}}"]`).parent().addClass('selected');

        // Scroll đến chapter được chọn khi mở dropdown
        $('#dd-chapters .dropdown-toggle').on('shown.bs.dropdown', function () {
            const selectedChapter = $('.l-chapter.selected');
            if (selectedChapter.length) {
                const dropdownMenu = $('.dropdown-menu .list-chap');
                const scrollTop = selectedChapter.position().top + dropdownMenu.scrollTop() - (dropdownMenu.height() / 2);
                dropdownMenu.scrollTop(scrollTop);
            }
        });
    });
    function updateSelectedPage() {
        // Xóa selected cũ
        $('.list-pages .dropdown-item').parent().removeClass('selected');
        // Thêm selected cho trang hiện tại
        const activeImageId = $('.mi-item.active').data('id');
        const selectedPageElement = $(`.chose-img-${activeImageId}`);

        $(`.chose-img-${activeImageId}`).parent().addClass('selected');

        // Cập nhật text trong dropdown toggle
        $('#dd-pages .dropdown-toggle span').text(selectedPageElement.text());
    }
    $(document).ready(function() {
        $('.list-pages .dropdown-item').on('click', function(e) {
            e.preventDefault();
            // Xóa active cũ
            $('.mi-item').removeClass('active');
            // Thêm active mới cho image tương ứng
            const imageId = $(this).data('id');
            $(`#img-id-${imageId}`).addClass('active');
            // Cập nhật text trong dropdown toggle ngay lập tức
            $('#dd-pages .dropdown-toggle span').text($(this).text());

            // Cập nhật selected
            updateSelectedPage();
        });


        // Scroll đến trang được chọn khi mở dropdown
        $('#dd-pages .dropdown-toggle').on('shown.bs.dropdown', function () {
            const selectedPage = $('.list-pages span.selected');
            if (selectedPage.length) {
                const dropdownMenu = $('.dropdown-menu .list-pages');
                const scrollTop = selectedPage.position().top + dropdownMenu.scrollTop() - (dropdownMenu.height() / 2);
                dropdownMenu.scrollTop(scrollTop);
            }
        });

        // Cập nhật selected page mỗi khi active image thay đổi
        $('.mi-item').on('click', function() {
            updateSelectedPage();
        });

    });
</script>

<script>
    async function postReport(mangaId, reasons, chapterId) {
        const response = await $.ajax({
            type: 'POST',
            xhrFields: { withCredentials: true },
            url: chapterId
                ? `https://api.hangtruyen.co/mangas/${mangaId}/${chapterId}/report`
                : `https://api.hangtruyen.co/mangas/${mangaId}/report`,
            contentType: 'application/json',
            data: JSON.stringify({ reasons }),
            dataType: 'json',
        }).catch(() => {
            return null;
        });

        if (response && response.status === 200) {
            return response.data;
        }
        return null;
    }

    const listChapters = $('.dropdown-menu .list-chap');

    $('#dd-chapters .form-search input').on(
        'keyup',
        debounce(function () {
            const keyword = $(this).val();
            const elemListChapters = listChapters.find('.l-chapter');
            if (keyword) {
                listChapters.addClass('reserve-list');
                for (const elemChapter of elemListChapters) {
                    const text = $(elemChapter)
                        .find('a')
                        .attr('title')
                        .toLowerCase();
                    if (text.includes(keyword.toLowerCase())) {
                        $(elemChapter).removeClass('d-none');
                    } else {
                        $(elemChapter).addClass('d-none');
                    }
                }
                // Scroll to top of list after filtering
                listChapters.scrollTop(listChapters[0].scrollHeight);
            } else {
                listChapters.removeClass('reserve-list');
                elemListChapters.removeClass('d-none');
            }
        }, 200),
    );

    $('.chose-img').on('click', function (e) {
        e.preventDefault();
        var activeItems = $('.mi-item.active');
        activeItems.removeClass('active');

        $(`#img-id-${$(this).attr('data-id')}`).addClass('active');

        $('.set-pages #dropdownPages span').text(`${$(this).text()}`);
        updateActiveItems();
    });

    $('.l-chapter .dropdown-item').on('click', async function (e) {
        window.location.href = $(this).attr('href');
    });


    $('#lightmode .dl-mode').on('click', function () {
        toggleDarkModeConfig($(this).attr('data-value') === 'false');
    });

    function handleUpdateDarkmodeConfig() {
        const darkMode = $('body').hasClass('darkmode');
        $('#dropdownLightmode > sub').text(darkMode ? 'Tối' : 'Sáng');
    }

    handleUpdateDarkmodeConfig();
</script>
@endsection
