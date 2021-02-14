<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required'
        ]);

        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->post_id = $id;
        $comment->user_id = auth()->user()->id;
        $comment->status = 1;
        $comment->save();

        return redirect()->back()->with('success', 'Thanks for your comment');
    }
}
