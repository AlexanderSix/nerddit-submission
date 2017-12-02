<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DBBackup;
use App\Post;
use App\User;

use Auth;
use Log;
use DB;

class AdminController extends Controller
{
    /**
     * Gateway method for admin panel
     * @return View|Redirect    View if the authenticated User is an Admin
     *                               Redirect to home otherwise
     */
    public function showDashboard()
    {
        if (Auth::user()->is_admin) {
            return view('admin.admin');
        } else {
            return redirect('/home')->with(['page' => 1]);
        }
    }

    /**
     * Gateway method for the backup page
     * @return View|Redirect    View if the authenticated User is an Admin
     *                               Redirect to home otherwise
     */
    public function showBackup()
    {
        if (Auth::user()->is_admin) {
            return view('admin.backup');
        } else {
            return redirect('/home');
        }
    }

    /**
     * Shows the Admin Users page if the authenticated User is an Admin
     * @param  integer $page         User page number
     * @param  string  $query        Query string to be passed through
     * @param  string  $searchString Search string to be passed through
     * @param  string  $error        Error message to be passed through
     * @return View|Redirect         View if the authenticated User is an Admin
     *                                    Redirect to home otherwise
     */
    public function showUsers($page = null, $query = null, $searchString = null, $error = null)
    {
        if (Auth::user()->is_admin) {

            $total_pages = User::getTotalPages();
            if (is_null($page) || $page > $total_pages) {
                $page = 1;
            }

            $users = User::getPagedUsers($page, $query, $searchString);

            return view('admin.manageusers')->with(['users' => $users])->with(['total_pages' => $total_pages])->with(['page' => $page])->with(['query' => $query])->with(['searchString' => $searchString])->with(['sql_error' => $error]);
        } else {
            return redirect('/home');
        }
    }

    /**
     * Shows the Admin Posts page if the authenticated User is an Admin
     * @param  integer $page         User page number
     * @param  string  $query        Query string to be passed through
     * @param  string  $searchString Search string to be passed through
     * @param  string  $error        Error message to be passed through
     * @return View|Redirect         View if the authenticated User is an Admin
     *                                    Redirect to home otherwise
     */
    public function showPosts($page = null, $query = null, $searchString = null, $error = null)
    {
        if (Auth::user()->is_admin) {

            $total_pages = Post::getTotalPages();
            if (is_null($page) || $page > $total_pages) {
                $page = 1;
            }

            $posts = Post::getPagedPosts($page, $query, $searchString);


            return view('admin.queryposts')->with([
                'posts' => $posts,
                'total_pages' => $total_pages,
                'page' => $page,
                'query' => $query,
                'searchString' => $searchString,
                'sql_error' => $error
            ]);
        } else {
            return redirect('/home');
        }
    }

    /**
     * Gateway method to back up the DB
     * @param  string $tableName    The name of the table to be backed up
     * @return null|Redirect        null if backup was successful
     *                                   Redirect to admin panel otherwise
     */
    public function dbBackUp($tableName)
    {
        $backup_engine = new DBBackup;
        if (!is_null($tableName)) {
            $backup_engine->backUpTable($tableName);
        } else {
            return redirect('admin.admin');
        }
    }

    /**
     * Gateway method to restore the DB from file
     * @param  Request $request Request object from the frontend
     * @return View             Admin panel
     */
    public function restoreBackup(Request $request)
    {
        $backup_engine = new DBBackup;
        if (!empty($request->backupfile) && !is_null($request->backupfile)) {
            $backup_engine->restoreTable($request->backupfile);
            return view("admin.admin");
        } else {
            return view("admin.admin");
        }
    }

    /**
     * Allows the user to (safely!) run a custom SQL query in a sandboxed enviornment
     * @param  Request $request   Request object from frontend
     * @param  string  $tableName Name of the table to be queried
     * @return View               Returns whichever view was present before function call
     */
    public function customQuery(Request $request, string $tableName)
    {
        if ($tableName == 'users') {
            $custom_statement = explode(';', $request['searchString']);

            if ($custom_statement[0] == "") {
                return redirect()->action('AdminController@showUsers', [
                    'page' => 1,
                    'query' => null,
                    'searchString' => null,
                    'error' => null
                ]);
            } else {
                $SQL_statement = "SELECT * FROM users WHERE " . $custom_statement[0];
            }

        } else if ($tableName == 'posts') {
            $custom_statement = explode(';', $request['searchString']);

            if ($custom_statement[0] == "") {
                return redirect()->action('AdminController@showPosts', [
                    'page' => 1,
                    'query' => null,
                    'searchString' => null,
                    'error' => null
                ]);
            } else {
                $SQL_statement = "SELECT * FROM posts WHERE " . $custom_statement[0];
            }
        }

        try {
            $results = DB::select($SQL_statement);
        } catch (\Exception $e) {
            if ($tableName == 'users') {
                return $this->showUsers(1, null, null, "Bad SQL query for table " . $tableName);
            } else if ($tableName == 'posts') {
                return $this->showPosts(1, null, null, "Bad SQL query for table " . $tableName);
            }
        }

        if ($tableName == 'users') {
            return view('admin.manageusers')->with(['users' => $results, 'page' => 1, 'total_pages' => 1])->with(['sql_error' => null]);
        } else if ($tableName == 'posts') {
            return view('admin.queryposts')->with(['posts' => $results, 'page' => 1, 'total_pages' => 1])->with(['sql_error' => null]);
        }

    }
}
