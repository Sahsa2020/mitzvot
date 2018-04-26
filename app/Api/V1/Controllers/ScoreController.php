<?php

namespace App\Api\V1\Controllers;

use Auth;
use Config;
use Validator;
use App\User;
use App\Models\Box;
use App\Models\Follow;
use App\Models\MessageHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Mail;

class ScoreController extends BaseController
{
    /**
    * Get Scores
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function getScore(Request $request){
        $data = $request->only(['sort_item','sort_rule', 'search', 'start', 'length', 'filter']);

        $validator = Validator::make($data, [
          'start'       => 'required|numeric',
          'length'      => 'required|numeric'
        ]);

        if ($validator->fails()) {
          $res["success"] = false;
          $res["message"] = "The data is not correct.";
          return $res;
        }

        $data['sort_item'] = is_null($data['sort_item'])?"score":$data['sort_item'];
        $data['sort_rule'] = is_null($data['sort_rule'])?"DESC":$data['sort_rule'];

        if (!(strtolower($data['sort_item']) == strtolower('id') ||
              strtolower($data['sort_item']) == strtolower('name') ||
              strtolower($data['sort_item']) == strtolower('type') ||
              strtolower($data['sort_item']) == strtolower('score') ||
              strtolower($data['sort_item']) == strtolower('country') ||
              strtolower($data['sort_item']) == strtolower('city') ||
              strtolower($data['sort_item']) == strtolower('school') ||
              strtolower($data['sort_item']) == strtolower('deposit_count') ||
              strtolower($data['sort_item']) == strtolower('daily_count') ||
              strtolower($data['sort_item']) == strtolower('box_count'))) {
                $res["success"] = false;
                $res["message"] = "Invalid Sort Item. Valid Item: id, name, type, score, box_count, country, city, school, deposit_count.";
                return $res;
        }

        if (!(strtolower($data['sort_rule']) == strtolower('desc') ||
              strtolower($data['sort_rule']) == strtolower('asc'))) {
                $res["success"] = false;
                $res["message"] = "Invalid Sort Rule. Valid Item: DESC, ASC.";
                return $res;
        }

        $data['search'] = is_null($data['search'])?"":$data['search'];
        $data['filter'] = is_null($data['filter'])?"":$data['filter'];
        if($data['filter'] == 'name')
          $data['filter'] = 'users.name';
        $dCountTable = DB::raw("(
            SELECT users.id as user_id, sum(cbox_boxes.d_count) as d_count, roles.name as role FROM users
            join role_user on (role_user.user_id = users.id)
            join roles on (roles.id = role_user.role_id)
            LEFT JOIN cbox_boxes ON (cbox_boxes.user_id = users.id and cbox_boxes.del_flg <> " . config('constants.ITEM_IS_DELETE') .")
            GROUP BY users.id
            ) as user_d_counts
        ");
        $curuser_id = -1;
        if(Auth::check()){
          $user = Auth::user();
          $curuser_id = $user->id;
          //Following users
          $base_query = DB::table('users')
                      ->join('role_user', function($join){
                            $join->on('users.id', '=', 'role_user.user_id');
                      })
                      ->join('roles', function($join){
                            $join->on('roles.id', '=', 'role_user.role_id');
                      })
                      ->join('cbox_following', function($join) use($user){
                        $join->on('cbox_following.follow_user_id', '=', 'users.id');
                        $join->where('cbox_following.user_id', '=', $user->id);
                      })
                      ->leftjoin('cbox_boxes', function($join){
                        $join->on('users.id', '=', 'cbox_boxes.user_id');
                        $join->where('cbox_boxes.del_flg', '<>' , config('constants.ITEM_IS_DELETE'));
                      })
                      ->leftjoin('cbox_deposits', function($join){
                            $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
                      })
                      ->leftjoin('cbox_currencyts', function($join){
                            $join->on('cbox_deposits.currencyt', '=', 'cbox_currencyts.currencyt');
                      })
                      // ->where('cbox_deposits.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->where('users.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->where('roles.name', '<>', config('constants.ADMIN_USER'))
                      ->select('users.id', 'users.name', 'users.address', 'users.country', 'users.age', 'users.image_url', DB::raw('count(cbox_deposits.id) as deposit_count'), DB::raw('count(Distinct cbox_boxes.id) as box_count'))
                      ->groupby('users.id');
          $follow_users = $base_query->get();
          foreach ($follow_users as $follower) {
            $follower->image_url = $follower->image_url?url('/').$follower->image_url:"";
          }
          $res['data']['following'] = $follow_users;
        }
        $total_query = DB::table('users')
                      ->join('role_user', function($join){
                            $join->on('users.id', '=', 'role_user.user_id');
                      })
                      ->join('roles', function($join){
                            $join->on('roles.id', '=', 'role_user.role_id');
                      })
                      ->leftjoin('cbox_boxes', function($join){
                            $join->on('users.id', '=', 'cbox_boxes.user_id')->where('cbox_boxes.del_flg', '<>', config('constants.ITEM_IS_DELETE'));
                      })
                      ->leftjoin('cbox_deposits', function($join){
                            $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
                      })
                      ->leftjoin('cbox_currencyts', function($join){
                            $join->on('cbox_deposits.currencyt', '=', 'cbox_currencyts.currencyt');
                      })
                      ->leftjoin($dCountTable, 'users.id', '=', 'user_d_counts.user_id')
                      ->leftjoin('cbox_following', function($join) use($curuser_id){
                          $join->on('cbox_following.follow_user_id', '=', 'users.id');
                          $join->where('cbox_following.user_id', '=', $curuser_id);
                      })
                      ->where('users.del_flg', '<>' , config('constants.ITEM_IS_DELETE'))
                      ->where('roles.name', '<>', config('constants.ADMIN_USER'))
                      ->select(
                                'users.id',
                                'users.name as name',
                                'users.email',
                                'users.age',
                                'users.school',
                                'users.address',
                                'users.city',
                                'users.country',
                                'users.birthday',
                                'users.image_url',
                                'user_d_counts.d_count as daily_count',
                                'user_d_counts.role',
                                DB::raw('!ISNULL(cbox_following.id) as is_current_user_following'),
                                DB::raw('count(cbox_deposits.id) as deposit_count'),
                                DB::raw('sum(cbox_deposits.amount*cbox_currencyts.rate*(CASE cbox_deposits.del_flg WHEN 2 THEN 0 ELSE 1 END)) as score'),
                                DB::raw('count(Distinct cbox_boxes.id) as box_count'))
                      ->groupby('users.id')
                      ->orderby($data['sort_item'], $data['sort_rule'])
                      ->where($data['filter'], 'LIKE', '%'.$data['search'].'%');
        $total = count($total_query->get());

        $users = $total_query->offset($data['start'])->take($data['length'])->get();

        $res['success'] = true;
        $res['data']['total'] = $total;
        $res['data']['data'] = $users;
        return $res;
    }
    
    /**
    * Get Server Time
    * 
    * @param 
    * @return Array for JSON Response
    */
    public function getTime(){
        $time =  DB::select(DB::raw("SELECT  DATE_FORMAT(now(), '%b, %d, %Y') as date, TIME_TO_SEC(CURRENT_TIME()) AS time"));
        $res['success'] = true;
        $res['data']['date'] = $time[0]->date;
        $res['data']['time'] = $time[0]->time;
        return $res;
    }

    /**
    * Send Contact Message to admin users
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function sendContactMessage(Request $request){
      //Send mails to Admins. 
      $contact = $request->input();
      $data = array('contact'=>$contact);
      $mail_to = [];
      $admins = User::join('role_user', function($join){
                  $join->on('users.id', '=', 'role_user.user_id');
                })
                ->join('roles', function($join){
                  $join->on('roles.id', '=', 'role_user.role_id');
                })
                ->where('roles.name', '=', config('constants.ADMIN_USER'))
                ->get();
      foreach($admins as $admin){
        $mail_to[] = $admin->email;
      }
      MessageHistory::unguard();
      $message_history = MessageHistory::create($contact);
      MessageHistory::reguard();
      //To Admins
      Mail::send('mail/contact_mail', $data, function($message) use($message_history, $mail_to, $contact) {
         $message->to($mail_to, 'The user wants contact.')->subject
            ("MESSAGE".$message_history->id);
         $message->from($contact['email'],$contact['name']);
      });
      //To Customer
      Mail::send('mail/contact_mail', $data, function($message) use($message_history, $contact) {
         $message->to($contact['email'], 'The user wants contact.')->subject
            ("MESSAGE".$message_history->id);
         $message->from('noreply@milionmitzvot.com','MilionMitzvot');
      });
      $res['success'] = true;

      return $res;

    }
}
