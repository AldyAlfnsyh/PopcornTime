<x-layout>

    @php
      $currentIndex = 0;   
    @endphp
  <div class="container px-10 sm:mx-auto mt-10 flex items-stretch gap-2">
    <div id="default-carousel" class="relative lg:w-7/10 w-full h-auto" data-carousel="slide">
      {{-- Carousel wrapper --}}
      <div class="relative h-[500px] overflow-hidden rounded-lg md:h-[500px]">
        @foreach ($trendings as $index => $trending )
          <div class="{{ $index === 0 ? '' : 'hidden' }} duration-700 ease-in-out bg-center bg-cover w-full h-full  inset-0 flex flex-col justify-end"
             data-carousel-item
            style="background-image: url('{{ isset($trending['backdrop_path']) ? asset($img_path . $trending['backdrop_path']) : asset('storage/backdrop-not-found.jpg') }}')">
            {{-- Overlay gelap transparan --}}
            <div class="absolute inset-0 bg-black/40"></div>
            {{-- Gradient Bottom Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
            <div class="absolute bottom-5  z-10 text-white m-10 flex h-2/4 gap-3">
              <img class="h-full w-auto  border-white rounded-lg shadow-xl" src="{{isset($trending['poster_path']) ? asset($img_path . $trending['poster_path']) : asset('storage/poster-not-found.png')}}">
              <div class="h-full flex flex-col justify-between">
                <div class="flex flex-col gap-2">
                  <h1 class="text-5xl line-clamp-2">{{$trending['original_title'] ?? $trending['original_name']}}</h1>
                  <p class=" text-1xl text-slate-300 line-clamp-4">{{($trending['overview'])}}</p>
                </div>
                <a href="/{{ $trending['media_type'] == 'tv' ? 'tv' : 'movie' }}/{{ $trending['id']}}">
                  <h1 class="text-white  border-2 border-white h-fit w-fit px-2 py-1 rounded-lg text-xl hover:text-yellow-500 hover:border-yellow-500">view more</h1>
                </a>
              </div>
            </div>
          </div>        
        @endforeach
      </div>
    </div>
  
    {{-- Up Next --}}
    <div id="default-carousel" class="hidden lg:block w-3/10 h-auto bg-slate-800 rounded-lg p-2" >
      <h2 class="text-white font-bold text-lg mb-4">Up Next</h2>
      <div class="relative flex flex-col h-auto gap-4 duration-700 ease-in-out" >
        @for($i= $currentIndex + 1; $i <= $currentIndex +3; $i++)
          @if(isset($trendings[$i]))
            <a href="/{{ $trendings[$i]['media_type'] == 'tv' ? 'tv' : 'movie' }}/{{ $trendings[$i]['id'] }}" class="flex gap-3 items-start">
              
              <img src="{{ isset($trendings[$i]['poster_path']) ? asset($img_path.$trendings[$i]['poster_path']) : asset('storage/poster-not-found.png')}}" alt="Poster"
                class="w-20 h-auto rounded shadow-md">
              <div>
                <h3 class="text-white font-medium">{{$trendings[$i]["original_title"] ?? $trendings[$i]["original_name"]}}</h3>
                <p class="hidden xl:block  text-sm text-gray-300 ">
                  {{Str::limit($trendings[$i]['overview'], 60)}}
                </p>
              </div>
            </a>
            @if($i<$currentIndex+3)
            <hr class="border-t border-slate-400 w-full">
            @endif
          @endif
        @endfor
      </div>
    </div>
  </div>

  {{-- Upcoming Movie --}}
  <div class="container mx-auto mt-10">
    <div class="flex justify-between text-white font-bold text-2xl">
      <h1>Up Coming Movie</h1>
      <a href="/upcoming-movie" class="hover:text-yellow-500"><h1>view more</h1></a>
    </div>

    {{-- Using Swiper JS --}}
    <div class="swiper mt-5">
      <div class="swiper-wrapper items-stretch">
        @foreach ($upcoming_movies as $movie)
          <div class="swiper-slide ">
            <div class="rounded-lg shadow-xl bg-gray-700 flex-1 h-auto ">
            <a href="/movie/{{ $movie['id'] }}">
                <div class="h-3/4">
                  <img class='rounded-t-lg h-full w-full' src='{{isset($movie['poster_path']) ? asset($img_path.$movie['poster_path']) : asset('/storage/poster-not-found.png')}}'/>
                </div>
            </a>
            <div class="p-2 text-white flex flex-col gap-1">
                <div class="flex gap-1 items-center">
                    <img class='w-5 h-5' src="https://img.icons8.com/fluency/48/star--v1.png" alt="star--v1"/>
                    <span class="text-gray-400">{{round($movie['vote_average'],1)}}</span>
                </div>
                <a href="/movie/{{ $movie['id'] }}">
                <h1 class="font-bold  text-xl line-clamp-2 min-h-[3.5rem]">{{Str::limit($movie['title'] ?? $movie['name'],60)}}</h1>
                </a>
            </div>
        </div>
          </div>
        @endforeach
      </div>

      <!-- Navigation Button -->
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>

  </div>

  {{-- On the Air TV Series --}}
  <div class="container mx-auto mt-10">
    <div class="flex justify-between text-white font-bold text-2xl">
      <h1>On The Air TV Series</h1>
      <a href="/on-the-air-tv" class="hover:text-yellow-500"><h1>view more</h1></a>
    </div>

    {{-- Using Swiper JS --}}
    <div class="swiper mt-5">
      <div class="swiper-wrapper items-stretch">
        @foreach ($ota_tvs as $movie)
          <div class="swiper-slide">
            <div class="rounded-lg shadow-xl bg-gray-700 flex-1 h-auto">
              <a href="/tv/{{ $movie['id'] }}">
                <div class="h-3/4">
                  <img class='rounded-t-lg h-full w-full' src='{{isset($movie['poster_path']) ? asset($img_path.$movie['poster_path']) : asset('/storage/poster-not-found.png')}}'/>
                </div>
              </a>
            <div class="p-2 text-white flex flex-col gap-1">
              <div class="flex gap-1 items-center">
                <img class='w-5 h-5' src="https://img.icons8.com/fluency/48/star--v1.png" alt="star--v1"/>
                <span class="text-gray-400">{{round($movie['vote_average'],1)}}</span>
              </div>
              <a href="/tv/{{ $movie['id'] }}">
                <h1 class="font-bold text-xl line-clamp-2 min-h-[3.5rem]">{{Str::limit($movie['title'] ?? $movie['name'],60)}}</h1>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

      <!-- Navigation Button -->
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>
  </div>
  



</x-layout>