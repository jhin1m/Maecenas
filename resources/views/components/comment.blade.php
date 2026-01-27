<div class="list-all-comments">
    <div class="offcanvas-header">
        <button class="btn-close" type="button" data-bs-dismiss="offcanvas" aria-label="Close"><i class="icon-close-circle"></i></button>
        <h4 class="title">
            Bình luận <span class="countComment">({{$data->total()}})</span>
        </h4>
    </div>

    <div id="content-comments">
        @if (!Auth::check())
        <div class="comment-input d-flex">
            <div class="avatar-temp user-avatar-img avatar-temp-cmt">
                <img src="/assets/images/favicon.png" style="object-fit:contain">
            </div>
            <div class="ci-form flex-grow-1">
                <div class="user-name cmt-noti">Bạn cần <a href="" class="color" rel="nofollow" data-bs-toggle="modal" data-bs-target="#loginModal">đăng nhập</a> để
                    bình luận.</div>
            </div>
        </div>
        @else
        <div class="comment-input d-flex">
            <div class="avatar-temp user-avatar-img" data-name="{{Auth::user()->name}}" style="background-image: url({{Auth::user()->avatar}});background-size: cover; background-position: center; background-repeat: no-repeat; background-color: #787978"></div>
            <div class="ci-form flex-grow-1">
                <form class="preform comment-form">
                    <div class="loading-absolute bg-white" style="display: none;">
                        <div class="loading">
                            <div class="span1"></div>
                            <div class="span2"></div>
                            <div class="span3"></div>
                        </div>
                    </div>
                    <div class="cmt-box">
                        <textarea class="form-control form-control-textarea" id="df-cm-content" name="content" maxlength="3000" placeholder="Bình luận" required=""></textarea>
                        <div class="ci-buttons align-items-center" id="df-cm-buttons">
                            <div class="ci-b-right d-flex align-items-center">
                                <button type="button" class="btn btn-cmt">Bình luận</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

        @include('components.list-comments')
    </div>
</div>
