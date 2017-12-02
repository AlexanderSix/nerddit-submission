<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Comment;

use DB;
use Log;
use Auth;
use Carbon\Carbon;

class Post extends Model
{

    // Can be changed--will affect the paging algorithm
    const POSTS_PER_PAGE = 20;

    /**
     * Returns all columns for all entries in the 'posts' table
     * @return array Collection of all posts in the posts table
     */
    public static function getAllPosts()
    {
        return DB::select('SELECT * FROM posts');
    }

    /**
     * Get a post from the table by the associated user_id
     * @param  integer $user_id  The ID associated with a User
     * @return Post              The latest post from the specified User
     */
    public function getLatestPostByUserId($user_id)
    {
        $ret = DB::select('SELECT * FROM posts WHERE user_id = :user_id ORDER BY id DESC LIMIT 1', ['user_id' => $user_id])[0];
        return Caster::cast('App\Post', $ret);
    }

    /**
     * Gets post content by the corresponding post_id
     * @param  integer $id  The corresponding post ID
     * @return array        Array containing post content
     */
    public static function getContent($id)
    {
        return DB::select('SELECT content FROM posts WHERE id = :id', ['id' => $id]);
    }

    /**
     * Gets the user ID of the User who created the Post
     * @param  integer $id  Post ID
     * @return array        Array containing the corresponding user_id
     */
    public static function getUserId($id)
    {
        return DB::select('SELECT user_id FROM posts WHERE id = :id', ['id' => $id]);
    }

    /**
     * Get a post from the table by their ID
     * @param  integer $id  The ID associated with a Post
     * @return array        An array containing the Post entry from the posts table
     */
    public static function getById($id)
    {
        return DB::select('SELECT username, posts.id, user_id, title, content, link, likes, posts.created_at, posts.updated_at FROM posts INNER JOIN users ON posts.user_id = users.id WHERE posts.id = :post_id LIMIT 1;', ['post_id' => $id]);
    }

    /**
     * Delete a Post entry from the DB by their ID
     * @param  integer $id  The ID associated with a Post
     * @return mixed        Rows deleted | null
     */
    public static function deleteById($id)
    {
        return DB::delete("DELETE FROM posts WHERE id = :id", ['id' => $id]);
    }

    /**
     * Inserts a Post object into the posts table
     * @param  Request $request Request from the view
     * @return Post             The Post that was just inserted
     */
    public function createPost($request)
    {
        $now = Carbon::now();

        $did_insert = DB::insert("INSERT INTO posts (
            user_id,
            title,
            content,
            link,
            likes,
            created_at,
            updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?)", [
            Auth::user()->id,
            $request->title,
            $request->content,
            $request->link,
            0,
            $now,
            $now
        ]);


        $ret = $this->getLatestPostByUserId(Auth::user()->id);
        log::info($ret);
        return $ret;
    }

    /**
     * Updates a Post
     * @param  Request $request Request object from the PHP script
     * @return Post             Post object that was updated
     */
    public function updatePost($request)
    {
        $now = Carbon::now();

        $SQL_statement = "UPDATE posts SET user_id = " .
            Auth::user()->id .
            ", title = '" .
            $request->title .
            "', content = '" .
            $request->content .
            "', link = '" .
            $request->link .
            "', updated_at = '" .
            $now . "' WHERE id = " . $this->id;

        $did_update = DB::connection()->getPdo()->exec($SQL_statement);

        $ret = $this->getById($this->id);
        return Caster::cast('App\Post', $ret[0]);
    }

    /**
     * Gets all comments associated with a particular post
     * @return array Collection of comments
     */
    public function getComments()
    {
        return Comment::getCommentsByPostId($this->id);
    }

    /**
     * Increments the Post's likes
     */
    public function addLike()
    {
        DB::connection()->getPdo()->exec(
            "UPDATE posts SET likes = likes+1 WHERE ID = " . $this->id
        );
    }

    /**
     * Decrements the Post's likes
     */
    public function addDislike()
    {
        DB::connection()->getPdo()->exec(
            "UPDATE posts SET likes = likes-1 WHERE ID = " . $this->id
        );
    }

    /**
     * Gets the offset for the page
     * @param integer $page The current page number
     */
    private static function getPageOffset($page)
    {
        return (($page-1) * Post::POSTS_PER_PAGE);
    }

    /**
     * Gets the Post entries for a specific page
     * @param  integer $page            The page number
     * @param  string  $query           A query
     * @param  string  $searchString    A search string
     * @return array                    The array containing the pages posts
     */
    public static function getPagedPosts($page, $query = null, $searchString = null)
    {
        if (is_null($query) || $query == 'mostrecent') {
            $SQL_statement = "SELECT username, posts.id, user_id, title, content, link, likes, posts.created_at, posts.updated_at FROM users INNER JOIN posts ON users.id = posts.user_id ORDER BY created_at DESC LIMIT " . Post::getPageOffset($page)
                . ", " . Post::POSTS_PER_PAGE;
        } else if ($query == 'mostLikes') {
            $SQL_statement = "SELECT username, posts.id, user_id, title, content, link, likes, posts.created_at, posts.updated_at FROM users INNER JOIN posts ON users.id = posts.user_id ORDER BY likes DESC LIMIT " . Post::getPageOffset($page)
                . ", " . Post::POSTS_PER_PAGE;
        } else if ($query == 'leastLikes') {
            $SQL_statement = "SELECT username, posts.id, user_id, title, content, link, likes, posts.created_at, posts.updated_at FROM users INNER JOIN posts ON users.id = posts.user_id ORDER BY likes ASC LIMIT " . Post::getPageOffset($page)
                . ", " . Post::POSTS_PER_PAGE;
        } else if ($query == 'search' && !is_null($searchString)) {
            $SQL_statement = "SELECT username, posts.id, user_id, title, content, link, likes, posts.created_at, posts.updated_at FROM users INNER JOIN posts ON users.id = posts.user_id WHERE title LIKE '%" . $searchString . "%' ORDER BY created_at DESC LIMIT " . Post::getPageOffset($page)
                . ", " . Post::POSTS_PER_PAGE;
        }


        // Get the posts dependant upon their page
        return DB::select($SQL_statement);
    }

    /**
     * Gets the total pages needed to hold all the posts in the DB
     * @return Integer The number of pages needed to hold all the posts
     */
    public static function getTotalPages()
    {
        $PDO_query = DB::connection()->getPdo()->query(
            "SELECT * FROM `posts`"
        );
        $row_count = $PDO_query->rowCount();

        return (int) ceil($row_count / Post::POSTS_PER_PAGE);
    }

    /**
     * Gets the username and post data
     * @param  integer $user_id     The specified User's ID
     * @return array                A collection of Posts associated with the
     *                                username of the User who created them
     */
    public function getUserNameByJoin($user_id)
    {
        return DB::raw('SELECT username FROM users INNER JOIN posts ON users.id = posts.user_id WHERE id = :id LIMIT 1', ['id' => $user_id])[0];
    }
}
