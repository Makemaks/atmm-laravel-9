<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Userscancelled;
use App\Models\SubscriberMetrics;
use App\Models\Nmipayments;
use App\Models\SongAuthors;
use App\Models\Author;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriberMetricsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$this->isCurrentUserAdmin())
            abort(404);
        else
          return view('subscriber_metrics.admin.index');
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
        $user = Auth::user();
        //$request->merge(["ip_adress"=>request()->ip()]);
        $request->merge(["ip_adress"=> $this->getUserIP() ]);
        $request->merge(["user_id"=>$user->id]);
        $result = SubscriberMetrics::create($request->all());
        return $result;
    }

    private function getUserIP() {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                  $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)) { $ip = $client; }
        elseif(filter_var($forward, FILTER_VALIDATE_IP)) { $ip = $forward; }
        else { $ip = $remote; }

        return $ip;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SubscriberMetrics  $subscriberMetrics
     * @return \Illuminate\Http\Response
     */
    public function show(SubscriberMetrics $subscriberMetrics)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SubscriberMetrics  $subscriberMetrics
     * @return \Illuminate\Http\Response
     */
    public function edit(SubscriberMetrics $subscriberMetrics)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SubscriberMetrics  $subscriberMetrics
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubscriberMetrics $subscriberMetrics)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SubscriberMetrics  $subscriberMetrics
     * @return \Illuminate\Http\Response
     */
    //public function destroy(SubscriberMetrics $subscriberMetrics)
    public function destroy($id)
    {
      try {
          if(!$this->isCurrentUserAdmin())
              abort(404);

          $this->stopSubscription($id);
          return back();

      } catch (\Throwable $th) {
          throw $th;
      }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRoyaltyList(Request $request)
    {
        if(!$this->isCurrentUserAdmin())
            abort(404);

          $items = SongAuthors::from('songs_authors as sa')
                      ->select('sa.author_id'
                            , 'authors.name AS author_name'
                            , DB::raw('
                                  (SUM(
                                      (
                                          SELECT COUNT(id)
                                              FROM subscriber_metrics
                                              WHERE song_id = sa.music_detail_id
                                                  AND time_streamed
                                                  BETWEEN ? AND ?
                                      )
                                  )) AS songs_streamed
                              ')
                              , DB::raw('
                                  SUM(((
                                      (SELECT COUNT(id)
                                          FROM subscriber_metrics
                                          WHERE song_id = sa.music_detail_id) * .0075)
                                      /
                                      (SELECT COUNT(id)
                                          FROM songs_authors
                                          WHERE music_detail_id = sa.music_detail_id)
                                  )) AS royalty_value
                              ')
                          )
                      ->leftJoin('authors', 'authors.id', '=', 'sa.author_id')
                      ->leftJoin('music_details', 'music_details.id', '=', 'sa.music_detail_id')
                      ->whereIn('music_details.id', function($query) use ($request)
                      {
                          $query->select('song_id')
                                ->from('subscriber_metrics')
                                //->whereBetween('time_streamed', [$request->date_start, $request->date_end])
                                ->whereRaw('song_id = sa.music_detail_id');
                      })

                      ->groupBy('sa.author_id')
                      ->groupBy('authors.name')
                      ->setBindings([$request->date_start, $request->date_end])
                      ->get();

          return $items;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRoyaltyDetails(Request $request)
    {
      if(!$this->isCurrentUserAdmin())
        abort(404);

        $items = SongAuthors::select('songs_authors.author_id'
                                        , 'songs_authors.music_detail_id'
                                        , 'music_details.title AS song'
                                        , 'users.name'
                                        , 'users.email'
                                        , 'subscriber_metrics.ip_adress'
                                        , 'subscriber_metrics.time_streamed'
                                        , DB::raw('
                                            (SELECT COUNT(id) FROM subscriber_metrics WHERE song_id = songs_authors.music_detail_id) AS songs_streamed
                                        ')
                                        /*
                                        , DB::raw('
                                            (SELECT COUNT(id)
                                                FROM songs_authors
                                                WHERE music_detail_id = songs_authors.music_detail_id
                                                ) AS author_count
                                        ')
                                        , DB::raw('
                                            (
                                                ((SELECT COUNT(id)
                                                    FROM subscriber_metrics
                                                    WHERE song_id = songs_authors.music_detail_id)
                                                     * .0075)
                                                    /
                                                (SELECT COUNT(id)
                                                    FROM songs_authors
                                                    WHERE music_detail_id = songs_authors.music_detail_id)
                                            ) AS royalty_due
                                        ')
                                        */
                                    )
                    ->leftJoin('music_details', 'music_details.id', '=', 'songs_authors.music_detail_id')
                    ->leftJoin('subscriber_metrics', 'subscriber_metrics.song_id', '=', 'songs_authors.music_detail_id')
                    ->leftJoin('users', 'users.id', '=', 'subscriber_metrics.user_id')
                    ->where('songs_authors.author_id','=', $request->author_id)
                    ->whereIn('music_details.id', function($query)
                    {
                        $query->select('song_id')
                              ->from('subscriber_metrics')
                              ->whereRaw('song_id = songs_authors.music_detail_id');
                    })
                    ->whereBetween('subscriber_metrics.time_streamed', [$request->date_start, $request->date_end])
                    //->groupBy('songs_authors.music_detail_id')
                    ->orderBy('subscriber_metrics.time_streamed', 'desc')
                    ->get();


        // get authors
        $total_royalty_due = 0;
        if( count($items) > 0 ) {
            $authors = array();
            foreach ($items as $key => $value) {
                $authors_str = '';
                $songauthors = SongAuthors::select('author_id','authors.name')
                                ->leftJoin('authors', 'authors.id', '=', 'songs_authors.author_id')
                                ->where('music_detail_id','=', $value->music_detail_id)->get();
                $items[$key]->authors = $songauthors;
                $items[$key]->author_count = count($songauthors);
                //$items[$key]->royalty_due = ($value->songs_streamed * .0075) / count($songauthors);
                $items[$key]->royalty_due = (1 * .0075) / count($songauthors);
                $total_royalty_due += $items[$key]->royalty_due;
            }
        }

        $return_data = array();
        $return_data['items'] = $items;
        $return_data['total_royalty_due'] = $total_royalty_due;
        return $return_data;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function getAllSubscribers(Request $request)
     {
       try {
             if(!$this->isCurrentUserAdmin())
                 abort(404);

             $items = User::select('id', 'name', 'email', 'created_at',
                                   'subscription_status', 'subscription_type_id'
                                 )
                       ->where('isAdmin','=',0)
                       ->with('nmiTransactions');
                       ;

             if($request->ajax()){

               $all_subscription_status = User::distinct()->get(['subscription_status']);

               $filter_by_fields = array();
               if( $request->select_subscription_status != '' ) {
                 $filter_by_fields['subscription_status'] =  trim($request->select_subscription_status);
               }

               $subscribers = $this->paginate($request, [
                   'items' => $items,
                   's_fields' => ['name'],
                   'sortBy' => $request->sort_field,
                   'sortOrder' => $request->sort_order,
                   'filter_by_fields' => $filter_by_fields,
               ]);
               $response = [
                    'pagination' => [
                        'total' => $subscribers->total(),
                        'per_page' => $subscribers->perPage(),
                        'current_page' => $subscribers->currentPage(),
                        'last_page' => $subscribers->lastPage(),
                        'from' => $subscribers->firstItem(),
                        'to' => $subscribers->lastItem()
                    ],
                    'data' => $subscribers,
                    'all_subscription_status' => $all_subscription_status,
                ];
                return response()->json($response);

             } else {
               return view('subscriber_metrics.admin.subscriberlist', [
                   'subscribers' => $this->paginate($request, [
                       'items' => $items,
                       's_fields' => ['name'],
                       'sortBy' => 'created_at',
                       'sortOrder' => 'DESC'
                   ])
               ]);
               return View('subscriber_metrics.admin.subscriberlist', ['subscribers' => $items]);
             }

         } catch (\Throwable $th) {
             throw $th;
         }
     }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllSubscribersOld(Request $request)
    {
        try {
            if(!$this->isCurrentUserAdmin())
                abort(404);

            $request['page'] = $request['page'] = $request->page ? $request->page : 1;
            //$items = new User;
            //$items = $items->where('isAdmin','=',0);

            //DATEDIFF(NOW(), "2020-06-15 15:25:35");
            //DB::raw(' DATEDIFF(NOW(), "2020-06-20")  as trial_day_left')
            $items = User::select('id', 'name', 'email', 'created_at',
                                  'subscription_status', 'subscription_type_id'
                                )
                      ->where('isAdmin','=',0);
            if ($request->has('page')) {
              if ($request->page) {
                  /* $items = $items->paginate(10); */
                  return view('subscriber_metrics.admin.subscriberlist', [
                      'subscribers' => $this->paginate($request, [
                          'items' => $items,
                          's_fields' => ['name'],
                          'sortBy' => 'created_at',
                          'sortOrder' => 'DESC'
                      ])
                  ]);
              } else {
                  return View('subscriber_metrics.admin.subscriberlist', ['subscribers' => $items]);
              }
            }
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIPAddressDetails(Request $request)
    {
        if(!$this->isCurrentUserAdmin()) {
          abort(404);
        } else {
          $output = shell_exec('whois '.$request->ip.'');
          return "<pre>$output</pre>";
        }
    }


    public function stopSubscription($id)
    {
        try {
            $user =  User::findOrFail($id);
            $nmipayments = Nmipayments::select('id', 'subscription_id')
                                ->where('user_id', $user->id)
                                ->orderBy('id', 'desc')->first();


            $nmipayment_id = $nmipayments->id;

            $api_method = 'POST';
            $end_point = '/api/transact.php';
            $api_data = array();
            $api_data['recurring'] = 'delete_subscription';
            $api_data['subscription_id'] =  $nmipayments->subscription_id;
            $nmi_data = $this->api_send_request_to_nmi($api_method, $end_point, $api_data);

            //echo '<pre>';
            //print_r($nmi_data);
            //echo '</pre>';


            $data = explode("&",$nmi_data);
            for($i=0;$i<count($data);$i++) {
                $rdata = explode("=",$data[$i]);
                $responses[$rdata[0]] = $rdata[1];
            }
            $error_msg = '';
            $error = true;
            $status_code = 403;
            if(strtolower( trim($responses['responsetext']) ) == 'recurring transaction deleted') {

              $userscancelled = new Userscancelled();
              $userscancelled->user_id = $user->id;
              $userscancelled->email = $user->email;
              $userscancelled->reason_to_stop = 'Cancelled by the Admin';
              $userscancelled->subscription_id = $nmipayments->subscription_id;
              $userscancelled->original_transaction_id = $nmipayments->subscription_id;
              $userscancelled->save();

              /* the logic code below dont need or there's no need to update the user status
                 since there is cronjob that will check the latest payment and will update the status
              */
              //change the original email so that user can signup again in case want to come back
              $user =  User::findOrFail($user->id);
              $user->subscription_status = 'FORCE_STOP';
              $user->save();


              $error = false;
              $status_code = 200;
              $error_msg = 'Your Subscription successfully cancelled!';


              // notify end-user
              $mail_data = array();
              $mail_data['email'] = 'songwritersundayschool@gmail.com';
              $mail_data['subject'] = 'Your subscription have successfully cancelled  in AllThingsMichaelMcLean.com ';
              $mail_data['message'] = '
                              Hi '.$user->name.',
                              <br><br>
                              Your account has been cancelled in AllThingsMichaelMcLean.com by the Admin.
                              <br><br>
                              Thanks,<br>
                          ';
              //Notification::route('mail', $user->email)->notify(new SendFeedbackNotification($mail_data));



            } else {

              if( isset($responses['responsetext']) && trim($responses['responsetext']) != '' ) {
                $error_msg = $responses['responsetext'];
              } else {
                $error_msg = 'Problem occured when cancelling a subscription in NMI.';
              }

              $status_code = 422;
              $errors = new \stdClass;
              $errors->nmimessage = $error_msg;
              return response()->json( ['errors' => $errors, 'message' => 'The given data was invalid.' ], 422 );

            }

            return response()->json( ['error' => $error, 'message' => $error_msg ], $status_code );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
