<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;


class MovieTvController extends Controller
{

    public function index(){

        $trending_response = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/trending/all/week',[
            'api_key' => env('TMDB_API_KEY')
        ]);


        $upcoming_movies_response = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/movie/upcoming', [
            'api_key' => env('TMDB_API_KEY'),  // Ambil dari .env
            'language' => 'en-US',
            // 'sort_by' => 'popularity.desc',
            // 'page' => $page,
            'include_adult' => false,
            'include_video' => false,
        ]);

        $ota_tvs_response = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/tv/on_the_air', [
            'api_key' => env('TMDB_API_KEY'),  // Ambil dari .env
            'language' => 'en-US',
            // 'sort_by' => 'popularity.desc',
            // 'page' => $page,
            'include_adult' => false,
            'include_video' => false,
        ]);

        $genres_response = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/movie/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);
        if($trending_response->successful() && $upcoming_movies_response->successful() && $ota_tvs_response->successful()  && $genres_response->successful()){
            $trendings = collect($trending_response->json()['results'])->take(10);
            $upcoming_movies = collect($upcoming_movies_response->json()['results'])->take(5);
            $ota_tvs = collect($ota_tvs_response->json()['results'])->take(5);
            $img_path='https://image.tmdb.org/t/p/w500';
            $genres = collect($genres_response->json()['genres'])->keyBy('id');
            return view('index',compact('upcoming_movies','ota_tvs','trendings','img_path','genres'));
        }
    }

    public function list_movie_tv(Request $request){
        $page = $request->query('page',1);
        
        $sort_field = $request->query('sort_field');
        $order = $request->query('order');

        if($request->filled('query')){
            $endpoint = 'search';
            $params =[
                'language' => 'en-US',
                'page' => $page,
                'include_adult' => false,
                'query' => $request->input('query'),
            ];
        }else{
            $endpoint = 'discover';
            $params =[
                'language' => 'en-US',
                'sort_by' => $sort_field == 'top_rated' ? 'vote_average.' . $order : 'popularity.' . $order,
                'page' => $page,
                'include_adult' => false,
            ];
        }

        $responseMovie = Http::withOptions([
            'verify' => false,
        ])->get("https://api.themoviedb.org/3/{$endpoint}/movie", array_merge([
            'api_key' => env('TMDB_API_KEY'),
        ], $params));

        $responseTvShow = Http::withOptions([
            'verify' => false,
        ])->get("https://api.themoviedb.org/3/{$endpoint}/tv",array_merge([
            'api_key' => env('TMDB_API_KEY'),
        ], $params));

        // dd($responseMovie->json());

        $responseMovieGenres = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/movie/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);

        $responseTvGenres = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/tv/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);
        
        if($responseMovie->successful() && $responseTvShow->successful() && $responseMovieGenres->successful() && $responseTvGenres->successful()  ){
            $movie = $responseMovie->json()['results'];        
            $movie = collect($movie)->map(function($item){
                $item['type'] = 'movie';
                return $item;
            });
        
            $tvShow = $responseTvShow->json()['results'];
            $tvShow = collect($tvShow)->map(function($item){
                $item['type'] = 'tv';
                if (!isset($item['release_date']) && isset($item['first_air_date'])) {
                    $item['release_date'] = $item['first_air_date'];
                }
                return $item;
            });
            
            $combined = $movie->merge($tvShow);

            // dd($combined);
            
            if($sort_field){
                $sort_type = match ($sort_field) {
                                'top_rated' => 'vote_average',
                                'release_date' => 'release_date',
                                default => 'popularity',
                            };
                
                if($order == 'asc'){
                    $combined = $combined->sortBy($sort_type)->values();
                }else{
                    $combined = $combined->sortByDesc($sort_type)->values();
                }
            }else{
                $combined = $combined->values();
            }
            $typeFilter = $request->query('type');
            
            if($typeFilter == 'movie'){
                $combined = $combined->where('type', 'movie')->values();
            }else if($typeFilter == 'tv'){
                $combined = $combined->where('type', 'tv')->values();
            }
            
            // dd($combined);
        
            $movieGenres = $responseMovieGenres->json()['genres'];
            $tvGenres = $responseTvGenres->json()['genres'];

            // Gabungkan dan keyBy ID
            $genres = collect(array_merge($movieGenres, $tvGenres))->keyBy('id');

            // dd($genre->json()['genres']);
            // dd($response->json()['results']);
        
            $poster_path='https://image.tmdb.org/t/p/w500';
            $totalPagesMovie =$responseMovie->json()['total_pages'] ?? 1;
            $totalPagesTv = $responseTvShow->json()['total_pages'] ?? 1;
            $totalPages = max($totalPagesMovie, $totalPagesTv); 
            $movies = $combined;
            // dd($totalPages);
            return view('list-movie-tv',compact('movies','poster_path','genres','page','totalPages'));
        }

        return abort(500, 'Gagal ambil data dari TMDb');
    }

    public function list_movie_tv_byGenre(Request $request,$id){
        $page = $request->query('page',1);
        
        $sort_field = $request->query('sort_field');
        $order = $request->query('order');
        
        
        $responseMovie = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/discover/movie', [
            'api_key' => env('TMDB_API_KEY'),  // Ambil dari .env
            'language' => 'en-US',
            'sort_by' => $sort_field == 'top_rated' ? 'vote_average.' . $order : 'popularity.' . $order,
            'page' => $page,
            'include_adult' => false,
            'with_genres' => $id
            // 'include_video' => false,
        ]);
        $responseTvShow = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/discover/tv',[
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'en-US',
            'sort_by' => $sort_field == 'top_rated' ? 'vote_average.' . $order : 'popularity.' . $order,
            'page' => $page,
            'include_adult' =>false,
            'with_genres' => $id
        ]);

        $responseMovieGenres = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/movie/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);

        $responseTvGenres = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/tv/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);
        
        if($responseMovie->successful() && $responseTvShow->successful() && $responseMovieGenres->successful() && $responseTvGenres->successful()  ){
            $movie = $responseMovie->json()['results'];        
            $movie = collect($movie)->map(function($item){
                $item['type'] = 'movie';
                return $item;
            });
        
            $tvShow = $responseTvShow->json()['results'];
            $tvShow = collect($tvShow)->map(function($item){
                $item['type'] = 'tv';
                if (!isset($item['release_date']) && isset($item['first_air_date'])) {
                    $item['release_date'] = $item['first_air_date'];
                }
                return $item;
            });
            
            $combined = $movie->merge($tvShow);

            if($sort_field){
                $sort_type = match ($sort_field) {
                                'top_rated' => 'vote_average',
                                'release_date' => 'release_date',
                                default => 'popularity',
                            };
                
                if($order == 'asc'){
                    $combined = $combined->sortBy($sort_type)->values();
                }else{
                    $combined = $combined->sortByDesc($sort_type)->values();
                }
            }else{
                $combined = $combined->values();
            }
            $typeFilter = $request->query('type');
            
            if($typeFilter == 'movie'){
                $combined = $combined->where('type', 'movie')->values();
            }else if($typeFilter == 'tv'){
                $combined = $combined->where('type', 'tv')->values();
            }
            
            // dd($combined);
            $movieGenres = $responseMovieGenres->json()['genres'];
            $tvGenres = $responseTvGenres->json()['genres'];

            // Gabungkan dan keyBy ID
            $genres = collect(array_merge($movieGenres, $tvGenres))->keyBy('id');

            
        
            $poster_path='https://image.tmdb.org/t/p/w500';
            $totalPagesMovie =$responseMovie->json()['total_pages'] ?? 1;
            $totalPagesTv = $responseTvShow->json()['total_pages'] ?? 1;
            $totalPages = max($totalPagesMovie, $totalPagesTv); 
            $movies = $combined;
            // dd($movies);
            return view('list-movie-tv',compact('movies','poster_path','genres','page','totalPages'));
        }

        return abort(500, 'Gagal ambil data dari TMDb');
    }


    public function list_movie_tv_byDirector(Request $request,$id){
        $sort_field = $request->query('sort_field');
        $order = $request->query('order');

        $responseMovieGenres = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/movie/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);

        $responseTvGenres = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/tv/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);
        
        // Step 2: Ambil daftar film yang disutradarai
        $movie_credits = Http::withOptions(['verify' => false])->get("https://api.themoviedb.org/3/person/{$id}/movie_credits", [
        'api_key' => env('TMDB_API_KEY'),
        ]);
        $tv_credits = Http::withOptions(['verify' => false])->get("https://api.themoviedb.org/3/person/{$id}/tv_credits", [
            'api_key' => env('TMDB_API_KEY')
        ]);

        // dd($movie_credits->json());
        $movie_director = collect($movie_credits->json()['crew'])->filter(function($crew){
            return $crew['job'] == 'Director';
        })->values()->map(function ($movie){
            $movie['type']= 'movie';
            return $movie;
        });

        $tv_director = collect($tv_credits->json()['crew'])->filter(function ($crew){
            return $crew['job'] == 'Director';
        })->values()->map(function ($tv){
            $tv['type'] = 'tv';
            if (!isset($tv['release_date']) && isset($tv['first_air_date'])) {
                    $tv['release_date'] = $tv['first_air_date'];
                }
            return $tv;
        });

        if( $responseMovieGenres->successful() && $responseTvGenres->successful()  ){
            
            $combined = $movie_director->merge($tv_director);
     
            if($sort_field){
                $sort_type = match ($sort_field) {
                                'top_rated' => 'vote_average',
                                'release_date' => 'release_date',
                                default => 'popularity',
                            };
                
                if($order == 'asc'){
                    $combined = $combined->sortBy($sort_type)->values();
                }else{
                    $combined = $combined->sortByDesc($sort_type)->values();
                }
            }else{
                $combined = $combined->values();
            }
            $typeFilter = $request->query('type');
            
            if($typeFilter == 'movie'){
                $combined = $combined->where('type', 'movie')->values();
            }else if($typeFilter == 'tv'){
                $combined = $combined->where('type', 'tv')->values();
            }
            
            // dd($combined);
        
            $movieGenres = $responseMovieGenres->json()['genres'];
            $tvGenres = $responseTvGenres->json()['genres'];

            // Gabungkan dan keyBy ID
            $genres = collect(array_merge($movieGenres, $tvGenres))->keyBy('id');
        
            $poster_path='https://image.tmdb.org/t/p/w500';
            $movies = $combined;

            return view('list-movie-tv',compact('movies','poster_path','genres'));
        }

        return abort(500, 'Gagal ambil data dari TMDb');
    }


    public function list_movie_tv_byWriter(Request $request,$id){
        $sort_field = $request->query('sort_field');
        $order = $request->query('order');

        $responseMovieGenres = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/movie/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);

        $responseTvGenres = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/tv/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);

        // Step 2: Ambil daftar film yang disutradarai
        $movie_credits = Http::withOptions(['verify' => false])->get("https://api.themoviedb.org/3/person/{$id}/movie_credits", [
        'api_key' => env('TMDB_API_KEY'),
        ]);
        $tv_credits = Http::withOptions(['verify' => false])->get("https://api.themoviedb.org/3/person/{$id}/tv_credits", [
            'api_key' => env('TMDB_API_KEY')
        ]);

        // dd($movie_credits->json());
        $movie_writer = collect($movie_credits->json()['crew'])->filter(function($crew){
            return $crew['job'] == 'Writer';
        })->values()->map(function ($movie){
            $movie['type']= 'movie';
            return $movie;
        });

        $tv_writer = collect($tv_credits->json()['crew'])->filter(function ($crew){
            return $crew['job'] == 'Writer';
        })->values()->map(function ($tv){
            $tv['type'] = 'tv';
            if (!isset($tv['release_date']) && isset($tv['first_air_date'])) {
                    $tv['release_date'] = $tv['first_air_date'];
                }
            return $tv;
        });

        $combined = $movie_writer->merge($tv_writer);

        if($sort_field){
                $sort_type = match ($sort_field) {
                                'top_rated' => 'vote_average',
                                'release_date' => 'release_date',
                                default => 'popularity',
                            };
                
                if($order == 'asc'){
                    $combined = $combined->sortBy($sort_type)->values();
                }else{
                    $combined = $combined->sortByDesc($sort_type)->values();
                }
        }else{
            $combined = $combined->values();
        }
        $typeFilter = $request->query('type');    
        if($typeFilter == 'movie'){
            $combined = $combined->where('type', 'movie')->values();
        }else if($typeFilter == 'tv'){
            $combined = $combined->where('type', 'tv')->values();
        }
        
        $movieGenres = $responseMovieGenres->json()['genres'];
        $tvGenres = $responseTvGenres->json()['genres'];

        // Gabungkan dan keyBy ID
        $genres = collect(array_merge($movieGenres, $tvGenres))->keyBy('id');
        
        $poster_path='https://image.tmdb.org/t/p/w500';
        // dd($writedMovies);
        $movies = $combined;
        return view('list-movie-tv',compact('movies','poster_path','genres'));
    }

    public function list_movie_tv_byCast(Request $request,$id){
        $sort_field = $request->query('sort_field');
        $order = $request->query('order');

        $responseMovieGenres = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/movie/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);

        $responseTvGenres = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/tv/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);
        
        // Step 2: Ambil daftar film yang disutradarai
        $movie_credits = Http::withOptions(['verify' => false])->get("https://api.themoviedb.org/3/person/{$id}/movie_credits", [
        'api_key' => env('TMDB_API_KEY'),
        ]);
        $tv_credits = Http::withOptions(['verify' => false])->get("https://api.themoviedb.org/3/person/{$id}/tv_credits", [
            'api_key' => env('TMDB_API_KEY')
        ]);

        // dd($movie_credits->json());
        $movie_cast = collect($movie_credits->json()['cast'])->values()->map(function ($movie){
            $movie['type']= 'movie';
            return $movie;
        });

        $tv_cast = collect($tv_credits->json()['cast'])->values()->map(function ($tv){
            $tv['type'] = 'tv';
            if (!isset($tv['release_date']) && isset($tv['first_air_date'])) {
                    $tv['release_date'] = $tv['first_air_date'];
                }
            return $tv;
        });

        if( $responseMovieGenres->successful() && $responseTvGenres->successful()  ){
            
            $combined = $movie_cast->merge($tv_cast);
     
            if($sort_field){
                $sort_type = match ($sort_field) {
                                'top_rated' => 'vote_average',
                                'release_date' => 'release_date',
                                default => 'popularity',
                            };
                
                if($order == 'asc'){
                    $combined = $combined->sortBy($sort_type)->values();
                }else{
                    $combined = $combined->sortByDesc($sort_type)->values();
                }
            }else{
                $combined = $combined->values();
            }
            $typeFilter = $request->query('type');
            
            if($typeFilter == 'movie'){
                $combined = $combined->where('type', 'movie')->values();
            }else if($typeFilter == 'tv'){
                $combined = $combined->where('type', 'tv')->values();
            }
            
            // dd($combined);
        
            $movieGenres = $responseMovieGenres->json()['genres'];
            $tvGenres = $responseTvGenres->json()['genres'];

            // Gabungkan dan keyBy ID
            $genres = collect(array_merge($movieGenres, $tvGenres))->keyBy('id');
        
            $poster_path='https://image.tmdb.org/t/p/w500';
            $movies = $combined;

            return view('list-movie-tv',compact('movies','poster_path','genres'));
        }

        return abort(500, 'Gagal ambil data dari TMDb');
    }
}
