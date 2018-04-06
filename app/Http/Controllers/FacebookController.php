<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Facebook\Exceptions\FacebookSDKException;
use Log;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Session;

class FacebookController extends Controller
{
    /**
     * Handle Facebook login
     * 
     * @param  LaravelFacebookSdk $facebook
     * @return  mixed
     */
    public function facebookLogin(LaravelFacebookSdk $facebook)
    {
        return redirect($facebook->getLoginUrl([
            'email',
            'user_birthday',
            'public_profile',
            'user_about_me',
            'user_birthday',
            'user_events',
            'user_friends',
            'user_hometown',
            'user_photos',
            'user_posts',
            'user_religion_politics',
            'publish_actions'
        ]));
    }

    /**
     * Handle events post Facebook login
     * 
     * @param  LaravelFacebookSdk $facebook
     * @return mixed
     */
    public function facebookCallback(LaravelFacebookSdk $facebook)
    {
        try {
            /* Obtain an access token. */
            $token = $facebook->getAccessTokenFromRedirect();
            /*
             * Access token will be null if the user denied the request
             * or if someone just hit this URL outside of the OAuth flow.
             */
            if (! $token) {
                /* Get the redirect helper */
                $helper = $facebook->getRedirectLoginHelper();
                if (! $helper->getError()) {
                    abort(403, 'Unauthorized action.');
                }

                /* User denied the request */
                Log::info(
                    $helper->getError() .
                    $helper->getErrorCode() .
                    $helper->getErrorReason() .
                    $helper->getErrorDescription()
                );
            }
            if (! $token->isLongLived()) {
                /* OAuth 2.0 client handler */
                $oauth_client = $facebook->getOAuth2Client();

                /* Extend the access token. */
                $token = $oauth_client->getLongLivedAccessToken($token);
            }
            $facebook->setDefaultAccessToken($token);

            /* Save in session for later use */
            Session::put('facebook_access_token', (string) $token);

            /* Get basic info on the user from Facebook. */
            $response = $facebook->get('/me?fields=id,name,email');

            $facebookUser = $response->getGraphUser();
            $user = User::createOrUpdateGraphNode($facebookUser);
            /** @noinspection PhpUndefinedFieldInspection */
            $user->access_token = $token;
            $user->save();
        } catch (FacebookSDKException $facebookSDKException) {
            Log::info($facebookSDKException->getMessage());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }

        return redirect('/#news-feed')->with('message', 'Successfully connected in with Facebook.');
    }
}
