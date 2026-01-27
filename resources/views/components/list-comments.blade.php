<div class="list-comments">
    @foreach ($data as $comment)
    <div class="cmt-line d-flex" data-parent-id="{{$comment->id}}" id="cmt-{{$comment->id}}">
        <div class="user-avatar flex-shrink-0">
            <div id="avatar-temp-hs" class="avatar-temp user-avatar-img" style="background-image: url({{$comment->user->avatar}}); background-size: cover; background-position: center; background-repeat: no-repeat; background-color: #787978 " data-name="{{$comment->user->name}}">
            </div>
        </div>
        <div class="info flex-grow-1">
            <div class="ihead">
                <div class="user-name">{{$comment->user->name}}</div>

                @if ($comment->chapter)
                <a href="{{route('showRead', ['slug' => $comment->comic->slug, 'chapter' => $comment->chapter->slug])}}" class="link-cmt-chap">Chapter {{$comment->chapter->name}}</a>
                @endif
                <div class="time">{{$comment->created_at->diffForHumans()}}</div>
            </div>
            <div class="ibody">
                <p class="">{!! $comment->content !!}</p>
            </div>
            <div class="ibottom">
                <div class="ib-li ib-reply">
                    <a href="#" class="btn-reply">
                        <i class="icon-messages"></i> Trả lời</a>
                </div>
                <div class="ib-li ib-like">
                    <span class="cm-btn-like" data-id="{{$comment->id}}">
                        <i class="icon-like"></i> Thích<span class="value">{{$comment->like}}</span>
                    </span>
                </div>
            </div>

            @if ($comment->replies->count() > 0)
            <div class="replies" id="block-reply-{{$comment->id}}">
                <div class="rep-more rep-in">
                    <a class="cm-btn-show-rep" data-bs-toggle="collapse" href="#collapseReply-{{$comment->id}}" role="button" aria-expanded="false" aria-controls="collapseReply-{{$comment->id}}">
                        <ion-icon name="caret-down"></ion-icon><span>{{$comment->replies->count()}} câu trả
                            lời</span>
                    </a>
                </div>

                <div class="replies-wrap collapse" id="collapseReply-{{$comment->id}}">
                    @foreach ($comment->replies as $reply)
                    <div class="cmt-line d-flex" id="cmt-{{$reply->id}}">
                        <div class="user-avatar flex-shrink-0">
                            <div id="avatar-temp-hs" class="avatar-temp user-avatar-img" style="background-image: url({{$reply->user->avatar}}); background-size: cover; background-position: center; background-repeat: no-repeat; background-color: #787978" data-name="{{$reply->user->name}}">
                            </div>
                        </div>
                        <div class="info flex-grow-1">
                            <div class="ihead">
                                <div class="user-name">{{$reply->user->name}}</div>

                                <div class="time">{{$reply->created_at->diffForHumans()}}</div>

                            </div>
                            <div class="ibody">
                                <p class="">{!! $reply->content !!}</p>
                            </div>
                            <div class="ibottom">
                                <div class="ib-li ib-reply">
                                    <a href="" class="btn-reply">
                                        <i class="icon-messages"></i>Trả
                                        lời</a>
                                </div>
                                <div class="ib-li ib-like">
                                    <a class="cm-btn-like" data-type="1" data-id="{{$reply->id}}">
                                        <i class="icon-like"></i><span class="value">{{$reply->like}}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="reply-box">
                <div class="cmt-line d-none"></div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="template-comment d-none">
        <div class="cmt-line d-flex" data-parent-id="" id="cmt-">
            <div class="user-avatar flex-shrink-0">

                <div id="avatar-temp-hs" class="avatar-temp user-avatar-img" style="background-image: url(); background-size: cover; background-position: center; background-repeat: no-repeat; background-color: #787978 " data-name="U">
                    U
                </div>
            </div>
            <div class="info flex-grow-1">
                <div class="ihead">
                    <div class="user-name"></div>

                    <div class="time">vài giây trước</div>
                </div>
                <div class="ibody">
                    <p class=""></p>
                </div>
                <div class="ibottom">
                    <div class="ib-li ib-reply">
                        <a href="#" class="btn-reply">
                            <i class="icon-messages"></i> Trả lời</a>
                    </div>
                    <div class="ib-li ib-like">
                        <span class="cm-btn-like">
                            <i class="icon-like"></i> Thích<span class="value"></span>
                        </span>
                    </div>
                </div>

                <div class="reply-box">
                    <div class="cmt-line d-none"></div>
                </div>
            </div>
        </div>
    </div>

    {{$data->links('vendor.pagination.custom')}}
</div>
