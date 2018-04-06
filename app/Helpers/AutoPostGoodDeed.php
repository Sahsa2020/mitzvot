<?php

namespace App\Helpers;

use App\Models\Post;
use Auth;
use DB;
use Exception;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Log;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Thujohn\Twitter\Facades\Twitter;

/**
 * Helper Class for auto posting good deed after completing goals
 *
 * @class AutoPostGoodDeed
 * @package App\Helpers
 */
class AutoPostGoodDeed
{
    /**
     * Automatically post good deed on Mitzvot, Facebook and Twitter
     *
     * @param int $amount
     * @return array
     */
    public static function post($amount)
    {
        try {
            DB::beginTransaction();

            $content = Auth::user()->name . " donated " . $amount . " coins.";

            $post = new Post();
            $post->content = $content;
            $post->posted_by = Auth::user()->id;
            $post->is_approved = 1;
            $post->save();

            $facebook = app(LaravelFacebookSdk::class);
            $facebookPostResponse = $facebook->post('/me/feed', [
                'message' => $content,
                'link' => url('/api/v1/posts/') . "/" . hashEncode($post->id)
            ], Auth::user()->access_token);

            /** @noinspection PhpUndefinedMethodInspection */
            $twitterTweetResponse = Twitter::postTweet([
                'status' => $content . "\n" . url('/api/v1/posts/') . "/" . hashEncode($post->id),
                'format' => 'json'
            ]);

            $response = [
                'status' => true,
                'facebook_post_response' => $facebookPostResponse->getDecodedBody(),
                'twitter_tweet_response' => $twitterTweetResponse ? json_decode($twitterTweetResponse, true) : $twitterTweetResponse,
            ];
        } catch (FacebookResponseException $facebookResponseException) {
            DB::rollBack();
            Log::info($facebookResponseException->getMessage());

            $response = [
                'status' => false,
                'error' => "Graph API returned an error.",
                'error_info' => $facebookResponseException->getMessage()
            ];
        } catch (FacebookSDKException $facebookSDKException) {
            DB::rollBack();
            Log::info($facebookSDKException->getMessage());

            $response = [
                'status' => false,
                'error' => "Facebook SDK returned an error.",
                'error_info' => $facebookSDKException->getMessage()
            ];
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());

            $response = [
                'status' => false,
                'error' => "Something went wrong.",
                'error_info' => $exception->getMessage()
            ];
        } finally {
            DB::commit();
        }

        return $response;
    }
}
