<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Input;
use Thujohn\Twitter\Facades\Twitter;
use Session;

class TwitterController extends Controller
{
    /**
     * Twitter OAuth Api first hit function for generating token secret
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index() {
        /* Initialize empty configuration values, in case of login it will be auto populated. */
        /** @noinspection PhpUndefinedMethodInspection */
        Twitter::reconfig(['token' => '', 'secret' => '']);

        /* Generate access token */
        /** @noinspection PhpUndefinedMethodInspection */
        $token = Twitter::getRequestToken(route('twitter.callback'));

        if (isset($token['oauth_token_secret'])) {
            /* Login using Twitter and get Callback URL */
            /** @noinspection PhpUndefinedMethodInspection */
            $url = Twitter::getAuthorizeURL($token, true, false);

            /* Save oauth responses in session for later use and redirect to Twitter Callback URL */
            Session::put('oauth_state', 'start');
            Session::put('oauth_request_token', $token['oauth_token']);
            Session::put('oauth_request_token_secret', $token['oauth_token_secret']);
            return redirect()->to($url);
        } else {
            /* Failed to generate oauth_token_secret so going to error handler */
            Session::put('twitter_lib_error', "Could not be able to generate token. Please try again later!");
            return redirect()->route('twitter.error');
        }
    }

    /**
     * Authenticating through this function after generating token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postOAuth() {
        /* In case if user refresh the page and try to hit api twice restrict user and handle Runtime Exception in this try catch */
        try {
            if (Session::has('oauth_request_token')) {
                $requestToken = [
                    'token'  => Session::get('oauth_request_token'),
                    'secret' => Session::get('oauth_request_token_secret'),
                ];
                /** @noinspection PhpUndefinedMethodInspection */
                Twitter::reconfig($requestToken);

                /** @noinspection PhpUndefinedMethodInspection */
                if (Input::has('oauth_verifier')) {
                    /** @noinspection PhpUndefinedMethodInspection */
                    $token = Twitter::getAccessToken(Input::get('oauth_verifier'));
                } else {
                    /* Failed to generate oauth_verifier so going to error handler */
                    Session::put('twitter_lib_error', "Failed to generate OAuth verifier!");
                    return redirect()->route('twitter.error');
                }

                if (! isset($token['oauth_token_secret'])) {
                    /* Failed to generate oauth_token_secret so going to error handler */
                    Session::put('twitter_lib_error', "Could not be able to generate token. Please try again later!");
                    return redirect()->route('twitter.error');
                }

                /** @noinspection PhpUndefinedMethodInspection */
                $credentials = Twitter::getCredentials();
                if (is_object($credentials) && ! isset($credentials->error)) {
                    $user = \Auth::user();
                    $user->oauth_token = $token['oauth_token'];
                    $user->oauth_token_secret = $token['oauth_token_secret'];
                    $user->user_id = $token['user_id'];
                    $user->screen_name = $token['screen_name'];
                    $user->save();

                    /* Save in session for later use */
                    Session::put('access_token', $token);
                    return redirect('/#news-feed')->with('message', 'Successfully connected in with Twitter.');
                } else {
                    /* Credentials object returns null or something went wrong */
                     return redirect()->route('twitter.error');
                }
            } else {
                /* Failed to generate oauth_token_secret so going to error handler */
                Session::put('twitter_lib_error', "Could not be able to generate token. Please try again later!");
                return redirect()->route('twitter.error');
            }
        } catch (Exception $exception) {
            Session::put(
                'twitter_lib_error',
                $exception->getMessage() . " Troubleshoot : hit /twitter/logout and try again!"
            );
            return redirect()->route('twitter.error');
        }
    }

    /**
     * Function handling twitter errors
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getErrors() {
        if (Session::has('twitter_lib_error')) {
            $error_body = Session::get('twitter_lib_error');
            Session::forget('twitter_lib_error');
            return response()->json([
                'status' => false,
                'message' => $error_body
            ], 500);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Something went wrong! Please try again later!"
            ], 500);
        }
    }

    /**
     * Disconnect from twitter OAuth
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogout(){
        Session::forget('access_token');
        $user = \Auth::user();
        $user->oauth_token = null;
        $user->oauth_token_secret = null;
        $user->user_id = null;
        $user->screen_name = null;
        $user->save();

        return redirect('/#news-feed')->with('message', 'Successfully disconnected in with Twitter.');
    }
}
