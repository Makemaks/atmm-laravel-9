<?php

namespace App\Http\Controllers\Web;

use App\Models\Apploginhistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ApploginhistoriesController extends Controller
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

              $login_history = $this->paginate($request, [
                  'items' => (new Apploginhistory())->with('user'),
                  's_fields' => ['device_os'],
                  'sortBy' => $request->sort_field,
                  'sortOrder' => $request->sort_order,
              ]);
              $response = [
                   'pagination' => [
                       'total' => $login_history->total(),
                       'per_page' => $login_history->perPage(),
                       'current_page' => $login_history->currentPage(),
                       'last_page' => $login_history->lastPage(),
                       'from' => $login_history->firstItem(),
                       'to' => $login_history->lastItem()
                   ],
                   'data' => $login_history,
                   'aws_url' => \Config::get('filesystems.aws_url'),
               ];
               return response()->json($response);

            } else {

              return view('apploginhistory.admin.index', [
                  'login_history' => $this->paginate($request, [
                      'items' => (new Apploginhistory())->select('*'),
                      's_fields' => ['device_os'],
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
     * @param  \App\Appactivitylog  $appactivitylog
     * @return \Illuminate\Http\Response
     */
    public function show(Appactivitylog $appactivitylog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Appactivitylog  $appactivitylog
     * @return \Illuminate\Http\Response
     */
    public function edit(Appactivitylog $appactivitylog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appactivitylog  $appactivitylog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appactivitylog $appactivitylog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Appactivitylog  $appactivitylog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appactivitylog $appactivitylog)
    {
        //
    }
}
