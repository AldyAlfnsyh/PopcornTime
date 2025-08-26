<x-layout>
    <div class="mx-auto container mt-10 text-white flex flex-col gap-5">
        <div class="flex flex-col-reverse md:flex-col">
        <div class="flex md:justify-between md:flex-row flex-col ">
            <div class="flex flex-col">
                <h1 class="font-bold md:text-5xl text-3xl">{{$title}}</h1>
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
                    <h1  class="font-bold ">Vote Average</h1>
                    <div class="flex gap-1 items-center h-full">
                        <img class='md:w-8 md:h-8 w-6 h-6' src="https://img.icons8.com/fluency/48/star--v1.png" alt="star--v1"/>    
                        <h1 class="text-gray-400"><span class="text-gray-400 md:text-2xl text-xl font-bold">{{round($vote_average,1)}}/</span>{{$vote_count}}</h1>
                    </div>
                </div>
                @if(isset($popularity))
                <div class="flex flex-col items-start ">
                    <h1 class="font-bold">Popularity</h1>
                    <div class="flex gap-1 items-center h-full">
                        <img class='md:w-8 md:h-8 w-6 h-6' src="https://img.icons8.com/?size=100&id=85933&format=png&color=4AC82F"/>
                        <h1 class="text-gray-400 font-bold md:text-2xl text-xl">{{$popularity}}</h1>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="flex md:h-120 md:gap-1 md:justify-center ">
            @if(isset($type) && $type=='episode_detail')
                <img class=' rounded-xl h-auto w-full md:h-full md:w-auto' src='{{isset($still_path) ? asset($img_path.$still_path) : asset("storage/still-image-not-found.jpg")}}'/>
            @else
                <img class='hidden md:block rounded-xl h-auto w-auto' src='{{isset($poster_path) ? asset($img_path.$poster_path) : asset("storage/poster-not-found.png")}}'/>
            @endif


            @if(isset($trailers) && count($trailers))
            <iframe width="100%" height="100%" class="h-80 md:h-auto rounded-xl" src="https://www.youtube.com/embed/{{ $trailers[0]['key'] }}" frameborder="0" allowfullscreen>
            </iframe>
            @endif
        </div>
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
                @if(isset($directors)!= null)
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
                @if(isset($writers) != null)
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
                    <div class="flex flex-col w-45 items-center justify-center text-center">
                        <img class=" h-45 w-full rounded-full object-cover" src="{{isset($cast['profile_path']) ? asset($img_path.$cast['profile_path']) : asset('storage/profile-not-found.jpg')}}" >
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
        <div class=" mt-10">
            <h1 class="font-bold text-3xl text-white">Recomendation</h1>


            {{-- Using Swiper JS --}}
            <div class="swiper mt-5">
                <div class="swiper-wrapper items-stretch">
                    @foreach ($recomendations as $recomendation)
                    <div class="swiper-slide">
                        <div class="rounded-lg shadow-xl bg-gray-700 flex-1 h-auto">
                            <a href='/movie/{{$recomendation['id']}}'>
                                <div class="h-3/4">

                                    <img class='rounded-t-lg h-full w-full' src='{{isset($recomendation['poster_path']) ? asset($img_path.$recomendation['poster_path']) : asset("storage/poster-not-found.png")}}'/>
                                    
                                </div>
                            </a>
                            <div class="p-2 text-white flex flex-col gap-1">
                                <div class="flex gap-1 items-center">
                                    <img class='w-5 h-5' src="https://img.icons8.com/fluency/48/star--v1.png" alt="star--v1"/>
                                    <span class="text-gray-400">{{round($recomendation['vote_average'],1)}}</span>
                                </div>
                                <a href='/movie/{{$recomendation['id']}}'>
                                <h1 class="font-bold text-xl line-clamp-2 min-h-[3.5rem]">{{Str::limit($recomendation['title'],60)}}</h1>
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
        @endif
        
    </div>
</x-layout>