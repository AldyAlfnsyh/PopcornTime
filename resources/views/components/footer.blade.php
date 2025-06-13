<footer class="p-10  bg-slate-800 mt-10 py-10 ">
    <div class="flex justify-between">

            <div class="flex flex-col gap-1 ml-20 ">
                <a href="/" class="p-1 flex items-center border-3 border-yellow-500 rounded-lg h-fit w-fit ">
                    <h1 class="text-yellow-500 text-2xl font-bold">PopcornTime</h1>
                </a>

                <div class="flex flex-col text-white">
                    <a href="/" class="hover:underline hover:text-yellow-500 w-fit">Home</a>
                    <a href="/list-movie-tv" class="hover:underline hover:text-yellow-500 w-fit">Browse</a>
                </div>

                <div class=" text-white">
                    © {{ date('Y') }} PopcornTime. Built by <a href="https://github.com/AldyAlfnsyh" class="underline hover:text-yellow-500" target="_blank">Aldy Alfiansyah</a>.
                </div>
            </div>


            <div class="mr-20 flex gap-2 text-white">
                <a href="https://www.themoviedb.org/">
                    <img src="/storage/TMDB.svg" class="h-auto w-24">
                </a>
                <p class="w-50">This product uses the TMDB API but is not endorsed or certified by TMDB.</p>
            </div>
    </div>
</footer>