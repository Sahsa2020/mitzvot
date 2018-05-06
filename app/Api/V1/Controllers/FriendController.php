<?php

namespace App\Api\V1\Controllers;

use Auth;
use Config;
use DB;
use App\User;
use App\Models\Box;
use App\Models\MemberBox;
use App\Models\Follow;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Donate;
use App\Models\Friend;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class FriendController extends BaseController
{
    public function getFriends(Request $request)
    {
      $search = $request->input('search');

      // $dCountTable = DB::raw("(
      //     SELECT users.id as user_id, sum(cbox_boxes.d_count) as d_count, roles.name as role FROM users
      //     join role_user on (role_user.user_id = users.id)
      //     join roles on (roles.id = role_user.role_id)
      //     LEFT JOIN cbox_boxes ON (cbox_boxes.user_id = users.id and cbox_boxes.del_flg <> " . config('constants.ITEM_IS_DELETE') .")
      //     GROUP BY users.id
      //     ) as user_d_counts
      // ");

      $allFriends = Friend::
                          where('friends.user_id', '=', Auth::user()->id)
                          ->join('users', function($join) {
                              $join->on('friends.friend_id', '=', 'users.id');
                          })
                          ->where('users.name', 'LIKE', '%'.$search.'%')
                          ->leftjoin('cbox_boxes', function($join) {
                              $join->on('friends.friend_id', '=', 'cbox_boxes.user_id');
                          })
                          // ->leftjoin($dCountTable, 'users.id', '=', 'user_d_counts.user_id')
                          // ->select(DB::raw('sum(cbox_boxes.d_count) as deposit_count'))
                          // ->where('cbox_boxes.user_id', 'friends.friend_id')
                          // ->orderByDesc('friends.created_at')
                          ->select('image_url', 'name', 'city', 'device_id', 'friend_id')
                          ->get();


      // if ($allFriends['image_url'] == "") {
      //   $allFriends['image_url'] ="/assets/global/img/default_avatar.jpg";
      // }

      // $allFriends['image_url'] = is_null($allFriends['image_url'])?"":$allFriends['image_url'];
      // $friends = $allFriends;
      // $friends['temp'] = 'temp';
      // $daily_count = Friend::where('friends.user_id', '=', Auth::user()->id)
      //                     ->join('users', function($join) {
      //                         $join->on('friends.friend_id', '=', 'users.id');
      //                     })
      //                     ->select(DB::raw('sum(cbox_boxes.d_count) as deposit_count'))
      //                     ->where('cbox_boxes.user_id', 'friends.friend_id')
      //                     ->first()->deposit_count;
      
      $res['success'] = true;
      $res['data'] = $allFriends;
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
