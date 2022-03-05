<?php

namespace App\Http\Controllers\Web;

use App\Models\BandAlbum;
use App\Models\MusicDetail;
//use App\MusicDetailsBandAlbum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BandAlbum\Store;
use App\Http\Requests\BandAlbum\Update;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\BandAlbum\AlbumIDRequest;
use App\Http\Requests\BandAlbum\addMusicToBandAlbum;
use App\Http\Requests\BandAlbum\removeMusicToBandAlbum;
use App\Jobs\SaveImages;
use DateTime;
use DB;

class BandAlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            if($request->ajax()){

              $band_albums = $this->paginate($request, [
                  'items' => (new BandAlbum())->with('songLists'),
                  's_fields' => ['album'],
                  'sortBy' => $request->sort_field,
                  'sortOrder' => $request->sort_order,
              ]);
              $response = [
                   'pagination' => [
                       'total' => $band_albums->total(),
                       'per_page' => $band_albums->perPage(),
                       'current_page' => $band_albums->currentPage(),
                       'last_page' => $band_albums->lastPage(),
                       'from' => $band_albums->firstItem(),
                       'to' => $band_albums->lastItem()
                   ],
                   'data' => $band_albums,
                   'aws_url' => \Config::get('filesystems.aws_url'),
               ];
               return response()->json($response);

            } else {

              return view('albums.admin.index', [
                  'band_albums' => $this->paginate($request, [
                      'items' => (new BandAlbum())->select('*'),
                      's_fields' => ['album'],
                      'sortBy' => 'album',
                      'sortOrder' => 'ASC'
                  ])
              ]);

            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function getAlbums (Request $request) {
        try {
            $sortBy = $request->input('sortBy');
            $items = BandAlbum::when($request->has('sortBy'), function ($query) use ($sortBy) {
                if ($sortBy == 1) {
                    $query->orderBy('release_date', 'asc');
                    $query->orderBy('album', 'asc');
                } else if ($sortBy == 2) {
                    $query->orderBy('release_date', 'desc');
                    $query->orderBy('album', 'asc');
                } else {
                    $query->orderBy('album', 'asc');
                }


            })
            ->where('is_public', 1);

            if ($request->has('search')) {
                $searchFields = explode(',', $request->get('s_fields'));
                foreach ($searchFields as $key => $field) {
                    if (!$key) {
                        $items = $items->where($field, 'like', '%'. $request->get('search') .'%');
                    } else {
                        $items = $items->orwhere($field, 'like', '%'. $request->get('search') .'%');
                    }
                }
            }

            return $items->paginate(48);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function getAlbumSongs(AlbumIDRequest $request)
    {
        try {
            $items = BandAlbum::with(['musicDetails' => function($query) {
                $query->orderBy('music_details_band_albums.track_sequence', 'ASC');
                //$query->orderBy('title', 'asc');
            }])
            ->where('is_public', 1)
            ->find($request->album_id);

            $items['aws_url'] = \Config::get('filesystems.aws_url');

            return $items;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            return view('albums.admin.create');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                return response()->json('error', 404);

            /* $releaseDate = DateTime::createFromFormat('m/d/Y', $request['release_date']);
            $request['release_date'] = $releaseDate->format('Y-m-d'); */
            $request['is_public'] = $request['is_public'] == 'yes' ? 1 : 0;
            $band_album = BandAlbum::create($request->except(['image', 'liner']));

            if ($request->input('image')) {
                $band_album->saveImage($request->input('image'));
            }
            if ($request->input('liner')) {
                $band_album->saveLiner($request->input('liner'));
            }
            if ($request['is_public'] == 1) {
              $this->sendMobileNotification('Album', $request->album);
            }
            return response()->json('success', 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            $band_album = BandAlbum::findOrFail($id);
            $all_musics = MusicDetail::whereDoesntHave('bandAlbums', function ($query) use ($band_album) {
                $query->where('band_album_id', $band_album->id);
            })
            ->orderBy('title', 'ASC')
            ->get();

            /*
            return view('albums.admin.show', [
                'band_album' => $band_album,
                'all_musics' => $all_musics,
                'musics' => $band_album->musicDetails
            ]);
            */

            //'musics' => MusicDetailsBandAlbum::getMusics($id)->get()
            return view('albums.admin.newshow', [
                'band_album' => $band_album,
                'all_musics' => $all_musics,
                'musics' => $this->paginate($request, [
                    'items' => $band_album->MusicDetails(false),
                    's_fields' => ['title'],
                    'sortBy' => 'track_sequence',
                    'sortOrder' => 'ASC'
                ])
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addMusicToBandAlbum(addMusicToBandAlbum $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                return response()->json('error', 404);

            $band_album = BandAlbum::find($request->band_album_id);
            $band_album->musicDetails()->attach($request->music_detail_id, [
                'track_sequence' => $request->track_sequence
            ]);

            return response()->json('success', 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addMultipleMusicToBandAlbum(Request $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                return response()->json('error', 404);

            $band_album = BandAlbum::find($request->band_album_id);
            foreach ($request->musics as $key => $id) {
                $band_album->musicDetails()->attach($id, [
                        'track_sequence' => $key
                    ]);
            }
            return response()->json('success', 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateMusicTrackSequence(addMusicToBandAlbum $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                return response()->json('error', 404);

            $band_album = BandAlbum::find($request->band_album_id);
            $band_album->musicDetails()->updateExistingPivot($request->music_detail_id, [
                'track_sequence' => $request->track_sequence
            ]);

            return response()->json('success', 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function removeMusicToBandAlbum(removeMusicToBandAlbum $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                return response()->json('error', 404);

            $band_album = BandAlbum::find($request->band_album_id);
            $band_album->musicDetails()->detach($request->music_detail_id);

            return response()->json('success', 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            $band_album = BandAlbum::findOrFail($id);
            /* if ($band_album->release_date) {
                $band_album->release_date = date('m/d/Y', strtotime($band_album->release_date));
            } */
            return view('albums.admin.edit', [
                'band_album' => $band_album
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            /* $releaseDate = DateTime::createFromFormat('m/d/Y', $request['release_date']);
            $request['release_date'] = $releaseDate->format('Y-m-d'); */
            $request['is_public'] = $request->has('is_public') ? 1 : 0;
            $band_album = BandAlbum::findOrFail($id);
            $band_album->update($request->all());

            if ($request->input('image')) {
                $band_album->removeImage();
                $band_album->saveImage($request->input('image'));
            }
            if ($request->input('liner')) {
                $band_album->removeLiner();
                $band_album->saveLiner($request->input('liner'));
            }

            return back()->with(['success' => 'Details updated successfully.']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            $band_album = BandAlbum::findOrFail($id);
            $band_album->delete();

            Storage::deleteDirectory('albums/'. $id);

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getAlbumMusics(Request $request)
    {
        try {
            if ($request->has('id')) {
                $music_details = MusicDetail::where('band_album_id', $request->id)->with('bandAlbum')->get();
                $band_album = BandAlbum::where('id', $request->id)->first();
                return View('albums.album_details', [
                    'album_details' => $music_details,
                    'band_album' => $band_album,
                    'musics' => count($music_details)
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function searchAlbumMusic(Request $request)
    {
        try {
            if ($request->has('search')) {
                $music_details = MusicDetail::where('band_album_id', $request->id)
                                            ->where('title', $request->search)
                                            ->with('bandAlbum')->get();
                $band_album = BandAlbum::where('id', $request->id)->first();
                return View('albums.album_details', [
                    'album_details' => count($music_details) ? $music_details : [['bandAlbum' => $band_album]],
                    'musics' => count($music_details)
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSongList(Request $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                return response()->json('error', 404);

            $band_album = BandAlbum::findOrfail($request->id);
            if( trim(strtolower($request->task)) == 'sort') {
                $all_songs = $band_album->songLists;
            } elseif( trim(strtolower($request->task)) == 'remove') {
                $all_songs = MusicDetail::whereHas('bandAlbums', function ($query) use ($band_album) {
                                $query->where('band_album_id', $band_album->id);
                            })
                            ->orderBy('title', 'asc')
                            ->get();
            } else {
                $all_songs = MusicDetail::whereDoesntHave('bandAlbums', function ($query) use ($band_album) {
                                $query->where('band_album_id', $band_album->id);
                            })
                            ->orderBy('title', 'asc')
                            ->get();
            }

            $return_data = array();
            $return_data['band_album_info'] = $band_album;
            $return_data['all_songs'] = $all_songs;
            return $return_data;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processMultipleSongsToAlbum(Request $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                return response()->json('error', 404);

            if( trim(strtolower($request->task)) == 'sort') {
              $band_album = BandAlbum::find($request->album_id);
              foreach ($request->songs as $key => $song) {
                $band_album->musicDetails()->updateExistingPivot($song['id'], [
                    'track_sequence' => $key + 1
                ]);
              }
            } elseif( trim(strtolower($request->task)) == 'remove') {
                foreach ($request->songs as $key => $id) {
                    $song = MusicDetail::find($id);
                    $song->bandAlbums()->detach($request->album_id);
                }
            } else {
                $band_album = BandAlbum::with('songLists')->find($request->album_id);
                $band_album_song_count = count($band_album->songLists);
                foreach ($request->songs as $key => $id) {
                    $track_sequence = 1 + $key + $band_album_song_count;
                    $band_album->musicDetails()->attach($id, ['track_sequence' => $track_sequence]);
                }
            }

            return response()->json('success', 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
