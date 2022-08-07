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

              $all_device_os = Apploginhistory::distinct()->get(['device_os']);
              $all_device_version = Apploginhistory::distinct()->get(['device_version']);
              $all_appLogIn_user = Apploginhistory::with('user')->distinct()->get(['user_id']);

              $filter_by_fields = array();
              if( $request->select_appLogIn_user != '' )
              {
                $filter_by_fields['user_id'] = trim($request->select_appLogIn_user);
                $all_device_os = Apploginhistory::select('device_os')->where('user_id',$filter_by_fields['user_id'])->distinct()->get(['device_os']);
              }
              if( $request->select_device_os != '' )
              {
                $filter_by_fields['device_os'] = trim($request->select_device_os);
                $all_device_version = Apploginhistory::select('device_version')->where('device_os',$filter_by_fields['device_os'])->distinct()->get(['device_version']);        
              }
              if( $request->select_device_version != '' )
              {
                $filter_by_fields['device_version'] = trim($request->select_device_version);
              }

              $login_history = $this->paginate($request, [
                  'items' => (new Apploginhistory())->with('user'),
                  's_fields' => ['device_os'],
                  'sortBy' => $request->sort_field,
                  'sortOrder' => $request->sort_order,
                  'filter_by_fields' => $filter_by_fields,

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
                   'all_device_os' => $all_device_os,
                   'all_device_version' => $all_device_version,
                   'all_appLogIn_user' => $all_appLogIn_user,
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
