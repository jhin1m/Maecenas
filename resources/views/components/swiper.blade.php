<section class="w-[90%] mx-auto w-max-[1300px] mt-6 overflow-x-hidden">
    <h2 class="mt-4 flex select-none items-center font-secondary text-3xl text-white hover:cursor-pointer  md:text-4xl lg:text-5xl">
        <div class="flex items-center transition-all hover:text-primary">
            <a href="{{ $link }}">{{ $title }}</a>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon" class="h-8 w-8 lg:h-10 lg:w-10"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"></path></svg>
        </div>
    </h2>
    <div class="swiper section-swiper mt-5 swiper-updated">
        <div class="swiper-wrapper">
            @foreach ($items as $comic)
                <div class="swiper-slide">
                    <div class="aspect-h-4 aspect-w-3 rounded-xl relative group">
                        <div>
                            <a href="/{{$comic->slug}}" class='z-0 cursor-pointer' >
                                <img data-src="{{$comic->thumbnail}}" class="lozad fancy-fade-in z-0 absolute top-0 left-0 rounded-xl object-cover object-center w-full h-full" alt="manga-thumbnail" />
                            </a>
                            @isset($comic->chapter)
                                <span class="absolute top-2 left-2 w-fit h-fit rounded-xl bg-white bg-opacity-40 px-4 py-2 text-base backdrop-blur-md md:text-xl lg:text-3xl">
                                    Chapter {{$comic->chapter->name}}
                                </span>
                            @endisset
                            <div class="relative cursor-pointer animate__faster z-[9999] animate__animated animate__fadeIn h-full w-full flex-col space-y-2 overflow-hidden rounded-xl bg-highlight invisible md:visible hidden text-white group-hover:flex">
                                <a href="/{{$comic->slug}}">
                                    <h3 class="ml-4 mt-4 min-h-[40px] text-[100%] font-semibold line-clamp-2 hover:text-primary">
                                        {{$comic->name}}
                                    </h3>
                                </a>
                                @isset($comic->chapter)
                                    <p class="ml-4 flex flex-nowrap items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                                        </svg>
                                        <span class="ml-2 text-[90%] line-clamp-1">
                                            Chapter {{$comic->chapter->name}}
                                        </span>
                                    </p>
                                @endisset
                                <p class="ml-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span class="ml-2 text-[90%]">
                                        {{ $comic->updated_at->format('d/m/Y') }}
                                    </span>
                                </p>
                                <p class="ml-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.348 14.652a3.75 3.75 0 0 1 0-5.304m5.304 0a3.75 3.75 0 0 1 0 5.304m-7.425 2.121a6.75 6.75 0 0 1 0-9.546m9.546 0a6.75 6.75 0 0 1 0 9.546M5.106 18.894c-3.808-3.807-3.808-9.98 0-13.788m13.788 0c3.808 3.807 3.808 9.98 0 13.788M12 12h.008v.008H12V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                    <span class="ml-2 text-[90%]">
                                        {{$comic->status}}
                                    </span>
                                </p>
                                <div class="flex h-fit w-full flex-col items-center space-y-4 py-6">
                                    @isset($comic->chapter)
                                        <a href="/{{$comic->slug}}/{{$comic->chapter->slug}}" class="flex w-fit items-center justify-center space-x-4 rounded-xl bg-primary py-2 px-4 transition-all hover:scale-[110%]">
                                            <span>Đọc ngay</span>
                                        </a>
                                    @endisset
                                    <button class="flex w-fit items-center justify-center space-x-4 rounded-xl bg-white py-2 px-4 text-gray-700 transition-all hover:scale-[110%]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                        </svg>
                                        <a href="/{{$comic->slug}}">
                                            Thông tin
                                        </a>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="/{{$comic->slug}}">
                        <h2 class="my-2 select-none text-xl text-white transition-all line-clamp-1 hover:text-primary md:text-2xl">
                            {{$comic->name}}
                        </h2>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>
