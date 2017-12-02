<?php

namespace App\Http\Controllers;

use App\Post;
use App\Caster;
use Illuminate\Http\Request;

use DB;
use Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::getAllPosts();
        return view('home')->with(['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = new Post;
        $returnpost = $post->createPost($request);

        if (is_null($post)) {
            // Return an error view or something?
        }

        $comments = $post->getComments();

        return redirect()->action('PostController@show', ['post' => $returnpost]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        // Get all comments that have post_id == $post->id
        $retpost = Caster::cast('App\Post' , $post->getById($post->id)[0]);
        return view('posts.show')->with(['post' => $retpost])->with(['comments' => $retpost->getComments()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.edit')->with(['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $returnpost = $post->updatePost($request);
        return redirect()->action('PostController@show', ['post' => $returnpost]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $number_affected = Post::deleteById($post->id);

        if ($number_affected < 1) {
            return response()->json("no post found--none deleted", 200);
        } else if ($number_affected > 1) {
            return response()->json("this shouldn't ever get here. you probably have an error on your hands", 500);
        } else {
            return redirect()->action('AdminController@showPosts', [
                'page' => 1,
                'query' => null,
                'searchString' => null,
                'error' => null
            ]);
        }
    }

    /**
     * Adds a User's vote to a specific post
     * @param  Request $request Request includes:
     *                              vote_type = "upvote" | "downvote"
     *                              return_destination = "showpost" | "home"
     * @param  Integer  $id      Post ID
     * @return Redirect           Redirect to the appropriate action based on
     *                          the request return_destination
     */
    public function vote(Request $request, $id)
    {
        if ($request->vote_type == "upvote") {
            // Log::info("Upvote!");
            $post = Caster::cast('App\Post', Post::getById($id)[0]);
            $post->addLike();
        } else if ($request->vote_type == "downvote") {
            $post = Caster::cast('App\Post', Post::getById($id)[0]);
            $post->addDislike();
        } else {
            return response()->json("Whoops! Sorry about that!", 500);
        }

        if ($request->return_destination == 'showpost') {
            return redirect()->action('PostController@show', ['post' => $post]);
        } else {
            return redirect()->action('HomeController@index', ['page' => $request->page, 'query' => $request->querystring]);
        }

    }
}
