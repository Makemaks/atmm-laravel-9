<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', 'HomeController@index')->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/super', [App\Http\Controllers\HomeController::class, 'super'])->name('super');
Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');

Route::post('sign-up', 'App\Http\Controllers\Api\InfusionLoginController@signUp');
Route::post('validate-sign-up', 'App\Http\Controllers\Api\InfusionLoginController@validateSignUp');

Route::get('/thankyou_page', function () {
    return view('thank_you.index');
});
Route::get('/infusion', function () {
    return view('landing-page');
});


Route::get('/explore', function () {
    $videoCategory = App\VideoCategory::where('description', 'Explore')->first();
    $video = App\VideoDetail::where('video_category_id', $videoCategory->id)->first();

    $exploreVideo = App\VideoDetail::select('id', 'title', 'video', 'video_category_id')
                         ->where('video_category_id', 5)->first();

    $mediaSampleData = [
        'videos' => App\VideoDetail::where('show_in_explore', 1)
                                    // make sure we don't include the 'Welcome video'
                                    ->where('video_category_id', '<>', 5)
                                    ->limit(4)
                                    ->get(),
        'music' => App\MusicDetail::where('show_in_explore', 1)
                    ->limit(4)
                    ->get(),
        'sheetMusic' => App\SheetMusic::where('show_in_explore', 1)
                    ->limit(4)
                    ->get(),
        'instrumental' => App\Instrumental::where('show_in_explore', 1)
                    ->limit(4)
                    ->get(),
        'podcasts' => App\Podcast::where('show_in_explore', 1)
                    ->orderBy('title', 'ASC')
                    ->orderBy('type', 'DESC')
                    ->limit(4)
                    ->get()
    ];

    return view('explore.index', [
        'exploreVideo' => $exploreVideo,
        'mediaSampleData' => $mediaSampleData
    ]);
});


//Route::get('/select_payment', function () { return view('select_payment.index');    });

/**** star temporary remove *****/
//Route::get('/select_payment', 'App\Http\Controllers\Web\InfusionsoftSettingsController@selectPayment');

/****** TEMPORARILY CLOSE FOR CONSTRUCTION *****/
Route::get('/select_payment', 'App\Http\Controllers\Web\NmiUserSettingsController@selectPayment');

/**** end temporary remove *****/

// Route::get('subscription', function () {    return view('select_payment.subscription'); });
//Route::get('subscription', 'App\Http\Controllers\Api\InfusionsoftController@subscriptiontest');

/**** star temporary remove *****/
// Route::get('subscription/{periodic}', 'App\Http\Controllers\Web\InfusionsoftController@subscriptionpage');

/****** TEMPORARILY CLOSE FOR CONSTRUCTION *****/
Route::get('subscription/{periodic}', 'App\Http\Controllers\Web\NmiUserSettingsController@subscriptionpage');

/**** end temporary remove *****/

//Route::post('subscribe', 'App\Http\Controllers\Api\InfusionsoftController@subscribe');     // for direct payment
//Route::post('subscribe', 'App\Http\Controllers\Web\InfusionsoftController@subscribe');   // for trial (payment made after trial)

Route::post('subscribe', 'App\Http\Controllers\Web\NmipaymentsController@store');   // for nmi trial (payment made after trial)

Route::get('refresh-infusionsoft-access-token', 'App\Http\Controllers\Api\InfusionsoftController@refresh_access_token');
Route::resource('contact-us', 'App\Http\Controllers\Web\ContactusController');
Route::post('send-message', 'App\Http\Controllers\Web\ContactusController@sendMessage');
//Route::group(['middleware' => 'auth',], function ()
Route::group(['middleware' => 'auth', '2fa'], function ()
{
    Route::resource('dashboard', 'App\Http\Controllers\Web\DashboardController');
    Route::resource('videos', 'App\Http\Controllers\Web\VideoDetailController');
    Route::resource('video-categories', 'App\Http\Controllers\Web\VideoCategoryController');
    Route::resource('instrumentals', 'App\Http\Controllers\Web\InstrumentalController');
    Route::resource('albums', 'App\Http\Controllers\Web\BandAlbumController');
    Route::resource('songs', 'App\Http\Controllers\Web\MusicDetailController');
    //Route::resource('settings', 'Web\UserController');
    Route::resource('settings', 'App\Http\Controllers\Web\NmiUserSettingsController');

    Route::get('album_details', 'App\Http\Controllers\Web\BandAlbumController@getAlbumMusics');
    Route::post('album_details', 'App\Http\Controllers\Web\BandAlbumController@searchAlbumMusic');
    Route::resource('feedbacks', 'App\Http\Controllers\Web\FeedbackController');
    Route::post('send-feedback', 'App\Http\Controllers\Web\FeedbackController@sendFeedback');
    Route::resource('sheet_musics', 'App\Http\Controllers\Web\SheetMusicController');
    Route::resource('/podcasts', 'App\Http\Controllers\Web\PodcastController');
    Route::resource('/artists', 'App\Http\Controllers\Web\ArtistController');
    Route::resource('/authors', 'App\Http\Controllers\Web\AuthorController');
    Route::resource('/subscriber-metrics', 'App\Http\Controllers\Web\SubscriberMetricsController');
    Route::resource('/infusionsoft-settings', 'App\Http\Controllers\Web\InfusionsoftSettingsController');
    Route::resource('/products', 'App\Http\Controllers\Web\ProductsController');
    Route::get('/infusionsoft-settings-promotions', 'App\Http\Controllers\Web\InfusionsoftSettingsController@getPromotions');
    Route::resource('/adminsettings', 'App\Http\Controllers\Web\SettingsController');
    Route::post('/hide-product', 'App\Http\Controllers\Web\InfusionsoftSettingsController@hideShowInfusionSoftProduct');
    Route::post('/save-sales-tax', 'App\Http\Controllers\Web\InfusionsoftSettingsController@saveSalesTax');

    Route::get('/view-video/{id}', 'App\Http\Controllers\VideoStreamController@streamVideo');
    Route::get('/view-video-compress/{id}/{resolution}', 'App\Http\Controllers\VideoStreamController@streamVideo');
    Route::post('/add-music-to-band-album', 'App\Http\Controllers\Web\BandAlbumController@addMusicToBandAlbum');
    Route::post('/add-multiple-music-to-band-album', 'App\Http\Controllers\Web\BandAlbumController@addMultipleMusicToBandAlbum');
    Route::post('/remove-music-to-band-album', 'App\Http\Controllers\Web\BandAlbumController@removeMusicToBandAlbum');
    Route::post('/update-music-track-sequence', 'App\Http\Controllers\Web\BandAlbumController@updateMusicTrackSequence');
    Route::post('/add-artist-to-song', 'App\Http\Controllers\Web\MusicDetailController@addArtistToSong');
    Route::post('/add-author-to-song', 'App\Http\Controllers\Web\MusicDetailController@addAuthorToSong');
    Route::post('/remove-author-to-song', 'App\Http\Controllers\Web\MusicDetailController@removeAuthorToSong');
    Route::post('/remove-artist-to-song', 'App\Http\Controllers\Web\MusicDetailController@removeArtistToSong');
    Route::resource('subscriber-metrics', 'App\Http\Controllers\Web\SubscriberMetricsController');
    Route::post('/get-royalty-info-list', 'App\Http\Controllers\Web\SubscriberMetricsController@getRoyaltyList');
    Route::post('/get-royalty-details', 'App\Http\Controllers\Web\SubscriberMetricsController@getRoyaltyDetails');
    Route::post('/get-ip-details', 'App\Http\Controllers\Web\SubscriberMetricsController@getIPAddressDetails');
    Route::get('/get-all-subscribers', 'App\Http\Controllers\Web\SubscriberMetricsController@getAllSubscribers');
    Route::resource('nmipayments', 'App\Http\Controllers\Web\NmipaymentsController');
    Route::resource('nmitransactions', 'App\Http\Controllers\Web\NmitransactionsController');
    Route::get('/get-nmitransactions-by-user', 'App\Http\Controllers\Web\NmitransactionsController@getTransactionByUser');
    Route::get('/get-users-dont-have-recurring', 'App\Http\Controllers\Web\NmitransactionsController@getUserDontHaveRecurring');

    Route::get('/music-detail', 'App\Http\Controllers\Web\MusicDetailController@getSongs');
    Route::post('/get-music-author-artist-list', 'App\Http\Controllers\Web\MusicDetailController@getMusicAuthorArtistList');
    Route::post('/process-multiple-authors-artists-to-song', 'App\Http\Controllers\Web\MusicDetailController@processMultipleAuthorsArtistsToSong');
    Route::post('/get-music-list-for-author', 'App\Http\Controllers\Web\AuthorController@getSongList');
    Route::post('/process-multiple-songs-to-author', 'App\Http\Controllers\Web\AuthorController@processMultipleSongsToAuthor');
    Route::post('/get-music-list-for-artist', 'App\Http\Controllers\Web\ArtistController@getSongList');
    Route::post('/process-multiple-songs-to-artist', 'App\Http\Controllers\Web\ArtistController@processMultipleSongsToArtist');
    Route::post('/get-music-list-for-album', 'App\Http\Controllers\Web\BandAlbumController@getSongList');
    Route::post('/process-multiple-songs-to-album', 'App\Http\Controllers\Web\BandAlbumController@processMultipleSongsToAlbum');
    Route::get('/get-albums', 'App\Http\Controllers\Web\BandAlbumController@getAlbums');
    Route::get('/get-album-songs', 'App\Http\Controllers\Web\BandAlbumController@getAlbumSongs');
    Route::get('/get-sheet-musics', 'App\Http\Controllers\Web\SheetMusicController@getSheetMusics');
    Route::get('/get-instrumentals', 'App\Http\Controllers\Web\InstrumentalController@getInstrumentals');
    Route::get('/get-podcasts', 'App\Http\Controllers\Web\PodcastController@getPodcasts');
    Route::get('/get-videos', 'App\Http\Controllers\Web\VideoDetailController@getVideos');
    Route::get('/get-videos/{id}', 'App\Http\Controllers\Web\VideoDetailController@paginateVideos');

    Route::post('update_credential', 'App\Http\Controllers\Web\UserController@updateCredential');
    //Route::post('cancel-subscription', 'Web\UserController@cancelSubscription');
    Route::post('cancel-subscription', 'App\Http\Controllers\Web\NmiUserSettingsController@cancelSubscription');
    Route::resource('loginhistory', 'App\Http\Controllers\Web\ApploginhistoriesController');
    Route::resource('appactivitylog', 'App\Http\Controllers\Web\AppactivitylogController');
    Route::resource('users-cancelled', 'App\Http\Controllers\Web\UserscancelledController');
});



Route::get('/payment', function () {
    return view('payment.index');
});

Auth::routes();
Route::post('/do_login', [App\Http\Controllers\Auth\LoginController::class, 'doLogin']);
//Route::post('update_credential', 'Web\UserController@updateCredential');
Route::post('/send-email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendTemporaryPassword']);
//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});

Route::get('/terms-of-service', function () {
    return view('terms-of-service');
});

//Route::any('/register', [App\Http\Controllers\Web\CheckoutController::class, 'subscriptionpage']);
Route::any('/register', function() {
    return redirect('/');
});

Route::get('/m-checkout/{periodic}', [App\Http\Controllers\Web\CheckoutController::class, 'subscriptionpage']);
Route::get('/m-checkout-next/{periodic}', [App\Http\Controllers\Web\CheckoutController::class, 'secondstep']);
Route::get('/m-checkout-last', [App\Http\Controllers\Web\CheckoutController::class, 'downloadstep']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
