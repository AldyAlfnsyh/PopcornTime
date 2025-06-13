<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoviesController extends Controller
{
    
    public function list_movie(Request $request){
        $page = $request->query('page',1);
        $response = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/discover/movie', [
            'api_key' => env('TMDB_API_KEY'),  // Ambil dari .env
            'language' => 'en-US',
            // 'sort_by' => 'popularity.desc',
            'page' => $page,
            'include_adult' => false,
            'include_video' => false,
        ]);

        $genresResponse = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/movie/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);

        

        if($response->successful() && $genresResponse->successful()){
            $movies = $response->json()['results'];
            $poster_path='https://image.tmdb.org/t/p/w500';
            $genres = collect($genresResponse->json()['genres'])->keyBy('id');
            $totalPages = 500;
            return view('list-movie',compact('movies','poster_path','genres','page','totalPages'));
        }

        return abort(500, 'Gagal ambil data dari TMDb');
    }

    public function movie_detail($id){
        $response_movie = Http::withOptions([
            'verify' =>false,
        ])-> get("https://api.themoviedb.org/3/movie/{$id}",[
            'api_key' => env('TMDB_API_KEY'),
        ]);
        $response_videos = Http::withOptions([
            'verify' => false,
        ])->get("https://api.themoviedb.org/3/movie/{$id}/videos",[
            'api_key' => env('TMDB_API_KEY'),
        ]);
        $genresResponse = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/movie/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);
        $creditsResponse = Http::withOptions([
            'verify' => false,
        ])->get("https://api.themoviedb.org/3/movie/{$id}/credits",[
            'api_key' => env('TMDB_API_KEY'),
        ]);
        $recomendationsResponse = Http::withOptions([
            'verify' => false,
        ])->get("https://api.themoviedb.org/3/movie/{$id}/recommendations",[
            'api_key' => env('TMDB_API_KEY'),
        ]);
        
        // dd($creditsResponse->json());
        if($response_movie->successful() && $response_videos->successful() && $genresResponse->successful()){
            $movie = $response_movie->json();
            $videos = $response_videos->json()['results'];
            $trailers = collect($videos)->filter(function ($videos){
                return $videos['type'] == 'Trailer';
            })->values();
            $data_cast = $creditsResponse->json()['cast'];
            $data_crew = $creditsResponse->json()['crew'];
            // dd($movie);
            $list_cast = collect($data_cast)->sortByDesc('popularity')->take(12);
            $directors = collect($data_crew)->filter(function ($data_crew){
                return $data_crew["job"]  == "Director";
            })->sortByDesc('popularity')->take(5);
            $writers = collect($data_crew)->filter(function ($data_crew){
                return $data_crew["department"]  == "Writing";
            })->sortByDesc('popularity')->take(5);
            $recomendations_data = $recomendationsResponse->json()['results'];
            $recomendations = collect($recomendations_data)->sortByDesc('popularity')->take(5);
            
            $img_path='https://image.tmdb.org/t/p/w500';
            $genres = collect($genresResponse->json()['genres'])->keyBy('id');
            
            return view('detail_movie',
                [
                    'title' => $movie['original_title'],
                    'release_date' => $movie['release_date'],
                    'origin_country' => $movie['origin_country'],
                    'runtime' => $movie['runtime'],
                    'vote_average' => $movie['vote_average'],
                    'vote_count' => $movie['vote_count'],
                    'popularity' => $movie['popularity'],
                    'poster_path' => $movie['poster_path'],
                    'overview' => $movie['overview'],
                    'directors' => $directors,
                    'writers' => $writers,
                    'casts'=> $list_cast,
                    'recomendations' => $recomendations,
                    'trailers' => $trailers,
                    'genres' => $genres,
                    'movie_genres' => $movie['genres'],
                    'production_companies' => $movie['production_companies'],
                    'spoken_languages' => $movie['spoken_languages'],
                    'img_path' => $img_path
                ]);

        }
    }

    public function list_upcoming_movie(Request $request){
        $page = $request->query('page',1);
        
        $sort_field = $request->query('sort_field');
        $order = $request->query('order');
        
        $response_upcoming_movies = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/movie/upcoming', [
            'api_key' => env('TMDB_API_KEY'),  // Ambil dari .env
            'language' => 'en-US',
            'sort_by' => $sort_field == 'top_rated' ? 'vote_average.' . $order : 'popularity.' . $order,
            'page' => $page,
            'include_adult' => false,
            // 'include_video' => false,
        ]);
        

        $responseMovieGenres = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/movie/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);


        
        if($response_upcoming_movies->successful() && $responseMovieGenres->successful() ){
            $movie = $response_upcoming_movies->json()['results'];        
            $movie = collect($movie)->map(function($item){
                $item['type'] = 'movie';
                return $item;
            });
                    
            if($sort_field){
                $sort_type = $sort_field == 'top_rated' ? 'vote_average' : 'popularity';
                
                if($order == 'asc'){
                    $movie = $movie->sortBy($sort_type)->values();
                }else{
                    $movie = $movie->sortByDesc($sort_type)->values();
                }
            }else{
                $movie = $movie->values();
            }

            
            
        
            $movieGenres = $responseMovieGenres->json()['genres'];

            // Gabungkan dan keyBy ID
            $genres = collect($movieGenres)->keyBy('id');

            // dd($genre->json()['genres']);
            // dd($response->json()['results']);
        
            $poster_path='https://image.tmdb.org/t/p/w500';
            $totalPages = $response_upcoming_movies->json()['total_pages'] ?? 1;
            $movies = $movie;
            return view('list-movie-tv',compact('movies','poster_path','genres','page','totalPages'));
        }

        return abort(500, 'Gagal ambil data dari TMDb');
    }

}
