@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Crawl DamCoNuong'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="py-4">
                <div class="card step-1">
                    <div class="card-body">
                        <form id="apiForm">
                            <div class="alert alert-danger d-flex align-items-center d-none">
                                <p class="text-danger message-error"></p>
                            </div>
                            <div class="form-group mb-3">
                                <label for="baseUrl">Base URL API</label>
                                <input type="text" id="baseUrl" class="form-control" name="baseUrl"
                                    value="https://api.mymanga.vn/api/v1">
                                <small><i>Ví dụ: https://api.mymanga.vn/api/v1</i></small>
                            </div>
                            <div class="form-group mb-3 d-flex gap-2 w-100">
                                <div class="w-50">
                                    <label for="page" class="form-label">Crawl từ trang</label>
                                    <input type="number" id="page" class="form-control" name="page" min="1" value="1">
                                </div>
                                <div class="w-50">
                                    <label for="toPage" class="form-label">Đến trang</label>
                                    <input type="number" id="toPage" class="form-control" name="page" min="1" value="1">
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="option" class="form-label text-danger">Loại trừ số chương (lớn hơn tự động bỏ
                                    qua)</label>
                                <input type="number" id="option" class="form-control" name="option" min="0" value="1000">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-load">Tải</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card step-2 d-none">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <h4>Chọn truyện</h4>
                                <p>Đã chọn <span class="selected-movie-count">0</span>/<span
                                        class="total-movie-count">0</span> bộ</p>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="check-all" checked="">
                                    <label class="custom-control-label" for="customCheck1">Lấy tất cả</label>
                                </div>
                                <div class="row px-3 my-3">
                                    <div class="w-100 col-form-label overflow-auto rounded-2" id="movie-list"
                                        style="height: 30rem;background-color: #f7f7f7">
                                    </div>
                                </div>
                                <button class="btn btn-secondary btn-previous">Trước</button>
                                <button class="btn btn-primary btn-next">Tiếp</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card step-3 row d-none">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <h4>Đang tiến hành...</h4>
                                <p>Crawl <span class="crawled-count">0</span>/<span class="total-crawl-count">0</span>
                                    truyện (Thành công: <span class="crawl-success-count">0</span>, thất bại: <span
                                        class="crawl-failed-count">0</span>).</p>
                                <p class="craw-chapter-status d-none">Crawl <span
                                        class="crawled-chapter-count">0</span>/<span
                                        class="total-crawl-chapter-count">0</span>
                                    chương (Thành công: <span class="crawl-success-chapter-count">0</span>, thất bại: <span
                                        class="crawl-failed-chapter-count">0</span>).</p>
                                <div class="form-group row p-3">
                                    <div class="w-100 col-form-label overflow-auto mb-5" id="crawl-list"
                                        style="height: 50vh;background-color: #f7f7f7">
                                    </div>
                                    <small><i id="wait_message"></i></small>
                                </div>
                                <button class="btn btn-primary btn-finally">Xong</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
@push('js')
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            let baseUrl = "";

            function crawlHomePage(url, btn) {
                let comicSlugs = [];
                const page = $('#page').val();
                const toPage = $('#toPage').val();
                let result = '';
                let count = 0;

                let promises = [];

                for (let i = parseInt(page); i <= parseInt(toPage); i++) {
                    let p = $.ajax({
                        url: url + "/mangas?page=" + i,
                        method: 'GET',
                        success: function (response) {
                            // Check for different possible structures of response
                            let comics = [];
                            if (response.data && Array.isArray(response.data.data)) {
                                comics = response.data.data;
                            } else if (response.data && Array.isArray(response.data)) {
                                comics = response.data;
                            } else if (Array.isArray(response)) {
                                comics = response;
                            }

                            if (comics.length === 0) {
                                console.warn("No comics found on page " + i);
                                return;
                            }

                            comics.forEach((comic, index) => {
                                // Logic to filter chapters if needed, but list might not have chapter count readily available
                                // or we check 'latest_chapter'
                                // For now, accept all

                                result += '<div class="form-check">' +
                                    '<input type="checkbox" class="form-check-input comic-checkbox" checked id="comic-' + index + '-' + i + '" value="' + comic.slug + '">' +
                                    '<label class="custom-control-label">' + comic.name + '</label>' +
                                    '</div>';
                                comicSlugs.push(comic.slug);
                                count++;
                            });
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log("Error crawling page " + i + ": " + textStatus);
                        }
                    });
                    promises.push(p);
                }

                Promise.all(promises).then(() => {
                    $('.selected-movie-count').text(count);
                    $('.total-movie-count').text(count);
                    $('#movie-list').html(result);
                    $('.step-1').addClass("d-none");
                    $('.step-2').removeClass("d-none");
                    btn.prop('disabled', false).text('Tải');
                    sessionStorage.setItem('ListComics', comicSlugs.join(','));
                    baseUrl = url; // Save base URL
                }).catch(() => {
                    $(".alert-danger").removeClass("d-none");
                    $(".message-error").text("Có lỗi xảy ra khi tải danh sách truyện.");
                    btn.prop('disabled', false).text('Tải');
                });
            }

            $('#apiForm').on('submit', function (event) {
                event.preventDefault();
                let submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true).text('Đang crawl...');
                let urlValue = $('#baseUrl').val().replace(/\/$/, ""); // remove trailing slash
                sessionStorage.setItem('ListComics', "");
                $(".alert-danger").addClass("d-none");
                $('#movie-list').html("");
                $('.selected-movie-count').text("0");
                $('.total-movie-count').text("0");

                crawlHomePage(urlValue, submitButton);
            });

            $('#check-all').on('change', function () {
                let comicSlugs = sessionStorage.getItem('ListComics') ? sessionStorage.getItem('ListComics').split(',') : [];
                let comicCheckboxes = $('.comic-checkbox');
                if ($(this).is(':checked')) {
                    comicCheckboxes.each(function () {
                        let comicSlug = $(this).val();
                        if (!comicSlugs.includes(comicSlug)) {
                            comicSlugs.push(comicSlug);
                        }
                        $(this).prop('checked', true);
                    });
                } else {
                    comicCheckboxes.each(function () {
                        let comicSlug = $(this).val();
                        let index = comicSlugs.indexOf(comicSlug);
                        if (index !== -1) {
                            comicSlugs.splice(index, 1);
                        }
                        $(this).prop('checked', false);
                    });
                }
                sessionStorage.setItem('ListComics', comicSlugs.join(','));
                updateCounts();
            });

            $(document).on('change', '.comic-checkbox', function () {
                let comicSlugs = sessionStorage.getItem('ListComics') ? sessionStorage.getItem('ListComics').split(',') : [];
                let comicSlug = $(this).val();
                if ($(this).is(':checked')) {
                    comicSlugs.push(comicSlug);
                } else {
                    let index = comicSlugs.indexOf(comicSlug);
                    if (index !== -1) {
                        comicSlugs.splice(index, 1);
                    }
                }
                sessionStorage.setItem('ListComics', comicSlugs.join(','));
                updateCounts();
            });

            function updateCounts() {
                let totalComics = $('.comic-checkbox').length;
                let checkedComics = $('.comic-checkbox:checked').length;
                $('.selected-movie-count').text(checkedComics);
                $('.total-movie-count').text(totalComics);
            }

            $('.btn-previous').on('click', function () {
                $('.step-2').addClass("d-none");
                $('.step-1').removeClass("d-none");
            });

            $('.btn-next').on('click', async function () {
                let listComics = sessionStorage.getItem('ListComics');
                let comicSlugs = listComics.split(',').filter(s => s); // exclude empty strings
                $('.step-2').addClass("d-none");
                $('.step-3').removeClass("d-none");
                $(".total-crawl-count").text(comicSlugs.length);

                let messages = "";
                let totalChapters = 0;
                let currentChap = 0;

                for (const comicSlug of comicSlugs) {
                try {
                    // 1. Fetch comic details
                    const response = await $.ajax({
                        url: baseUrl + "/mangas/" + comicSlug,
                        method: 'GET'
                    });

                    const comicData = response.data;

                    // 2. Fetch Chapters List specifically (if not fully included/paginated in detail)
                    // API docs: /api/v1/mangas/{slug}/chapters
                    let chapters = [];
                    try {
                        let page = 1;
                        let hasMore = true;
                        while (hasMore) {
                            const chaptersResp = await $.ajax({
                                url: baseUrl + "/mangas/" + comicSlug + "/chapters?per_page=1000&page=" + page,
                                method: 'GET'
                            });
                            // ChapterListResponse: data -> array of Chapter
                            let fetchedChapters = [];
                            if (Array.isArray(chaptersResp.data)) {
                                fetchedChapters = chaptersResp.data;
                            } else if (chaptersResp.data && Array.isArray(chaptersResp.data.data)) { // Pagination wrapper
                                fetchedChapters = chaptersResp.data.data;
                            }

                            if (fetchedChapters.length > 0) {
                                chapters = chapters.concat(fetchedChapters);
                                page++;
                                // Safety break or check meta last_page
                                if (chaptersResp.meta && chaptersResp.meta.last_page && page > chaptersResp.meta.last_page) {
                                    hasMore = false;
                                } else if (fetchedChapters.length < 1000) {
                                    hasMore = false;
                                }
                            } else {
                                hasMore = false;
                            }
                        }
                    } catch (e) {
                        console.warn("Could not fetch separate chapter list, using embedded if available", e);
                        chapters = comicData.chapters || [];
                    }

                    // 3. Prepare data for backend
                    const postData = {
                        _token: "{{ csrf_token() }}",
                        serverName: 'DamCoNuong',
                        baseUrl: baseUrl,
                        name: comicData.name,
                        slug: comicData.slug,
                        origin_name: comicData.name_alt,
                        content: comicData.pilot,
                        status: comicData.status,
                        author: comicData.artist ? comicData.artist.name : "Updating",
                        thumbnail: comicData.cover_full_url,
                        categories: comicData.genres,
                        manga_uuid: comicData.uuid
                    };

                    const res = await $.ajax({
                        url: "{{route("admin.addComicByCrawl")}}",
                        method: 'POST',
                        data: postData
                    });

                    totalChapters += chapters.length;
                    $(".total-crawl-chapter-count").text(totalChapters);
                    messages += "<span class='text-success'><i class='bi bi-check'></i>" + res.message + "</span><br>";
                    $(".crawl-success-count").text(parseInt($(".crawl-success-count").text()) + 1);
                    $(".crawled-count").text(parseInt($(".crawled-count").text()) + 1);

                    // 4. Crawl Chapters
                    if (chapters.length > 0) {
                        // Reverse to start from oldest (Chapter 1)
                        // API usually returns newest first?
                        // Check 'order' or 'chapter_number' to be sure? 
                        // Let's assume standard DESC return and Reverse it.
                        let reversedChapters = [...chapters].reverse();

                        for (const chapter of reversedChapters) {
                            if (parseFloat(chapter.chapter_number) <= parseFloat(res.currentChapter)) {
                                currentChap += 1;
                                $(".crawled-chapter-count").text(currentChap);
                                continue;
                            }

                            try {
                                const response1 = await $.ajax({
                                    url: "{{route("admin.addChapterByCrawl")}}",
                                    method: 'POST',
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        serverName: 'DamCoNuong',
                                        baseUrl: baseUrl,
                                        manga_slug: comicSlug,
                                        chapter_slug: chapter.slug,
                                        chapter_data: chapter,
                                        id: res.id
                                    },
                                    beforeSend: function () {
                                        $(".craw-chapter-status").removeClass("d-none");
                                    }
                                });

                                messages += "<span class='text-success'><i class='bi bi-check'></i>" + response1 + "</span><br>";
                                $(".crawl-success-chapter-count").text(parseInt($(".crawl-success-chapter-count").text()) + 1);

                            } catch (error) {
                                $(".crawl-failed-chapter-count").text(parseInt($(".crawl-failed-chapter-count").text()) + 1);
                                messages += "<span class='text-danger'><i class='bi bi-x'></i>" + "Thêm thất bại bộ " + comicSlug + " chương " + chapter.chapter_number + ".</span><br>";
                            } finally {
                                currentChap += 1;
                                $(".crawled-chapter-count").text(currentChap);
                                $("#crawl-list").html(messages);
                                const listsDiv = document.getElementById('crawl-list');
                                listsDiv.scrollTop = listsDiv.scrollHeight;
                                await new Promise(resolve => setTimeout(resolve, 1000));
                            }
                        }
                    }

                } catch (error) {
                    $(".crawl-failed-count").text(parseInt($(".crawl-failed-count").text()) + 1);
                    $(".crawled-count").text(parseInt($(".crawled-count").text()) + 1);
                    messages += "<span class='text-danger'><i class='bi bi-x'></i>" + "Thêm thất bại bộ " + comicSlug + ": " + (error.responseText || error.statusText) + "</span><br>";
                } finally {
                    $("#crawl-list").html(messages);
                }
            }
            $(".btn-finally").text('Xong');
        });

        $('.btn-finally').on('click', function () {
            sessionStorage.setItem('ListComics', "");
            window.location.href = "{{ route('admin.api.damconuong') }}";
        });
            });
    </script>
@endpush