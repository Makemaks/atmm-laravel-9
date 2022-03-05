<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

function isPaidSubscriber() {
    $user = Auth::user();
    if( $user ) {
        if( $user->subscription_status == 'PAID' && $user->isAdmin == 0 ) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function isNewSubscriber() {
    $is_new_subscriber_user = Session::pull('new_subscriber_user');
    return $is_new_subscriber_user;
}

function generateQueryUrl($sortBy) {
    $queryStrings = [];

    if (Request::has('search')) {
        $queryStrings['search'] = Request::input('search');
    }

    if (Request::has('page')) {
        $queryStrings['page'] = Request::input('page');
    }

    $queryStrings['sortBy'] = $sortBy;

    if (Request::has('sortOrder')) {
        if (Request::input('sortBy') === $sortBy && Request::input('sortOrder') === 'ASC') {
            $queryStrings['sortOrder'] = 'DESC';
        } else {
            $queryStrings['sortOrder'] = 'ASC';
        }
    } else {
        $queryStrings['sortOrder'] = 'DESC';
    }


    return '?' .http_build_query($queryStrings, null, '&', PHP_QUERY_RFC3986);
}