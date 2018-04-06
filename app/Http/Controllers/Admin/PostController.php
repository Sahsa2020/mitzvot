<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Show all paginated posts in descending order
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $posts = Post::orderByDesc('created_at')->paginate(10);

        return view('admin.posts.index', ['posts' => $posts]);
    }

    /**
     * Approve a post
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        $post = Post::find(hashDecode($id));
        $post->is_approved = 1;
        $post->update();

        return redirect()->back()->with('flash_message', "Post has been approved.");
    }

    /**
     * Disapprove a post
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disapprove($id)
    {
        $post = Post::find(hashDecode($id));
        $post->is_approved = 0;
        $post->update();

        return redirect()->back()->with('flash_message', "Post has been disapproved.");
    }

    /**
     * Delete a post
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        Post::find(hashDecode($id))->delete();

        return redirect()->back()->with('flash_message', "Post has been deleted.");
    }
}
