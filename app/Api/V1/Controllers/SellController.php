<?php

namespace App\Api\V1\Controllers;

use DB;
use Config;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon;
use App\User;
use App\Models\Box;
use App\Models\SellBox;
use App\Models\SellOrder;
use App\Models\BoxImage;
use App\Models\Buyer;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Donate;
use App\Models\Discount;
use Auth;
use Paypal;
use Mail;
class SellController extends PaypalPaymentController
{
    
    /**
    * Constructor
    * 
    * @param 
    * @return 
    */
    public function __construct()
    {
      parent::__construct();
    }

    /**
    * Get Boxes to sell -- There should be several box types
    * 
    * @param
    * @return Array for JSON Response
    */
    public function getAllBoxes(){
      // $sellBoxes = SellBo
      $sellBoxes = DB::table('cbox_sell_boxes')
                  ->leftjoin('cbox_images', function($join){
                        $join->on('cbox_images.box_id', '=', 'cbox_sell_boxes.id');
                  })
                  ->where('cbox_sell_boxes.del_flg', '<>', config('constants.ITEM_IS_DELETE'))
                  ->select(
                      'cbox_sell_boxes.id as id',
                      'cbox_sell_boxes.title',
                      'cbox_sell_boxes.type',
                      'cbox_sell_boxes.price',
                      'cbox_sell_boxes.detail',
                      'cbox_sell_boxes.sold_count',
                      'cbox_sell_boxes.amount',
                      'cbox_sell_boxes.main_image_url as main_image',
                      DB::raw("GROUP_CONCAT(cbox_images.image_url SEPARATOR ',') as images"),
                      DB::raw("GROUP_CONCAT(cbox_images.order SEPARATOR ',') as orders")
                      )
                  ->groupby('cbox_sell_boxes.id')
                  // ->where('cbox_images.del_flg', '<>', config('constants.ITEM_IS_DELETE'))
                  ->get();
      foreach ($sellBoxes as $sb) {
        if ($sb->images != null) {
          $sb->images = explode(',', $sb->images);
        } else {
          $sb->images = array();
        }
        if ($sb->orders != null) {
          $sb->orders = explode(',', $sb->orders);
        } else {
          $sb->orders = array();
        }
      }
      $res['success'] = true;
      $res['data'] = $sellBoxes;
      return $res;
    }

    /**
    * Redirect to paypal to pay 
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function payNow(Request $request){
      $payData = $request->only(['user_id', 'donateIds', 'donateQuantities', 'sellbox_id', 'buy_count', 'total_price', 'payer_name', 'payer_email', 'payer_address', 'payer_city', 'payer_state', 'payer_country', 'payer_comment', 'discount_code']);
      $validator = Validator::make($payData, [
          'sellbox_id' => 'required|numeric',
          'buy_count' => 'required|numeric',
          'total_price' => 'required|numeric',
          'payer_name' => 'required',
          'payer_email' => 'required'
      ]);

      $sellbox = SellBox::where('id', $payData['sellbox_id'])->first();
      if(!isset($payData['donateIds']))
        $payData['donateIds'] = '';
      if(!isset($payData['donateQuantities']))
        $payData['donateQuantities'] = $payData['buy_count'];
      if(!isset($payData['user_id']))
        $payData['user_id'] = 0;
      if(!isset($payData['discount_code']) || $payData['discount_code'] == '')
        $payData['discount_code'] = '';
      // save to buyer
      $buyerData['donateIds'] = $payData['donateIds'];
      $buyerData['donateQuantities'] = $payData['donateQuantities'];
      $buyerData['user_id'] = $payData['user_id'];
      $buyerData['name'] = $payData['payer_name'];
      $buyerData['email'] = $payData['payer_email'];
      $buyerData['address'] = $payData['payer_address'];
      $buyerData['city'] = $payData['payer_city'];
      $buyerData['state'] = $payData['payer_state'];
      $buyerData['country'] = $payData['payer_country'];
      $buyerData['comment'] = $payData['payer_comment'];

      //Calculate Total price again
      $discount = Discount::where('code', $payData['discount_code'])->first();
      $payData['total_price'] = $payData['buy_count'] * $sellbox->price;
      if($discount)
        $payData['total_price'] = round($payData['total_price'] * (100 - $discount->percent) / 100, 2);
      Buyer::unguard();
      $buyer = Buyer::create($buyerData);
      Buyer::reguard();

      $invoiceData['user_id'] = $payData['user_id'];
      $invoiceData['buyer_id'] = $buyer->id;
      $invoiceData['amount'] = $sellbox->price;
      $invoiceData['count'] = $payData['buy_count'];
      $invoiceData['type'] = config('constants.INVOICE_TYPE_BUYBOX');
      $invoiceData['status'] = config('constants.INVOICE_STATUS_NOPAY');
      $invoice_key = md5($invoiceData['user_id'].$invoiceData['buyer_id'].date('YmdHis'));
      $invoiceData['invoice_key'] = $invoice_key;

      Invoice::unguard();
      $invoice = Invoice::create($invoiceData);
      Invoice::reguard();


      $payer = PayPal::Payer();
      $payer->setPaymentMethod('paypal');

      $amount = PayPal:: Amount();
      $amount->setCurrency('USD');
      $amount->setTotal($payData['total_price']); // This is the simple way,
      // you can alternatively describe everything in the order separately;
      // Reference the PayPal PHP REST SDK for details.

      $transaction = PayPal::Transaction();
      $transaction->setAmount($amount);
      $transaction->setDescription('C-Box');

      $redirectUrls = PayPal::RedirectUrls();
      $redirectUrls->setReturnUrl(url('/api/v1/sell/postPayDone?invoice_key='.$invoice_key));
      $redirectUrls->setCancelUrl(url('/api/v1/sell/postPayCancel'));

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
    * Purchase box success
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
        $buy_count = $invoice->count;
        $buyer = Buyer::where('id', $invoice->buyer_id)->first();
        $new_order_count = 1;
        if ( $buyer->donateIds != '') {
          $donateIds = explode(",", $buyer->donateIds);
          $quantities = explode(",", $buyer->donateQuantities);
          for($i = 0; $i < count($donateIds); $i++){
            $donate = Donate::where('id', $donateIds[$i])->first();
            $donate->exist_count += $quantities[$i];
            $donate->save();
            $org = User::find($donate->org_id);
            $order['donate_id'] = $donateIds[$i];
            $order['amount'] = $quantities[$i];
            $order['name'] = $donate->name;
            $order['email'] = $org->email;
            $order['address'] = $donate->address;
            $order['city'] = $donate->city;
            $order['state'] = $donate->state;
            $order['country'] = $donate->country;
            Order::unguard();
            $order = Order::create($order);
            Order::reguard();
          }
          $new_order_count = count($donateIds);
        }
        else{
            $order['donate_id'] = 0;
            $order['amount'] = $buy_count;
            $order['name'] = $buyer->name;
            $order['email'] = $buyer->email;
            $order['address'] = $buyer->address;
            $order['city'] = $buyer->city;
            $order['state'] = $buyer->state;
            $order['country'] = $buyer->country;
            Order::unguard();
            $order = Order::create($order);
            Order::reguard();
        }
        //Send email to Admins and Droppers
        $mail_to = [];
        $admin_droppers = User::join('role_user', function($join){
                    $join->on('users.id', '=', 'role_user.user_id');
                  })
                  ->join('roles', function($join){
                    $join->on('roles.id', '=', 'role_user.role_id');
                  })
                  ->where(function($query){
                      $query->orwhere('roles.name', config('constants.ADMIN_USER'));
                      $query->orwhere('roles.name', config('constants.SHIPPER_USER'));
                  })
                  ->get();
        foreach($admin_droppers as $_user){
          $mail_to[] = $_user->email;
        }
        $mail_data = array('new_order_cnt'=>$new_order_count);
        Mail::send('mail/new_order_mail', $mail_data, function($message) use($mail_to){
           $message->to($mail_to)->subject('New orders have been arrived.');
           $message->from('noreply@milionmitzvot.com','MilionMitzvot');
        });
        return redirect('/#/home/sell;pay_success=0');
    }

    /**
    * Redirect to paypal to donate
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function donateNow(Request $request){
      $payData = $request->only(['amount', 'donateId']);
      $validator = Validator::make($payData, [
          'amount' => 'required|numeric',
          'donateId' => 'required|numeric'
      ]);
      $donate = Donate::where('id', $payData['donateId'])->first();
      $amount = $payData['amount'];
      $user_id = Auth::user()->id;
      
      $invoiceData['user_id'] = $donate->org_id;
      $invoiceData['buyer_id'] = $user_id;//This is hackable way
      $invoiceData['amount'] = $amount;
      $invoiceData['count'] = 1;
      $invoiceData['type'] = config('constants.INVOICE_TYPE_DONATION');
      $invoiceData['status'] = config('constants.INVOICE_STATUS_NOPAY');
      $invoice_key = md5($invoiceData['user_id'].$invoiceData['buyer_id'].date('YmdHis'));
      $invoiceData['invoice_key'] = $invoice_key;

      Invoice::unguard();
      $invoice = Invoice::create($invoiceData);
      Invoice::reguard();


      $payer = PayPal::Payer();
      $payer->setPaymentMethod('paypal');

      $pp_amount = PayPal::Amount();
      $pp_amount->setCurrency('USD');
      $pp_amount->setTotal($amount); // This is the simple way,
      // you can alternatively describe everything in the order separately;
      // Reference the PayPal PHP REST SDK for details.

      $transaction = PayPal::Transaction();
      $transaction->setAmount($pp_amount);
      $transaction->setDescription('C-Box Donation');

      $redirectUrls = PayPal::RedirectUrls();
      $redirectUrls->setReturnUrl(url('/api/v1/sell/donateDone?invoice_key='.$invoice_key));
      $redirectUrls->setCancelUrl(url('/api/v1/sell/donateCancel'));

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
    * Donation succeed
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function donateDone(Request $request)
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
        $data = DB::table('cbox_boxes')
                      ->leftjoin('cbox_deposits', function($join){
                            $join->on('cbox_boxes.device_id', '=', 'cbox_deposits.device_id');
                      })
                      ->leftjoin('cbox_currencyts', function($join){
                            $join->on('cbox_deposits.currencyt', '=', 'cbox_currencyts.currencyt');
                      })
                      ->where('cbox_boxes.user_id', '='  , $invoice->buyer_id)
                      ->where('cbox_deposits.del_flg', '<>'  , config('constants.ITEM_IS_NONE'))
                      ->update(['cbox_deposits.del_flg' => config('constants.ITEM_IS_NONE')]);
        $user = User::where('id', $invoice->buyer_id)->first();
        $org = User::where('id', $invoice->user_id)->first();
        $mail_data = array('user'=>$org->name, 'donator'=>$user->name, 'amount'=>$invoice->amount);
        Mail::send('mail/donation_mail', $mail_data, function($message) use($org) {
           $message->to($org->email, $org->name)->subject
              ('Received Money');
           $message->from('noreply@milionmitzvot.com','MilionMitzvot');
        });
        return redirect('/#/home/sell;pay_success=0');
    }

    /**
    * Donation cancelled
    * 
    * @param 
    * @return 
    */
    public function donateCancel()
    {
        // Curse and humiliate the user for cancelling this most sacred payment (yours)

        return redirect('/#/home/sell;pay_success=1');
    }

    /**
    * Purchase cancelled
    * 
    * @param 
    * @return 
    */
    public function payCancel()
    {
        // Curse and humiliate the user for cancelling this most sacred payment (yours)

        return redirect('/#/home/sell;pay_success=1');
    }

    /**
    * Save Donation
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function saveDonate(Request $request){
      // $data = $request->only(['name', 'city', 'country', 'commitment', 'donate_count', 'exist_count'])
      $input = $request->input();
      $userID = Auth::user()->id;
      $donate = Donate::where('org_id', $userID)->first();
      if ($donate === null) {
        $donate = new Donate();
        $donate->org_id = $userID;
      }
      $donate->name = $input['name'];
      $donate->address = $input['address'];
      $donate->city = $input['city'];
      $donate->country = $input['country'];
      $donate->commitment = $input['commitment'];
      $donate->donate_count = $input['donate_count'];
      $donate->description = $input['description'];
      $donate->category = $input['category'];


      if (!is_null($request->file('pictureFile'))){
        $file = $request->file('pictureFile');
        $extension = $file->getClientOriginalExtension(); // getting image extension

        $img_path = '/public'.config('constants.IMAGE_PATH');
        $destinationPath = base_path().$img_path;

        $destinationPath = base_path().$img_path; // upload path
        $curtime = Carbon\Carbon::now()->toDateTimeString();
        $filename = md5('donate'.$userID.$curtime).".".$extension;
        $file->move($destinationPath, $filename); // uploading file to given path
        $donate->picture = config('constants.IMAGE_PATH').$filename;
      }

      $image_origin = $request->file('image_origin');
      if (!is_null($image_origin)) {
        $destinationPath = base_path().$img_path; // upload path
        $extension = $image_origin->getClientOriginalExtension(); // getting image extension
        $fileName = md5('donateOrigin'.$userID.$curtime).".".$extension;
        $image_origin->move($destinationPath, $fileName); // uploading file to given path
        $donate->image_origin = config('constants.IMAGE_PATH').$fileName;
      }
      $donate->save();
      $res['success'] = true;
      return $res;
    }

    /**
    * Approve Donation -- Admin only
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function approveDonate(Request $request){
      $data = $request->only(['donate_id']);
      $validator = Validator::make($data, [
          'donate_id' => 'required|numeric'
      ]);
      if ($validator->fails()) {
        $res['success'] = false;
        $res['message'] = json_encode($validator->errors()->all());
        return $res;
      }
      $donate = Donate::where('id', $data['donate_id'])->first();
      if ($donate === null) {
        $donate = null;
        $res['success'] = false;
        $res['message'] = "Could not find the donation with your id.";
        return $res;
      }
      $donate->del_flg = 0;
      $donate->save();
      $res['success'] = true;
      $res['data'] = $donate;
      return $res;
    }

    /**
    * Get donation infomation
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function getCurrentDonate(Request $request){
        $userID = Auth::user()->id;
        $donate = Donate::where('org_id', $userID)->first();
        if ($donate === null) {
          $donate = null;
        }
        $res['success'] = true;
        $res['data'] = $donate;
        return $res;
    }

    /**
    * Get All Donations
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function getAllDonates(Request $request){
        $data = $request->only(['start', 'length', 'search', 'filter', 'category']);
        $validator = Validator::make($data, [
            'start' => 'required|numeric',
            'length' => 'required|numeric',
        ]);
        if ($validator->fails()) {
          $res['success'] = false;
          $res['message'] = "The data is not correct.";
          return $res;
        }

        $res['count'] = Donate::where('donate_count', '>', 'exist_count')
                          ->where('del_flg', '<>', config('constants.ITEM_IS_DELETE'))
                          ->where($data['filter'], 'LIKE', '%'.$data['search'].'%')
                          ->where('category', '=', $data['category'])
                          ->count();

        $donates = Donate::where('donate_count', '>', 'exist_count')
                          ->where('del_flg', '<>', config('constants.ITEM_IS_DELETE'))
                          ->where($data['filter'], 'LIKE', '%'.$data['search'].'%')
                          ->where('category', '=', $data['category'])
                          ->orderBy('updated_at', 'desc')
                          ->offset($data['start'])
                          ->take($data['length'])
                          ->get();
        $res['success'] = true;
        $res['data'] = $donates;
        return $res;
    }

    /**
    * Get Discount Information
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function getDiscountPercent(Request $request){
        $data = $request->only(['code']);
        $discount = Discount::where('code', $data['code'])->first();
        $res['success'] = true;
        if($discount)
          $res['percent'] = $discount->percent;
        else
          $res['percent'] = 0;
        return $res;
    }
}
