<?php

namespace App\Http\Controllers\Web;

use Google2FA;
use App\Models\Podcast;
use App\Models\BandAlbum;
use App\Models\SheetMusic;
use App\Models\MusicDetail;
use App\Models\VideoDetail;
use App\Models\Instrumental;
use App\Models\VideoCategory;
use App\Models\SongAuthors;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->isCurrentUserAdmin())
            abort(404);

        $videos = VideoDetail::count();
        $video_categories = VideoCategory::count();
        $musics = MusicDetail::count();
        $band_albums = BandAlbum::count();
        $instrumentals = Instrumental::count();
        $podcasts = Podcast::count();
        $sheet_musics = SheetMusic::count();


        // set initial dates
        $date = new \DateTime('today + 1 day');
        $date_end = $date->format('Y-m-d');
        $date = new \DateTime('today - 1 month');
        $date = new \DateTime($date->format('Y-m-d').' - 1 day');
        $date_start = $date->format('Y-m-d');

        $subscriber_metrics = SongAuthors::from('songs_authors as sa')
                    ->select('sa.author_id')
                    ->leftJoin('music_details', 'music_details.id', '=', 'sa.music_detail_id')
                    ->whereIn('music_details.id', function($query) use ($date_start, $date_end)
                    {
                        $query->select('song_id')
                              ->from('subscriber_metrics')
                              ->whereBetween('time_streamed', [$date_start, $date_end])
                              ->whereRaw('song_id = sa.music_detail_id');
                    })
                    ->groupBy('sa.author_id')
                    ->get();

        return view('admin-dashboard', [
            'videos' => $videos,
            'video_categories' => $video_categories,
            'musics' => $musics,
            'band_albums' => $band_albums,
            'instrumentals' => $instrumentals,
            'podcasts' => $podcasts,
            'sheet_musics' => $sheet_musics,
            'subscriber_metrics' => count($subscriber_metrics)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
          /*
          Google2FA::setQRCodeBackend('svg');
          $inlineUrl = Google2FA::getQRCodeInline(
              'All Things Michael Mclean',
              'admin@songwriter.com',
              'JA5F4SKHZIFA25GW'
          );
          */
        //
        //Google2FA::setQRCodeBackend('svg');
        return Google2FA::generateSecretKey();
        //$google2fa = app('pragmarx.google2fa');
        //return $google2fa->generateSecretKey();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
