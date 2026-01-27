@extends("users.layout.main")
@section('metadata')
    <title>Kết quả tìm kiếm</title>
    <meta property="og:title" content="Kết quả tìm kiếm">
    <meta property="robots" content="noindex, nofollow">
@endsection
@section('class') homepage @endsection
@section('content')
<div class="content">
    <div class="div_middle">
        <div class="alert-note-fix"></div>
        <div class="main_content">
            <div id="main_homepage">
                <div class="homepage_tags">
                    <h1>
                        <p class="text_list_update">
                            <i class="fa fa-font-awesome" aria-hidden="true"></i> {{__('string.resultSearch')}}
                        </p>
                    </h1>
                    <div class="clear"></div>
                </div>
                <div class="list_grid_out">
                    @if ($comics->count() == 0)
                    <div class="warning-list box">{{__('string.notFound')}}</div>
                    @endif
                    <ul class="list_grid grid">
                        @foreach ($comics as $item)
                        <li>
                            <div class="book_avatar">
                                <a href="{{route('detail', ['slug' => $item->slug])}}">
                                    <img class="center lozad" data-src="{{$item->thumbnail}}" alt="{{$item->name}}">
                                </a>
                                <div class="top-notice">
                                    <span class="time-ago">{{$item->updated_at->diffForHumans()}}</span>
                                    @if ($item->total_views > 1000)
                                    <span class="type-label hot">Hot</span>
                                    @endif
                                </div>
                            </div>
                            <div class="book_info">
                                <div class="book_name qtip" data-qtip="#truyen-tranh-15893">
                                    <h3><a title="Lostend- Thất Lạc" href="{{route('detail', ['slug' => $item->slug])}}">{{$item->name}}</a></h3>
                                </div>
                                <div class="clear"></div>
                                <div class="last_chapter">
                                @if ($item->chapters->count() > 0)
                                    <a href="{{route('showRead', ['slug' => $item->slug, 'chapter' => $item->chapters->last()->slug])}}" title="Chapter {{$item->chapters->last()->name}}">Chapter {{$item->chapters->last()->name}}</a>
                                @else
                                    <a href="#">{{__('string.updating')}}</a>
                                @endif
                                </div>
                            </div>
                            <div class="clear"></div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="clear"></div>
                {{$comics->links('vendor.pagination.custom')}}
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
@endsection
@section('scripts')

@endsection
