<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function posts()
    {
        if (auth()->user()->role === 0) {
            $view = 'user.posts';
            $posts = Post::with('comments')->orderBy('created_at', 'DESC')->get();

        }elseif (auth()->user()->role === 1) {
            $view = 'operator.posts';
            $posts = Post::with('comments')->where('user_id', '!=', 2)->orderBy('created_at', 'DESC')->get();

        }elseif (auth()->user()->role === 2) {
            $view = 'admin.posts';
            $posts = Post::with('comments')->where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->get();
        }
        return view($view, compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'post'  => 'required|max:500',
            'image' => 'required|mimes:jpeg,bmp,png,jpg',
        ]);

        $image = $request->file('image')->getClientOriginalName();
        $rename = time().$image;
        $request->file('image')->move('post_images', $rename);

        $post          = new Post();
        $post->post    = $request->post;
        $post->user_id = auth()->user()->id;
        $post->status  = intval($request->status);
        $post->image   = $rename;
        $post->save();

        return redirect()->back()->with('success', 'Post published successfully');
    }
}