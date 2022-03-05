<?php

namespace App\Http\Controllers;

use Google2FA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     *
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     *
     */
    public function index()
    {
        $user = Session::get('user');
        if(!$user) {
            return view('welcome');
        } else {
            if ($user->isAdmin) {
                return Redirect::to('dashboard');
            } else {
                return Redirect::to('videos');
            }
        }
    }


    public function super()
    {
        Google2FA::setQRCodeBackend('svg');
        $qr_code_source = Google2FA::getQRCodeInline(
            'All Things Michael Mclean',
            'admin@songwriter.com',
            'JA5F4SKHZIFA25GW'
        );

        //echo $qr_code_source;
        return view('super', [
            'qr_code_source' => $qr_code_source
        ]);
    }
}
