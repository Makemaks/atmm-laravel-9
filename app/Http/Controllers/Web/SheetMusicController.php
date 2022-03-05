<?php

namespace App\Http\Controllers\Web;

use App\Models\SheetMusic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\SheetMusic\Store;
use App\Http\Requests\SheetMusic\Update;
use Illuminate\Support\Facades\Storage;

class SheetMusicController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Session::get('user');
            $admin = $user->isAdmin;
            if ($admin) {

              if($request->ajax()){

                $sheet_musics = $this->paginate($request, [
                    'items' => (new SheetMusic()),
                    's_fields' => ['title'],
                    'sortBy' => $request->sort_field,
                    'sortOrder' => $request->sort_order,
                ]);
                $response = [
                     'pagination' => [
                         'total' => $sheet_musics->total(),
                         'per_page' => $sheet_musics->perPage(),
                         'current_page' => $sheet_musics->currentPage(),
                         'last_page' => $sheet_musics->lastPage(),
                         'from' => $sheet_musics->firstItem(),
                         'to' => $sheet_musics->lastItem()
                     ],
                     'data' => $sheet_musics,
                     'aws_url' => \Config::get('filesystems.aws_url'),
                 ];
                 return response()->json($response);


              } else {

                    $sortByDate = $request->input('sort_by_date');
                    $sheet_musics = SheetMusic::orderBy('created_at', $request->has('sort_by_date') ? $sortByDate : 'desc')
                        ->paginate(10);
                    return view('sheet_musics.admin.Index', [
                        'sheet_musics' => $this->paginate($request, [
                            'items' => new SheetMusic(),
                            's_fields' => ['title'],
                            'sortBy' => 'title',
                            'sortOrder' => 'ASC'
                        ])
                    ]);
              }


            } else {
                if (!empty($user)) {
                    return view('sheet_musics.index');
                } else {
                    return view('sheet_musics.index_not_logged_in');
                }

            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getSheetMusics(Request $request)
    {
        try {
            /* $search = $request->input('search');
            $sheets = SheetMusic::orderBy('title', 'asc')
            ->when($request->has('search'), function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%');
            })
            ->where('is_public', 1)
            ->paginate(30);
            return $sheets; */

            return $this->paginate($request, [
                'items' => ((new SheetMusic())->where('is_public', 1)),
                's_fields' => ['title'],
                'sortBy' => 'title',
                'sortOrder' => 'ASC',
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function create()
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            return view('sheet_musics.admin.Create');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Store $request)
    {
        try {
            if(!$this->isCurrentUserAdmin()) {
                return response()->json('error', 404);
            } else {
                $request['show_in_explore'] = $request['show_in_explore'] == 'yes' ? 1 : 0;
                $request['is_public'] = $request['is_public'] == 'yes' ? 1 : 0;
                $sheet_music = SheetMusic::create($request->except(['image', 'file']));
                if ($request->input('image')) {
                    $sheet_music->saveImage($request->input('image'));
                }
                if ($request->input('file')) {
                    $sheet_music->saveFile($request->input('file'));
                }
                if ($request['is_public'] == 1) {
                  $this->sendMobileNotification('Sheet Music', $request->title);
                }
                return response()->json('success', 200);
            }


        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function show($id)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function edit($id)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            $sheet_music = SheetMusic::findOrfail($id);
            return view('sheet_musics.admin.Edit', [
                'sheet_music' => $sheet_music
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(Update $request, $id)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            $request['show_in_explore'] = $request->has('show_in_explore') ? 1 : 0;
            $request['is_public'] = $request->has('is_public') ? 1 : 0;
            $sheet_music = SheetMusic::findOrfail($id);
            $sheet_music->update($request->except(['image', 'file']));
            if ($request->input('image')) {
                $sheet_music->removeImage();
                $sheet_music->saveImage($request->input('image'));
            }
            if ($request->input('file')) {
                $sheet_music->removeFile();
                $sheet_music->saveFile($request->input('file'));
            }

            return back()->with(['success' => 'Sheet Music details updated successfully.']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroy($id)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            $sheet_music = SheetMusic::findOrFail($id);
            $sheet_music->delete();

            Storage::deleteDirectory('sheet_musics/' . $id);

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
