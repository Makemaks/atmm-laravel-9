<?php

namespace App\Http\Controllers\Web;

use App\Models\Author;
use App\Models\MusicDetail;
use Illuminate\Http\Request;
use App\Http\Requests\Author\Store;
use App\Http\Controllers\Controller;

class AuthorController extends Controller
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

              $author = $this->paginate($request, [
                  'items' => (new Author())->with('musicDetails'),
                  's_fields' => ['name'],
                  'sortBy' => $request->sort_field,
                  'sortOrder' => $request->sort_order,
              ]);
              $response = [
                   'pagination' => [
                       'total' => $author->total(),
                       'per_page' => $author->perPage(),
                       'current_page' => $author->currentPage(),
                       'last_page' => $author->lastPage(),
                       'from' => $author->firstItem(),
                       'to' => $author->lastItem()
                   ],
                   'data' => $author,
                   'aws_url' => \Config::get('filesystems.aws_url'),
               ];
               return response()->json($response);

            } else {
                $authors = Author::orderBy('name', 'asc')->paginate(10);
                return view('authors.admin.Index', [
                    'authors' => $this->paginate($request, [
                        'items' => new Author(),
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

            return view('authors.admin.Create');
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

            $artist = Author::create($request->all());

            return back()->with(['success' => 'Author details added successfully.']);
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

            $author = Author::findOrfail($id);
            return view('authors.admin.Edit', [
                'author' => $author
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

            $author = Author::findOrFail($id);
            $author->update($request->all());

            return back()->with(['success' => 'Author details updated successfully.']);
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

            $author = Author::findOrFail($id);
            $author->delete();

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

            //$author = Author::findOrfail($id);
            $author = Author::findOrfail($request->id);
            if( trim(strtolower($request->task)) == 'remove') {
                $all_songs = MusicDetail::whereHas('authors', function ($query) use ($author) {
                                $query->where('author_id', $author->id);
                            })
                            ->orderBy('title', 'asc')
                            ->get();
            } else {
                $all_songs = MusicDetail::whereDoesntHave('authors', function ($query) use ($author) {
                                $query->where('author_id', $author->id);
                            })
                            ->orderBy('title', 'asc')
                            ->get();
            }

            $return_data = array();
            $return_data['author_info'] = $author;
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
    public function processMultipleSongsToAuthor(Request $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                return response()->json('error', 404);

            if( trim(strtolower($request->task)) == 'remove') {
                foreach ($request->songs as $key => $id) {
                    $song = MusicDetail::find($id);
                    $song->authors()->detach($request->author_id);
                }
            } else {
                $author = Author::find($request->author_id);
                foreach ($request->songs as $key => $id) {
                    $author->musicDetails()->attach($id);
                }
            }

            return response()->json('success', 200);
        } catch (\Throwable $th) {
            throw $th;
        }

    }

}
