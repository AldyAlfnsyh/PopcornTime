<nav class="bg-slate-800 p-5">
    

        @if(Str::contains(request()->path(), 'list-movie-tv') || Str::contains(request()->path(),'upcoming-movie') || Str::contains(request()->path(),'on-the-air-tv'))
        <div class="mx-auto container flex gap-2 flex-col md:flex-row md:w-full md:justify-between">
            <div class="flex flex-row gap-2 md:justify-between  md:w-full">
                <a href="/" class=" p-1 flex items-center border-3 border-yellow-500 rounded-lg">
                    <h1 class="text-yellow-500 text-xl font-bold">PopcornTime</h1>
                </a>    
            
                <form action="{{ route('list-movie-tv') }}" method="GET" class="flex items-center w-full">
                        <div class="bg-slate-100 p-0 rounded-lg w-full "><input class='w-full px-4 py-2 rounded-lg focus:outline-none bg-white' type="search" name="query" placeholder="search..."></div>
                </form>
            </div>
            <form method="GET" class="text-white flex items-center gap-4 justify-end">
                {{-- Sort Field --}}
                <div >
                    {{-- <label for="sort_field" class="mr-2">Sort by:</label> --}}
                    <select name="sort_field" id="sort_field" class="no-arrow p-2 border rounded bg-slate-800" onchange="this.form.submit()">
                        <option value="none" {{ request('sort_field') == 'none' ? 'selected' : '' }}>Sort by</option>
                        <option value="popular" {{ request('sort_field') == 'popular' ? 'selected' : '' }}>Popularity</option>
                        <option value="release_date" {{ request('sort_field') == 'release_date' ? 'selected' : '' }}>Release Date</option>
                        <option value="top_rated" {{ request('sort_field') == 'top_rated' ? 'selected' : '' }}>Rating</option>
                    </select>
                </div>

                {{-- Order Direction --}}
                <div>
                    {{-- <label for="order" class="mr-2">Order:</label> --}}
                    <select name="order" id="order" class="no-arrow  p-2 border rounded bg-slate-800" onchange="this.form.submit()">
                        <option value="none" {{ request('order') == 'none' ? 'selected' : '' }}>Order by</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>

                @if(Str::contains(request()->path(), 'list-movie-tv'))
                <div>
                    {{-- <label for="type" class="mr-2">Type:</label> --}}
                    <select name="type" id="type" class="no-arrow  p-2 border rounded  bg-slate-800" onchange="this.form.submit()">
                        <option value="none" {{ request('type') == 'none' ? 'selected' : '' }}>Type</option>
                        <option value="movie" {{ request('type') == 'movie' ? 'selected' : '' }}>Movie</option>
                        <option value="tv" {{ request('type') == 'tv' ? 'selected' : '' }}>Tv</option>
                    </select>
                </div>
                @endif
            </form>
        </div>
        @else
        <div class="mx-auto container flex gap-2 flex-row">

            <a href="/" class=" p-1 flex items-center border-3 border-yellow-500 rounded-lg">
                <h1 class="text-yellow-500 text-xl font-bold">PopcornTime</h1>
            </a>

            <div class="w-full flex justify-end">
                <a class="flex flex-col justify-center" href="/list-movie-tv">
                    <h1 class="text-white hover:text-yellow-500 text-xl font-bold">List Movie & TV</h1>
                </a>
            </div>
        </div>
        @endif
  </nav>
  