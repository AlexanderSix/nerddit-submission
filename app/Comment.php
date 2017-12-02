<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Log;

use Auth;
use Carbon\Carbon;

class Comment extends Model
{

    /**
     * Returns all columns for all entries in the 'comments' table
     * @return array Collection of all comments in the comments table
     */
    public static function getAllComments()
    {
        return DB::select('SELECT * FROM comments');
    }

    /**
     * Get all Comments associated with a specific post
     * @param  integer $id  The ID associated with a Post
     * @return array        A collection of Comments
     */
    public static function getCommentsByPostId($id)
    {
        return DB::select('SELECT username, comments.id, user_id, post_id, content, comments.created_at, comments.updated_at FROM users INNER JOIN comments ON users.id = comments.user_id WHERE post_id = :post_id', ['post_id' => $id]);
    }

    /**
     * Gets a specific Comment from the DB
     * @param  integer $id  A specific Comment's ID
     * @return array        A collection containing a single Comment
     */
    public static function getById($id)
    {
        return DB::select('SELECT * FROM comments WHERE id = :id', ['id' => $id]);
    }

    /**
     * Inserts a new Comment into the DB
     * @param  Request $request The Request object passed from the frontend
     * @return boolean          True if inserted, false otherwise
     */
    public function createComment($request)
    {

        $now = Carbon::now();

        $did_insert = DB::insert("INSERT INTO comments (
            user_id,
            post_id,
            content,
            created_at,
            updated_at
        ) VALUES (?, ?, ?, ?, ?)", [
            Auth::user()->id,
            $request->post_id,
            $request->content,
            $now,
            $now
        ]);

        $this->user_id = Auth::user()->id;
        $this->post_id = $request->post_id;
        $this->content = $request->content;
        $this->created_at = $now;
        $this->updated_at = $now;

        return $did_insert;

    }

    /**
     * Updates a specific Comment's content
     * @param  Request $request The request object passed in from the frontend
     * @return Comment          The Comment object that was updated
     */
    public function updateComment($request)
    {
        $now = Carbon::now();

        $SQL_statement = "UPDATE comments SET user_id = " .
            Auth::user()->id .
            ", content = '" .
            $request->content .
            "', updated_at = '" .
            $now . "' WHERE id = " . $this->id;

        $did_update = DB::connection()->getPdo()->exec($SQL_statement);

        $ret = $this->getById($this->id);
        return Caster::cast('App\Comment', $ret[0]);
    }
}
