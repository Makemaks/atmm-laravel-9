<?php

namespace App\Http\Controllers\Web;

use App\Models\Artist;
use App\Models\MusicDetail;
use Illuminate\Http\Request;
use App\Http\Requests\Artist\Store;
use App\Http\Controllers\Controller;

class ArtistController extends Controller
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

              $artists = $this->paginate($request, [
                  'items' => (new Artist())->with('musicDetails'),
                  's_fields' => ['name'],
                  'sortBy' => $request->sort_field,
                  'sortOrder' => $request->sort_order,
              ]);
              $response = [
                   'pagination' => [
                       'total' => $artists->total(),
                       'per_page' => $artists->perPage(),
                       'current_page' => $artists->currentPage(),
                       'last_page' => $artists->lastPage(),
                       'from' => $artists->firstItem(),
                       'to' => $artists->lastItem()
                   ],
                   'data' => $artists,
                   'aws_url' => \Config::get('filesystems.aws_url'),
               ];
               return response()->json($response);

            } else {

              return view('artists.admin.Index', [
                  'artists' => $this->paginate($request, [
                      'items' => new Artist(),
                      's_fields' => ['name'],
                      'sortBy' => 'name',
                      'sortOrder' => 'ASC'
                  ])
              ]);

            }
        } catch (\Throwable $th) {
            throw $th;
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

            return view('artists.admin.Create');
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
                abort(404);

            $artist = Artist::create($request->all());

            return back()->with(['success' => 'Artist details added successfully.']);
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
            if(!$this->isCurrentUserAdmin())
                abort(404);

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

            $artist = Artist::findOrfail($id);
            return view('artists.admin.Edit', [
                'artist' => $artist
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
    public function update(Store $request, $id)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            $artist = Artist::findOrFail($id);
            $artist->update($request->all());

            return back()->with(['success' => 'Artist details updated successfully.']);
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

            $artist = Artist::findOrFail($id);
            $artist->delete();

            return back();
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
    //public function getSongList($id)
    public function getSongList(Request $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                return response()->json('error', 404);

            $artist = Artist::findOrfail($request->id);
            if( trim(strtolower($request->task)) == 'remove') {
                $all_songs = MusicDetail::whereHas('artists', function ($query) use ($artist) {
                                $query->where('artist_id', $artist->id);
                            })
                            ->orderBy('title', 'asc')
                            ->get();
            } else {
                $all_songs = MusicDetail::whereDoesntHave('artists', function ($query) use ($artist) {
                                $query->where('artist_id', $artist->id);
                            })
                            ->orderBy('title', 'asc')
                            ->get();
            }

            $return_data = array();
            $return_data['artist_info'] = $artist;
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
    public function processMultipleSongsToArtist(Request $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                return response()->json('error', 404);

            if( trim(strtolower($request->task)) == 'remove') {
                foreach ($request->songs as $key => $id) {
                    $song = MusicDetail::find($id);
                    $song->artists()->detach($request->artist_id);
                }
            } else {
                $artist = Artist::find($request->artist_id);
                foreach ($request->songs as $key => $id) {
                    $artist->musicDetails()->attach($id);
                }
            }

            return response()->json('success', 200);
        } catch (\Throwable $th) {
            throw $th;
        }

    }

}
