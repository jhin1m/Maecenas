@extends('users.layout.main')
@section('metadata')
    <title>Bình luận của tôi</title>
    <meta property="og:title" content="Bình luận của tôi">
    <meta property="robots" content="noindex, nofollow">
@endsection
@section('content')
<img src="/assets/images/search.png" alt="" class="w-screen absolute left-0 top-0 h-[526px] object-cover object-center -z-20">
<div class="w-screen absolute left-0 top-0 h-[526px] bg-gradient-to-t from-background to-background/10 -z-10"></div>
<div class="2xl:px-[0px] px-5 md:mt-16 mt-8">
    <div class="flex flex-col items-center justify-center">
        <div class="flex items-center gap-2 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
               <path fill-rule="evenodd" clip-rule="evenodd" d="M6.93683 0.663369C7.63339 0.272484 8.27861 0.041748 9.00033 0.041748C9.72205 0.041748 10.3673 0.272484 11.0638 0.663369C11.7387 1.0421 12.5121 1.60128 13.4846 2.30443L13.4846 2.30446L14.7421 3.21369C15.5226 3.77797 16.1459 4.22866 16.6161 4.64581C17.1015 5.07653 17.4647 5.50591 17.6951 6.04603C17.926 6.58732 17.9818 7.14212 17.9508 7.78181C17.9209 8.39913 17.8065 9.14393 17.6637 10.0727L17.401 11.7823C17.1982 13.1022 17.0367 14.1531 16.7995 14.9719C16.5541 15.8185 16.2085 16.4907 15.5908 17.0044C14.9757 17.5161 14.2429 17.7439 13.3478 17.8527C12.477 17.9584 11.3785 17.9584 9.99109 17.9584H8.00957C6.62212 17.9584 5.52366 17.9584 4.65288 17.8527C3.75775 17.7439 3.02497 17.5161 2.40983 17.0044C1.79212 16.4907 1.44653 15.8185 1.2012 14.9719C0.963948 14.1531 0.802456 13.1022 0.599645 11.7823L0.336931 10.0728C0.194192 9.14398 0.0797244 8.39915 0.0498362 7.78181C0.0188658 7.14212 0.0747036 6.58732 0.305574 6.04603C0.535941 5.50591 0.899117 5.07653 1.38457 4.64581C1.85473 4.22866 2.47807 3.77797 3.25852 3.21368L4.51605 2.30445C5.48855 1.60129 6.26191 1.04211 6.93683 0.663369ZM9.83366 11.5001C9.83366 11.0398 9.46056 10.6667 9.00033 10.6667C8.54009 10.6667 8.16699 11.0398 8.16699 11.5001V14.0001C8.16699 14.4603 8.54009 14.8334 9.00033 14.8334C9.46056 14.8334 9.83366 14.4603 9.83366 14.0001V11.5001Z" fill="url(#paint0_linear_14108_2109)"></path>
               <defs>
                  <linearGradient id="paint0_linear_14108_2109" x1="9.00088" y1="0.0419348" x2="9.00088" y2="17.9598" gradientUnits="userSpaceOnUse">
                     <stop stop-color="#EFB78F"></stop>
                     <stop offset="1" stop-color="#E99B63"></stop>
                  </linearGradient>
               </defs>
            </svg>
            <a href="/" class="text-sm font-medium leading-4">Trang chủ</a>
            <svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12" fill="none">
               <path d="M1.50004 1C1.50004 1 6.49999 4.68244 6.5 6.00004C6.50001 7.31763 1.5 11 1.5 11" stroke="#8991A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <a href="#" class="text-sm font-medium leading-4 text-lightgray">Truyện đang theo dõi</a>
        </div>
        @include('users.layout.nav')

        <div class="w-full">
            <div class="space-y-6">
                @foreach ($comments as $comment)
                @if ($comment->comic->slug)
                <a title="Truyện {{$comment->comic->name}}" href="{{route('detail', ['slug' => $comment->comic->slug])}}" class="p-6 block border rounded-2xl border-border-gray">
                    <div class="flex flex-col justify-between gap-4 mb-6 md:items-center md:flex-row">
                       <div class="flex items-center gap-4">
                          <img loading="lazy" src="{{$comment->user->avatar}}" alt="" class="object-cover object-center w-12 rounded-2xl aspect-square">
                          <div>
                             <p class="mb-2 gradient-text-primary">{{$comment->user->name}}</p>
                             <div class="flex items-center gap-2">
                                <img src="/images/clock.svg" alt="">
                                <p class="text-xs text-lightgray">{{$comment->created_at->diffForHumans()}}</p>
                             </div>
                          </div>
                       </div>
                       <div class="flex items-center gap-2 shrink-0">
                            <button data-comment-like="96" class="rounded-xl bg-background p-3 flex items-center gap-2 border border-border-gray">
                                <img src="/assets/images/like.svg" class="" alt="">
                                <p class="text-[#38CB89] text-sm">{{$comment->like}}</p>
                            </button>
                            <button data-comment-dislike="96" class="rounded-xl bg-background p-3 flex items-center gap-2 border border-border-gray">
                                <img src="/assets/images/dislike.svg" class="" alt="">
                                <p class="text-[#FE6565] text-sm">{{$comment->dislike}}</p>
                            </button>

                            <button class="flex items-center gap-2 p-3 border rounded-xl bg-background border-border-gray">
                                <img src="/assets/images/star.svg" class="" alt="">
                                <p class="text-[#FFAD40] text-sm">{{$comment->report}}</p>
                            </button>
                       </div>
                    </div>
                    <div class="pt-6 border-t border-t-border-gray">
                       <div class="overflow-y-auto text-sm leading-6 text-lightgray max-h-56">
                            {!! $comment->content !!}
                       </div>
                    </div>
                </a>
                @else
                <div class="p-6 block border rounded-2xl border-border-gray">
                    <div class="flex flex-col justify-between gap-4 mb-6 md:items-center md:flex-row">
                       <div class="flex items-center gap-4">
                          <img loading="lazy" src="{{$comment->user->avatar}}" alt="" class="object-cover object-center w-12 rounded-2xl aspect-square">
                          <div>
                             <p class="mb-2 gradient-text-primary">{{$comment->user->name}}</p>
                             <div class="flex items-center gap-2">
                                <img src="/images/clock.svg" alt="">
                                <p class="text-xs text-lightgray">{{$comment->created_at->diffForHumans()}}</p>
                             </div>
                          </div>
                       </div>
                       <div class="flex items-center gap-2 shrink-0">
                            <button data-comment-like="96" class="rounded-xl bg-background p-3 flex items-center gap-2 border border-border-gray">
                                <img src="/assets/images/like.svg" class="" alt="">
                                <p class="text-[#38CB89] text-sm">{{$comment->like}}</p>
                            </button>
                            <button data-comment-dislike="96" class="rounded-xl bg-background p-3 flex items-center gap-2 border border-border-gray">
                                <img src="/assets/images/dislike.svg" class="" alt="">
                                <p class="text-[#FE6565] text-sm">{{$comment->dislike}}</p>
                            </button>

                            <button class="flex items-center gap-2 p-3 border rounded-xl bg-background border-border-gray">
                                <img src="/assets/images/star.svg" class="" alt="">
                                <p class="text-[#FFAD40] text-sm">{{$comment->report}}</p>
                            </button>
                       </div>
                    </div>
                    <div class="pt-6 border-t border-t-border-gray">
                       <div class="overflow-y-auto text-sm leading-6 text-lightgray max-h-56">
                            {!! $comment->content !!}
                       </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            <div class="mt-5">
                {{$comments->links('vendor.pagination.custom')}}
            </div>
        </div>
    </div>
</div>
@endsection
