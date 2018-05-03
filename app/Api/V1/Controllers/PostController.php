<?php

namespace App\Api\V1\Controllers;

use App\Exceptions\HttpBadRequestException;
use App\Models\Post;
use App\Models\Friend;
use App\Models\PostedMedia;
use Auth;
use DB;
use Exception;
use finfo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    /**
     * Fetch all posts
     *
     * @return mixed
     */
    public function getAll()
    {
        try {
            /*
             * Fetch all posts
             */
            $allPosts = Post::orderByDesc('created_at')->get();
            $posts = $this->organizePosts($allPosts);
            $response = [
                'status' => true,
                'message' => "All posts are fetched successfully.",
                'user' => [
                    'name' => Auth::user()->name,
                    'avatar' => Auth::user()->image_url ? url('/') . Auth::user()->image_url : null
                ],
                'posts' => $posts
            ];
            $responseCode = 200;
        } catch (Exception $exception) {
            $response = ['status' => false,
                'error' => "Internal server error",
                'error_info' => $exception->getMessage()];
            $responseCode = 500;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $this->response->array($response)->setStatusCode($responseCode);
    }

    public function getFriendsPosts(Request $request)
    {
        try {
            /*
             * Fetch all posts
             */
            $user = Auth::user();
            $userID = $user->id;
            $friendPosts = Post::join('users', function($join) {
                $join->on('posts.posted_by', '=', 'users.id');
            })->join('friends', function($join) {
                $join->on('friends.friend_id', '=', 'users.id');
            })->where('friends.user_id', '=', $userID)->orderByDesc('posts.created_at')->get();
            $posts = $this->organizePosts($friendPosts);
            $response = [
                'status' => true,
                'message' => "All posts are fetched successfully.",
                'user' => [
                    'name' => Auth::user()->name,
                    'avatar' => Auth::user()->image_url ? url('/') . Auth::user()->image_url : null
                ],
                'posts' => $posts
            ];
            $responseCode = 200;
        } catch (Exception $exception) {
            $response = ['status' => false,
                'error' => "Internal server error",
                'error_info' => $exception->getMessage()];
            $responseCode = 500;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $this->response->array($response)->setStatusCode($responseCode);
    }

    public function getMyPosts(Request $request)
    {
        try {
            $user = Auth::user();
            $userID = $user->id;
            $myPosts = Post::where("posts.posted_by", "=", $userID)->orderByDesc('created_at')->get();
            $posts = $this->organizePosts($myPosts);
            $response = [
                'status' => true,
                'message' => "All posts are fetched successfully.",
                'user' => [
                    'name' => Auth::user()->name,
                    'avatar' => Auth::user()->image_url ? url('/') . Auth::user()->image_url : null
                ],
                'posts' => $posts
            ];
            $responseCode = 200;
        } catch (Exception $exception) {
            $response = ['status' => false,
                'error' => "Internal server error",
                'error_info' => $exception->getMessage()];
            $responseCode = 500;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $this->response->array($response)->setStatusCode($responseCode);
    }

    /**
     * Create a new post
     *
     * @param Request $request
     * @return mixed
     */
    public function postNew(Request $request)
    {
        try {
            /*
             * Validate mandatory fields
             */
            if (!$request->has('content'))
                throw new HttpBadRequestException("Content is required for posting an update.");

            DB::beginTransaction();

            /*
             * Create a new post
             */
            $post = new Post();
            $post->content = $request->input('content');
            $post->posted_by = Auth::user()->id;
            $post->save();

            /*
             * If post has media like image or video or both
             * then, save those contents
             */
            if ($request->hasFile('image')) {
                /*
                 * Check uploaded image is a valid image or not
                 */
                if (preg_match('/(image)/', (new finfo(FILEINFO_MIME))->file($request->file('image')->getPathname()))) {
                    $postedMedia = new PostedMedia();
                    $postedMedia->media_content_path = "/" . $request->file('image')->move(
                            'uploaded/posted_medias/',time() . "_" . $request->file('image')->getClientOriginalName());
                    $postedMedia->media_type = 0;
                    $postedMedia->in_post = $post->id;
                    $postedMedia->save();

                    /* If post has media then update post entity with that info */
                    $post->has_media_content = 1;
                } else throw new HttpBadRequestException("Uploaded image is not a valid image file.");
            }
            if ($request->hasFile('video')) {
                /*
                 * Check uploaded image is a valid video or not
                 */
                if (preg_match('/(video)/', (new finfo(FILEINFO_MIME))->file($request->file('video')->getPathname()))) {
                    $postedMedia = new PostedMedia();
                    $postedMedia->media_content_path = "/" . $request->file('video')->move(
                            'uploaded/posted_medias/', time() . "_" .$request->file('video')->getClientOriginalName());
                    $postedMedia->media_type = 1;
                    $postedMedia->in_post = $post->id;
                    $postedMedia->save();

                    /* If post has media then update post entity with that info */
                    $post->has_media_content = 1;
                } else throw new HttpBadRequestException("Uploaded video is not a valid video file.");
            }
            /* Finally update post info */
            $post->update();

            /** @noinspection PhpUndefinedFieldInspection */
            $response = [
                'status' => true,
                'message' => "Your good deed has posted successfully and waiting for approval.",
                'post' => [
                    'id' => hashEncode($post->id),
                    'content' => $post->content,
                    'posted_image' => $post->postedImage() ? url('/') . $post->postedImage()->media_content_path : null,
                    'posted_video' => $post->postedVideo() ? url('/') . $post->postedVideo()->media_content_path : null,
                    'is_hidden' => $post->is_hidden ? $post->is_hidden : false,
                    'is_approved' => $post->is_approved ? $post->is_approved : false,
                    'posted_by' => [
                        'id' => hashEncode($post->postedBy->id),
                        'name' => $post->postedBy->name,
                        'avatar' => $post->postedBy->image_url ? url('/') . $post->postedBy->image_url : null,
                        'is_mine' => $post->postedBy->id == Auth::user()->id ? true : false
                    ],
                    'posted_at' => prettifyDate($post->created_at)
                ]
            ];
            $responseCode = 201;
        } catch (HttpBadRequestException $httpBadRequestException) {
            DB::rollBack();

            $response = [
                'status' => false,
                'error' => $httpBadRequestException->getMessage()
            ];
            $responseCode = 400;
        } catch (Exception $exception) {
            DB::rollBack();

            $response = [
                'status' => false,
                'error' => "Internal server error",
                'error_info' => $exception->getMessage()
            ];
            $responseCode = 500;
        } finally {
            DB::commit();
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $this->response->array($response)->setStatusCode($responseCode);
    }

    /**
     * Fetch an existing post details
     *
     * @param string $id
     * @return mixed
     */
    public function getExisting($id)
    {
        try {
            /*
             * Fetch an existing post
             */
            $postInfo = Post::findOrFail(hashDecode($id));

            /*
             * Organize post info for response payload
             */
            /** @noinspection PhpUndefinedFieldInspection */
            $post['id'] = hashEncode($postInfo->id);
            /** @noinspection PhpUndefinedFieldInspection */
            $post['content'] = $postInfo->content;
            /** @noinspection PhpUndefinedMethodInspection */
            $post['image'] = $postInfo->postedImage() ? url('/') . $postInfo->postedImage()->media_content_path : null;
            /** @noinspection PhpUndefinedMethodInspection */
            $post['video'] = $postInfo->postedVideo() ? url('/') . $postInfo->postedVideo()->media_content_path : null;
            /** @noinspection PhpUndefinedFieldInspection */
            $post['is_hidden'] = $postInfo->is_hidden ? true : false;
            /** @noinspection PhpUndefinedFieldInspection */
            $post['is_edited'] = $postInfo->is_edited ? true : false;
            /** @noinspection PhpUndefinedFieldInspection */
            $post['is_approved'] = $postInfo->is_approved ? true : false;
            /** @noinspection PhpUndefinedFieldInspection */
            $post['posted_by']['id'] = hashEncode($postInfo->postedBy->id);
            /** @noinspection PhpUndefinedFieldInspection */
            $post['posted_by']['name'] = $postInfo->postedBy->name;
            /** @noinspection PhpUndefinedFieldInspection */
            $post['posted_by']['avatar'] = strlen(trim($postInfo->postedBy->image_url)) ?
                url('/') . $postInfo->postedBy->image_url : null;
            /** @noinspection PhpUndefinedFieldInspection */
            $post['posted_by']['is_mine'] = $postInfo->postedBy->id == Auth::user()->id ? true : false;
            if (count($postInfo->comments->toArray())) {
                foreach ($postInfo->comments as $key => $comment) {
                    $post['comments'][$key]['id'] = hashEncode($comment->id);
                    $post['comments'][$key]['comment'] = $comment->comment;
                    $post['comments'][$key]['is_edited'] = $comment->is_edited ? true : false;
                    $post['comments'][$key]['commented_by']['id'] =
                        hashEncode($comment->commentedBy->id);
                    $post['comments'][$key]['commented_by']['name'] = $comment->commentedBy->name;
                    $post['comments'][$key]['commented_by']['avatar'] =
                        strlen(trim($comment->commentedBy->image_url)) ?
                            url('/') . $comment->commentedBy->image_url : null;
                    $post['comments'][$key]['commented_by']['is_mine'] =
                        $comment->commentedBy->id == Auth::user()->id ? true : false;
                    $post['comments'][$key]['commented_at'] = prettifyDate($comment->created_at);
                    $post['comments'][$key]['edited_at'] = prettifyDate($comment->updated_at);
                }
            } else $post['comments'] = [];
            if (count($postInfo->likes)) {
                foreach($postInfo->likes as $key => $like) {
                    $post['likes'][$key]['id'] = hashEncode($like->id);
                    $post['likes'][$key]['liked_by']['id'] = hashEncode($like->likedBy->id);
                    $post['likes'][$key]['liked_by']['name'] = $like->likedBy->name;
                    $post['likes'][$key]['liked_by']['avatar'] =
                        strlen(trim($like->likedBy->image_url)) ? url('/') . $like->likedBy->image_url : null;
                    $post['likes'][$key]['liked_by']['is_mine'] =
                        $like->likedBy->id == Auth::user()->id ? true : false;
                    $post['likes'][$key]['liked_at'] = $like->liked_at;
                }
            } else $post['likes'] = [];
            /** @noinspection PhpUndefinedFieldInspection */
            $post['posted_at'] = prettifyDate($postInfo->created_at);
            /** @noinspection PhpUndefinedFieldInspection */
            $post['edited_at'] = prettifyDate($postInfo->updated_at);

            $response = [
                'status' => true,
                'message' => "Post fetched successfully",
                'post' => $post
            ];
            $responseCode = 200;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => "The post you are looking for is no longer available.",
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
     * Edit an existing post details
     *
     * @param Request $request
     * @param string $id
     * @return mixed
     */
    public function editExisting(Request $request, $id)
    {
        try {
            /*
             * Validate mandatory fields
             */
            if (!$request->has('content'))
                throw new HttpBadRequestException("Content is required for posting an update.");

            DB::beginTransaction();

            /*
             * Edit an existing post
             */
            $post = Post::findOrFail(hashDecode($id));
            /** @noinspection PhpUndefinedFieldInspection */
            $post->content = $request->input('content');
            /** @noinspection PhpUndefinedFieldInspection */
            $post->is_edited = Auth::user()->hasRole('ADMIN') ? $post->is_edited : 1;
            /** @noinspection PhpUndefinedFieldInspection */
            $post->is_approved = Auth::user()->hasRole('ADMIN') ? $request->input('is_approved') : 0;
            /** @noinspection PhpUndefinedFieldInspection */
            $post->has_media_content =
                ($request->input('is_image_deleted') && $request->input('is_video_deleted')) ? 0 : 1;

            /*
             * If post has image content uploaded
             * then, if previously had any image content then replace that
             * else save new image content path
             */
            if ($request->hasFile('image')) {
                /*
                 * Check uploaded image is a valid image or not
                 * if valid then save it into filesystem
                 * else throw new bad request exception
                 */
                if (preg_match('/(image)/', (new finfo(FILEINFO_MIME))->file($request->file('image')->getPathname()))) {
                    $newImagePath = "/" . $request->file('image')->move('uploaded/posted_medias/', time() . "_" .
                        $request->file('image')->getClientOriginalName());
                } else throw new HttpBadRequestException("Uploaded image is not a valid image file.");

                if ($post->postedImage()) {
                    /* Delete previous image from file system */
                    unlink(getcwd() . $post->postedImage()->media_content_path);
                    /* Update the new image content path into dataBase */
                    $postedImage = $post->postedImage();
                    $postedImage->media_content_path = $newImagePath;
                    /** @noinspection PhpUndefinedMethodInspection */
                    $postedImage->update();
                } else {
                    /* Save new image content path into dataBase */
                    $postedImage = new PostedMedia();
                    $postedImage->media_content_path = $newImagePath;
                    $postedImage->media_type = 0;
                    /** @noinspection PhpUndefinedFieldInspection */
                    $postedImage->in_post = $post->id;
                    $postedImage->save();

                    /* If post has media then update post entity with that info */
                    /** @noinspection PhpUndefinedFieldInspection */
                    $post->has_media_content = 1;
                }
            } else if ((int) $request->input('is_image_deleted') && $post->postedImage()) {
                /* Delete previous image from file system */
                unlink(getcwd() . $post->postedImage()->media_content_path);
                /* Delete previous image content path from dataBase */
                $post->postedImage()->delete();
            }

            /*
             * If post has video content uploaded
             * then, if previously had any video content then replace that
             * else save new video content path
             */
            if ($request->hasFile('video')) {
                /*
                 * Check uploaded video is a valid video or not
                 * if valid then save it into filesystem
                 * else throw new bad request exception
                 */
                if (preg_match('/(video)/', (new finfo(FILEINFO_MIME))->file($request->file('video')->getPathname()))) {
                    $newVideoPath = "/" . $request->file('video')->move('uploaded/posted_medias/', time() . "_" .
                        $request->file('video')->getClientOriginalName());
                } else throw new HttpBadRequestException("Uploaded video is not a valid video file.");

                if ($post->postedVideo()) {
                    /* Delete previous video from file system */
                    unlink(getcwd() . $post->postedVideo()->media_content_path);
                    /* Update the new video content path into dataBase */
                    $postedVideo = $post->postedVideo();
                    $postedVideo->media_content_path = $newVideoPath;
                    /** @noinspection PhpUndefinedMethodInspection */
                    $postedVideo->update();
                } else {
                    /* Save new video content path into dataBase */
                    $postedVideo = new PostedMedia();
                    $postedVideo->media_content_path = $newVideoPath;
                    $postedVideo->media_type = 1;
                    /** @noinspection PhpUndefinedFieldInspection */
                    $postedVideo->in_post = $post->id;
                    $postedVideo->save();

                    /* If post has media then update post entity with that info */
                    /** @noinspection PhpUndefinedFieldInspection */
                    $post->has_media_content = 1;
                }
            } else if ((int) $request->input('is_video_deleted') && $post->postedVideo()) {
                /* Delete previous video from file system */
                unlink(getcwd() . $post->postedVideo()->media_content_path);
                /* Delete previous video content path from dataBase */
                $post->postedVideo()->delete();
            }

            /* Finally update post info */
            $post->update();

            /** @noinspection PhpUndefinedMethodInspection */
            /** @noinspection PhpUndefinedFieldInspection */
            $response = [
                'status' => true,
                'message' => "Your post has edited successfully and waiting for approval.",
                'post' => [
                    'id' => hashEncode($post->id),
                    'content' => $post->content,
                    'posted_image' => $post->postedImage() ? url('/') . $post->postedImage()->media_content_path : null,
                    'posted_video' => $post->postedVideo() ? url('/') . $post->postedVideo()->media_content_path : null,
                    'is_hidden' => $post->is_hidden ? $post->is_hidden : false,
                    'is_approved' => $post->is_approved ? $post->is_approved : false,
                    'posted_by' => [
                        'id' => hashEncode($post->postedBy->id),
                        'name' => $post->postedBy->name,
                        'avatar' => $post->postedBy->image_url ? url('/') . $post->postedBy->image_url : null,
                        'is_mine' => $post->postedBy->id == Auth::user()->id ? true : false
                    ],
                    'posted_at' => prettifyDate($post->created_at)
                ]
            ];
            $responseCode = 201;
        } catch (HttpBadRequestException $httpBadRequestException) {
            DB::rollBack();

            $response = [
                'status' => false,
                'error' => $httpBadRequestException->getMessage()
            ];
            $responseCode = 400;
        } catch (ModelNotFoundException $modelNotFoundException) {
            DB::rollBack();

            $response = [
                'status' => false,
                'error' => "No post has been found.",
                'error_info' => preg_replace('/(\\[App\\\\Models\\\\)|(\\])/', '',$modelNotFoundException->getMessage())
            ];
            $responseCode = 404;
        } catch (Exception $exception) {
            DB::rollBack();

            $response = [
                'status' => false,
                'error' => "Internal server error",
                'error_info' => $exception->getMessage()
            ];
            $responseCode = 500;
        } finally {
            DB::commit();
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $this->response->array($response)->setStatusCode($responseCode);
    }

    /**
     * Delete an existing post
     *
     * @param string $id
     * @return mixed
     */
    public function deleteExisting($id)
    {
        try {
            /*
             * Delete an existing post
             */
            $postInfo = Post::findOrFail(hashDecode($id));
            $postInfo->delete();

            $response = [
                'status' => true,
                'message' => "Post has deleted successfully."
            ];
            $responseCode = 200;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $response = [
                'status' => false,
                'error' => "No post has been found.",
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

    private function organizePosts($allPosts)
    {
        /*
        * Organize every post info for response payload
        */
        $posts = [];
        if (count($allPosts)) {
            $index = 0;
            foreach ($allPosts as $post) {
                $posts[$index]['id'] = hashEncode($post->id);
                $posts[$index]['content'] = $post->content;
                /** @noinspection PhpUndefinedMethodInspection */
                $posts[$index]['image'] = $post->postedImage() ?
                    url('/') . $post->postedImage()->media_content_path : null;
                /** @noinspection PhpUndefinedMethodInspection */
                $posts[$index]['video'] = $post->postedVideo() ?
                    url('/') . $post->postedVideo()->media_content_path : null;
                $posts[$index]['is_hidden'] = $post->is_hidden ? true : false;
                $posts[$index]['is_edited'] = $post->is_edited ? true : false;
                $posts[$index]['is_approved'] = $post->is_approved ? true : false;
                $posts[$index]['posted_by']['id'] = hashEncode($post->postedBy->id);
                $posts[$index]['posted_by']['name'] = $post->postedBy->name;
                $posts[$index]['posted_by']['avatar'] = strlen(trim($post->postedBy->image_url)) ?
                    url('/') . $post->postedBy->image_url : null;
                $posts[$index]['posted_by']['is_mine'] = $post->postedBy->id == Auth::user()->id ? true : false;
                if (count($post->comments)) {
                    foreach ($post->comments as $key => $comment) {
                        $posts[$index]['comments'][$key]['id'] = hashEncode($comment->id);
                        $posts[$index]['comments'][$key]['comment'] = $comment->comment;
                        $posts[$index]['comments'][$key]['is_edited'] = $comment->is_edited ? true : false;
                        $posts[$index]['comments'][$key]['commented_by']['id'] =
                            hashEncode($comment->commentedBy->id);
                        $posts[$index]['comments'][$key]['commented_by']['name'] = $comment->commentedBy->name;
                        $posts[$index]['comments'][$key]['commented_by']['avatar'] =
                            strlen(trim($comment->commentedBy->image_url)) ?
                                url('/') . $comment->commentedBy->image_url : null;
                        $posts[$index]['comments'][$key]['commented_by']['is_mine'] =
                            $comment->commentedBy->id == Auth::user()->id ? true : false;
                        $posts[$index]['comments'][$key]['commented_at'] = prettifyDate($comment->created_at);
                        $posts[$index]['comments'][$key]['edited_at'] = prettifyDate($comment->updated_at);
                    }
                } else $posts[$index]['comments'] = [];
                if (count($post->likes)) {
                    foreach($post->likes as $key => $like) {
                        $posts[$index]['likes'][$key]['id'] = hashEncode($like->id);
                        $posts[$index]['likes'][$key]['liked_by']['id'] = hashEncode($like->likedBy->id);
                        $posts[$index]['likes'][$key]['liked_by']['name'] = $like->likedBy->name;
                        $posts[$index]['likes'][$key]['liked_by']['avatar'] =
                            strlen(trim($like->likedBy->image_url)) ? url('/') . $like->likedBy->image_url : null;
                        $posts[$index]['likes'][$key]['liked_by']['is_mine'] =
                            $like->likedBy->id == Auth::user()->id ? true : false;
                        $posts[$index]['likes'][$key]['liked_at'] = $like->liked_at;
                    }
                } else $posts[$index]['likes'] = [];
                $posts[$index]['posted_at'] = prettifyDate($post->created_at);
                $posts[$index]['edited_at'] = prettifyDate($post->updated_at);
                $index++;
            }
        }
        return $posts;
    }
}
