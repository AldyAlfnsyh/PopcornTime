<footer class="  bg-slate-800 mt-10 py-10 ">
    <div class="mx-auto container flex md:flex-row md:justify-between gap-5 md:gap-0 flex-col-reverse items-center md:items-start">

            <div class="flex flex-col gap-1  items-center md:items-start">
                <a href="/" class="p-1 flex items-center border-3 border-yellow-500 rounded-lg h-fit w-fit ">
                    <h1 class="text-yellow-500 text-2xl font-bold">PopcornTime</h1>
                </a>

                <div class="flex md:flex-col md:gap-0 gap-5 flex-row text-white">
                    <a href="/" class="hover:underline hover:text-yellow-500 w-fit">Home</a>
                    <a href="/list-movie-tv" class="hover:underline hover:text-yellow-500 w-fit">Browse</a>
                </div>

                <div class=" text-white">
                    © {{ date('Y') }} PopcornTime. Built by <a href="https://github.com/AldyAlfnsyh" class="underline hover:text-yellow-500" target="_blank">Aldy Alfiansyah</a>.
                </div>
            </div>


            <div class=" flex gap-2 text-white items-center md:items-start">
                <a href="https://www.themoviedb.org/">
                    <img src="/storage/TMDB.svg" class="h-auto w-24">
                </a>
                <p class="w-50">This product uses the TMDB API but is not endorsed or certified by TMDB.</p>
            </div>
    </div>
</footer>