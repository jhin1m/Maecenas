<section>
    @if ($data->count() > 0)
    <div class="mb-3 flex">
        <div class="w-9 flex-none content-center pr-3 text-sm font-bold">
            <img src="/img/medal-1.svg" width="24" height="32">
        </div>
        <div class="flex grow separate-border">
            <div class="grow">
                <h3><a class="line-clamp-2 font-bold hover:underline" href="{{ route('detailStory', ['slug' => $data[0]->slug]) }}">{{$data[0]->name}}</a></h3>
                @if($data[0]->author->count() > 0)
                <div class="my-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="mr-1 h-4 w-4" height="16" width="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                    </svg>
                    <a href="{{ route('authorStory', ['slug' => $data[0]->author[0]->slug]) }}" class="line-clamp-1 text-sm hover:underline">{{$data[0]->author[0]->name}}</a>
                </div>
                @endif
                @if($data[0]->categories->count() > 0)
                <div class="my-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="mr-1 h-4 w-4 text-sm" height="16" width="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path>
                    </svg>
                    <a class="line-clamp-1 text-sm hover:underline capitalize" href="{{ route('categoryStory', ['slug' => $data[0]->categories[0]->slug]) }}">{{$data[0]->categories[0]->name}}</a>
                </div>
                @endif
            </div>
            <div class="h-32 w-20 flex-none pr-2 pb-2">
                <div class="book-cover aspect-w-1 aspect-h-1 md:aspect-none w-full group-hover:opacity-75">
                    <figure class="pl-2">
                        <span style="box-sizing:border-box;display:inline-block;overflow:hidden;width:initial;height:initial;background:none;opacity:1;border:0;margin:0;padding:0;position:relative;max-width:100%">
                            <span style="box-sizing:border-box;display:block;width:initial;height:initial;background:none;opacity:1;border:0;margin:0;padding:0;max-width:100%">
                                <img class="lozad" style="display:block;max-width:100%;width:initial;height:initial;background:none;opacity:1;border:0;margin:0;padding:0" alt="" data-src="{{$data[0]->thumbnail}}">
                            </span>
                        </span>
                    </figure>
                    <span class="book-cover-shadow"></span>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if ($data->count() > 1)
    <div class="group mb-2 flex text-sm">
        <div class="w-9 flex-none content-center pr-3 font-bold">
            <img src="/img/medal-2.svg" width="24" height="32">
        </div>
        <div class="flex grow group-last:border-b-0 separate-border">
            <div class="grow">
                <h3><a class="line-clamp-1 hover:underline" href="{{ route('detailStory', ['slug' => $data[1]->slug]) }}">{{$data[1]->name}}</a></h3>
            </div>
            <div class="line-clamp-1 flex-none pl-3 pb-2"></div>
        </div>
    </div>
    @endif
    @if ($data->count() > 2)
    <div class="group mb-2 flex text-sm">
        <div class="w-9 flex-none content-center pr-3 font-bold">
            <img src="/img/medal-3.svg" width="24" height="32">
        </div>
        <div class="flex grow group-last:border-b-0 separate-border">
            <div class="grow">
                <h3><a class="line-clamp-1 hover:underline" href="{{ route('detailStory', ['slug' => $data[2]->slug]) }}">{{$data[2]->name}}</a></h3>
            </div>
            <div class="line-clamp-1 flex-none pl-3 pb-2"></div>
        </div>
    </div>
    @endif
    @if ($data->count() > 3)
    @foreach ($data->slice(3) as $item)
    <div class="group mb-2 flex text-sm">
        <div class="w-9 flex-none content-center pr-3 text-center font-bold">{{$loop->iteration + 3}}</div>
        <div class="flex grow group-last:border-b-0 separate-border">
            <div class="grow">
                <h3>
                    <a class="line-clamp-1 hover:underline" href="{{ route('detailStory', ['slug' => $item->slug]) }}">{{$item->name}}</a>
                </h3>
            </div>
            <div class="line-clamp-1 flex-none pl-3 pb-2"></div>
        </div>
    </div>
    @endforeach
    @endif
</section>
