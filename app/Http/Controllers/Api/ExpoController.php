<?php

namespace App\Http\Controllers\Api;

use App\Models\Expo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      // temporary display the list of the expo push tokens for debugging purposes
      return $this->paginate($request, [
          'items' => (new Expo),
          'sortBy' => 'id',
          'sortOrder' => 'DESC'
      ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $expo_push = Expo::where('value', $request->expopushtoken)->count();
        if($expo_push < 1) {
          $expo_obj = new Expo();
          $expo_obj->user_id = $request->user_id;
          $expo_obj->value = $request->expopushtoken;
          if($expo_obj->save())
            return response()->json( ['result' => $expo_obj ], 200 );
          else
            return response()->json( ['result' => 'Error occured while saving the token.' ], 501 );
        } else {
          return response()->json( ['result' =>'Expo token already exist.' ], 419 );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getExpoInfo(Request $request)
    {
      $expo_push = Expo::where('value', $request->expopushtoken)->firstOrFail();
      return $expo_push;
    }

    /**
     * Send notification to Expo Servers
     * Reference: https://docs.expo.io/versions/latest/guides/push-notifications/
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * This result now an error: PUSH_TOO_MANY_EXPERIENCE_IDS"
     */
    public function sendBulkNotification()
    {
        $bulk_api_data = array();
        $expos = Expo::select('value')->limit(100)->get();
        if( count($expos) > 0 ) {
          $api_data = array();
          foreach( $expos as $key => $expo) {
            $api_data['to'] = $expo->value;
            $api_data['title'] = 'hello world title bulk';
            $api_data['body'] = 'hello world message bulk';
            $bulk_api_data[] = $api_data;
          }
        }
        $bulk_api_data = json_encode($bulk_api_data);
        $url = 'https://exp.host/--/api/v2/push/send';
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $bulk_api_data,
        ];
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, $options);
        $result = json_decode($response->getBody());
        return response()->json( ['result' => $result ], 200 );
    }


    /**
     * Send notification to Expo Servers
     * Reference: https://docs.expo.io/versions/latest/guides/push-notifications/
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendNotification(Request $request)
    {
        $api_data = array();
        $api_data['to'] = $request->expopushtoken;
        $api_data['title'] = $request->title;
        $api_data['body'] = $request->message;
        $api_data = json_encode($api_data);
        $url = 'https://exp.host/--/api/v2/push/send';
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $api_data,
        ];
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, $options);
        $result = json_decode($response->getBody());
        return response()->json( ['result' => $result ], 200 );
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $expo_push = Expo::findOrFail($id);
      return $expo_push;
    }


}
