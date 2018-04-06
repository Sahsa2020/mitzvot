<?php
namespace App\Api\V1\Controllers;

use App\Exceptions\HttpBadRequestException;
use App\Models\Comment;
use App\Models\Post;
use Auth;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    /**
     * Fetch all comments in a post
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
             * Fetch all comments in that post
             */
            /** @noinspection PhpUndefinedFieldInspection */
            $allComments = Comment::whereOnPost($post->id)
                ->get();

            /*
             * Organize every comment info for response payload
             */
            $comments = [];
            if (count($allComments)) {
                $index = 0;
                foreach ($allComments as $comment) {
                    $comments[$index]['id'] = hashEncode($comment->id);
                    $comments[$index]['comment'] = $comment->comment;
                    $comments[$index]['is_edited'] = $comment->is_edited ? true : false;
                    $comments[$index]['commented_by']['id'] = hashEncode($comment->commentedBy->id);
                    $comments[$index]['commented_by']['name'] = $comment->commentedBy->name;
                    $comments[$index]['commented_by']['avatar'] = strlen(trim($comment->commentedBy->image_url)) ?
                        url('/') . $comment->commentedBy->image_url : null;
                    $comments[$index]['commented_by']['is_mine'] = $comment->commentedBy->id == Auth::user()->id ?
                        true : false;
                    $comments[$index]['commented_at'] = prettifyDate($comment->created_at);
                    $comments[$index]['edited_at'] = prettifyDate($comment->updated_at);
                    $index++;
                }
            }

            $response = [
                'status' => true,
                'message' => "Likes in this post have fetched successfully.",
                'comments' => $comments
            ];
            $responseCode = 200;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => "Comments for the post you are looking is no longer available.",
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
     * Make a new comment on a post
     * 
     * @param Request $request
     * @param string $postId
     * @return mixed
     */
    public function commentNew(Request $request, $postId)
    {
        try {
            /*
             * Validate mandatory field
             */
            if (!$request->has('comment'))
                throw new HttpBadRequestException("Comment field is empty.");

            /*
             * First check that post is exist or not
             */
            $post = Post::findOrFail(hashDecode($postId));

            /*
             * Make a new comment on that post
             */
            $comment = new Comment();
            $comment->comment = $request->input('comment');
            /** @noinspection PhpUndefinedFieldInspection */
            $comment->on_post = $post->id;
            $comment->commented_by = Auth::user()->id;
            $comment->save();

            $response = [
                'status' => true,
                'message' => "Your comment has posted successfully.",
                'comment' => [
                    'id' => hashEncode($comment->id),
                    'comment' => $comment->comment,
                    'commented_by' => [
                        'id' => hashEncode($comment->commentedBy->id),
                        'name' => $comment->commentedBy->name,
                        'avatar' => strlen(trim($comment->commentedBy->image_url)) ?
                            url('/') . $comment->commentedBy->image_url : null,
                        'is_mine' => $comment->commentedBy->id == Auth::user()->id ? true : false
                    ],
                    'commented_at' => prettifyDate($comment->created_at)
                ]
            ];
            $responseCode = 201;
        } catch (HttpBadRequestException $httpBadRequestException) {
            $response = [
                'status' => false,
                'error' => $httpBadRequestException->getMessage()
            ];
            $responseCode = 400;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => "The post you wanted to comment on is no longer available.",
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
     * Fetch an existing comment on a post
     * @param string $postId
     * @param string $commentId
     * @return mixed
     */
    public function getExisting($postId, $commentId)
    {
        try {
            /*
             * First check that post is exist or not
             */
            $post = Post::findOrFail(hashDecode($postId));

            /*
             * Fetch an existing comment on that post
             */
            /** @noinspection PhpUndefinedFieldInspection */
            $comment = Comment::whereOnPost($post->id)
                ->whereId(hashDecode($commentId))
                ->firstOrFail();

            $response = [
                'status' => true,
                'message' => "Comment fetched successfully.",
                'comment' => [
                    'id' => hashEncode($comment->id),
                    'comment' => $comment->comment,
                    'is_edited' => $comment->is_edited ? true : false,
                    'commented_by' => [
                        'id' => hashEncode($comment->commentedBy->id),
                        'name' => $comment->commentedBy->name,
                        'avatar' => strlen(trim($comment->commentedBy->image_url)) ?
                            url('/') . $comment->commentedBy->image_url : null,
                        'is_mine' => $comment->commentedBy->id == Auth::user()->id ? true : false
                    ],
                    'commented_at' => prettifyDate($comment->created_at),
                    'edited_at' => prettifyDate($comment->updated_at)
                ]
            ];
            $responseCode = 200;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => preg_match('/(Post)/', $modelNotFoundException->getMessage())
                    ? "The post you have commented is no longer available."
                    : "Your comment is no longer available.",
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
     * Edit an existing post on a post
     * 
     * @param Request $request
     * @param string $postId
     * @param string $commentId
     * @return mixed
     */
    public function editExisting(Request $request, $postId, $commentId)
    {
        try {
            /*
             * Validate mandatory field
             */
            if (!$request->has('comment'))
                throw new HttpBadRequestException("Comment field is empty.");

            /*
             * First check that post is exist or not
             */
            $post = Post::findOrFail(hashDecode($postId));

            /*
             * Edit an existing comment on that post
             */
            /** @noinspection PhpUndefinedFieldInspection */
            $comment = Comment::whereOnPost($post->id)
                ->whereId(hashDecode($commentId))
                ->firstOrFail();
            $comment->comment = $request->input('comment');
            $comment->is_edited = 1;
            $comment->update();

            $response = [
                'status' => true,
                'message' => "Your comment has edited successfully.",
                'comment' => [
                    'id' => hashEncode($comment->id),
                    'comment' => $comment->comment,
                    'commented_by' => [
                        'id' => hashEncode($comment->commentedBy->id),
                        'name' => $comment->commentedBy->name,
                        'avatar' => strlen(trim($comment->commentedBy->image_url)) ?
                            url('/') . $comment->commentedBy->image_url : null,
                        'is_mine' => $comment->commentedBy->id == Auth::user()->id ? true : false
                    ],
                    'commented_at' => prettifyDate($comment->created_at)
                ]
            ];
            $responseCode = 201;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => preg_match('/(Post)/', $modelNotFoundException->getMessage())
                    ? "The post you have commented is no longer available."
                    : "Your comment is no longer available.",
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
     * Delete an existing comment on a post
     * 
     * @param $postId
     * @param $commentId
     * @return mixed
     */
    public function deleteExisting($postId, $commentId)
    {
        try {
            /*
             * First check that post is exist or not
             */
            $post = Post::findOrFail(hashDecode($postId));

            /*
             * Delete an existing comment on that post
             */
            /** @noinspection PhpUndefinedFieldInspection */
            $comment = Comment::whereOnPost($post->id)
                ->whereId(hashDecode($commentId))
                ->firstOrFail();
            $comment->delete();

            $response = [
                'status' => true,
                'message' => "Your comment has deleted successfully."
            ];
            $responseCode = 200;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => preg_match('/(Post)/', $modelNotFoundException->getMessage())
                    ? "The post you have commented is no longer available."
                    : "Your comment is no longer available.",
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
