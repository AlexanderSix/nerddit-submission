<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\Caster;

use Illuminate\Http\Request;


use DB;
use Log;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::getAllComments();
        return view('posts.show')->with(['comments' => $comments]);

        //return response()->json($comments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO -- Make this happen with the specific post that
        // it is supposed to be tied to appearing as a variable
        // on the view
        return view('comment.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $comment = new Comment;

        if ($comment->createComment($request)) {
            $post = Post::getByid($comment->post_id)[0];
            $post = Caster::cast('App\Post', $post);

            return redirect()->action('PostController@show', ['post' => $post]);
        } else {
            $post = Post::getByid($comment->post_id)[0];
            $post = Caster::cast('App\Post', $post);

            return redirect()->action('PostController@show', ['post' => $post])->with('status', 'Could not store comment');

            // TODO: Actually test what happens if this branch executes

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //return view('posts.show')->with(['comment' => $comment]);
        $comment = Comment::getCommentById($id);

        return response()->json($comment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        return view('comments.edit')->with(['comment' => $comment]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //$comment = Comment::getById($id);

        $returncomment = $comment->updateComment($request);
        $post = Post::getByid($returncomment->post_id)[0];
        $post = Caster::cast('App\Post', $post);
        return redirect()->action('PostController@show', ['post' => $post]);
    }
}
