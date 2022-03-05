<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

  //Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  Route::middleware('auth:api')->get('/user', function (Request $request) {
      return $request->user();
  });

  Route::get('/test', function () {
      return 'test';
  });

  Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
      Route::post('login', 'AuthController@login');
      Route::put('login', 'LoginController@doLogin')->name('apiDoLogin');
      Route::post('generate-otp-login', 'AuthController@generateOtpForLogin')->name('generateOtpForLogin');
      Route::post('verify-otp-for-login', 'AuthController@verifyOtpForLogin')->name('verifyOtpForLogin');
  });

  Route::group(['namespace' => 'App\Http\Controllers\Api', 'middleware' => 'auth:api'], function () {
      Route::get('albums/{id}/songs', 'BandAlbumController@getAlbumSongs');
      Route::apiResource('albums', 'BandAlbumController');
      Route::apiResource('podcasts', 'PodcastController');
      Route::apiResource('instrumentals', 'InstrumentalController');
      Route::apiResource('videos', 'VideoDetailController');
      Route::apiResource('songs', 'MusicDetailController');
      Route::apiResource('sheet-music', 'SheetMusicController');
      Route::get('video-categories/{id}/videos', 'VideoCategoryController@getAlbumSongs');
      Route::apiResource('video-categories', 'VideoCategoryController');
      Route::apiResource('app-settings', 'AppSettingsController');
      Route::apiResource('nmitransactions', 'NmitransactionsController');
      Route::get('update-user-status-using-nmi', 'NmitransactionsController@updateUserStatus');

      // need to add this, above apiResource put/patch(update) went something wrong
      Route::post('app-settings', 'AppSettingsController@updateVersion');

      Route::apiResource('cronvideos', 'VideosController');
      Route::put('subscribe', 'SubscribeController@subscribe');
      Route::post('subscribe', 'SubscribeController@subscribe');
      Route::get('cronvideos-other-server', 'VideosController@getVideosCopiedOnOtherServer');
      Route::get('cronsinglevideo-other-server', 'VideosController@getSingleVideoCopiedOnOtherServer');
      Route::get('get-completed-process-videos', 'VideosController@getCompletedProcessVideos');
      Route::get('get-infusionsoft-token', 'InfusionsoftController@getToken');
      Route::get('get-completed-trials', 'InfusionsoftController@getCompletedTrials');

      Route::apiResource('expo', 'ExpoController');
      Route::post('get-expo', 'ExpoController@getExpoInfo');
      Route::post('send-to-expo', 'ExpoController@sendNotification');
      Route::post('bulk-send', 'ExpoController@sendBulkNotification');

      Route::apiResource('app-activity-log', 'AppactivitylogController');
  });

  Route::group(['namespace' => 'App\Http\Controllers\Api'], function() {
      //Route::post('infusion-oauth', 'InfusionLogin@getToken');

      Route::post('/chunk-upload', function(Request $request)
      {
          $dir = \Request::all()['dir'];

          return Plupload::receive('file', function ($file) use ($dir, $request)
          {
              $storagePath = storage_path();

              if($request->query('prod')) {
                  $storagePath = '/var/www/songwritersundayschool.com/livesongwritersundayschool/storage';
              }

              $filename = uniqid('', true) . "." . $file->getClientOriginalExtension();
              //$file->move(storage_path() . '/uploads/', $filename);
              $file->move($storagePath . "/{$dir}/", $filename);
              return [
                  'ready' => true,
                  'file' => $filename
              ];
          });
      });
  });
