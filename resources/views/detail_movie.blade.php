<x-layout>
    <div class="mt-10 text-white flex flex-col gap-5">
        <div class="flex justify-between">
            <div class="flex flex-col">
                <h1 class="font-bold text-5xl">{{$title}}</h1>
                <div class="flex gap-1">
                    <h1>{{$release_date}} •</h1>
                    @if(isset($origin_country) && count($origin_country ))
                    <h1>{{$origin_country[0]}} •</h1>
                    @endif
                    <h1>{{$runtime}} min</h1>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="flex flex-col items-start">
                    <h1  class="font-bold">Vote Average</h1>
                    <div class="flex gap-1 items-center h-full">
                        <img class='w-8 h-8' src="https://img.icons8.com/fluency/48/star--v1.png" alt="star--v1"/>    
                        <h1 class="text-gray-400"><span class="text-gray-400 text-2xl font-bold">{{round($vote_average,1)}}/</span>{{$vote_count}}</h1>
                    </div>
                </div>
                @if(isset($popularity))
                <div class="flex flex-col items-start ">
                    <h1 class="font-bold">Popularity</h1>
                    <div class="flex gap-1 items-center h-full">
                        <img class='w-8 h-8' src="https://img.icons8.com/?size=100&id=85933&format=png&color=4AC82F"/>
                        <h1 class="text-gray-400 font-bold text-2xl">{{$popularity}}</h1>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        {{-- <div class="flex h-100">
            <img class='rounded-t-lg h-auto w-auto' src='{{asset($img_path.$movie['poster_path'])}}'/>
            <div class="swiper">
                <div class="slider-wrapper">
                    <div class="swiper-wrapper">
                        @foreach ($trailers as $trailer)
                        <div class="swiper-slide">
                            <iframe width="100%" height="100%" class="mt-2" src="https://www.youtube.com/embed/{{ $trailer['key'] }}" frameborder="0" allowfullscreen>
                            </iframe>
                        </div>
                        @endforeach
                    </div>
                    <!-- If we need navigation buttons -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        <div> --}}

        <div class="flex h-120 gap-1 justify-center">
            @if(isset($type) && $type=='episode_detail')
                <img class='rounded-xl h-auto w-auto' src='{{$still_path ? asset($img_path.$still_path) : asset("storage/still-image-not-found.jpg")}}'/>
            @else
                <img class='rounded-xl h-auto w-auto' src='{{$poster_path ? asset($img_path.$poster_path) : asset("storage/poster-not-found.png")}}'/>
            @endif


            @if(isset($trailers) && count($trailers))
            <iframe width="100%" height="100%" class="rounded-xl" src="https://www.youtube.com/embed/{{ $trailers[0]['key'] }}" frameborder="0" allowfullscreen>
            </iframe>
            @endif
        </div>

        @if(isset($movie_genres))
        <div class="flex flex-wrap gap-2">
            @foreach($movie_genres as $genre)
            <a href="/genre/{{$genre['id']}}/list-movie-tv" class="rounded-xl border-1 px-3 text-lg"><h1>{{$genre['name']}}</h1></a> 
            @endforeach
        </div>
        @endif


        <div class="flex flex-col gap-2">
            <p>{{$overview}}</p>
            <div class="flex gap-2">
                
                <h1 class="font-bold">Director</h1>
                @if(isset($directors) == null)
                    @foreach ($directors as $director)
                        <a href="/director/{{$director['id']}}/list-movie-tv">
                            <p class="text-yellow-500">{{$director['name']}} •</p>
                        </a>
                    @endforeach
                @else
                    <p class="text-yellow-500"> - </p>
                @endif
            </div>
            <div class="flex gap-2">
                <h1 class="font-bold">Writer</h1>
                @if(isset($writers) == null)
                    @foreach ($writers as $writer)
                        <a href="/writer/{{$writer['id']}}/list-movie-tv">
                            <p class="text-yellow-500">{{$writer['name']}} •</p>
                        </a>
                    @endforeach
                @else
                    <p class="text-yellow-500"> - </p> 
                @endif
            </div>
            @if(isset($production_companies))
            <div class="flex gap-2">
                <h1 class="font-bold">Production Companies</h1>
                @foreach ($production_companies as $production_companie)
                    <p class="text-yellow-500">{{$production_companie['name']}} •</p>
                @endforeach
            </div>
            @endif
            @if(isset($spoken_languages))
            <div class="flex gap-2">
                <h1 class="font-bold">Language</h1>
                @foreach ($spoken_languages as $spoken_language)
                <p class="text-yellow-500">{{$spoken_language['english_name']}} •</p>
                @endforeach
            </div>
            @endif
        </div>
        <div class="flex flex-col  mt-10">
            <h1 class="font-bold text-3xl text-white">Top Cast</h1>
            <div>
                <div class="mt-10 flex flex-wrap justify-center items-center gap-5">
                    @foreach($casts as $cast)
                    <div class="flex flex-col items-center justify-center">
                        <img class=" h-45 w-45 rounded-full object-cover" src="{{$cast['profile_path'] ? asset($img_path.$cast['profile_path']) : asset('storage/profile-not-found.jpg')}}" >
                        <a href="/cast/{{$cast['id']}}/list-movie-tv" ><h1>{{$cast['name']}}</h1></a>
                        @if($cast['character'])
                        <span>as {{$cast['character']}}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @if(isset($recomendations))
        <div class="flex flex-col  mt-10">
            <h1 class="font-bold text-3xl text-white">Recomendation</h1>
            <div class="mt-10 flex flex-wrap items-center gap-3 justify-center">
                @foreach ($recomendations as $recomendation)
            
                <div class="rounded-lg shadow-xl bg-gray-700 w-60 h-124">
                    <a href='/movie/{{$recomendation['id']}}'>
                    <img class='rounded-t-lg h-auto w-auto' src='{{$recomendation['poster_path'] ? asset($img_path.$recomendation['poster_path']) : asset("storage/poster-not-found.png")}}'/>
                    </a>
                    <div class="p-2 text-white flex flex-col gap-1">
                        <div class="flex gap-1 items-center">
                            <img class='w-5 h-5' src="https://img.icons8.com/fluency/48/star--v1.png" alt="star--v1"/>
                            <span class="text-gray-400">{{round($recomendation['vote_average'],1)}}</span>
                        </div>
                        <a href='/movie/{{$recomendation['id']}}'>
                        <h1 class="font-bold text-xl">{{Str::limit($recomendation['title'],60)}}</h1>
                        </a>
                        {{-- <div class="flex flex-wrap gap-2">
                            @foreach($recomendation['genre_ids'] as $genreId)
                            <a href='/genre/{{$genreId}}/list-movie-tv' class="rounded-lg border-1 p-1 text-xs"><h1>{{$genres[$genreId]['name']}}</h1></a> 
                            @endforeach
                        </div> --}}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
    </div>
</x-layout>