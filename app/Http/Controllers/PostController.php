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
            $posts = Post::with('comments', 'user')->orderBy('created_at', 'DESC')->get();

        }elseif (auth()->user()->role === 1) {
            $view = 'operator.posts';
            $posts = Post::with('comments', 'user')->orderBy('created_at', 'DESC')->get();

        }elseif (auth()->user()->role === 2) {
            $view = 'admin.posts';
            $posts = Post::with('comments', 'user')->orderBy('created_at', 'DESC')->get();
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

    public function destroy($id)
    {
        $image = Post::find($id)->image;

        unlink('post_images/'.$image);

        Post::destroy($id);

        return redirect()->back()->with('error', 'Post deleted successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'post'  => 'required|max:500',
        ]);

        $post = Post::find($id);

        if ($request->hasFile('image')) {
            
            unlink('post_images/'.$post->image);

            $image = $request->file('image')->getClientOriginalName();
            $rename = time().$image;
            $request->file('image')->move('post_images', $rename);

            $post->image   = $rename;
        }

        $post->post    = $request->post;
        $post->status  = intval($request->status);
        $post->save();

        return redirect()->back()->with('success', 'Post updated successfully');
    }
}