<?php

namespace App\Api\V1\Controllers;

use Auth;
use Config;
use DB;
use App\User;
use App\Models\Box;
use App\Models\Sponsor;
use App\Models\State;
use App\Models\City;
use App\Models\District;
use App\Models\Country;
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
        $data = $request->only(['search_id', 'search_type']);
        // $validator = Validator::make($data, [
        //     'search_id' => 'required',
        //     'search_type' => 'required|numeric',
        // ]);
        // if ($validator->fails()) {
        //     $res['success'] = false;
        //     $res['message'] = "The data is not correct.";
        //     return $res;            
        // }

        // 1 -- country
        // 2 -- city
        // 3 -- state
        // 4 -- district

        $query = Sponsor::where('sponsors.del_flg', '<>', config('constants.ITEM_IS_DELETE'));

        if ($data['search_type'] == 1) {

        }        
        if ($data['search_type'] == 2) {
            
        }
        if ($data['search_type'] == 3) {
            $query->where('state_id', '=', $data['search_id']);
        }
        if ($data['search_type'] == 4) {
            $query->where('district_id', '=', $data['search_id']);
        }

        $query
                ->leftJoin('users', 'sponsors.user_id', '=', 'users.id')
                ->leftJoin('cities', 'cities.id', '=', 'sponsors.city_id')
                ->leftJoin('districts', 'districts.id', '=', 'sponsors.district_id')
                ->select('cities.name as city_name', 'districts.name as district_name', 'districts.population', 'districts.unit', 'districts.cost_assumption', 'districts.profit_assumption', 'users.name', 'sponsors.id');
      
        $sponsors = $query->get();
    
        $res['success'] = true;
        $res['data'] = $sponsors;
        return $res;
    }

    // public function getAll(Request $request)
    // {
    //     $data = $request->only(['search', 'start', 'length']);
    //     $validator = Validator::make($data, [
    //         'start'       => 'required|numeric',
    //         'length'      => 'required|numeric'
    //       ]);          
    //     if ($validator->fails()) {
    //     $res["success"] = false;
    //     $res["message"] = "The data is not correct.";
    //     return $res;
    //     }

    //     $data['search'] = is_null($data['search'])?"":$data['search'];

    //     $total_query = User::
    //                 where('del_flg', '<>', config('constants.ITEM_IS_NONE'))
    //                 ->where('name', 'LIKE', '%'.$data['search'].'%')
    //                 ->select('name', 'school', 'id');

    //     $total = count($total_query->get());                    
    //     $users = $total_query->offset($data['start'])->take($data['length'])->get();

    //     $res['data'] = $users;
    //     $res['success'] = true;
    //     $res['total_user_count'] = $total;
    //     return $res;
    // }

    public function getCountries(Request $request)
    {
      $search = $request->input('search');
      $sponsors = Country::
                          where('del_flg', '<>', config('constants.ITEM_IS_DELETE'))
                          ->get();
    
      $res['success'] = true;
      $res['data'] = $sponsors;
      return $res;
    }

    public function getTotalCountries(Request $request)
    {
      $search = $request->input('search');
      $sponsors = DB::table('countries')
                        ->select('id', 'name')
                        ->get();
    
      $res['success'] = true;
      $res['data'] = $sponsors;
      return $res;
    }

    public function addCountry(Request $request) 
    {
        $data = $request->only(['id']);
        $validator = Validator::make($data, [
                'id' => 'required',                      
            ]);
        if ($validator->fails()) {
            $res['success'] = false;
            $res['message'] = "The data is not correct.";
            return $res;
        }        

        $country = Country::find($data['id']);
        $country->del_flg = false;
        $country->save();

        // $data_added = Country::where('del_flg', '<>', config('constants.ITEM_IS_DELETE'))
        //              ->get();

        // $data_total = DB::table('countries')
        //             ->select('id', 'name')
        //             ->get();
  
        $res['success'] = true;
        // $res['total'] = $data_total;
        // $res['added'] = $data_added;
        return $res;
    }

    public function deleteCountry(Request $request) 
    {
        $data = $request->only(['id']);
        $validator = Validator::make($data, [
                'id' => 'required',                      
            ]);
        if ($validator->fails()) {
            $res['success'] = false;
            $res['message'] = "The data is not correct.";
            return $res;
        }


        $data = Country::find($data['id']);
        $data->del_flg = true;
        $data->save();
  
        $res['success'] = true;
        return $res;
    }

    public function getStates(Request $request)
    {
      $search = $request->input('country');
      $states = DB::table('states')
                        ->where('country_id', '=', $search)
                        ->select('id', 'name', 'country_id')                        
                        ->get();
    
      $res['success'] = true;
      $res['data'] = $states;
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
