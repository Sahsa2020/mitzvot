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
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProfileController extends BaseController
{

    /**
    * Get current user's profile
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function Find(Request $request){
        $dataArray = Auth::user()->toArray();
        
        unset($dataArray['activation_token']);
        unset($dataArray['password_token']);
        unset($dataArray['del_flg']);

        if ($dataArray['image_url'] != null && !empty($dataArray['image_url'])) {
          $dataArray['image_url'] = url('/').$dataArray['image_url'];
        }

        if ($dataArray['image_origin'] != null && !empty($dataArray['image_origin'])) {
          $dataArray['image_origin'] = url('/').$dataArray['image_origin'];
        }

        $dataArray['name'] = is_null($dataArray['name'])?"":$dataArray['name'];
        $dataArray['school'] = is_null($dataArray['school'])?"":$dataArray['school'];
        $dataArray['company'] = is_null($dataArray['company'])?"":$dataArray['company'];
        $dataArray['address'] = is_null($dataArray['address'])?"":$dataArray['address'];
        $dataArray['city'] = is_null($dataArray['city'])?"":$dataArray['city'];
        $dataArray['country'] = is_null($dataArray['country'])?"":$dataArray['country'];
        $dataArray['birthday'] = is_null($dataArray['birthday'])?"":$dataArray['birthday'];
        $dataArray['phone'] = is_null($dataArray['phone'])?"":$dataArray['phone'];
        $dataArray['weekly_mail_video'] = is_null($dataArray['weekly_mail_video'])?"":$dataArray['weekly_mail_video'];


        $dataArray['bank_account'] = is_null($dataArray['bank_account'])?"":$dataArray['bank_account'];
        $dataArray['routing_number'] = is_null($dataArray['routing_number'])?"":$dataArray['routing_number'];
        $dataArray['account_number'] = is_null($dataArray['account_number'])?"":$dataArray['account_number'];
        $dataArray['name_of_bank_account'] = is_null($dataArray['name_of_bank_account'])?"":$dataArray['name_of_bank_account'];
        $dataArray['bank_name'] = is_null($dataArray['bank_name'])?"":$dataArray['bank_name'];
        $dataArray['account_type'] = is_null($dataArray['account_type'])?"":$dataArray['account_type'];

        $dataArray['role'] = DB::table('role_user')->join('roles', function($join){
            $join->on('role_user.role_id', '=', 'roles.id');
          })
          ->select(DB::raw("roles.name as role_name"))
          ->where('role_user.user_id', $dataArray['id'])
          ->first()->role_name;         
        

        if(Auth::user()->hasRole(config('constants.MEMBER_USER'))){
          $dataArray['daily_count'] = MemberBox::join('cbox_boxes', function($join){
                          $join->on('cbox_member_boxes.device_id', '=', 'cbox_boxes.device_id');
                    })
                  ->select(DB::raw('sum(cbox_boxes.d_count) as deposit_count'))
                  ->where('cbox_member_boxes.member_id', $dataArray['id'])
                  ->first()->deposit_count;
          $dataArray['weekly_count'] = MemberBox::join('cbox_boxes', function($join){
                          $join->on('cbox_member_boxes.device_id', '=', 'cbox_boxes.device_id');
                    })
                    ->leftjoin('cbox_deposits', function($join){
                          $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
                    })
                    ->select(DB::raw('count(cbox_deposits.id) as deposit_count'))
                    ->where('cbox_member_boxes.member_id', $dataArray['id'])
                    ->where('cbox_deposits.created_at', '>=' , date('Y-m-d', strtotime('monday this week')))
                    ->first()->deposit_count;
          $dataArray['monthly_count'] =  MemberBox::join('cbox_boxes', function($join){
                          $join->on('cbox_member_boxes.device_id', '=', 'cbox_boxes.device_id');
                    })
                    ->leftjoin('cbox_deposits', function($join){
                          $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
                    })
                    ->select(DB::raw('count(cbox_deposits.id) as deposit_count'))
                    ->where('cbox_member_boxes.member_id', $dataArray['id'])
                    ->where('cbox_deposits.created_at', '>=' , date('Y-m-01'))
                    ->first()->deposit_count;
          $dataArray['deposit_money'] = DB::table('cbox_member_boxes')
                    ->leftjoin('cbox_deposits', function($join){
                          $join->on('cbox_member_boxes.device_id', '=', 'cbox_deposits.device_id');
                    })
                    ->leftjoin('cbox_currencyts', function($join){
                          $join->on('cbox_deposits.currencyt', '=', 'cbox_currencyts.currencyt');
                    })
                    ->where('cbox_member_boxes.member_id', '='  , $dataArray['id'])
                    ->where('cbox_deposits.del_flg', '<>'  , config('constants.ITEM_IS_NONE'))
                    ->select(DB::raw('cast(sum(cbox_deposits.amount*cbox_currencyts.rate) as decimal(10,2)) as amount'))
                    ->groupby('cbox_member_boxes.member_id')
                    ->first();
          $dataArray['deposit_count'] = MemberBox::join('cbox_boxes', function($join){
                          $join->on('cbox_member_boxes.device_id', '=', 'cbox_boxes.device_id');
                    })
                    ->leftjoin('cbox_deposits', function($join){
                          $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
                    })
                    ->select(DB::raw('count(cbox_deposits.id) as deposit_count'))
                    ->where('cbox_member_boxes.member_id', $dataArray['id'])
                    ->first()->deposit_count;
        }
        else{
          $dataArray['daily_count'] = DB::table('cbox_boxes')
                  ->select(DB::raw('sum(cbox_boxes.d_count) as deposit_count'))
                  ->where('cbox_boxes.user_id', $dataArray['id'])
                  ->first()->deposit_count;
          $dataArray['weekly_count'] = DB::table('cbox_boxes')
                    ->leftjoin('cbox_deposits', function($join){
                          $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
                    })
                    ->select(DB::raw('count(cbox_deposits.id) as deposit_count'))
                    ->where('cbox_boxes.user_id', $dataArray['id'])
                    ->where('cbox_deposits.created_at', '>=' , date('Y-m-d', strtotime('monday this week')))
                    ->first()->deposit_count;
          $dataArray['monthly_count'] = DB::table('cbox_boxes')
                    ->leftjoin('cbox_deposits', function($join){
                          $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
                    })
                    ->select(DB::raw('count(cbox_deposits.id) as deposit_count'))
                    ->where('cbox_boxes.user_id', $dataArray['id'])
                    ->where('cbox_deposits.created_at', '>=' , date('Y-m-01'))
                    ->first()->deposit_count;
          $dataArray['deposit_money'] = DB::table('cbox_boxes')
                      ->leftjoin('cbox_deposits', function($join){
                            $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
                      })
                      ->leftjoin('cbox_currencyts', function($join){
                            $join->on('cbox_deposits.currencyt', '=', 'cbox_currencyts.currencyt');
                      })
                      ->where('cbox_boxes.user_id', '='  , $dataArray['id'])
                      ->where('cbox_deposits.del_flg', '<>'  , config('constants.ITEM_IS_NONE'))
                      ->select(DB::raw('cast(sum(cbox_deposits.amount*cbox_currencyts.rate) as decimal(10,2)) as amount'))
                      ->groupby('cbox_boxes.user_id')
                      ->first();
          $dataArray['deposit_count'] = DB::table('cbox_boxes')
                    ->leftjoin('cbox_deposits', function($join){
                          $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
                    })
                    ->select(DB::raw('count(cbox_deposits.id) as deposit_count'))
                    ->where('cbox_boxes.user_id', $dataArray['id'])
                    ->first()->deposit_count;
        }
        $dataArray['deposit_money'] = ($dataArray['deposit_money'])?$dataArray['deposit_money']->amount:0;
        $res['success'] = true;
        $res['data'] = $dataArray;
        return $res;
    }

    /**
    * Update Profile Image
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function updateProfileImage(Request $request){
      $user = Auth::user();
      $img_path = '/public'.config('constants.IMAGE_PATH');
      $image = $request->file('image');
      $image_origin = $request->file('image_origin');
      if (!is_null($image)) {
        $destinationPath = base_path().$img_path; // upload path
        $extension = "jpg";//$image->getClientOriginalExtension(); // getting image extension
        $fileName = 'img_'.$user['id'].'_'.rand().'.'.$extension; // renameing image
        $image->move($destinationPath, $fileName); // uploading file to given path
        // $request->file('image')->move($destinationPath, $fileName); // uploading file to given path
        $user['image_url'] = config('constants.IMAGE_PATH').$fileName;
      }
      if (!is_null($image_origin)) {
        $destinationPath = base_path().$img_path; // upload path
        $extension = $image_origin->getClientOriginalExtension(); // getting image extension
        $fileName = 'img_origin_'.$user['id'].'_'.rand().'.'.$extension; // renameing image
        $image_origin->move($destinationPath, $fileName); // uploading file to given path
        $user['image_origin'] = config('constants.IMAGE_PATH').$fileName;
      }
      $user->save();
      $res['success'] = true;
      $res['data'] = $user;
      return $res;
    }

    /**
    * Update Profile
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function Update(Request $request){

      $userData = $request->only(['name', 'role', 'age', 'school', 'company', 'address', 'city', 'country', 'birthday', 'phone', 'image', 'goal_daily', 'goal_weekly', 'goal_monthly', 'weekly_mail_ignore', 'weekly_mail_video']);
      $validator = Validator::make($userData, [
          'name' => 'required',
          'role' => 'required',
          'age' => 'required|numeric',
          'address' => 'required',
          'city' => 'required',
          'country' => 'required',
          'birthday' => 'required',
          'goal_daily' => 'required|numeric',
          'goal_weekly' => 'required|numeric',
          'goal_monthly' => 'required|numeric',
          'weekly_mail_video' => 'required',
          'weekly_mail_ignore' => 'required|numeric'
      ]);
      if ($validator->fails()) {
        $res['success'] = false;
        $res['message'] = "The data is not correct.";
        return $res;
      }

      $user = Auth::user();
      $user['name'] = $userData['name'];
      // $user['role'] = $userData['type'];
      // $user->assignRole($userData['role']);
      $user['age'] = $userData['age'];
      $user['goal_daily'] = $userData['goal_daily'];
      $user['goal_weekly'] = $userData['goal_weekly'];
      $user['goal_monthly'] = $userData['goal_monthly'];
      $user['school'] = is_null($userData['school'])?"":$userData['school'];
      $user['company'] = is_null($userData['company'])?"":$userData['company'];
      $user['address'] = is_null($userData['address'])?"":$userData['address'];
      $user['city'] = is_null($userData['city'])?"":$userData['city'];
      $user['country'] = is_null($userData['country'])?"":$userData['country'];
      $user['birthday'] = is_null($userData['birthday'])?"":$userData['birthday'];
      $user['phone'] = is_null($userData['birthday'])?"":$userData['phone'];
      $user['weekly_mail_video'] = is_null($userData['weekly_mail_video'])?"":$userData['weekly_mail_video'];
      $user['weekly_mail_ignore'] = is_null($userData['weekly_mail_ignore'])?"":$userData['weekly_mail_ignore'];

      // $img_path = '/public'.config('constants.IMAGE_PATH');
      // $image = $request->file('image');
      // if (!is_null($image)) {
      //   $destinationPath = base_path().$img_path; // upload path
      //   $extension = "jpg";//$image->getClientOriginalExtension(); // getting image extension
      //   $fileName = 'img_'.$user['id'].'_'.rand().'.'.$extension; // renameing image
      //   $image->move($destinationPath, $fileName); // uploading file to given path
      //   // $request->file('image')->move($destinationPath, $fileName); // uploading file to given path
      //   $user['image_url'] = config('constants.IMAGE_PATH').$fileName;
      // }
      // if (!is_null($request->file('image_origin'))) {
      //   $destinationPath = base_path().$img_path; // upload path
      //   $extension = $request->file('image_origin')->getClientOriginalExtension(); // getting image extension
      //   $fileName = 'img_origin_'.$user['id'].'_'.rand().'.'.$extension; // renameing image
      //   $request->file('image_origin')->move($destinationPath, $fileName); // uploading file to given path
      //   $user['image_origin'] = config('constants.IMAGE_PATH').$fileName;
      // }
      $user->save();
      
      $role_id = DB::table('roles')
                    ->where('name', $userData['role'])
                    ->first()->id;
      DB::table('role_user')->where('user_id', $user->id)->update(['role_id' => $role_id]);
      $res['success'] = true;
      $res['data'] = $user;
      return $res;
    }

    /**
    * Update Password
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function updatePassword(Request $request){
      $input = $request->only(['cur_password', 'new_password']);
      $validator = Validator::make($input, ['cur_password'   => 'required', 'new_password'   => 'required']);

      if ($validator->fails()) {
        $res["success"] = false;
        $res["message"] = "The data is not correct.";
        return $res;
      }

      $user = Auth::user();
      if (Auth::attempt(['email' => $user->email, 'password' => $input['cur_password']])) {
        $user->password = bcrypt($input['new_password']);
        $user->save();
      } else {
        $res["success"] = false;
        $res["message"] = "Current Password is not correct";
        return $res;
      }

      $res['success'] = true;
      return $res;
    }

    /**
    * Follow user. -- From scoreboard page
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function followUser(Request $request){
      $input = $request->only(['follow_user_id']);
      $validator = Validator::make($input, ['follow_user_id'   => 'required']);

      if ($validator->fails()) {
        $res["success"] = false;
        $res["message"] = "The data is not correct.";
        return $res;
      }

      $user = Auth::user();
      $follow = Follow::where('user_id', '=', $user->id)->where('follow_user_id', '=', $input['follow_user_id'])->first();
      if($follow == null){
        $res['exist'] = false;
        $follow = Follow::create([
          'user_id' => $user->id,
          'follow_user_id' => $input['follow_user_id']
        ]);
        $follow->save();
      }
      else
        $res['exist'] = true;
      $res['success'] = true;
      return $res;
    }
    
    /**
    * Unfollow user -- from scoreboard page
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function unFollowUser(Request $request){
      $input = $request->only(['follow_user_id']);
      $validator = Validator::make($input, ['follow_user_id'   => 'required']);

      if ($validator->fails()) {
        $res["success"] = false;
        $res["message"] = "The data is not correct.";
        return $res;
      }

      $user = Auth::user();
      $follow = Follow::where('user_id', '=', $user->id)->where('follow_user_id', '=', $input['follow_user_id'])->first();
      if($follow == null)
        $res['exist'] = false;
      else{
        $res['exist'] = true;
        $follow->forceDelete();
      }
      $res['success'] = true;
      return $res;
    }

    /**
    * Get Unread Message Count
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function unReadMessages(Request $request){
      $input = $request->only(['cur_conversation_id']);
      $validator = Validator::make($input, ['cur_conversation_id'   => 'required']);

      if ($validator->fails()) {
        $res["success"] = false;
        $res["message"] = "The data is not correct.";
        return $res;
      }

      $user = Auth::user();
      Message::where('conversation_id', $input['cur_conversation_id'])->where('user_id', '<>', $user->id)->update(['is_seen' => config('constants.MESSAGE_READ')]);
      $unread_messages = Conversation::leftjoin('messages', function($join) use($user){
                          $join->on('messages.conversation_id', '=', 'conversations.id');
                          $join->where('messages.user_id', '<>', $user->id);
                          $join->where('messages.is_seen', config('constants.MESSAGE_UNREAD'));
                    })
                    ->where(function($query) use($user){
                        $query->orwhere('conversations.user_one', $user->id);
                        $query->orwhere('conversations.user_two', $user->id);
                    })
                    ->select(
                      DB::raw('count(messages.id) as unread_messages'))
                    ->first()->unread_messages;
      $res['data'] = $unread_messages;
      $res['success'] = true;
      return $res;
    }

    /**
    * Pay Money for deposit amounts
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function donateForBoxAmount(Request $request){
      $payData = $request->only(['amount', 'donateId']);
      $validator = Validator::make($payData, [
          'amount' => 'required|numeric'
      ]);
      $amount = $payData['amount'];
      $user = Auth::user();
      $donation_orgId = 0;
      if($user->hasRole(config('constants.MEMBER_USER'))){
        $donation_orgId = $user->parent_id;
      }
      else{
        $donate = Donate::where('id', $payData['donateId'])->first();
        $donation_orgId = $donate->org_id;
      }
      $user_id = $user->id;
      $invoiceData['user_id'] = $donation_orgId;
      $invoiceData['buyer_id'] = $user_id;
      $invoiceData['amount'] = $amount;
      $invoiceData['count'] = 1;
      if($user->hasRole(config('constants.MEMBER_USER')))
        $invoiceData['type'] = config('constants.INVOICE_TYPE_MEMBER_PAY');
      else
        $invoiceData['type'] = config('constants.INVOICE_TYPE_DONATION');
      $invoiceData['status'] = config('constants.INVOICE_STATUS_NOPAY');
      $invoice_key = md5($invoiceData['user_id'].$invoiceData['buyer_id'].date('YmdHis'));
      $invoiceData['invoice_key'] = $invoice_key;

      Invoice::unguard();
      $invoice = Invoice::create($invoiceData);
      Invoice::reguard();


      $payer = PayPal::Payer();
      $payer->setPaymentMethod('paypal');

      $pp_amount = PayPal:: Amount();
      $pp_amount->setCurrency('USD');
      $pp_amount->setTotal($amount); // This is the simple way,
      // you can alternatively describe everything in the order separately;
      // Reference the PayPal PHP REST SDK for details.

      $transaction = PayPal::Transaction();
      $transaction->setAmount($pp_amount);
      $transaction->setDescription('C-Box');

      $redirectUrls = PayPal:: RedirectUrls();
      $redirectUrls->setReturnUrl(url('/api/v1/donateMoneyDone?invoice_key='.$invoice_key));
      $redirectUrls->setCancelUrl(url('/api/v1/donateMoneyCancel'));

      $payment = PayPal::Payment();
      $payment->setIntent('sale');
      $payment->setPayer($payer);
      $payment->setRedirectUrls($redirectUrls);
      $payment->setTransactions(array($transaction));

      try {
        $response = $payment->create($this->_apiContext);
          // $payment->create($apiContext);
      } catch (PayPal\Exception\PayPalConnectionException $ex) {
          echo $ex->getCode(); // Prints the Error Code
          echo $ex->getData(); // Prints the detailed error message
          die($ex);
      } catch (Exception $ex) {
          die($ex);
      }

      $redirectUrl = $response->links[1]->href;
      return redirect( $redirectUrl );
    }

    /**
    * Callback from Paypal -- Donate for deposit amount success
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function payDone(Request $request)
    {
        $id = $request->get('paymentId');
        $token = $request->get('token');
        $payer_id = $request->get('PayerID');
        $invoice_key = $request->get('invoice_key');

        $payment = PayPal::getById($id, $this->_apiContext);
        $paymentExecution = PayPal::PaymentExecution();
        $paymentExecution->setPayerId($payer_id);
        $executePayment = $payment->execute($paymentExecution, $this->_apiContext);

        $invoice = Invoice::where('invoice_key', $invoice_key)->first();
        $invoice->status = config('constants.INVOICE_STATUS_PAID');
        $invoice->save();

        $member_id = $invoice->buyer_id;
        $member = User::where('id', $member_id)->first();
        $donationOrg = User::where('id', $invoice->user_id)->first();
        $fee = $invoice->amount * 0.1;

        $invoiceData['user_id'] = $donationOrg;
        $invoiceData['buyer_id'] = 0;
        $invoiceData['amount'] = $fee * (-1);
        $invoiceData['count'] = 1;
        $invoiceData['type'] = config('constants.INVOICE_TYPE_FEE');
        $invoiceData['status'] = config('constants.INVOICE_STATUS_PAID');
        $invoice_key = md5($invoiceData['user_id'].$invoiceData['buyer_id'].date('YmdHis'));
        $invoiceData['invoice_key'] = $invoice_key;

        Invoice::unguard();
        $invoice = Invoice::create($invoiceData);
        Invoice::reguard();

        
        if($member->hasRole(config('constants.MEMBER_USER'))){
          $data = DB::table('cbox_member_boxes')
                      ->leftjoin('cbox_deposits', function($join){
                            $join->on('cbox_member_boxes.device_id', '=', 'cbox_deposits.device_id');
                      })
                      ->leftjoin('cbox_currencyts', function($join){
                            $join->on('cbox_deposits.currencyt', '=', 'cbox_currencyts.currencyt');
                      })
                      ->where('cbox_member_boxes.member_id', '='  , $member_id)
                      ->where('cbox_deposits.del_flg', '<>'  , config('constants.ITEM_IS_NONE'))
                      ->update(['cbox_deposits.del_flg' => config('constants.ITEM_IS_NONE')]);
        }
        else{
          $data = DB::table('cbox_boxes')
                      ->leftjoin('cbox_deposits', function($join){
                            $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
                      })
                      ->leftjoin('cbox_currencyts', function($join){
                            $join->on('cbox_deposits.currencyt', '=', 'cbox_currencyts.currencyt');
                      })
                      ->where('cbox_boxes.user_id', '='  , $member_id)
                      ->where('cbox_deposits.del_flg', '<>'  , config('constants.ITEM_IS_NONE'))
                      ->update(['cbox_deposits.del_flg' => config('constants.ITEM_IS_NONE')]);
        }
        $mail_data = array('user'=>$donationOrg->name, 'donator'=>$member->name, 'amount'=>$invoice->amount);
        Mail::send('mail/donation_mail', $mail_data, function($message) use($donationOrg) {
           $message->to($donationOrg->email, $donationOrg->name)->subject
              ('Received Money');
           $message->from('noreply@milionmitzvot.com','MilionMitzvot');
        });


        return redirect('/#/home;member_pay_success=0');
    }
    
    /**
    * Callback from Paypal -- Donate for deposit amount cancelled
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function payCancel()
    {
        // Curse and humiliate the user for cancelling this most sacred payment (yours)
        return redirect('/#/home;member_pay_success=1');
    }

    function updatePersonalDetails(Request $request)
    {
      $userData = $request->only(['email', 'name','phone', 'birthday', 'city', 'address', 'country']);
      var_dump($userData);
      $validator = Validator::make($userData, [
        'name' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'address' => 'required',
        'city' => 'required',
        'country' => 'required',
        'birthday' => 'required',    
      ]);
      if ($validator->fails()) {
        $res['success'] = false;
        $res['data'] = "The data is not correct.";
        return $res;
      }

      $user = Auth::user();
      $user['phone'] = $userData['phone'];
      $user['email'] = $userData['email'];
      $user['name'] = $userData['name'];
      $user['address'] = $userData['address'];
      $user['email'] = $userData['email'];
      $user['city'] = $userData['city'];
      $user['country'] = $userData['country'];
      $user->save();

      $res['data'] = $userData;
      $res['success'] = true;

      return $res;
    }

    function updateBankDetails(Request $request)
    {
      $userData = $request->only(['bank_account', 'routing_number','account_number', 'name_of_bank_account', 'bank_name', 'account_type']);
      var_dump($userData);
      $validator = Validator::make($userData, [
        'bank_account' => 'required',
        'routing_number' => 'required',
        'account_number' => 'required',
        'name_of_bank_account' => 'required',
        'bank_name' => 'required',
        'account_type' => 'required',
      ]);
      if ($validator->fails()) {
        $res['success'] = false;
        $res['data'] = "The data is not correct.";
        return $res;
      }

      $user = Auth::user();
      $user['bank_account'] = $userData['bank_account'];
      $user['routing_number'] = $userData['routing_number'];
      $user['account_number'] = $userData['account_number'];
      $user['name_of_bank_account'] = $userData['name_of_bank_account'];
      $user['bank_name'] = $userData['bank_name'];
      $user['account_type'] = $userData['account_type'];
      $user->save();

      $res['data'] = $userData;
      $res['success'] = true;

      return $res;
    }

    function updateGoals(Request $request)
    {
      $userData = $request->only(['goal_daily', 'goal_weekly','goal_monthly']);
      var_dump($userData);
      $validator = Validator::make($userData, [
        'goal_daily' => 'required',
        'goal_weekly' => 'required',
        'goal_monthly' => 'required',
      ]);
      if ($validator->fails()) {
        $res['success'] = false;
        $res['data'] = "The data is not correct.";
        return $res;
      }

      $user = Auth::user();
      $user['goal_daily'] = $userData['bank_account'];
      $user['goal_weekly'] = $userData['routing_number'];
      $user['goal_monthly'] = $userData['account_number'];
      $user->save();

      $res['data'] = $userData;
      $res['success'] = true;

      return $res;
    }

    function updateVideo(Request $request) 
    {
      $userData = $request->only(['weekly_mail_video']);
      $validator = Validator::make($userData, [
          'weekly_mail_video' => 'required',
      ]);
      if ($validator->fails()) {
        $res['success'] = false;
        $res['data'] = "The data is not correct.";
        return $res;
      }

      $user = Auth::user();
      $user['weekly_mail_video'] = $userData['weekly_mail_video'];
      $user->save();

      $res['success'] = true;
      return $res;
    }

    function deleteAccount(Request $request) 
    {
      $userData = $request->only(['del_flg']);
      $validator = Validator::make($userData, [
          'del_flg' => 'required',
      ]);
      if ($validator->fails()) {
        $res['success'] = false;
        $res['data'] = "The data is not correct.";
        return $res;
      }

      $user = Auth::user();
      $user['del_flg'] = true;
      $user->save();

      $res['success'] = true;
      $res['data'] = 'Account is deleted';
      return $res;
    }


}
