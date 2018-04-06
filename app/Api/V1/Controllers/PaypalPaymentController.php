<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Paypal;

class PaypalPaymentController extends BaseController
{
  public $_apiContext;

    /**
    * Constructor
    * 
    * @param 
    * @return 
    */
    public function __construct()
    {
        $this->_apiContext = Paypal::ApiContext(
            config('services.paypal.client_id'),
            config('services.paypal.secret'));

        $this->_apiContext->setConfig(array(
            'mode' => 'sandbox',
            'service.EndPoint' => 'https://api.sandbox.paypal.com',
            'http.ConnectionTimeOut' => 100,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path('logs/paypal.log'),
            'log.LogLevel' => 'FINE'
        ));
    }

    /**
    * Redirect user to the paypal pay screen for purchase box.
    * 
    * @param 
    * @return 
    */
    public function getCheckout()
    {
        // return;
        $payer = PayPal::Payer();
        $payer->setPaymentMethod('paypal');


        $amount = PayPal:: Amount();
        $amount->setCurrency('USD');
        $amount->setTotal(50); // This is the simple way,
        // you can alternatively describe everything in the order separately;
        // Reference the PayPal PHP REST SDK for details.

        $transaction = PayPal::Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription('C-Box');

        $redirectUrls = PayPal:: RedirectUrls();
        $redirectUrls->setReturnUrl(url('/payDone?abc=123'));
        $redirectUrls->setCancelUrl(url('/payCancel'));

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
    * Callback from Paypal -- Success Status
    * 
    * @param Request $request
    * @return string
    */
    public function getDone(Request $request)
    {
        $id = $request->get('paymentId');
        $token = $request->get('token');
        $payer_id = $request->get('PayerID');

        $payment = PayPal::getById($id, $this->_apiContext);

        $paymentExecution = PayPal::PaymentExecution();

        $paymentExecution->setPayerId($payer_id);
        $executePayment = $payment->execute($paymentExecution, $this->_apiContext);

        // Clear the shopping cart, write to database, send notifications, etc.

        // Thank the user for the purchase
        return 'Payment OK';
    }

    /**
    * Callback from Paypal -- Success Status
    * 
    * @param Request $request
    * @return String
    */
    public function getCancel()
    {
        // Curse and humiliate the user for cancelling this most sacred payment (yours)

        return 'Payment Cancel';
    }

}
