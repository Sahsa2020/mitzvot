<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mail;

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
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
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
