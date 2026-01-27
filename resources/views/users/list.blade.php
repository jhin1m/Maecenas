@extends("users.layout.main")
@section('metadata')
    <title>Danh sách {{$name}}</title>
    <meta property="og:title" content="Danh sách {{$name}}">
@endsection
@section('class')
homepage @endsection
@section('content')
<div class="content">
    <div class="div_middle">
        <div class="alert-note-fix"></div>
        <div class="main_content">
            <div id="main_homepage">
                <div class="homepage_tags">
                    <h1>
                        <p class="text_list_update"><i class="fa fa-font-awesome" aria-hidden="true"></i> {{$name}}</p>
                    </h1>
                    <div class="clear"></div>
                </div>
                <div class="story-list-bl01 box">
                    <table>
                        <tbody>
                            <tr>
                                <th>{{__('string.status')}}</th>
                                <td>
                                    <ul class="choose">
                                        <li><a class="{{ request()->has('status') && request()->input('status') == 1 ? 'active' : '' }}" href="{{ route('showList', ['slug' => $slug]) . '?' . http_build_query(array_merge(request()->query(), ['status' => 1])) }}">{{__('string.ongoing')}}</a></li>
                                        <li><a class="{{ request()->has('status') && request()->input('status') == 2 ? 'active' : '' }}" href="{{ route('showList', ['slug' => $slug]) . '?' . http_build_query(array_merge(request()->query(), ['status' => 2])) }}">{{__('string.completed')}}</a></li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="list_grid_out">
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
                                <div class="book_name qtip" data-qtip="#truyen-tranh-{{$item->id}}">
                                    <h3><a title="{{$item->name}}" href="{{route('detail', ['slug' => $item->slug])}}">{{$item->name}}</a></h3>
                                </div>
                                <div class="clear"></div>
                                <div class="text_detail">
                                     <span>
                                        <i class="fa fa-bookmark"></i> 0
                                    </span>
                                    <span>
                                        <i class="fa fa-eye"></i>{{$item->total_views}}
                                    </span>
                                </div>
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
