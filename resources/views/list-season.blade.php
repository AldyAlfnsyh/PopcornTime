<x-layout>
    <div class="flex h-30 -mx-26 w-screen bg-slate-700">
        <img class='rounded-xl h-auto w-auto p-2 ml-26' src='{{$poster_path ? asset($img_path.$poster_path) : '/storage/poster-not-found.png'}}'/>
        <div class="flex flex-col justify-end p-2  ">
            <h1 class="text-2xl  text-slate-300">{{$title}}</h1>
            <h1 class="text-4xl text-white font-bold">{{$type_group}} List</h1>
            {{-- {{dd($link_back)}} --}}
            <a href="{{$link_back}}" class=" text-white text-1xl">Back to Main</a>
        </div>
    </div>
    <div class="mt-10">
        <div class="flex flex-col">
            @foreach($datas as $data)
            {{-- {{dd($data)}} --}}
                <div class="h-60 w-full flex gap-5">
                    @if( isset($data['poster_path'])  || isset($data['still_path'] ))
                    <img class='rounded-xl h-auto w-auto p-2' src='{{asset($img_path. ($data['poster_path'] ?? $data['still_path']))}}'/>
                    @else
                    <img class='rounded-xl h-auto w-auto p-2' src='{{isset($data['episode_number']) ? asset('/storage/still-image-not-found.jpg') : asset('/storage/poster-not-found.png')}}'/>
                    @endif
                    <div class="flex flex-col justify-start py-2">
                        @if(isset($data['episode_number']))
                            <a href="/tv/{{$tv_id}}/season/{{$data['season_number']}}/episode/{{$data['episode_number']}}">
                        @else
                            <a href="/tv/{{$tv_id}}/season/{{$data['season_number']}}">
                        @endif
                        <h1 class="text-2xl text-white">{{$data['name']}}</h1>
                        </a>
                        @if(isset($data['episode_count']))
                        <h2 class="text-1xl text-slate-300">{{$data['air_date']}} • {{$data['episode_count']}} episode</h2>
                        @else
                        <h2 class="text-1xl text-slate-300">{{$data['air_date']}} • {{round($data['vote_average'],1)}} ⭐</h2>
                        @endif
                        <p class=" text-slate-300 pt-5">{{$data['overview']}}</p>
                    </div>
                </div>
                <hr class="border-t my-5 border-slate-400 w-full">
            @endforeach 
        </div>
    </div>

</x-layout>