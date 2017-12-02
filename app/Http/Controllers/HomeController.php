<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Post;

use Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the PAGED application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($page = null, $query = null, $searchString = null)
    {
        $total_pages = Post::getTotalPages();
        if (is_null($page) || $page > $total_pages) {
            $page = 1;
        }
        $posts = Post::getPagedPosts($page, $query, $searchString);

        return view('home')->with(['posts' => $posts])->with(['page' => $page])->with(['total_pages' => $total_pages])->with(['query' => $query])->with(['searchString' => $searchString]);
    }

    public function searchPosts(Request $request)
    {
        return redirect()->action('HomeController@index', ['page' => 1, 'query' => "search", 'searchString' => $request['searchString']]);
    }
}
