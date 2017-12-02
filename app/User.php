<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Auth;
use DB;
use Hash;
use Log;
use Carbon\Carbon;



class User extends Authenticatable
{
    use Notifiable;

    const USERS_PER_PAGE = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'username', 'email', 'password', 'is_admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Returns all columns for all entries in the 'users' table
     * @return array Collection of all users in the users table
     */
    public static function getAllUsers()
    {
        return DB::select('SELECT * FROM users');
    }

    /**
     * Get a user from the table by their ID
     * @param  integer $id  The ID associated with a User
     * @return array        An array containing the User entry from the users table
     */
    public static function getById($id)
    {
        return DB::select('SELECT * FROM users WHERE id = :id', ['id' => $id]);
    }

    /**
     * Get a username by the User's ID
     * @param  integer $id  The ID associated with a User
     * @return array        An array containing the username of the User associated
     *                         with the ID
     */
    public static function getUserNameById($id)
    {
        return DB::select('SELECT username FROM users WHERE id = :id', ['id' => $id]);
    }

    /**
     * Delete a User entry from the DB by their ID
     * @param  integer $id  The ID associated with a User
     * @return mixed        Rows deleted | null
     */
    public static function deleteById($id)
    {
        return DB::delete("DELETE * FROM users WHERE id = :id", ['id' => $id]);
    }

    /**
     * Inserts a User object into the users table
     * @param  Request $request Request from the view
     * @return JSON             True if inserted, false otherwise
     */
    public function createUser($request)
    {
        $did_insert = DB::insert('INSERT INTO users (
            first_name,
            last_name,
            username,
            email,
            password,
            is_admin,
            created_at,
            updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [
            $request->first_name,
            $request->last_name,
            $request->username,
            $request->email,
            Hash::make($request->password),
            0,
            Carbon::now(),
            Carbon::now()
        ]);

        return response()->json($did_insert);
    }

    /**
     * Updates a User's email in the users table
     * @param  string $email    The new email
     * @param  integer $id      The User's ID
     */
    public function updateEmail($email, $id)
    {
        DB::connection()->getPdo()->exec(
            "UPDATE users SET email = '" . $email . "' WHERE ID = " . $id
        );
    }

    /**
     * Updates a User's password in the users table
     * @param  string $password     The new unhashed password
     * @param  integer $id          The User's ID
     */
    public function updatePassword($password, $id)
    {
        DB::connection()->getPdo()->exec(
            "UPDATE users SET password = '" . Hash::make($password) . "' WHERE ID = " . $id
        );
    }

    /**
     * Updates a User's first name in the users table
     * @param  string $name         The new first name
     * @param  integer $id          The User's ID
     */
    public function updateFirstName($name, $id)
    {
        DB::connection()->getPdo()->exec(
            "UPDATE users SET first_name = '" . $name . "' WHERE ID = " . $id
        );
    }

    /**
     * Updates a User's last name in the users table
     * @param  string $name         The new last name
     * @param  integer $id          The User's ID
     */
    public function updateLastName($name, $id)
    {
        DB::connection()->getPdo()->exec(
            "UPDATE users SET last_name = '" . $name . "' WHERE ID = " . $id
        );
    }

    /**
     * Updates a User's admin privileges in the users table
     * @param  string $value        The new admin privileges
     * @param  integer $id          The User's ID
     */
    public function updateAdminPrivileges($value, $id)
    {
        DB::connection()->getPdo()->exec(
            "UPDATE users SET is_admin = '" . $value . "' WHERE ID = " . $id
        );
    }

    /**
     * Verifies a User's login attempt
     * @param  Request $request     Request from the login script
     * @return boolean              True if attempt is successful
     *                                   False otherwise
     */
    public static function checkLogin($request)
    {
        $statement = "SELECT * FROM users WHERE email = '" . $request->email . "';";

        $PDO_query = DB::select($statement);

        if (empty($PDO_query)) {
            return false;
        } else {
            $PDO_query = $PDO_query[0];
        }

        if ($did_login = password_verify($request->password, $PDO_query->password)) {
            $user = new User;
            $user->id = $PDO_query->id;
            $user->first_name = $PDO_query->first_name;
            $user->last_name = $PDO_query->last_name;
            $user->username = $PDO_query->username;
            $user->email = $PDO_query->email;
            $user->is_admin = $PDO_query->is_admin;
            $user->created_at = $PDO_query->created_at;

            // Sets the user to the global Auth (easier access later, fewer
            // DB accesses)
            Auth::loginUsingId($user->id);
        }

        return $did_login;
    }

    /**
     * Gets the offset for the page
     * @param integer $page The current page number
     */
    public static function getPageOffset($page)
    {
        return (($page-1) * User::USERS_PER_PAGE);
    }

    /**
     * Gets the total amount of pages that correspond to the amount of
     * entries in the users table
     * @return integer The total number of pages
     */
    public static function getTotalPages()
    {
        $PDO_query = DB::connection()->getPdo()->query(
            "SELECT * FROM `users`"
        );
        $row_count = $PDO_query->rowCount();

        return (int) ceil($row_count / User::USERS_PER_PAGE);
    }

    /**
     * Gets the User entries for a specific page
     * @param  integer $page            The page number
     * @param  string  $query           A query
     * @param  string  $searchString    A search string
     * @return array                    The array containing the pages users
     */
    public static function getPagedUsers($page, $query = null, $searchString = null)
    {
        if (is_null($query)) {
            $SQL_statement = "SELECT * FROM users ORDER BY first_name ASC LIMIT " . User::getPageOffset($page) . ", " . User::USERS_PER_PAGE;
        }

        return DB::select($SQL_statement);
    }

}
