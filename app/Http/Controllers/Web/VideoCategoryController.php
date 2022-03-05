<?php

namespace App\Http\Controllers\Web;

use App\Models\VideoCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VideoCategory\Store;

class VideoCategoryController extends Controller
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
              $videoCategory = $this->paginate($request, [
                  'items' => (new VideoCategory())->with('videoDetails'),
                  's_fields' => ['description'],
                  'sortBy' => $request->sort_field,
                  'sortOrder' => $request->sort_order,
              ]);
              $response = [
                   'pagination' => [
                       'total' => $videoCategory->total(),
                       'per_page' => $videoCategory->perPage(),
                       'current_page' => $videoCategory->currentPage(),
                       'last_page' => $videoCategory->lastPage(),
                       'from' => $videoCategory->firstItem(),
                       'to' => $videoCategory->lastItem()
                   ],
                   'data' => $videoCategory
               ];
               return response()->json($response);
            } else {
                return view('video_category.admin.index', [
                    'video_categories' => $this->paginate($request, [
                        'items' => new VideoCategory(),
                        's_fields' => ['description'],
                        'sortBy' => 'description',
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

            return view('video_category.admin.create');
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
            if(!$this->isCurrentUserAdmin()) {
                return response()->json('error', 404);
            } else {
                $video_category = VideoCategory::create($request->all());
                return response()->json('success', 200);
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

            $video_category = VideoCategory::findOrFail($id);
            return view('video_category.admin.edit', [
                'video_category' => $video_category,
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
    public function update(Request $request, $id)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            $video_category = VideoCategory::findOrFail($id);
            $video_category->update($request->all());

            return back()->with(['success' => 'Category successfully updated.']);
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

            $video_category = VideoCategory::findOrFail($id);
            $video_category->delete();

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
