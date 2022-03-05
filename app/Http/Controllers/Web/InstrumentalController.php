<?php

namespace App\Http\Controllers\Web;

use App\Models\MusicDetail;
use App\Models\Instrumental;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Instrumental\Store;
use App\Http\Requests\Instrumental\Update;

class InstrumentalController extends Controller
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
            $sortByDate = $request->input('sort_by_date');

            $items = Instrumental::orderBy('created_at', $request->has('sort_by_date') ? $sortByDate : 'desc');

            if ($request->has('search')) {
                $searchFields = explode(',', $request->get('s_fields'));
                foreach ($searchFields as $key => $field) {
                    if (!$key) {
                        $items = $items->whereHas('musicDetail', function ($query) use ($request, $field)
                        {
                            $query->where($field, 'like', '%'. $request->get('search') .'%');
                        });
                    } else {
                        $items = $items->orWhereHas('musicDetail', function ($query) use ($request, $field)
                        {
                            $query->where($field, 'like', '%'. $request->get('search') .'%');
                        });
                    }
                }
            }

            if ($request->has('page')) {
                if ($user->isAdmin) {
                    return View('instrumentals.admin.index', [
                        'instrumentals' => $this->paginate($request, [
                            'items' => new Instrumental(),
                            's_fields' => ['title'],
                            'sortBy' => 'title',
                            'sortOrder' => 'ASC'
                        ])
                    ]);
                } else {
                    if (!empty($user)) {
                        return View('instrumentals.index');
                    } else {
                        return View('instrumentals.index_not_logged_in');
                    }
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getInstrumentals(Request $request)
    {
        try {
            /* $search = $request->input('search');
            $items = Instrumental::when($request->has('search'), function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%');
            })
            ->where('is_public', 1)
            ->paginate(30);

            return $items; */

            /*
            $request['aws_url'] = \Config::get('filesystems.aws_url');
            return $this->paginate($request, [
                'items' => ((new Instrumental())->where('is_public', 1)),
                's_fields' => ['title'],
                'sortBy' => 'title',
                'sortOrder' => 'ASC',
            ]);
            */

            $return_data['aws_url'] = \Config::get('filesystems.aws_url');
            $return_data['orig'] = $this->paginate($request, [
                'items' => ((new Instrumental())->where('is_public', 1)),
                's_fields' => ['title'],
                'sortBy' => 'title',
                'sortOrder' => 'ASC',
            ]);
            return $return_data;
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

            $music_detail = MusicDetail::get();
            return view('instrumentals.admin.create', ['music_details' => $music_detail]);
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
            $instrumental = Instrumental::create($request->except(['image', 'high_key_audio','low_key_audio','high_key_video','low_key_video']));

            if ($request->input('image')) {
                $instrumental->saveImage($request->input('image'));
            }

            if ($request->input('high_key_audio')) {
                $instrumental->saveHighKeyAudio($request->input('high_key_audio'));
            }

            if ($request->input('low_key_audio')) {
                $instrumental->saveLowKeyAudio($request->input('low_key_audio'));
            }

            if ($request->input('high_key_video')) {
                $instrumental->saveHighKeyVideo($request->input('high_key_video'));
            }

            if ($request->input('low_key_video')) {
                $instrumental->saveLowKeyVideo($request->input('low_key_video'));
            }
            if ($request['is_public'] == 1) {
              $this->sendMobileNotification('Sing Along', $request->title);
            }
            return back()->with(['success' => 'Instrumental details added successfully.']);
        } catch (\Exception $exception) {
            throw $exception;
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

            $instrumental = Instrumental::findOrfail($id);
            $music_details = MusicDetail::get();
            return view('instrumentals.admin.edit', [
                'instrumental' => $instrumental,
                'music_details' => $music_details
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
            $instrumental = Instrumental::findOrFail($id);
            $instrumental->update($request->except(['image', 'high_key_audio','low_key_audio','high_key_video','low_key_video']));

            if ($request->input('image')) {
                $instrumental->removeImage();
                $instrumental->saveImage($request->input('image'));
            }

            if ($request->input('high_key_video')) {
                $instrumental->removeHighKeyVideo();
                $instrumental->saveHighKeyVideo($request->input('high_key_video'));
            }

            if ($request->input('low_key_video')) {
                $instrumental->removeLowKeyVideo();
                $instrumental->saveLowKeyVideo($request->input('low_key_video'));
            }

            if ($request->input('high_key_audio')) {
                $instrumental->removeHighKeyAudio();
                $instrumental->saveHighKeyAudio($request->input('high_key_audio'));
            }

            if ($request->input('low_key_audio')) {
                $instrumental->removeLowKeyAudio();
                $instrumental->saveLowKeyAudio($request->input('low_key_audio'));
            }

            return back()->with(['success' => 'Instrumental details updated successfully.']);
        } catch (\Exception $exception) {
            throw $exception;
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

            $instrumental = Instrumental::findOrFail($id);
            $instrumental->delete();

            Storage::deleteDirectory('instrumentals/' . $id);

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
