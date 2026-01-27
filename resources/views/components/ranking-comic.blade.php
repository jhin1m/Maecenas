@foreach ($data as $item)
    <div class="flex gap-3 items-center overflow-hidden h-fit border-b border-orangecus pb-3 last:border-none">
        <div class="w-[80px] h-[110px]">
            <a href="{{route('detailComic', ['slug' => $item->slug])}}">
                <img data-src="{{$item->thumbnail}}" class="lozad w-full h-full" alt="">
            </a>
        </div>
        <div class="flex-1">
            <a href="{{route('detailComic', ['slug' => $item->slug])}}">
                <h5 class="line-clamp-1 hover:underline text-ellipsis overflow-hidden">{{$item->name}}</h5>
            </a>
            @if($item->categories->count() > 0)
            <div class="text-[15px] line-clamp-1 overflow-hidden text-ellipsis">
                @foreach ($item->categories as $category)
                <span>
                    <a class="hover:text-orangecus" href="{{route('categoryComic', ['slug' => $category->slug])}}">
                        {{$category->name}}
                    </a>
                    @if (!$loop->last)
                        <span class="w-[4px] h-[4px] rounded-[50%] bg-orangecus inline-block mx-[4px] my-[3px]"></span>
                    @endif
                </span>
                @endforeach
            </div>
            @endif
            @if ($item->chapters->count() > 0)
            <div>
                <a class="flex gap-2 items-center text-[14px] text-[#3498db] hover:underline" href="{{route('readComic', ['slug' => $item->slug, 'chapter' => $item->chapters->last()->slug])}}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-medical" viewBox="0 0 16 16">
                        <path d="M7.5 5.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L6 7l-.549.317a.5.5 0 1 0 .5.866l.549-.317V8.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L8 7l.549-.317a.5.5 0 1 0-.5-.866l-.549.317zm-2 4.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/>
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
                    </svg>
                    <span>Chapter {{$item->chapters->last()->name}}</span>
                </a>
            </div>
            @endif
        </div>
        <div class="col-span-1 flex items-end flex-col">
            <div class="h-[40px] flex items-center justify-center w-[40px] text-center mb-2 bg-orangecus text-white">
                {{ sprintf('%02d', $loop->iteration) }}
            </div>
            <div class="border border-[#ccc] text-black dark:text-white rounded-md px-2 w-fit flex gap-1 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>
                {{ $item->total_views >= 1000 ? round($item->total_views / 1000, 1) . 'K' : $item->total_views }}
            </div>
        </div>
    </div>
    @endforeach
