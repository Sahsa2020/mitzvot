<?php
/**
* CurrencytController
* Contains these methods
* getInfo, getAgeGroupData, updateCurrencyt, getAdminUsers
*/

namespace App\Api\V1\Controllers;

use Illuminate\Support\Facades\Auth;
use Config;
use Validator;
use App\User;
use App\Models\Box;
use App\Models\Currencyt;
use App\Models\Deposit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CurrencytController extends BaseController
{
    /**
    * Get General Information
    * Life Leader, Daily Leader, Daily Organization Leader, Currencyt Info
    * @param Request $request
    * @return Array for JSON Response
    */
    public function getInfo(Request $request){
        $res['success'] = true;
        // life Leader
        $base_query = DB::table('users')
                      ->join('role_user', function($join){
                            $join->on('users.id', '=', 'role_user.user_id');
                      })
                      ->join('roles', function($join){
                            $join->on('roles.id', '=', 'role_user.role_id');
                      })
                      ->join('cbox_boxes', function($join){
                            $join->on('users.id', '=', 'cbox_boxes.user_id');
                      })
                      ->leftjoin('cbox_deposits', function($join){
                            $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
                      })
                      ->leftjoin('cbox_currencyts', function($join){
                            $join->on('cbox_deposits.currencyt', '=', 'cbox_currencyts.currencyt');
                      })
                      // ->where('cbox_deposits.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->where('cbox_boxes.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->where('users.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->select('users.id', 'users.name', 'users.address', 'users.country', 'users.age', 'users.image_url', DB::raw('count(cbox_deposits.id) as deposit_count'), DB::raw('sum(cbox_deposits.amount*cbox_currencyts.rate*(CASE cbox_deposits.del_flg WHEN 0 THEN 1 ELSE 0 END)) as amount'), DB::raw('count(Distinct cbox_boxes.id) as box_count'))
                      ->groupby('users.id')
                      ->orderby('deposit_count', 'DESC');
        $indiviual_query = $base_query->where('roles.name', '='  , config('constants.INDIVIDUAL_USER'));
        $users = $indiviual_query->get();
        $users = $this->getAgeGroupData($users);
        $lifeLeader = array();
        foreach ($users as $user) {
          if (!is_null($user)) {
            $user->image_url = $user->image_url?url('/').$user->image_url:"";
          }
          $lifeLeader[] = $user;
        }
        $res['lifeLeader'][] = $lifeLeader;

        $base_query = DB::table('users')
                      ->join('role_user', function($join){
                            $join->on('users.id', '=', 'role_user.user_id');
                      })
                      ->join('roles', function($join){
                            $join->on('roles.id', '=', 'role_user.role_id');
                      })
                      ->leftjoin('cbox_boxes', function($join){
                            $join->on('users.id', '=', 'cbox_boxes.user_id');
                      })
                      ->leftjoin('cbox_deposits', function($join){
                            $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
                      })
                      ->leftjoin('cbox_currencyts', function($join){
                            $join->on('cbox_deposits.currencyt', '=', 'cbox_currencyts.currencyt');
                      })
                      // ->where('cbox_deposits.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->where('cbox_boxes.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->where('users.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->select('users.id', 'users.name', 'users.address', 'users.country', 'users.age', 'users.image_url', DB::raw('count(cbox_deposits.id) as deposit_count'), DB::raw('sum(cbox_deposits.amount*cbox_currencyts.rate*(CASE cbox_deposits.del_flg WHEN 0 THEN 1 ELSE 0 END)) as amount'), DB::raw('count(Distinct cbox_boxes.id) as box_count'))
                      ->groupby('users.id')
                      ->orderby('box_count', 'DESC');
        $indiviual_query = $base_query->where('roles.name', '='  , config('constants.INDIVIDUAL_USER'));
        $users = $indiviual_query->get();
        $lifeLeader = array();
        foreach ($this->getAgeGroupData($users) as $user) {
          if (!is_null($user)) {
            $user->image_url = $user->image_url?url('/').$user->image_url:"";
          }
          $lifeLeader[] = $user;
        }
        $res['lifeLeader'][] = $lifeLeader;

        // daily Leader
        $date = date("Y-m-d");
        $base_query = DB::table('users')
                      ->join('role_user', function($join){
                            $join->on('users.id', '=', 'role_user.user_id');
                      })
                      ->join('roles', function($join){
                            $join->on('roles.id', '=', 'role_user.role_id');
                      })
                      ->leftjoin('cbox_boxes', function($join){
                            $join->on('users.id', '=', 'cbox_boxes.user_id');
                      })
                      ->where('cbox_boxes.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->where('users.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->select('users.id', 'users.name', 'users.address', 'users.country', 'users.age', 'users.image_url', DB::raw('sum(cbox_boxes.d_count) as deposit_count'), DB::raw('count(Distinct cbox_boxes.id) as box_count'))
                      ->groupby('users.id')
                      ->orderby('deposit_count', 'DESC');
        $indiviual_query = $base_query->where('roles.name', '='  , config('constants.INDIVIDUAL_USER'));
        $users = $indiviual_query->get();
        $dailyLeader = array();
        foreach ($this->getAgeGroupData($users) as $user) {
          if (!is_null($user)) {
            $user->image_url = $user->image_url?url('/').$user->image_url:"";
          }
          $dailyLeader[] = $user;
        }
        $res['dailyLeader'][] = $dailyLeader;

        $base_query = DB::table('users')
                      ->join('role_user', function($join){
                            $join->on('users.id', '=', 'role_user.user_id');
                      })
                      ->join('roles', function($join){
                            $join->on('roles.id', '=', 'role_user.role_id');
                      })
                      ->leftjoin('cbox_boxes', function($join){
                            $join->on('users.id', '=', 'cbox_boxes.user_id');
                      })
                      ->where('cbox_boxes.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->where('users.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->select('users.id', 'users.name', 'users.address', 'users.country', 'users.age', 'users.image_url', DB::raw('count(Distinct cbox_boxes.id) as box_count'))
                      ->groupby('users.id')
                      ->orderby('box_count', 'DESC');
        $indiviual_query = $base_query->where('roles.name', '='  , config('constants.INDIVIDUAL_USER'));
        $users = $indiviual_query->get();
        $dailyLeader = array();
        foreach ($this->getAgeGroupData($users) as $user) {
          if (!is_null($user)) {
            $user->image_url = $user->image_url?url('/').$user->image_url:"";
          }
          $dailyLeader[] = $user;
        }
        $res['dailyLeader'][] = $dailyLeader;

      // daily organization Leader
        $base_query = DB::table('users')
                      ->join('role_user', function($join){
                            $join->on('users.id', '=', 'role_user.user_id');
                      })
                      ->join('roles', function($join){
                            $join->on('roles.id', '=', 'role_user.role_id');
                      })
                      ->leftjoin('cbox_boxes', function($join){
                            $join->on('users.id', '=', 'cbox_boxes.user_id');
                      })
                      ->where('cbox_boxes.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->where('users.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->select('users.id', 'users.name', 'users.address', 'users.country', 'users.age', 'users.image_url', DB::raw('sum(cbox_boxes.d_count) as deposit_count'), DB::raw('count(Distinct cbox_boxes.id) as box_count'))
                      ->groupby('users.id')
                      ->orderby('deposit_count', 'DESC');
        $indiviual_query = $base_query->where(function($query){
                                    $query->orwhere('roles.name', config('constants.SCHOOL_USER'));
                                    $query->orwhere('roles.name', config('constants.INSTITUTION_USER'));
                                });
        $users = $indiviual_query->get();
        $dailyLeader = array();
        foreach ($this->getAgeGroupData($users) as $user) {
          if (!is_null($user)) {
            $user->image_url = $user->image_url?url('/').$user->image_url:"";
          }
          $dailyLeader[] = $user;
        }
        $res['organizations'][] = $dailyLeader;

        $base_query = DB::table('users')
                      ->join('role_user', function($join){
                            $join->on('users.id', '=', 'role_user.user_id');
                      })
                      ->join('roles', function($join){
                            $join->on('roles.id', '=', 'role_user.role_id');
                      })
                      ->leftjoin('cbox_boxes', function($join){
                            $join->on('users.id', '=', 'cbox_boxes.user_id');
                      })
                      ->where('cbox_boxes.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->where('users.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->select('users.id', 'users.name', 'users.address', 'users.country', 'users.age', 'users.image_url', DB::raw('count(Distinct cbox_boxes.id) as box_count'))
                      ->groupby('users.id')
                      ->orderby('box_count', 'DESC');
        $indiviual_query = $base_query->where(function($query){
                                    $query->orwhere('roles.name', config('constants.SCHOOL_USER'));
                                    $query->orwhere('roles.name', config('constants.INSTITUTION_USER'));
                                });
        $users = $indiviual_query->get();
        $dailyLeader = array();
        foreach ($this->getAgeGroupData($users) as $user) {
          if (!is_null($user)) {
            $user->image_url = $user->image_url?url('/').$user->image_url:"";
          }
          $dailyLeader[] = $user;
        }
        $res['dailyLeader'][] = $dailyLeader;


        //Currencyt info
        $currencytInfo = Currencyt::select('currencyt', 'country_name')->get();
        $res['currencyInfo'] = $currencytInfo;

        $dailyTotal = Box::where('del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->select(DB::raw('sum(d_count) as count'))->first()['count'] * 1;
        $res['daily_total'] = $dailyTotal;

        $lifeTotal = Deposit::count();
        $res['life_total'] = $lifeTotal;

        $cboxTotal = Box::where('del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                        ->count();
        $res['cbox_total'] = $cboxTotal;

        return $res;
    }

    /**
    * Get Age Group User -- It splits users into each age group array.
    * @param User $users
    * @return Array for users
    */
    private function getAgeGroupData($users){
      $res = array();
      foreach (config('constants.AGE_ITEM_ARRAY') as $ageItem) {
        $ageUser = null;
        foreach ($users as $user) {
          if ($user->age == $ageItem) {
            $ageUser = $user;
            break;
          }
        }
        if (!is_null($ageUser)) {
          $res[] = $ageUser;
        }
      }
      return $res;
    }

    /**
    * Update Currencyt Information -- From api.fixer.io
    * No @param
    * @return Array for JSON Response
    */
    public function updateCurrencyt(){
      $curl = curl_init();
      // Set some options - we are passing in a useragent too here
      curl_setopt_array($curl, array(
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => 'http://api.fixer.io/latest?base=USD',
          CURLOPT_USERAGENT => 'Codular Sample cURL Request'
      ));
      // Send the request & save response to $resp
      $resp = curl_exec($curl);
      // Close request to clear up some resources
      curl_close($curl);
      $rates = json_decode($resp, TRUE)['rates'];
      foreach ($rates as $key => $value) {
        $currencyt = Currencyt::where('currencyt', $key)->first();
        if ($currencyt === null) {
          $new['currencyt'] = $key;
          $new['rate'] = 1/$value;
          Currencyt::unguard();
          Currencyt::create($new);
          Currencyt::reguard();
        } else {
          $currencyt['rate'] = 1/$value;
          $currencyt->save();
        }
      }
      $res['success'] = true;
      return $rates;
    }

    /**
    * Returns Admin Users
    * No @param
    * @return Array for JSON Response
    */
    public function getAdminUsers(){
      // Admin users
        $admins = User::join('role_user', function($join){
                  $join->on('users.id', '=', 'role_user.user_id');
                })
                ->join('roles', function($join){
                  $join->on('roles.id', '=', 'role_user.role_id');
                })
                ->select('users.name', 'users.image_url', 'users.id')
                ->where('roles.name', '=', config('constants.ADMIN_USER'))
                ->get();
        $res['admins'] = $admins;
        $res['success'] = true;
        return $res;
    }
}
