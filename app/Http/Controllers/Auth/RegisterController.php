<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mail;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/#/activateAccount';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'type' => 'required',
            'country' => 'required',
            'city' => 'required',
            ''
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */

    protected function register(Request $request) {
        $data = array();
        $data = $request->all();        
        $this->create($data);
    }

    protected function create(array $data)
    {        
        if($data['type'] != 'INDIVIDUAL' && $data['type'] != 'SCHOOL' && $data['type'] != 'INSTITUTION'){
            return [];
        }

        //Create User
        if($data['birthday'] == "")
            $data['birthday'] = "1900-01-01";
        if($data['address'] == null)
            $data['address'] = "";
        if($data['phone'] == null)
            $data['phone'] = "";
        if($data['bank_account'] == null)
            $data['bank_account'] = "";
        if($data['routing_number'] == null)
            $data['routing_number'] = "";
        if($data['account_number'] == null)
            $data['account_number'] = "";
        if($data['name_of_bank_account'] == null)
            $data['name_of_bank_account'] = "";
        if($data['bank_name'] == null)
            $data['bank_name'] = "";
        if($data['account_type'] == null)
            $data['account_type'] = "";

        User::unguard();
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'country' => $data['country'],
            'city' => $data['city'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'birthday' => $data['birthday'],
            'bank_account' => $data['bank_account'],
            'routing_number' => $data['routing_number'],
            'account_number' => $data['account_number'],
            'name_of_bank_account' => $data['name_of_bank_account'],
            'bank_name' => $data['bank_name'],
            'account_type' => $data['account_type'],
        ]);
        User::reguard();
        $user->assignRole($data['type']);
        $user['activation_token'] = md5($user['id'].$user['email']);
        $user->save();
        //Assign Role for the user
        //Make activation url and send via email to the user
        $url = url('/activateUser?')."token=".$user['activation_token']."&email=".$user['email'];
        $data = array('name'=>"Virat Gandhi", 'url'=>$url, 'user'=>$user);
        Mail::send('mail/activate_mail', $data, function($message) use($user) {
           $message->to($user->email, 'Tutorials Point')->subject
              ('Activate your account to start sending email');
           $message->from('noreply@milionmitzvot.com','MilionMitzvot');
        });
        return $user;
    }
}
