<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function activateUser(Request $request){
      $req = $request->input();
      $user = User::where('email', '=', $req['email'])->where('activation_token', '=', $req['token'])->first();
      if ($user === null) { // don't match token
        return redirect('/#/activateAccount;success=false');
      }
      $user['status'] = config('constants.VERIFIED_USER');
      $user->save();
      return redirect('/#/activateAccount;success=true');
    }
    
    public function privatePolicy(Request $request){
        return view('policy');
    }
}
