<?php

namespace App\Api\V1\Controllers;

use Auth;
use Config;
use DB;
use App\User;
use App\Models\Box;
use App\Models\MemberBox;
use App\Models\Deposit;
use App\Models\Follow;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Donate;
use App\Models\Friend;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DateTime;


class FriendController extends BaseController
{
    public function getFriends(Request $request)
    {
      $search = $request->input('search'); 

      $allFriends = Friend::
                          where('friends.user_id', '=', Auth::user()->id)
                          ->join('users', function($join) {
                              $join->on('friends.friend_id', '=', 'users.id');
                          })
                          ->where('users.name', 'LIKE', '%'.$search.'%')
                          ->leftjoin('cbox_boxes', function($join) {
                              $join->on('friends.friend_id', '=', 'cbox_boxes.user_id');
                          })                         
                          ->select('image_url', 'name', 'city', 'device_id', 'friend_id')
                          ->get();

      $cur_date = new DateTime();
      $cur_date->format('Y-m-d H:i:s');

      $monthly = $cur_date->modify('-1 month');
      $daily = $cur_date->modify('-1 day');
      $weekly = $cur_date->modify('-1 week');
      
      foreach ($allFriends as $friend) {
              $daily_count = Deposit::
                                  where('user_id', '=', $friend['friend_id'])                                 
                                  ->whereBetween('cbox_deposits.created_at', [$daily, $cur_date])
                                  ->get();
                                  
              $weekly_count = Deposit::
                                  where('user_id', '=', $friend['friend_id'])
                                  ->whereBetween('cbox_deposits.created_at', [$weekly, $cur_date])
                                  ->get();


              $monthly_count = Deposit::
                                  where('user_id', '=', $friend['friend_id'])              
                                  ->whereBetween('cbox_deposits.created_at', [$monthly, $cur_date])
                                  ->get();

              $daily_[] = count($daily_count);
              $weekly_[] = count($weekly_count);
              $monthly_[] = count($monthly_count);
      }

      $res['success'] = true;
      $res['data'] = $allFriends;
      $res['daily'] = $daily_;
      $res['weekly'] = $weekly_;
      $res['monthly'] = $monthly_;    

      return $res;
    }


    public function addFriend(Request $request)
    {
      $data = $request->only(['friend_id']);
      $friend_id = $data['friend_id'];
      
      //check existing friends
      $users = Friend
                ::where('user_id', '=', Auth::user()->id)
                ->where('friend_id', '=', $friend_id);
    
      $count = count($users->get());
      if ($count > 0) {
        $res['success'] = false;
        $res['message'] = 'Please another friend';
        return $res;
      }                

      $friendData['user_id'] = Auth::user()->id;
      $friendData['friend_id'] = $friend_id;
      Friend::unguard();
      $friend = Friend::create($friendData);
      Friend::reguard();

      $res['success'] = true;
      return $res;
    }

    public function Remove(Request $request)
    {
      $data = $request->only(['friend_id']);
      $friend_id = $data['friend_id'];
      
      //check existing friends
      $friend = Friend
                ::where('user_id', '=', Auth::user()->id)
                ->where('friend_id', '=', $friend_id)
                ->first();  
             
      $friend->delete();
      $res['success'] = true;
      return $res;
    }
}
