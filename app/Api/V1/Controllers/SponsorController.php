<?php

namespace App\Api\V1\Controllers;

use Auth;
use Config;
use DB;
use App\User;
use App\Models\Box;
use App\Models\Sponsor;
use App\Models\MemberBox;
use App\Models\Follow;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Donate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SponsorController extends BaseController
{

    /**
    * Get current user's profile
    * 
    * @param Request $request
    * @return Array for JSON Response
    */

    public function addSponsor(Request $request) 
    {
        $data = $request->only(['country', 'box_count', 'state', 'city']);
        $validator = Validator::make($data, [
                'country' => 'required',
                'box_count' => 'required|numeric',
                'state' => 'required',
                'city' => 'required',             
            ]);
        if ($validator->fails()) {
            $res['success'] = false;
            $res['message'] = "The data is not correct.";
            return $res;
        }

        $data['user_id'] = Auth::user()->id;
        $data['country'] = $data['country'];
        $data['box_count'] = $data['box_count'];
        $data['state'] = $data['state'];
        $data['city'] = $data['city'];
        Sponsor::unguard();
        $sponsor = Sponsor::create($data);
        Sponsor::reguard();
  
        $res['success'] = true;
        return $res;
    }

    public function getSponsors(Request $request)
    {
      $search = $request->input('search');

      $sponsors = Sponsor::
                          where('country', 'LIKE', '%'.$search.'%')                                                
                          ->get();
    
      $res['success'] = true;
      $res['data'] = $sponsors;
      return $res;
    }

    public function Find(Request $request)
    {
      $sponsor = Sponsor::
                          where('user_id', '=', Auth::user()->id) 
                          ->get();
      
      $res['success'] = true;
      $res['data'] = $sponsor;
      return $res;
    }

    public function Update(Request $request){
        
        $data = $request->only(['country', 'box_count', 'state', 'city']);
        $validator = Validator::make($data, [
                'country' => 'required',
                'box_count' => 'required|numeric',
                'state' => 'required',
                'city' => 'required',             
            ]);
        if ($validator->fails()) {
            $res['success'] = false;
            $res['message'] = "The data is not correct.";
            return $res;
        }

        $sponsor = Sponsor::where('user_id', '=', '');
        $sponsor['user_id'] = Auth::user()->id;
        $data['country'] = $data['country'];
        $data['box_count'] = $data['box_count'];
        $data['state'] = $data['state'];
        $data['city'] = $data['city'];
        $sponsor->save();       
        
        $res['success'] = true;
        $res['data'] = $user;
        return $res;
    }
}
