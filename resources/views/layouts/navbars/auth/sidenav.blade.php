<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('home') }}" target="_blank">
            <img src="/logo.png" class="navbar-brand-img h-100" alt="main_logo">
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">truyện</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#collapseCrawl" role="button" aria-expanded="false"
                    aria-controls="collapseCrawl">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-cloud-upload-96 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Crawl</span>
                </a>
                <div class="collapse" id="collapseCrawl">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.api' ? 'active' : '' }}"
                                href="{{route('admin.api')}}">
                                <div
                                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-building text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1 ">Crawl Otruyen</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.api.damconuong' ? 'active' : '' }}"
                                href="{{route('admin.api.damconuong')}}">
                                <div
                                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-world text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1 ">Crawl DamCoNuong</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#collapseMana" role="button" aria-expanded="false"
                    aria-controls="collapseMana">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-trophy text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Quản lý truyện</span>
                </a>
                <div class="collapse" id="collapseMana">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.comic.index' ? 'active' : '' }}"
                                href="{{route('admin.comic.index')}}">
                                <div
                                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-image text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1 ">Truyện tranh</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.chapter.error' ? 'active' : '' }}"
                                href="{{route('admin.chapter.error')}}">
                                <div
                                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-image text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1 ">Chương lỗi</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#collapsePost" role="button" aria-expanded="false"
                    aria-controls="collapsePost">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Quản lý bài viết</span>
                </a>
                <div class="collapse" id="collapsePost">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.blog.index' ? 'active' : '' }}"
                                href="{{route('admin.blog.index')}}">
                                <div
                                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-image text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1 ">Bài viết</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#collapseClassify" role="button"
                    aria-expanded="false" aria-controls="collapseCrawl">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Phân loại</span>
                </a>
                <div class="collapse" id="collapseClassify">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.category.index' ? 'active' : '' }}"
                                href="{{route('admin.category.index')}}">
                                <div
                                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-image text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1 ">Thể loại</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.author.index' ? 'active' : '' }}"
                                href="{{route('admin.author.index')}}">
                                <div
                                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-planet text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1 ">Tác giả</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.level.index' ? 'active' : '' }}"
                                href="{{route('admin.level.index')}}">
                                <div
                                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-diamond text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1 ">Cấp bậc</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">TÙY CHỈNH</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#collapseSetting" role="button"
                    aria-expanded="false" aria-controls="collapseSetting">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-settings text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Cài đặt</span>
                </a>
                <div class="collapse" id="collapseSetting">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.seo' ? 'active' : '' }}"
                                href="{{route('admin.seo')}}">
                                <div
                                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-compass-04 text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1 ">SEO</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.advanced' ? 'active' : '' }}"
                                href="{{route('admin.advanced')}}">
                                <div
                                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-atom text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1 ">Khác</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'admin.sitemap' ? 'active' : '' }}"
                    href="{{ route('admin.sitemap') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-map-big text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Sitemap</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'admin.comment.index' ? 'active' : '' }}"
                    href="{{ route('admin.comment.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-chat-round text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Bình luận</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'admin.user.index' ? 'active' : '' }}"
                    href="{{ route('admin.user.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Người dùng</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="sidenav-footer mx-3 ">
        <div class="card card-plain shadow-none" id="sidenavCard">
            <img class="w-50 mx-auto" src="/img/illustrations/icon-documentation-warning.svg"
                alt="sidebar_illustration">
            <div class="card-body text-center p-3 w-100 pt-0">
                <div class="docs-info">
                    <h6 class="mb-0">Bạn cần trợ giúp?</h6>
                    <p class="text-xs font-weight-bold mb-0">Vui lòng liên hệ admin</p>
                </div>
            </div>
        </div>
        <a class="btn btn-primary btn-sm mb-0 w-100" href="https://t.me/onezoroone" target="_blank" type="button">Liên
            hệ ngay</a>
    </div>
</aside>