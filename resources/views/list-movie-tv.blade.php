<x-layout>

    <div class="mt-5 flex flex-wrap items-center gap-3 justify-center">
        @foreach ($movies as $movie)
    
        <div class="rounded-lg shadow-xl bg-gray-700 w-64 h-128">
            <a href="/{{ $movie['type'] == 'tv' ? 'tv' : 'movie' }}/{{ $movie['id'] }}">
                <div class="h-3/4">
                    <img class='rounded-t-lg h-full w-full' src='{{$movie['poster_path'] ? asset($poster_path.$movie['poster_path']) : asset('/storage/poster-not-found.png')}}'/>
                </div>
            </a>
            <div class="p-2 text-white flex flex-col gap-1">
                <div class="flex justify-between">
                    {{-- <div class="flex gap-2"> --}}
                        <div class="flex gap-1">
                            <img class='w-5 h-5' src="https://img.icons8.com/fluency/48/star--v1.png" alt="star--v1"/>
                            <span class="text-gray-400 ">{{round($movie['vote_average'],1)}}</span>
                        </div>
                        <div class=" rounded-lg border border-yellow-500 text-yellow-500">
                            <h1 class=" px-1 align-middle">{{$movie['type']}}</h1>
                        </div>
                    {{-- </div> --}}
                </div>
                <h1 class="text-gray-400 font-bold" >{{$movie['release_date']}}</h1>
                
                <a href="/{{ $movie['type'] == 'tv' ? 'tv' : 'movie' }}/{{ $movie['id'] }}">
                <h1 class="font-bold text-xl shadow-xl">{{Str::limit($movie['title'] ?? $movie['name'], 40)}}</h1>
                </a>
                {{-- <div class="flex flex-wrap gap-2">
                    @foreach(array_slice($movie['genre_ids'],0,2) as $genreId)
                    <a href='/genre/{{$genreId}}/list-movie-tv' class="rounded-lg border-1 p-1 text-xs"><h1>{{$genres[$genreId]['name']}}</h1></a> 
                    @endforeach
                </div> --}}
            </div>
        </div>
    @endforeach
    </div>
    @if(isset($page))
    <div class="flex justify-center gap-4 mt-6">
        @if ($page > 1)
            <a href="{{request()->fullUrlWithQuery(['page' => $page - 1])}}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                Previous
            </a>
        @endif
    
        <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded">Page {{ $page }}</span>
    
        @if ($page < $totalPages)
            <a href="{{request()->fullUrlWithQuery(['page' => $page + 1])}}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                Next
            </a>
        @endif
    </div>
    @endif
    {{-- <div class="mt-4">
    {{ $movies->links() }}
    </div> --}}
    
    
</x-layout>

