<?php

namespace App\Api\V1\Controllers;

use Config;
use Validator;
use DB;
use Hash;
use App\User;
use App\Models\Box;
use App\Models\Member;
use App\Models\MemberBox;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Paypal;
use Mail;

class MemberController extends PaypalPaymentController
{
    /**
    * Get current user's member -- This means current user is organization.
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function Find(Request $request){
        $user = Auth::user();
        $members = DB::select(DB::raw("
        SELECT
          MAIN_TABLE.*,
          IFNULL(WEEKLY_TABLE.weekly_count, 0) AS weekly_count,
          IFNULL(MONTHLY_TABLE.monthly_count, 0) AS monthly_count,
          LIFE_TABLE.lifetime_count
        FROM
          (SELECT
            members.id,
            members.name,
            members.email,
            members.address,
            members.city,
            members.country,
            members.phone,
            members.goal_daily,
            members.goal_weekly,
            members.goal_monthly,
            m_boxes.device_id,
            sum(d_count) AS daily_count,
            GROUP_CONCAT(m_boxes.device_id SEPARATOR ',') as box_ids
          FROM users AS members
          LEFT JOIN cbox_member_boxes AS m_boxes ON m_boxes.member_id = members.id AND m_boxes.del_flg = 0
          LEFT JOIN cbox_boxes AS boxes ON m_boxes.device_id = boxes.device_id AND boxes.del_flg = 0
          WHERE members.parent_id = ".$user->id."
          GROUP BY members.id) AS MAIN_TABLE
        LEFT JOIN (
                (SELECT
                  users.id,
                  count(users.id) AS weekly_count
                  FROM
                  users
                  LEFT JOIN cbox_member_boxes ON cbox_member_boxes.member_id = users.id AND cbox_member_boxes.del_flg = 0
                  LEFT JOIN cbox_deposits ON cbox_deposits.device_id = cbox_member_boxes.device_id
                  WHERE cbox_deposits.created_at >= '".date('Y-m-d', strtotime('monday this week'))."'
                  GROUP BY users.id) as WEEKLY_TABLE
              ) ON MAIN_TABLE.id = WEEKLY_TABLE.id
        LEFT JOIN (
                (SELECT
                  users.id,
                  count(users.id) AS monthly_count
                  FROM
                  users
                  LEFT JOIN cbox_member_boxes ON cbox_member_boxes.member_id = users.id AND cbox_member_boxes.del_flg = 0
                  LEFT JOIN cbox_deposits ON cbox_deposits.device_id = cbox_member_boxes.device_id
                  WHERE cbox_deposits.created_at >= '".date('Y-m-01')."'
                  GROUP BY users.id) as MONTHLY_TABLE
              ) ON MAIN_TABLE.id = MONTHLY_TABLE.id
        LEFT JOIN (
                (SELECT
                  users.id,
                  count(users.id) AS lifetime_count
                  FROM
                  users
                  LEFT JOIN cbox_member_boxes ON cbox_member_boxes.member_id = users.id AND cbox_member_boxes.del_flg = 0
                  LEFT JOIN cbox_deposits ON cbox_deposits.device_id = cbox_member_boxes.device_id
                  GROUP BY users.id) as LIFE_TABLE
              ) ON MAIN_TABLE.id = LIFE_TABLE.id
        "));


        foreach ($members as $member) {
          if ($member->box_ids != null) {
              $member->boxes = explode(',', $member->box_ids);
              $member->amount = DB::table('cbox_deposits')
                                ->leftjoin('cbox_currencyts', function($join){
                                  $join->on('cbox_deposits.currencyt', '=', 'cbox_currencyts.currencyt');
                                })
                                ->where(function($query) use($member){
                                  foreach ($member->boxes as $box_id) {
                                    $query->orwhere('device_id', $box_id);
                                  }
                                })
                                ->select(DB::raw('cast(sum(cbox_deposits.amount*cbox_currencyts.rate*(CASE cbox_deposits.del_flg WHEN 2 THEN 0 ELSE 1 END)) as decimal(10,2)) as amount'))
                                ->first()->amount;
             if (is_null($member->amount)) $member->amount = 0;
          } else {
            $member->boxes = array();
            $member->amount = 0;
          }
          if ($member->daily_count == null) {
            $member->daily_count = 0;
          }
        }
        $res['success'] = true;
        $res['data'] = $members;
        return $res;
    }

    /**
    * Add member
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function Add(Request $request){
      $user = Auth::user();
      if(!$user->can('MEMBER_MANAGE')) {
          $res['success'] = false;
          $res['message'] = 'You have not permission to manage members.';
          return $res;
      }
      $MemberData = $request->only(['name', 'email', 'address', 'city', 'country','phone', 'goal_daily', 'goal_weekly', 'goal_monthly', 'password', 'boxes', 'secretCodes']);
      $validator = Validator::make($MemberData, [
        'name'   => 'required',
        'email'   => 'required|email',
        'password'   => 'required',
        'address'   => 'required',
        'city'   => 'required',
        'country'   => 'required',
        'boxes' => 'required',
        'secretCodes' => 'required',
        'goal_daily' => 'required|numeric',
        'goal_weekly' => 'required|numeric',
        'goal_monthly' => 'required|numeric'
      ]);

      if ($validator->fails()) {
        $res["success"] = false;
        $res["message"] = "The data is not correct.";
        return $res;
      }

      //Add Box
      $userID = $user->id;
      for($i = 0; $i < count($MemberData['boxes']); $i++){
        $boxData['device_id'] = $MemberData['boxes'][$i];

        $boxData['country_code'] = $MemberData['country'];

        // check exist device
        if (Box::where('del_flg'  , '<>'  , config('constants.ITEM_IS_DELETE'))
                  ->where('device_id' ,  '='  , $boxData['device_id'])
              ->exists()) {
            $res["success"] = false;
            $res["message"] = "Device already exists";
            return $res;
        }
        if(getSecretCode($boxData['device_id']) != $MemberData['secretCodes'][$i]){
          $res["success"] = false;
          $res["message"] = "Secret code is not correct.";
          return $res;
        }
      }

      // check exist member
      if (User::where('del_flg'  , '<>'  , config('constants.ITEM_IS_DELETE'))
            ->where('email' ,  '='  , $MemberData['email'])
            ->exists()) {
          $res["success"] = false;
          $res["message"] = "That email already exists";
          return $res;
      }
      // add device
      for($i = 0; $i < count($MemberData['boxes']); $i++){
        $boxData['user_id'] = $userID;
        $boxData['device_id'] = $MemberData['boxes'][$i];
        $boxData['country_code'] = $MemberData['country'];
        Box::unguard();
        $box = Box::create($boxData);
        Box::reguard();
      }
      // add member
      $boxes = $MemberData['boxes'];
      unset($MemberData['boxes']);
      unset($MemberData['secretCodes']);
      $MemberData['parent_id'] = $userID;
      $MemberData['status'] = config('constants.VERIFIED_USER');
      $MemberData['password'] = bcrypt($MemberData['password']);
      // $MemberData['type'] = config('constants.MEMBER_USER');
      User::unguard();
      $member = User::create($MemberData);
      User::reguard();
      $member->assignRole(config('constants.MEMBER_USER'));
      // add boxes to member
      foreach ($boxes as $box_id) {
        if (Box::where('device_id', $box_id)
                  ->where('user_id', $userID)
                  ->where('del_flg', '<>'  , config('constants.ITEM_IS_DELETE'))
                  ->exists())
        {
            $MemberBoxData['member_id'] = $member->id;
            $MemberBoxData['device_id'] = $box_id;
            MemberBox::unguard();
            MemberBox::create($MemberBoxData);
            MemberBox::reguard();
        }
      }
      $data = DB::table('cbox_member_boxes')
              ->leftjoin('cbox_boxes', function($join){
                    $join->on('cbox_boxes.device_id', '=', 'cbox_member_boxes.device_id');
              })
              ->leftjoin('cbox_deposits', function($join){
                    $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
              })
              ->where('cbox_member_boxes.del_flg', '<>', config('constants.ITEM_IS_DELETE'))
              ->where('member_id', $member->id)
              ->select(DB::raw('sum(cbox_boxes.d_count) as daily_count'), DB::raw('count(cbox_deposits.id) as lifetime_count'))
              ->first();
      if ($data->daily_count == null) {
        $data->daily_count = 0;
      }
      $res['member_id'] = $member->id;
      $res['phone'] = $member->phone;
      $res['goal_daily'] = $member->goal_daily;
      $res['goal_weekly'] = $member->goal_weekly;
      $res['goal_monthly'] = $member->goal_monthly;
      $res['data'] = $data;
      $res['success'] = true;

      return $res;
    }

    /**
    * Update member
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function Update(Request $request){
      $user = Auth::user();
      if(!$user->can('MEMBER_MANAGE')) {
          $res['success'] = false;
          $res['message'] = 'You have not permission to manage members.';
          return $res;
      }
      $MemberData = $request->only(['id', 'name', 'email', 'address', 'city', 'country', 'phone', 'boxes', 'secretCodes', 'goal_daily', 'goal_weekly', 'goal_monthly']);
      $validator = Validator::make($MemberData, [
        'id'   => 'required|numeric',
        'email' => 'required',
        'name'   => 'required',
        'address'   => 'required',
        'city'   => 'required',
        'boxes' => 'required',
        'secretCodes' => 'required',
        'country'   => 'required',
        'goal_daily' => 'required|numeric',
        'goal_weekly' => 'required|numeric',
        'goal_monthly' => 'required|numeric'
        ]);
      if ($validator->fails()) {
        $res["success"] = false;
        $res["message"] = "The data is not correct.";
        return $res;
      }

      $userID = $user->id;

      // check email exist
      if (User::where('del_flg'  , '<>'  , config('constants.ITEM_IS_DELETE'))
            ->where('id'   ,  '<>'  ,$MemberData['id'])
            ->where('email' ,  '='  , $MemberData['email'])
            ->exists()) {
          $res["success"] = false;
          $res["message"] = "Email already exists";
          return $res;
      }

      $Member = User::where('del_flg'  , '<>'  , config('constants.ITEM_IS_DELETE'))
            ->where('id' ,  '='  , $MemberData['id'])
            ->first();

      if ($Member === null) {
          $res["success"] = false;
          $res["message"] = "Member don't exist in this user";
          return $res;
      }

      for($i = 0; $i < count($MemberData['boxes']); $i++){
        $boxData['device_id'] = $MemberData['boxes'][$i];

        $boxData['country_code'] = $MemberData['country'];

        // check exist device
        if (Box::where('del_flg'  , '<>'  , config('constants.ITEM_IS_DELETE'))
                  ->where('device_id' ,  '='  , $boxData['device_id'])
                  ->where('user_id', '<>', $userID)
                  ->exists()) {
            $res["success"] = false;
            $res["message"] = "Device already exists";
            return $res;
        }
        if(getSecretCode($boxData['device_id']) != $MemberData['secretCodes'][$i]){
          $res["success"] = false;
          $res["message"] = "Secret code is not correct.";
          return $res;
        }
      }
      // add device
      for($i = 0; $i < count($MemberData['boxes']); $i++){
        $boxData['user_id'] = $userID;
        $boxData['device_id'] = $MemberData['boxes'][$i];
        $boxData['country_code'] = $MemberData['country'];
        if (Box::where('del_flg'  , '<>'  , config('constants.ITEM_IS_DELETE'))
                  ->where('device_id' ,  '='  , $boxData['device_id'])
                  ->exists()) {
            continue;
        }
        Box::unguard();
        $box = Box::create($boxData);
        Box::reguard();
      }
      $Member['name'] = $MemberData['name'];
      $Member['email'] = $MemberData['email'];
      $Member['phone'] = $MemberData['phone'];
      $Member['address'] = $MemberData['address'];
      $Member['city'] = $MemberData['city'];
      $Member['country'] = $MemberData['country'];
      $Member['goal_daily'] = $MemberData['goal_daily'];
      $Member['goal_weekly'] = $MemberData['goal_weekly'];
      $Member['goal_monthly'] = $MemberData['goal_monthly'];

      $Member->save();

      // remove all boxes to member
      MemberBox::where('member_id', $Member->id)
            ->update(array('del_flg' => config('constants.ITEM_IS_DELETE')));
      // add boxes to member
      foreach ($request->input('boxes') as $box_id) {
        if (Box::where('device_id', $box_id)
                  ->where('user_id', $userID)
                  ->where('del_flg', '<>'  , config('constants.ITEM_IS_DELETE'))
                  ->exists())
        {
            $MemberBoxData['member_id'] = $Member->id;
            $MemberBoxData['device_id'] = $box_id;
            MemberBox::unguard();
            MemberBox::create($MemberBoxData);
            MemberBox::reguard();
        }
      }
      $data = DB::table('cbox_member_boxes')
              ->leftjoin('cbox_boxes', function($join){
                    $join->on('cbox_boxes.device_id', '=', 'cbox_member_boxes.device_id');
              })
              ->leftjoin('cbox_deposits', function($join){
                    $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
              })
              ->where('cbox_member_boxes.del_flg', '<>', config('constants.ITEM_IS_DELETE'))
              ->where('member_id', $Member->id)
              ->select(DB::raw('sum(cbox_boxes.d_count) as daily_count'), DB::raw('count(cbox_deposits.id) as lifetime_count'))
              ->first();
      if ($data->daily_count == null) {
        $data->daily_count = 0;
      }
      $res['data'] = $data;
      $res['success'] = true;
      return $res;
    }

    /**
    * Remove member
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function Remove(Request $request){
      $user = Auth::user();
      if(!$user->can('MEMBER_MANAGE')) {
          $res['success'] = false;
          $res['message'] = 'You have not permission to manage members.';
          return $res;
      }
      $MemberData = $request->only(['id']);
      $validator = Validator::make($MemberData, ['id'   => 'required|numeric']);
      if ($validator->fails()) {
        $res["success"] = false;
        $res["message"] = "The data is not correct.";
        return $res;
      }

      $userID = $user->id;

      $Member = User::where('id' ,  '='  , $MemberData['id'])
            ->first();

      if ($Member === null) {
          $res["success"] = false;
          $res["message"] = "The Member doesn't exist";
          return $res;
      }
      // $Member['del_flg'] = config('constants.ITEM_IS_DELETE');

      $Member->delete();
      $res['success'] = true;
      return $res;
    }

    /**
    * Send money to the current user's paypal address.
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function getPaid(Request $request){
      $input = $request->input();
    
      $user = Auth::user();

      if (!Hash::check($input['password'], $user->password)) {
        $res['success'] = false;
        return $res;
      }
      $user_id = $user->id;
      $money = DB::table('users')
                    ->leftjoin('cbox_invoices', function($join){
                          $join->on('cbox_invoices.user_id', '=', 'users.id');
                    })
                    ->where('users.id', $user_id)
                    ->where('cbox_invoices.status', config('constants.INVOICE_STATUS_PAID'))
                    ->where(function($query){
                             $query->orwhere('cbox_invoices.type', config('constants.INVOICE_TYPE_MEMBER_PAY'))
                                   ->orwhere('cbox_invoices.type', config('constants.INVOICE_TYPE_ORG_GET_PAID'))
                                   ->orwhere('cbox_invoices.type', config('constants.INVOICE_TYPE_DONATION'))
                                   ->orwhere('cbox_invoices.type', config('constants.INVOICE_TYPE_FEE'));
                    })
                    ->select(DB::raw('cast(sum(cbox_invoices.amount) as decimal(10,2)) as amount'))
                    ->first()->amount;


      if ($money < $input['amount']) {
        $res['success'] = false;
        return $res;
      }

      $payouts = new \PayPal\Api\Payout();
      $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
      $senderBatchHeader->setSenderBatchId(uniqid())
          ->setEmailSubject("Milionmitzvot Withdrawal Notification for $154");
          $senderItem = new \PayPal\Api\PayoutItem();
          $senderItem->setRecipientType('Email')
              ->setNote('Thanks for your patronage!')
              ->setReceiver($user->email)
              ->setAmount(new \PayPal\Api\Currency('{
                                  "value":'.$input['amount'].',
                                  "currency":"USD"
                              }'));

      $payouts->setSenderBatchHeader($senderBatchHeader)
              ->addItem($senderItem);

          try {
              $output = $payouts->createSynchronous($this->_apiContext);
          } catch (Exception $ex) {
            $res['success'] = false;
            return $res;
          }

          $user->money = $user->money - $input['amount'];
          $user->save();

          $invoiceData['user_id'] = $user->id;
          $invoiceData['buyer_id'] = 0;
          $invoiceData['amount'] = $input['amount'] * (-1);
          $invoiceData['count'] = 1;
          $invoiceData['type'] = config('constants.INVOICE_TYPE_ORG_GET_PAID');
          $invoiceData['status'] = config('constants.INVOICE_STATUS_PAID');
          $invoice_key = md5($invoiceData['user_id'].$invoiceData['buyer_id'].date('YmdHis'));
          $invoiceData['invoice_key'] = $invoice_key;

          Invoice::unguard();
          $invoice = Invoice::create($invoiceData);
          Invoice::reguard();

          $res['success'] = true;
          return $res;
    }

    /**
    * Get Invoices List for current user
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function getTransactionHistory(Request $request){
      $user = Auth::user();
      $user_id = $user->id;
      $money = DB::table('users')
                    ->leftjoin('cbox_invoices', function($join){
                          $join->on('cbox_invoices.user_id', '=', 'users.id');
                    })
                    ->where('users.id', $user_id)
                    ->where('cbox_invoices.status', config('constants.INVOICE_STATUS_PAID'))
                    ->where(function($query){
                             $query->orwhere('cbox_invoices.type', config('constants.INVOICE_TYPE_MEMBER_PAY'))
                                   ->orwhere('cbox_invoices.type', config('constants.INVOICE_TYPE_DONATION'))
                                   ->orwhere('cbox_invoices.type', config('constants.INVOICE_TYPE_ORG_GET_PAID'))
                                   ->orwhere('cbox_invoices.type', config('constants.INVOICE_TYPE_FEE'));
                    })
                    ->select(DB::raw('cast(sum(cbox_invoices.amount) as decimal(10,2)) as amount'))
                    ->first()->amount;

      $res['avaliable_money'] = is_null($money)?0:$money;
      $invoiceData = DB::table('users')
                    ->leftjoin('cbox_invoices', function($join){
                          $join->on('cbox_invoices.user_id', '=', 'users.id');
                    })
                    ->where('users.id', $user_id)
                    ->where('cbox_invoices.status', config('constants.INVOICE_STATUS_PAID'))
                    ->where(function($query){
                             $query->orwhere('cbox_invoices.type', config('constants.INVOICE_TYPE_MEMBER_PAY'))
                                   ->orwhere('cbox_invoices.type', config('constants.INVOICE_TYPE_DONATION'))
                                   ->orwhere('cbox_invoices.type', config('constants.INVOICE_TYPE_ORG_GET_PAID'))
                                   ->orwhere('cbox_invoices.type', config('constants.INVOICE_TYPE_FEE'));
                    })
                    ->select('*', 'cbox_invoices.updated_at as date')
                    ->orderBy('date', 'DESC')
                    ->get();
      $res['success'] = true;
      $res['invoiceData'] = $invoiceData;
      return $res;
    }
}
