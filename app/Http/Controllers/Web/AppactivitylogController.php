<?php

namespace App\Http\Controllers\Web;

use App\Models\Appactivitylog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppactivitylogController extends Controller
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

               $users = Appactivitylog::with('user')->distinct()->get(['user_id']);
               $all_os = Appactivitylog::distinct()->get(['device_os']);
               $all_device_name = Appactivitylog::distinct()->orderBy('device_name', 'asc')->get(['device_name']);

               $filter_by_fields = array();
               if( $request->select_user != '' )
                 $filter_by_fields['user_id'] = trim($request->select_user);
               if( $request->select_os != '' )
                 $filter_by_fields['device_os'] = trim($request->select_os);
               if( $request->select_device_name != '' )
                 $filter_by_fields['device_name'] = trim($request->select_device_name);

               $appactivitylog = $this->paginate($request, [
                   'items' => (new Appactivitylog())->with('user'),
                   's_fields' => ['apiroutename'],
                   'sortBy' => $request->sort_field,
                   'sortOrder' => $request->sort_order,
                   'filter_by_fields' => $filter_by_fields,
               ]);
               $response = [
                    'pagination' => [
                        'total' => $appactivitylog->total(),
                        'per_page' => $appactivitylog->perPage(),
                        'current_page' => $appactivitylog->currentPage(),
                        'last_page' => $appactivitylog->lastPage(),
                        'from' => $appactivitylog->firstItem(),
                        'to' => $appactivitylog->lastItem()
                    ],
                    'data' => $appactivitylog,
                    'users' => $users,
                    'all_os' => $all_os,
                    'all_device_name' => $all_device_name,
                    'aws_url' => \Config::get('filesystems.aws_url'),
                ];
                return response()->json($response);

             } else {
               return view('appactivitylog.admin.index', [
                   'appactivitylog' => $this->paginate($request, [
                       'items' => (new Appactivitylog())->select('*'),
                       's_fields' => ['apiroutename'],
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
