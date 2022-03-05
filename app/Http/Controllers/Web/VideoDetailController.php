<?php

namespace App\Http\Controllers\Web;

use App\Models\BandAlbum;
use App\Models\VideoDetail;
//use App\Models\VideoCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\VideoDetail\Store;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\VideoDetail\Update;
use App\Jobs\UploadVideoJob;
use DB;

class VideoDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $user_data = Session::get('user');
            if ($user_data->isAdmin) {

                $items = new VideoDetail;
                $request['page'] = $request->page ? $request->page : 1;

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

                if ($request->page) {
                    /* $items = $items->paginate(10); */
                    return View('videos.admin.index', [
                        'videos' => $this->paginate($request, [
                            'items' => new VideoDetail(),
                            's_fields' => ['title'],
                            'sortBy' => 'title',
                            'sortOrder' => 'ASC'
                        ])
                    ]);
                } else {
                    return View('videos.admin.index', ['videos' => $items]);
                }

            } else {
                if (!empty($user_data)) {
                    /*$memory = VideoDetail::whereHas('videoCategory', function ($query)
                                            {
                                                $query->where('description', 'LIKE', '%MEMORY%');
                                            })->with('videoCategory')->latest('created_at');
                    $songwriter_sunday_school = VideoDetail::whereHas('videoCategory', function ($query)
                                            {
                                                $query->where('description', 'LIKE', '%SONGWRITER SUNDAY SCHOOL%');
                                            })->with('videoCategory')->latest('created_at');
                    $fireside_friday = VideoDetail::whereHas('videoCategory', function ($query)
                                            {
                                                $query->where('description', 'LIKE', '%FIRESIDE FRIDAY%');
                                            })->with('videoCategory')->latest('created_at');

                    $request['page'] = $request->page ? $request->page : 1;

                    if ($request->has('search')) {
                        $searchFields = explode(',', $request->get('s_fields'));
                        foreach ($searchFields as $key => $field) {
                            if (!$key) {
                                $memory = $memory->where($field, 'like', '%'. $request->get('search') .'%');
                                $songwriter_sunday_school = $songwriter_sunday_school->where($field, 'like', '%'. $request->get('search') .'%');
                                $fireside_friday = $fireside_friday->where($field, 'like', '%'. $request->get('search') .'%');
                            } else {
                                $memory = $memory->orwhere($field, 'like', '%'. $request->get('search') .'%');
                                $songwriter_sunday_school = $songwriter_sunday_school->orwhere($field, 'like', '%'. $request->get('search') .'%');
                                $fireside_friday = $fireside_friday->orwhere($field, 'like', '%'. $request->get('search') .'%');
                            }
                        }
                    }*/

                    /*if ($request->has('page')) {

                        return View('videos.index', [
                            'memory' => $memory->paginate(3),
                            'fireside_friday' => $fireside_friday->paginate(3),
                            'songwriter_sinday_school' => $songwriter_sunday_school->paginate(3)
                        ]);
                    } else {
                        return View('videos.index', [
                            'memory' => $memory->get(),
                            'fireside_friday' => $fireside_friday->get(),
                            'songwriter_sinday_school' => $songwriter_sunday_school->get()
                        ]);
                    }*/
                    return View('videos.index');
                } else {
                    return View('videos.index_not_logged_in');
                }

            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getVideos(Request $request)
    {
        try {
            $search = $request->input('search');
            $page = $request->input('page');

            $firesides = VideoDetail::when($request->has('search'), function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%');
            })
            ->where('video_category_id', 2)
            ->where('is_public', 1)
            ->orderBy('updated_at', 'DESC')
            ->paginate(30)
            ->withPath(url('get-videos/2'));

            $memory_lane = VideoDetail::when($request->has('search'), function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%');
            })
            ->where('video_category_id', 3)
            ->where('is_public', 1)
            ->orderBy('updated_at', 'DESC')
            ->paginate(30)
            ->withPath(url('get-videos/3'));

            $songwriter_sunday_school = VideoDetail::when($request->has('search'), function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%');
            })
            ->where('video_category_id', 4)
            ->where('is_public', 1)
            ->orderBy('updated_at', 'DESC')
            ->paginate(30)
            ->withPath(url('get-videos/4'));

            $miscellaneous_michael = VideoDetail::when($request->has('search'), function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%');
            })
            ->where('video_category_id', 6)
            ->orderBy('updated_at', 'DESC')
            ->where('is_public', 1)
            ->paginate(30)
            ->withPath(url('get-videos/6s'));

            return response()->json(array(
                'firesides' => $firesides,
                'memory_lane' => $memory_lane,
                'songwriter_sunday_school' => $songwriter_sunday_school,
                'miscellaneous_michael' => $miscellaneous_michael,
            ));

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function paginateVideos(Request $request) {

        $search = $request->input('search');
        $id = $request->id;

        $pagination = VideoDetail::when($request->has('search'), function ($query) use ($search) {
            $query->where('title', 'like', '%'.$search.'%');
        })
        ->where('video_category_id', $id)
        ->where('is_public', 1)
        ->orderBy('updated_at', 'DESC')
        ->paginate(3);

        return response()->json($pagination);
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

            $video_categories = DB::select(DB::Raw(
                "SELECT * FROM video_categories
            LEFT JOIN (SELECT 1 as `exists`, video_category_id FROM video_details WHERE video_category_id = 5 ) as check_explore_category
            ON check_explore_category.video_category_id = video_categories.id
            WHERE check_explore_category.exists IS NULL"));

            $band_album = BandAlbum::get();
            return view('videos.admin.create', [
                'video_categories' => $video_categories,
                'band_albums' => $band_album
            ]);
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
                $video_detail = VideoDetail::create($request->except('video'));

                if ($request->has('image')) {
                    $video_detail->saveImage($request->input('image'));
                }

                if ($request->has('video')) {
                    $video_detail->saveVideo($request->input('video'));
                }
                if ($request['is_public'] == 1) {
                  $this->sendMobileNotification('Video', $request->title);
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

            $video_detail = VideoDetail::findOrFail($id);
            $video_categories = DB::select(DB::Raw(
                "SELECT * FROM video_categories
            LEFT JOIN (SELECT 1 as `exists`, video_category_id FROM video_details WHERE video_category_id = 5 AND id != {$video_detail->id} ) as check_explore_category
            ON check_explore_category.video_category_id = video_categories.id
            WHERE check_explore_category.exists IS NULL"));
            $band_albums = BandAlbum::get();
            return view('videos.admin.edit', [
                'video_detail' => $video_detail,
                'video_categories' => $video_categories,
                'band_albums' => $band_albums,
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
            $video_detail = VideoDetail::findOrFail($id);
            $video_detail->update($request->except(['image', 'video']));

            if ($request->input('image')) {
                $video_detail->removeImage();
                $video_detail->saveImage($request->input('image'));
            }

            if($request->input('video')) {
                $video_detail->removeVideo();
                $video_detail->saveVideo($request->input('video'));
            }

            return back()->with(['success' => 'Video details successfully updated.']);
        } catch (\Exception $th) {
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

            $video_detail = VideoDetail::findOrFail($id);
            $video_detail->delete();

            Storage::deleteDirectory('videos/'.$id);

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
