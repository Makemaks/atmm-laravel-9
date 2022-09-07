<?php

namespace App\Http\Controllers\Web;

use App\Models\Apploginhistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;


class ApploginhistoriesController extends Controller
{
      private $all_device_os;
      private $all_device_version;
      private $all_appLogIn_user;
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

              $this->all_device_os = Apploginhistory::distinct()->get(['device_os']);
              $this->all_device_version = Apploginhistory::distinct()->get(['device_version']);
              $this->all_appLogIn_user = Apploginhistory::with('user')->distinct()->get(['user_id']);

              $this->filter_by_fields = array();
              $this->filter($request);

              $login_history = $this->paginate($request, [
                  'items' => (new Apploginhistory())->with('user'),
                  's_fields' => ['device_os'],
                  'sortBy' => $request->sort_field,
                  'sortOrder' => $request->sort_order,
                  'filter_by_fields' => $this->filter_by_fields,

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
                   'all_device_os' => $this->all_device_os,
                   'all_device_version' => $this->all_device_version,
                   'all_appLogIn_user' => $this->all_appLogIn_user,
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

    public function filter($request)
    {
      if( $request->select_appLogIn_user != '' )
      {
        $this->filter_by_fields['user_id'] = trim($request->select_appLogIn_user);
        $this->all_device_os = Apploginhistory::select('device_os')
                                ->where('user_id',$this->filter_by_fields['user_id'])
                                ->distinct()
                                ->get(['device_os']);
        $this->all_device_version = Apploginhistory::select('device_version')
                                ->where('user_id',$this->filter_by_fields['user_id'])
                                ->distinct()
                                ->get(['device_version']);
      }
      if( $request->select_device_os != '' )
      {
        $this->filter_by_fields['device_os'] = trim($request->select_device_os);
        $this->all_device_version = Apploginhistory::select('device_version')
                                      ->where('device_os',$this->filter_by_fields['device_os'])
                                      ->distinct()
                                      ->get(['device_version']);

        if( $request->select_appLogIn_user != '' )
        {
          $this->all_device_version = Apploginhistory::
                                        select('device_version')
                                        ->where('user_id',$this->filter_by_fields['user_id'])
                                        ->where('device_os',$this->filter_by_fields['device_os'])
                                        ->distinct()
                                        ->get(['device_version']);
        }
        else
        {
          $this->all_appLogIn_user = Apploginhistory::with('user')
                                      ->where('device_os',$this->filter_by_fields['device_os'])
                                      ->distinct()
                                      ->get(['user_id']);
        }
      }
      if( $request->select_device_version != '' )
      {
        $this->filter_by_fields['device_version'] = trim($request->select_device_version);
        $this->all_device_os = Apploginhistory::select('device_os')
                                ->where('device_version',$this->filter_by_fields['device_version'])
                                ->distinct()
                                ->get(['device_os']);
        $this->all_appLogIn_user = Apploginhistory::with('user')
                                    ->where('device_version',$this->filter_by_fields['device_version'])
                                    ->distinct()
                                    ->get(['user_id']);
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
               $appLogIn = Apploginhistory::findOrFail($id);
               if($appLogIn != null || $appLogIn != '')
               {
                 $isLatestNum = $this->isAppLogInLatest($appLogIn);
                 $isValid = $isValid + $isLatestNum;
               }
             }

             if($isValid != 0)
             {
               Session::flash('messageAppLogError', "The App Log In History can't be deleted since it is the  5 latest App Log In History of a specific user");
               return back();
             }
             else
             {
               Session::flash('messageAppLogSuccess', "The App Log In History deleted successfully");
               foreach($appActLog_user_id as $id)
               {
                 Apploginhistory::findOrFail($id)->delete();
               }
             }

             return back();
         } catch (\Throwable $th) {
             throw $th;
         }
         **/
     }

     public function isAppLogInLatest($appLogIn)
     {
       /**
       $appactivitylog = Apploginhistory::select('id', 'ip_address', 'device_os', 'device_version', 'created_at')
                           ->where('user_id', $appLogIn->user_id)
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

         if($appLogIn->id == $appUser->id)
         {
           $isLatest = $isLatest + 1;
         }
       }
       return $isLatest;
       **/
     }
}
