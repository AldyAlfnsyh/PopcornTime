<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TvController extends Controller
{
    public function tv_detail($id){
        $response_tv = Http::withOptions([
            'verify' =>false,
        ])-> get("https://api.themoviedb.org/3/tv/{$id}",[
            'api_key' => env('TMDB_API_KEY'),
        ]);
        $response_videos = Http::withOptions([
            'verify' => false,
        ])->get("https://api.themoviedb.org/3/tv/{$id}/videos",[
            'api_key' => env('TMDB_API_KEY'),
        ]);
        $genresResponse = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/tv/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);
        $recomendationsResponse = Http::withOptions([
            'verify' => false,
        ])->get("https://api.themoviedb.org/3/tv/{$id}/recommendations",[
            'api_key' => env('TMDB_API_KEY'),
        ]);
        
        // dd($response_tv->json());
        if($response_tv->successful() && $response_videos->successful() && $genresResponse->successful()){
            $tv = $response_tv->json();
            $videos = $response_videos->json()['results'];
            $trailers = collect($videos)->filter(function ($videos){
                return $videos['type'] == 'Trailer';
            })->values();
            $recomendations_data = $recomendationsResponse->json()['results'];
            $recomendations = collect($recomendations_data)->sortByDesc('popularity')->take(5);
            // dd($recomendations);
            $img_path='https://image.tmdb.org/t/p/w500';
            $genres = collect($genresResponse->json()['genres'])->keyBy('id');
            return view('detail_tv',compact('tv','trailers','img_path','genres','recomendations'));

        }
    }

    public function tv_list_season($id){
        $response_tv = Http::withOptions([
            'verify' => false,
        ])->get("https://api.themoviedb.org/3/tv/{$id}",[
            'api_key' => env('TMDB_API_KEY'),
        ]);

        if($response_tv->successful()){
            $tv = $response_tv->json();
            // dd($tv);
            $img_path='https://image.tmdb.org/t/p/w500';
            return view('list-season', [
                'poster_path' => $tv['poster_path'],
                'title' => $tv['original_name'],
                'type_group' => 'Season',
                'link_back' => "/tv/{$tv['id']}",
                'datas' => $tv['seasons'],
                'img_path' => $img_path, 
                'tv_id' => $id
            ]);
        }

        
    }

    public function tv_list_episode($tv_id, $number_season){
        $response_season = Http::withOptions([
            'verify' => false,
        ])->get("https://api.themoviedb.org/3/tv/{$tv_id}/season/{$number_season}",[
            'api_key' => env('TMDB_API_KEY'),
        ]);

        $season = $response_season->json();
        $img_path='https://image.tmdb.org/t/p/w500';
        // dd($season);


        return view('list-season', [
            'poster_path' => $season['poster_path'],
            'title' => $season['name'],
            'type_group' => 'Episode',
            'link_back' => "/tv/{$tv_id}/season",
            'datas' => $season['episodes'],
            'img_path' => $img_path, 
            'tv_id' => $tv_id
        ]);

    }

    public function tv_episode_detail($tv_id, $number_season, $number_episode){
        $response_episode_detail = Http::withOptions([
            'verify' => false
        ])->get("https://api.themoviedb.org/3/tv/{$tv_id}/season/{$number_season}/episode/{$number_episode}",[
            'api_key' => env("TMDB_API_KEY")
        ]);

        $episode = $response_episode_detail->json();
        // dd($episode);

        $directors = collect($episode['crew'])->filter(function($crew){
            return $crew['job'] == 'Director';
        })->values()->take(5);

        $writers = collect($episode['crew'])->filter(function ($crew){
            return $crew['job'] == 'Writer';
        })->values()->take(5);

        $casts = collect($episode['guest_stars'])->sortByDesc('popularity')->values()->take(12); 
        // dd($casts);
        $img_path='https://image.tmdb.org/t/p/w500';

        return view('detail_movie',[
            'title' => $episode['name'],
            'release_date' => $episode['air_date'],
            'type' => 'episode_detail',
            // origin country NULL
            'runtime' => $episode['runtime'],
            'vote_average' => $episode['vote_average'],
            'vote_count' => $episode['vote_count'],
            // popularity NULL
            'still_path' => $episode['still_path'],
            // genres NULL
            'overview' => $episode['overview'],
            'directors' => $directors,
            'writers' => $writers,
            'casts'=> $casts,
            'img_path' => $img_path,
            // recomendations NULL
            // trailer NULL
            // production_companies NULL
            // spoken_languages NULL

        ]);
    }

    public function list_on_the_air_tv(Request $request){
        $page = $request->query('page',1);
        
        $sort_field = $request->query('sort_field');
        $order = $request->query('order');
        
        $response_ota_tvs = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/tv/on_the_air', [
            'api_key' => env('TMDB_API_KEY'),  // Ambil dari .env
            'language' => 'en-US',
            'sort_by' => $sort_field == 'top_rated' ? 'vote_average.' . $order : 'popularity.' . $order,
            'page' => $page,
            'include_adult' => false,
            // 'include_video' => false,
        ]);
        

        $responseTvGenres = Http::withOptions([
            'verify' => false,
        ])->get('https://api.themoviedb.org/3/genre/tv/list',[
            'api_key' => env('TMDB_API_KEY'),
        ]);


        
        if($response_ota_tvs->successful() && $responseTvGenres->successful() ){
            $tv = $response_ota_tvs->json()['results'];        
            $tv = collect($tv)->map(function($item){
                $item['type'] = 'tv';
                if (!isset($item['release_date']) && isset($item['first_air_date'])) {
                    $item['release_date'] = $item['first_air_date'];
                }
                return $item;
            });
                    
            if($sort_field){
                $sort_type = $sort_field == 'top_rated' ? 'vote_average' : 'popularity';
                
                if($order == 'asc'){
                    $tv = $tv->sortBy($sort_type)->values();
                }else{
                    $tv = $tv->sortByDesc($sort_type)->values();
                }
            }else{
                $tv = $tv->values();
            }
            
            
        
            $tvGenres = $responseTvGenres->json()['genres'];

            // Gabungkan dan keyBy ID
            $genres = collect($tvGenres)->keyBy('id');

            // dd($genre->json()['genres']);
            // dd($response->json()['results']);
        
            $poster_path='https://image.tmdb.org/t/p/w500';
            $totalPages = $response_ota_tvs->json()['total_pages'] ?? 1;
            $movies = $tv;
            return view('list-movie-tv',compact('movies','poster_path','genres','page','totalPages'));
        }

        return abort(500, 'Gagal ambil data dari TMDb');
    }
}
