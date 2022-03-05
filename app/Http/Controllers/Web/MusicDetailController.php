<?php

namespace App\Http\Controllers\Web;

use App\Models\Author;
use App\Models\Artist;
use App\Models\BandAlbum;
use App\Models\MusicDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\MusicDetail\Store;
use App\Transformers\MusicDetailTransformer;
use App\Http\Requests\MusicDetail\AddArtistToSong;
use App\Http\Requests\MusicDetail\AddAuthorToSong;

class MusicDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $request['page'] = $request['page'] = $request->page ? $request->page : 1;
            $user = Session::get('user');

            $items = new MusicDetail;

            /* if ($request->has('search')) {
                $searchFields = explode(',', $request->get('s_fields'));
                foreach ($searchFields as $key => $field) {
                    if (!$key) {
                        $items = $items->whereHas('bandAlbum', function ($query) use ($request, $field)
                        {
                            $query->where($field, 'like', '%'. $request->get('search') .'%');
                        });
                    } else {
                        $items = $items->orWhereHas('bandAlbum', function ($query) use ($request, $field)
                        {
                            $query->where($field, 'like', '%'. $request->get('search') .'%');
                        });
                    }
                }
            } */

            if ($request->has('sort')) {

                switch ($request->sort) {
                    case '1':
                        # code...
                        break;
                    case '2':
                        $items = $items->oldest();
                        break;
                    case '3':
                        $items = $items->latest();
                        break;
                    default:
                        $items = $items->orderBy('title');
                        break;
                }
            }

            if ($request->has('page')) {
                if ($user->isAdmin) {
                    return view('musics.admin.index', [
                        'musics' => $this->paginate($request, [
                            'items' => new MusicDetail(),
                            's_fields' => ['title'],
                            'sortBy' => 'title',
                            'sortOrder' => 'ASC'
                        ])
                    ]);
                } else {
                    if (!empty($user)) {
                        return View('musics.index');
                    } else {
                        return View('musics.index_not_logged_in');
                    }

                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getSongs (Request $request) {
        try {
            $sortBy = $request->input('sortBy');
            $items = MusicDetail::when($request->has('sortBy'), function ($query) use ($sortBy) {
                if ($sortBy == 1) {
                    $query->orderBy('created_at', 'desc');
                } else if ($sortBy == 2) {
                    $query->orderBy('created_at', 'asc');
                } else {
                    $query->orderBy('title', 'asc');
                }
            })
            ->where('is_public', 1)
            ->with('bandAlbums');

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

            $response = [
                 'paginate_data' => $items->paginate(1000),
                 'aws_url' => \Config::get('filesystems.aws_url'),
             ];
             return response()->json($response);

            //return $items->paginate(5);
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
            if($this->isCurrentUserAdmin()) {
                $band_albums = BandAlbum::get();
                return view('musics.admin.create', ['band_albums' => $band_albums]);
            } else {
                abort(404);
            }
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
            if($this->isCurrentUserAdmin()) {
                $request['show_in_explore'] = $request['show_in_explore'] == 'yes' ? 1 : 0;
                $request['is_public'] = $request['is_public'] == 'yes' ? 1 : 0;
                $music_detail = MusicDetail::create($request->except('audio'));
                if ($request->has('image')) {
                    $music_detail->saveImage($request->input('image'));
                }
                if ($request->has('audio')) {
                    $music_detail->saveAudio($request->input('audio'));
                }
                if ($request['is_public'] == 1) {
                  $this->sendMobileNotification('Music', $request->title);
                }
                return response()->json('success', 200);
            } else {
                return response()->json('error', 404);
            }
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
    public function show($id)
    {
        try {
            if($this->isCurrentUserAdmin()) {
                $music = MusicDetail::findOrfail($id);
                $all_artists = Artist::whereDoesntHave('musicDetails', function ($query) use ($music) {
                    $query->where('music_detail_id', $music->id);
                })->get();
                $all_authors = Author::whereDoesntHave('musicDetails', function ($query) use ($music) {
                    $query->where('music_detail_id', $music->id);
                })->get();
                $artists = $music->artists;
                $authors = $music->authors;
                return view('musics.admin.show', [
                    'music' => $music,
                    'artists' => $artists,
                    'all_artists' => $all_artists,
                    'authors' => $authors,
                    'all_authors' => $all_authors
                ]);
            }  else {
                 abort(404);
            }
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
    public function getMusicAuthorArtistList(Request $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                return response()->json('error', 404);

            $music = MusicDetail::findOrfail($request->id);
            if( trim(strtolower($request->task)) == 'remove') {
                if( strtolower($request->objlist) == 'artist') {
                    $all_authors_artists = Artist::whereHas('musicDetails', function ($query) use ($music) {
                        $query->where('music_detail_id', $music->id);
                    })->get();
                } else {
                    $all_authors_artists = Author::whereHas('musicDetails', function ($query) use ($music) {
                        $query->where('music_detail_id', $music->id);
                    })->get();
                }
            } else {
                if( strtolower($request->objlist) == 'artist') {
                    $all_authors_artists = Artist::whereDoesntHave('musicDetails', function ($query) use ($music) {
                        $query->where('music_detail_id', $music->id);
                    })->get();
                } else {
                  $all_authors_artists = Author::whereDoesntHave('musicDetails', function ($query) use ($music) {
                      $query->where('music_detail_id', $music->id);
                  })->get();
                }
            }

            $return_data = array();
            $return_data['music_info'] = $music;
            $return_data['all_authors_artists'] = $all_authors_artists;
            return $return_data;

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
            if($this->isCurrentUserAdmin()) {
                $music = MusicDetail::findOrfail($id);
                $band_albums = BandAlbum::get();
                return view('musics.admin.edit', [
                    'music' => $music,
                    'band_albums' => $band_albums
                ]);
            }  else {
                 abort(404);
            }
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
    public function update(Request $request, $id)
    {
        try {
            if($this->isCurrentUserAdmin()) {
                $request['show_in_explore'] = $request->has('show_in_explore') ? 1 : 0;
                $request['is_public'] = $request->has('is_public') ? 1 : 0;
                $music_detail = MusicDetail::findOrfail($id);
                $music_detail->update($request->except(['image', 'audio']));

                if ($request->input('image')) {
                    $music_detail->removeImage();
                    $music_detail->saveImage($request->input('image'));
                }
                if ($request->input('audio')) {
                    $music_detail->removeAudio();
                    $music_detail->saveAudio($request->input('audio'));
                }
                return back()->with(['success' => 'Music details updated successfully.']);
            }  else {
                 abort(404);
            }
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
            if($this->isCurrentUserAdmin()) {
                $music_detail = MusicDetail::findOrFail($id);
                $music_detail->delete();

                // delete s3 files
                Storage::deleteDirectory('audios/' . $id);

                return back();
            }  else {
                 abort(404);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addArtistToSong(AddArtistToSong $request)
    {
        try {
            if($this->isCurrentUserAdmin()) {
                $song = MusicDetail::find($request->music_detail_id);
                $song->artists()->attach($request->artist_id);

                return response()->json('success', 200);
            }  else {
                 return response()->json('error', 404);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addAuthorToSong(AddAuthorToSong $request)
    {
        try {
            if($this->isCurrentUserAdmin()) {
                $song = MusicDetail::find($request->music_detail_id);
                $song->authors()->attach($request->author_id);
                return response()->json('success', 200);
            }  else {
                 return response()->json('error', 404);
            }
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
    public function processMultipleAuthorsArtistsToSong(Request $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                return response()->json('error', 404);

            if( trim(strtolower($request->task)) == 'remove') {
                if( strtolower($request->objlist) == 'artist') {
                    $song = MusicDetail::find($request->music_detail_id);
                    foreach ($request->authors_artists as $key => $id) {
                        $song->artists()->detach($id);
                    }
                } else {
                    $song = MusicDetail::find($request->music_detail_id);
                    foreach ($request->authors_artists as $key => $id) {
                        $song->authors()->detach($id);
                    }
                }
            } else {
                if( strtolower($request->objlist) == 'artist') {
                    $song = MusicDetail::find($request->music_detail_id);
                    foreach ($request->authors_artists as $key => $id) {
                        $song->artists()->attach($id);
                    }
                } else {
                    $song = MusicDetail::find($request->music_detail_id);
                    foreach ($request->authors_artists as $key => $id) {
                        $song->authors()->attach($id);
                    }
                }
            }
            return response()->json('success', 200);
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function removeArtistToSong(AddArtistToSong $request)
    {
        try {
            if($this->isCurrentUserAdmin()) {
                $song = MusicDetail::find($request->music_detail_id);
                $song->artists()->detach($request->artist_id);

                return response()->json('success', 200);
            }  else {
                 return response()->json('error', 404);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function removeAuthorToSong(AddAuthorToSong $request)
    {
        try {
            if($this->isCurrentUserAdmin()) {
                $song = MusicDetail::find($request->music_detail_id);
                $song->authors()->detach($request->author_id);

                return response()->json('success', 200);
            }  else {
                 return response()->json('error', 404);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
