<?php

namespace App\Api\V1\Controllers;

use Config;
use App\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class BaseController extends Controller
{
    use Helpers;

    protected $currentUser;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        //
    }

    public function getCurrentUser(){
        if (Auth::check()) {
            return Auth::user();
        }
        return [];
    }

}
