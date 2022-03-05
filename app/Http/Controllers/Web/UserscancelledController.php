<?php

namespace App\Http\Controllers\Web;

use App\Models\Userscancelled;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserscancelledController extends Controller
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

              $userscancelled = $this->paginate($request, [
                  'items' => (new Userscancelled())->with('user'),
                  's_fields' => ['email'],
                  'sortBy' => $request->sort_field,
                  'sortOrder' => $request->sort_order,
              ]);
              $response = [
                   'pagination' => [
                       'total' => $userscancelled->total(),
                       'per_page' => $userscancelled->perPage(),
                       'current_page' => $userscancelled->currentPage(),
                       'last_page' => $userscancelled->lastPage(),
                       'from' => $userscancelled->firstItem(),
                       'to' => $userscancelled->lastItem()
                   ],
                   'data' => $userscancelled,
                   'aws_url' => \Config::get('filesystems.aws_url'),
               ];
               return response()->json($response);

            } else {
              return view('userscancelled.admin.index', [
                  'userscancelled' => $this->paginate($request, [
                      'items' => (new Userscancelled())->select('*'),
                      's_fields' => ['email'],
                      'sortBy' => 'id',
                      'sortOrder' => 'DESC'
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
        //
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
     * @param  \App\Userscancelled  $userscancelled
     * @return \Illuminate\Http\Response
     */
    public function show(Userscancelled $userscancelled)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Userscancelled  $userscancelled
     * @return \Illuminate\Http\Response
     */
    public function edit(Userscancelled $userscancelled)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Userscancelled  $userscancelled
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Userscancelled $userscancelled)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Userscancelled  $userscancelled
     * @return \Illuminate\Http\Response
     */
    public function destroy(Userscancelled $userscancelled)
    {
        //
    }
}
