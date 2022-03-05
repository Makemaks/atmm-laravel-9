<?php

namespace App\Http\Controllers\Web;

use App\Models\Podcast;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Podcast\Store;
use App\Http\Requests\Podcast\Update;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PodcastController extends Controller
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

            $items = new Podcast;

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

            if ($request->has('page')) {
                if ($user->isAdmin) {
                    return view('podcasts.admin.index', [
                        'podcasts' => $this->paginate($request, [
                            'items' => new Podcast(),
                            's_fields' => ['title'],
                            'sortBy' => 'date',
                            'sortOrder' => 'DESC'
                            //'sortBy' => 'title',
                            //'sortOrder' => 'ASC'
                        ])
                    ]);
                } else {
                    if (!empty($user)) {
                        return view('podcasts.index');
                    } else {
                        return view('podcasts.index_not_logged_in');
                    }

                }
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getPodcasts(Request $request)
    {
        try {
            $search = $request->input('search');
            $items = Podcast::when($request->has('search'), function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%');
            })
            ->where('type', $request->input('type'))
            ->where('is_public', 1);

            if($request->input('sortBy') === 'title') {
                $items = $items->orderByRaw('LOWER(title) ASC');
            } else {
                $items = $items->orderBy($request->input('sortBy'), $request->input('sortOrder'));
            }

            $items = $items->paginate(1000)
            ->withPath(url('get-podcasts?type=' . $request->input('type')));

            $custom = collect([ 'aws_url' => \Config::get('filesystems.aws_url'), 'base_url' => url('/') ] );
            $items = $custom->merge($items);

            return $items;
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

            return view('podcasts.admin.create');
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

            $request['show_in_explore'] = $request['show_in_explore'] == 'yes' ? 1 : 0;
            $request['is_public'] = $request['is_public'] == 'yes' ? 1 : 0;
            //$podcast = Podcast::create($request->except('audio'));
            $podcast = Podcast::create($request->except(['image', 'audio']));

            if ($request->input('image')) {
                $podcast->saveImage($request->input('image'));
            }

            if ($request->has('audio')) {
                $podcast->saveAudio($request->input('audio'));
            }

            if ($request['is_public'] == 1) {
              $this->sendMobileNotification(ucfirst($request->type), $request->title);
            }
            return back()->with(['success' => 'Podcast details added successfully.']);
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

            $podcast = Podcast::findOrfail($id);
            return view('podcasts.admin.edit', [
                'podcast' => $podcast
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

            $request['show_in_explore'] = $request->has('show_in_explore') ? 1 : 0;
            $request['is_public'] = $request->has('is_public') ? 1 : 0;
            $podcast = Podcast::findOrFail($id);
            $podcast->update($request->except('audio'));

            if ($request->input('audio')) {
                $podcast->removeAudio();
                $podcast->saveAudio($request->input('audio'));
            }

            return back()->with(['success' => 'Podcast details updated successfully.']);
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

            $podcast = Podcast::findOrFail($id);
            $podcast->delete();

            Storage::deleteDirectory('podcasts/'.$id);

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
