<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardResource;
use App\Http\Resources\SubjectResource;
use App\Http\Resources\UploadResource;
use App\Subject;
use App\Upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    //




    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {



        return [
            'top_subjects' => DashboardResource::collection(Subject::orderBy('total_uploads', 'desc')
                ->join('uploads', 'subjects.id', '=', 'uploads.subject_id')
                ->select(DB::raw('count(*) as total_uploads, subjects.*'))
                ->groupBy('subjects.id')
                ->take(10)
                ->get())->take(10),
            'playlist' => $this->getPlaylistCollection(),
            'links' => [ //meta data example
                'self' => 'dash board data',
            ],
        ];
    }


    private function getPlaylistCollection()
    {

        /*

        1. def an array playlist[] to hold playlist
        2. make a collection of 3 playlist separately
        3. push into playlist array


        Repeat for 3 playlists: most viewed, continue watching



        */

        //def playlist t

        $playlist = array();

        $currentUser = Auth::user()->id;


        //recently added uploads -> take 100
        $recently_added = [
            'id' => 1,
            'title' => 'Recently Added',
            'tracks' => UploadResource::collection(Upload::orderBy('id', 'desc')->take(10)->get()),

        ];


        //most viewed collections -> take 20
        $most_viewed = [
            'id' => 2,
            'title' => 'Most Popular',
            'tracks' => UploadResource::collection(Upload::orderBy('total_views', 'desc')
                ->join('views', 'uploads.id', '=', 'views.upload_id')
                ->select(DB::raw('count(*) as total_views, uploads.*'))
                ->groupBy('uploads.id')
                ->take(10)
                ->get())
        ];


        // continue watching collection -> take 20
        $continue_watching = [
            'id'=> 3,
            'title'=>  'Continue Watching',
            'tracks'=>  UploadResource::collection(Upload::with(['views', 'subtitle'])
                ->join('playback_statistics', 'uploads.id', '=', 'playback_statistics.upload_id')
                ->select(DB::raw('uploads.*, playback_statistics.total_playback'))
                ->take(20)
                ->where('playback_statistics.user_id', '=', $currentUser)
                ->orderBy('playback_statistics.updated_at', 'desc')
                ->get()),
        ];


        /**
         *
         * Subjects
         *
         */



//        $subjects_collection = [
//            'id' => 2,
//            'title' => 'Most Popular',
//            'tracks' => SubjectResource::collection(Upload::orderBy('total_uploads', 'desc')
//                ->join('uploads', 'subjects.id', '=', 'uploads.subject_id')
//                ->select(DB::raw('count(*) as total_uploads, subjects.*'))
//                ->groupBy('uploads.id')
//                ->take(10)
//                ->get())
//        ];



//        push collections
        array_push($playlist, $recently_added, $continue_watching, $most_viewed);


        return $playlist;
    }




}
