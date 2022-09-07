<?php

namespace App\Http\Controllers\Web;

use App\Models\Appactivitylog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\DB;

class AppactivitylogController extends Controller
{
    private $users;
    private $all_os;
    private $all_device_name;
    private $filter_by_fields;
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

               $this->users = Appactivitylog::with('user')->distinct()->get(['user_id']);
               $this->all_os = Appactivitylog::distinct()->get(['device_os']);
               $this->all_device_name = Appactivitylog::distinct()->orderBy('device_name', 'asc')->get(['device_name']);

               $this->filter_by_fields = array();
               $this->filter($request);

               $appactivitylog = $this->paginate($request, [
                   'items' => (new Appactivitylog())->with('user'),
                   's_fields' => ['apiroutename'],
                   'sortBy' => $request->sort_field,
                   'sortOrder' => $request->sort_order,
                   'filter_by_fields' => $this->filter_by_fields,
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
                    'users' => $this->users,
                    'all_os' => $this->all_os,
                    'all_device_name' => $this->all_device_name,
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

     public function filter($request)
     {
       if( $request->select_user != '' )
       {
         $this->filter_by_fields['user_id'] = trim($request->select_user);
         $this->all_os = Appactivitylog::select('device_os')
                                 ->where('user_id',$this->filter_by_fields['user_id'])
                                 ->distinct()
                                 ->get(['device_os']);
         $this->all_device_name = Appactivitylog::select('device_name')
                                 ->where('user_id',$this->filter_by_fields['user_id'])
                                 ->distinct()
                                 ->get(['device_name']);
       }
       if( $request->select_os != '' )
       {
         $this->filter_by_fields['device_os'] = trim($request->select_os);
         $this->all_device_name = Appactivitylog::select('device_name')
                                       ->where('device_os',$this->filter_by_fields['device_os'])
                                       ->distinct()
                                       ->get(['device_name']);

         if( $request->select_user != '' )
         {
           $this->all_device_name = Appactivitylog::
                                         select('device_name')
                                         ->where('user_id',$this->filter_by_fields['user_id'])
                                         ->where('device_os',$this->filter_by_fields['device_os'])
                                         ->distinct()
                                         ->get(['device_name']);
         }
         else
         {
           $this->users = Appactivitylog::with('user')
                                       ->where('device_os',$this->filter_by_fields['device_os'])
                                       ->distinct()
                                       ->get(['user_id']);
         }
       }
       if( $request->select_device_name != '' )
       {
         $this->filter_by_fields['device_name'] = trim($request->select_device_name);
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
    public function destroy($id)
    {
      /**
      try {
          if(!$this->isCurrentUserAdmin())
              abort(404);

          $appActLog_user_id = explode(',' , $id);
          $isValid = 0;

          foreach($appActLog_user_id as $id)
          {
            $appActLog = Appactivitylog::findOrFail($id);
            if($appActLog != null || $appActLog != '')
            {
              $isLatestNum = $this->isAppLogLatest($appActLog);
              $isValid = $isValid + $isLatestNum;
            }
          }

          if($isValid != 0)
          {
                Session::flash('messageAppActError', "The App Activity Log can't be deleted since it is one of the 5 latest App Activity Log of a user");
                return back();
          }
          else
          {
                Session::flash('messageAppActSuccess', "The App Activity Logs deleted successfully");
                foreach($appActLog_user_id as $id)
                {
                  Appactivitylog::findOrFail($id)->delete();
                }
          }

          return back();
      } catch (\Throwable $th) {
          throw $th;
      }
      **/
    }

    public function isAppLogLatest($appActLog)
    {
      /**
      $appactivitylog = Appactivitylog::select('id','action', 'ip_address', 'device_os', 'device_name', 'device_version', 'created_at')
                          ->where('user_id', $appActLog->user_id)
                          ->orderBy('id', 'desc')
                          ->take(5)
                          ->get();
      $isLatest = 0;
      foreach($appactivitylog as $appUser)
      {
      /**
      echo($appLogIn->id);
      echo('<pre>');
      echo($user->id);
      echo('</pre>');
      exit;

        if($appActLog->id == $appUser->id)
        {
          $isLatest = $isLatest + 1;
        }
      }
      return $isLatest;
      **/

    }
}
