<x-layout>
    <div class="container mx-auto mt-10 text-white flex flex-col gap-5">
        <div class="flex flex-col-reverse md:flex-col">
        <div class="flex md:justify-between md:flex-row flex-col">
            <div class="flex flex-col">
                <h1 class="font-bold md:text-5xl text-3xl">{{$tv['original_name']}}</h1>
                <div class="flex gap-1">
                    <h1>{{$tv['first_air_date']}} •</h1>
                    @if(count($tv['origin_country']))
                    <h1>{{$tv['origin_country'][0]}} •</h1>
                    @endif
                    <h1>{{$tv['number_of_seasons']}} seasons • {{$tv['number_of_episodes']}} episodes</h1>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="flex flex-col items-start">
                    <h1 class="font-bold">Vote Average</h1>
                    <div class="flex gap-1 items-center h-full">
                        <img class='md:w-8 md:h-8 w-6 h-6' src="https://img.icons8.com/fluency/48/star--v1.png" alt="star--v1"/>    
                        <h1 class="text-gray-400"><span class="text-gray-400 md:text-2xl text-xl font-bold">{{round($tv['vote_average'],1)}}/</span>{{$tv['vote_count']}}</h1>
                    </div>
                </div>
                <div class="flex flex-col items-start ">
                    <h1 class="font-bold">Popularity</h1>
                    <div class="flex gap-1 items-center h-full">
                        <img class='md:w-8 md:h-8 w-6 h-6' src="https://img.icons8.com/?size=100&id=85933&format=png&color=4AC82F"/>
                        <span class="text-gray-400 font-bold md:text-2xl text-xl">{{$tv['popularity']}}</span>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="flex md:h-120 md:gap-1 md:justify-center">
            <img class='hidden md:block rounded-xl h-auto w-auto' src='{{isset($tv['poster_path']) ? asset($img_path.$tv['poster_path']) : asset('storage/poster-not-found.png')}}'/>

            @if(count($trailers))
            <iframe width="100%" height="100%" class="rounded-xl h-80 md:h-auto" src="https://www.youtube.com/embed/{{ $trailers[0]['key'] }}" frameborder="0" allowfullscreen>
            </iframe>
            @endif
        </div>
        </div>

        <div class="flex gap-2">
            {{-- {{dd($tv['genres'] )}} --}}
            @foreach($tv['genres'] as $genre)
            <a href="/genre/{{$genres[$genre['id']]['id']}}/list-movie-tv" class="rounded-xl border-1 px-3 text-lg"><h1>{{$genres[$genre['id']]['name']}}</h1></a> 
            @endforeach
        </div>
        <div class="flex flex-col gap-2">
            <p>{{$tv['overview']}}</p>
            <div class="flex gap-2">
                <h1 class="font-bold">Production Companies</h1>
                @foreach ($tv['production_companies'] as $production_companie)
                    <p class="text-yellow-500">{{$production_companie['name']}} •</p>
                @endforeach
            </div>
            <div class="flex gap-2">
                <h1 class="font-bold">Language</h1>
                @foreach ($tv['spoken_languages'] as $spoken_language)
                <p class="text-yellow-500">{{$spoken_language['english_name']}} •</p>
                @endforeach
            </div>
        </div>
        <a href="/tv/{{$tv['id']}}/season">
            <div class="font-bold text-3xl flex items-center">
                <h1> | <span>{{$tv['number_of_seasons']}}</span> Seasons ></h1>
            </div>
        </a>

        @if(count($recomendations)>0)
        <div class="mt-10">
            <h1 class="font-bold text-3xl text-white">Recomendation</h1>

            {{-- Using Swiper JS --}}
            <div class="swiper mt-5">
                <div class="swiper-wrapper items-stretch">
                    @foreach ($recomendations as $recomendation)
                    <div class="swiper-slide">
                        <div class="rounded-lg shadow-xl bg-gray-700 flex-1 h-auto">
                    <a href='/tv/{{$recomendation['id']}}'>
                    <img class='rounded-t-lg h-3/4 w-full' src='{{isset($recomendation['poster_path']) ? asset($img_path.$recomendation['poster_path']) : asset("storage/poster-not-found.png")}}'/>
                    </a>
                    <div class="p-2 text-white flex flex-col gap-1">
                        <div class="flex gap-1 items-center">
                            <img class='w-5 h-5' src="https://img.icons8.com/fluency/48/star--v1.png" alt="star--v1"/>
                            <span class="text-gray-400">{{round($recomendation['vote_average'],1)}}</span>
                        </div>
                        <a href='/tv/{{$recomendation['id']}}'>
                        <h1 class="font-bold text-xl min-h-[3.5rem] line-clamp-2">{{Str::limit($recomendation['original_name'],60)}}</h1>
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