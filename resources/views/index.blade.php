<x-layout>

    @php
      $currentIndex = 0;
      
    @endphp
  <div class="mt-10 flex gap-2">
    <div id="default-carousel" class="relative w-7/10" data-carousel="slide">
    {{-- Carousel wrapper --}}
      <div class="relative h-[500px] overflow-hidden rounded-lg md:h-[500px]">
        @foreach ($trendings as $index => $trending )
          <div class="{{ $index === 0 ? '' : 'hidden' }} duration-700 ease-in-out bg-center bg-cover w-full h-full  inset-0 flex flex-col justify-end"
            data-carousel-item
            style="background-image: url('{{ $trending['backdrop_path'] ? asset($img_path . $trending['backdrop_path']) : asset('storage/backdrop-not-found.jpg') }}')">
            {{-- Overlay gelap transparan --}}
            <div class="absolute inset-0 bg-black/40"></div>
            {{-- Gradient Bottom Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
            <div class="absolute bottom-5  z-10 text-white m-10 flex gap-3">
              <img class="h-auto w-40  border-white rounded-lg shadow-xl" src="{{$trending['poster_path'] ? asset($img_path . $trending['poster_path']) : asset('storage/poster-not-found.png')}}">
              <div class="flex flex-col justify-between">
                <div class="flex flex-col gap-2">
                  <h1 class="text-5xl ">{{$trending['original_title'] ?? $trending['original_name']}}</h1>
                  <p class=" text-1xl text-slate-300">{{$trending['overview']}}</p>
                </div>
                <a href="/{{ $trending['media_type'] == 'tv' ? 'tv' : 'movie' }}/{{ $trending['id']}}">
                  <h1 class="text-white my-5 border-2 border-white h-fit w-fit px-2 py-1 rounded-lg text-xl hover:text-yellow-500 hover:border-yellow-500">view more</h1>
                </a>
              </div>
            </div>
          </div>        
        @endforeach
      </div>
    </div>

    {{-- SLider indicators --}}
    {{-- <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
      @foreach ($trendings as $index => $trending)
        <button type="button"
          class="w-3 h-3 rounded-full"
          aria-current="{{$index === 0 ? 'true' : 'false'}}"
          aria-label="Slide {{$index + 1}}"
          data-carousel-slide-to="{{$index}}">
        </button>
      @endforeach

      <!-- Slider controls -->
    <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
            <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
            </svg>
            <span class="sr-only">Previous</span>
        </span>
    </button>
    <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
            <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
            </svg>
            <span class="sr-only">Next</span>
        </span>
    </button>
    </div> --}}
  
    {{-- Up Next --}}
    <div class="w-3/10 bg-slate-800 rounded-lg p-2">
      <h2 class="text-white font-bold text-lg mb-4">Up Next</h2>
      <div class="flex flex-col gap-4 duration-700 ease-in-out">
        @for($i= $currentIndex + 1; $i <= $currentIndex +3; $i++)
          @if(isset($trendings[$i]))
            <a href="/{{ $trendings[$i]['media_type'] == 'tv' ? 'tv' : 'movie' }}/{{ $trendings[$i]['id'] }}" class="flex gap-3 items-start">
              
              <img src="{{ $trendings[$i]['poster_path'] ? asset($img_path.$trendings[$i]['poster_path']) : asset('storage/poster-not-found.png')}}" alt="Poster"
                class="w-20 h-auto rounded shadow-md">
              <div>
                <h3 class="text-white font-medium">{{$trendings[$i]["original_title"] ?? $trendings[$i]["original_name"]}}</h3>
                <p class="text-sm text-gray-300">
                  {{Str::limit($trendings[$i]['overview'], 180)}}
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

  <div class="mt-10">
    <div class="flex justify-between text-white font-bold text-2xl">
      <h1>Up Coming Movie</h1>
      <a href="/upcoming-movie" class="hover:text-yellow-500"><h1>view more</h1></a>
    </div>
    <div class="mt-5 flex flex-wrap items-center gap-3 justify-center">
        @foreach ($upcoming_movies as $movie)
    
        <div class="rounded-lg shadow-xl bg-gray-700 w-64 h-128">
            <a href="/movie/{{ $movie['id'] }}">
                <div class="h-3/4">
                  <img class='rounded-t-lg h-full w-full' src='{{$movie['poster_path'] ? asset($img_path.$movie['poster_path']) : asset('/storage/poster-not-found.png')}}'/>
                </div>
            </a>
            <div class="p-2 text-white flex flex-col gap-1">
                <div class="flex gap-1 items-center">
                    <img class='w-5 h-5' src="https://img.icons8.com/fluency/48/star--v1.png" alt="star--v1"/>
                    <span class="text-gray-400">{{round($movie['vote_average'],1)}}</span>
                </div>
                <a href="/movie/{{ $movie['id'] }}">
                <h1 class="font-bold  text-xl">{{Str::limit($movie['title'] ?? $movie['name'],60)}}</h1>
                </a>
                {{-- <div class="flex flex-wrap gap-2">
                    @foreach($movie['genre_ids'] as $genreId)
                    <a href='/genre/{{$genreId}}/list-movie-tv' class="rounded-lg border-1 p-1 text-xs"><h1>{{$genres[$genreId]['name']}}</h1></a> 
                    @endforeach
                </div> --}}
            </div>
        </div>
    @endforeach
    </div>
  </div>

  <div class="mt-10">
    <div class="flex justify-between text-white font-bold text-2xl">
      <h1>On The Air TV Series</h1>
      <a href="/on-the-air-tv" class="hover:text-yellow-500"><h1>view more</h1></a>
    </div>
    <div class="mt-5 flex flex-wrap items-center gap-3 justify-center">
        @foreach ($ota_tvs as $movie)
    
        <div class="rounded-lg shadow-xl bg-gray-700 w-64 h-128">
            <a href="/tv/{{ $movie['id'] }}">
                <div class="h-3/4">
                  <img class='rounded-t-lg h-full w-full' src='{{$movie['poster_path'] ? asset($img_path.$movie['poster_path']) : asset('/storage/poster-not-found.png')}}'/>
                </div>
            </a>
            <div class="p-2 text-white flex flex-col gap-1">
                <div class="flex gap-1 items-center">
                    <img class='w-5 h-5' src="https://img.icons8.com/fluency/48/star--v1.png" alt="star--v1"/>
                    <span class="text-gray-400">{{round($movie['vote_average'],1)}}</span>
                </div>
                <a href="/tv/{{ $movie['id'] }}">
                <h1 class="font-bold text-xl">{{Str::limit($movie['title'] ?? $movie['name'],60)}}</h1>
                </a>
                <div class="flex flex-wrap gap-2">
                    {{-- {{dd($movie['genre_ids'] )}} --}}
                    {{-- @foreach($movie['genre_ids'] as $genreId)
                    <a href='/genre/{{$genreId}}/list-movie-tv' class="rounded-lg border-1 p-1 text-xs"><h1>{{$genres[$genreId]['name']}}</h1></a> 
                    @endforeach --}}
                </div>
            </div>
        </div>
    @endforeach
    </div>
  </div>
  

</x-layout>