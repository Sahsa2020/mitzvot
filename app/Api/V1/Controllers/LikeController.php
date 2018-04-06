<?php

namespace App\Api\V1\Controllers;

use App\Models\Like;
use App\Models\Post;
use Auth;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class LikeController extends BaseController
{
    /**
     * Fetch all likes on a post
     * 
     * @param string $postId
     * @return mixed
     */
    public function getAll($postId)
    {
        try {
            /*
             * First check that post is exist or not
             */
            $post = Post::findOrFail(hashDecode($postId));

            /*
             * Fetch all likes on that post
             */
            /** @noinspection PhpUndefinedFieldInspection */
            $allLikes = Like::whereOnPost($post->id)->get();

            /*
             * Organize every like info for response payload
             */
            $likes = [];
            if (count($allLikes)) {
                foreach($allLikes as $key => $like) {
                    $likes[$key]['id'] = hashEncode($like->id);
                    $likes[$key]['on_post'] = $like->on_post;
                    $likes[$key]['liked_by']['id'] = hashEncode($like->likedBy->id);
                    $likes[$key]['liked_by']['name'] = $like->likedBy->name;
                    $likes[$key]['liked_by']['avatar'] = strlen(trim($like->likedBy->image_url)) ?
                        url('/') . $like->likedBy->image_url : null;
                    $likes[$key]['liked_by']['is_mine'] = $like->likedBy->id == Auth::user()->id ? true : false;
                    $likes[$key]['liked_at'] = prettifyDate($like->created_at);
                }
            }

            $response = [
                'status' => true,
                'message' => "Likes in this post have fetched successfully.",
                'likes' => $likes
            ];
            $responseCode = 200;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => "Likes for the post you are looking is no longer available.",
                'error_info' => preg_replace('/(\\[App\\\\Models\\\\)|(\\])/', '',$modelNotFoundException->getMessage())
            ];
            $responseCode = 404;
        } catch (Exception $exception) {
            $response = [
                'status' => false,
                'error' => "Internal server error",
                'error_info' => $exception->getMessage()
            ];
            $responseCode = 500;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $this->response->array($response)->setStatusCode($responseCode);
    }

    /**
     * Like a post
     * 
     * @param string $postId
     * @return mixed
     */
    public function likePost($postId)
    {
        try {
            /*
             * First check that post is exist or not
             */
            $post = Post::findOrFail(hashDecode($postId));

            /*
             * Like that post
             */
            $like = new Like();
            /** @noinspection PhpUndefinedFieldInspection */
            $like->on_post = $post->id;
            $like->liked_by = Auth::user()->id;
            $like->save();

            $response = [
                'status' => true,
                'message' => "You have liked this post.",
                'like' => [
                    'id' => hashEncode($like->id),
                    'on_post' => $like->onPost->id,
                    'liked_by' => [
                        'id' => $like->likedBy->id,
                        'name' => $like->likedBy->name,
                        'avatar' => strlen(trim($like->likedBy->image_url)) ?
                            url('/') . $like->likedBy->image_url : null,
                        'is_mine' => $like->likedBy->id == Auth::user()->id ? true : false
                    ],
                    'liked_at' => prettifyDate($like->created_at)
                ]
            ];
            $responseCode = 201;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => "The post you want to like is no longer available.",
                'error_info' => preg_replace('/(\\[App\\\\Models\\\\)|(\\])/', '',$modelNotFoundException->getMessage())
            ];
            $responseCode = 404;
        } catch (QueryException $queryException) {
            if (!empty($queryException->errorInfo) && $queryException->errorInfo[1] == 1062) {
                $response = [
                    'status' => false,
                    'error' => "You have already liked this post.",
                ];
                $responseCode = 409;
            } else {
                $response = [
                    'status' => false,
                    'error' => "Internal server error.",
                    'error_info' => $queryException->getMessage()
                ];
                $responseCode = 500;
            }
        } catch (Exception $exception) {
            $response = [
                'status' => false,
                'error' => "Internal server error",
                'error_info' => $exception->getMessage()
            ];
            $responseCode = 500;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $this->response->array($response)->setStatusCode($responseCode);
    }

    /**
     * Unlike a post
     * 
     * @param string $postId
     * @param string $likeId
     * @return mixed
     */
    public function unlikePost($postId, $likeId)
    {
        try {
            /*
             * First check that post is exist or not
             */
            $post = Post::findOrFail(hashDecode($postId));

            /*
             * Unlike that post
             */
            /** @noinspection PhpUndefinedFieldInspection */
            $like = Like::whereOnPost($post->id)
                ->whereId(hashDecode($likeId))
                ->firstOrFail();
            $like->delete();

            $response = [
                'status' => true,
                'message' => "You have unliked this post."
            ];
            $responseCode = 200;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => preg_match('/(Post)/', $modelNotFoundException->getMessage())
                    ? "The post you have liked is no longer available."
                    : "You like is no longer available.",
                'error_info' => preg_replace('/(\\[App\\\\Models\\\\)|(\\])/', '',$modelNotFoundException->getMessage())
            ];
            $responseCode = 404;
        } catch (Exception $exception) {
            $response = [
                'status' => false,
                'error' => "Internal server error",
                'error_info' => $exception->getMessage()
            ];
            $responseCode = 500;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $this->response->array($response)->setStatusCode($responseCode);
    }
}
