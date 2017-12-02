<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use Auth;
use DB;
use Hash;
use Log;

use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Gateway method to log in a user
     * @param  Request $request Request object from the frontend
     * @return Redirect         Home if User was authenticated,
     *                               Login otherwise
     */
    public function login(Request $request)
    {
        if (User::checkLogin($request)) {
            return redirect()->action('HomeController@index', ['page' => 1]);
        }

        return redirect('/login')->with(['error' => "Incorrect login credentials!"]);

    }

    /**
     * Registers a User using raw SQL and no built-in Laravel functionality
     * @param  Request  $data       Request object from the frontend
     * @return Redirect             Login if successful
     *                                    Register otherwise
     */
    public function registerUser(Request $data)
    {
        try {
            DB::insert('INSERT INTO users (
                first_name,
                last_name,
                username,
                email,
                password,
                is_admin,
                created_at,
                updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [
                $data['first_name'],
                $data['last_name'],
                $data['username'],
                $data['email'],
                Hash::make($data['password']),
                0,
                Carbon::now(),
                Carbon::now()
            ]);
        } catch (\Exception $e) {
            $str = "";
            preg_match_all("/'(.*?)'/", $e, $str);

            return redirect('/register')->with(['status' => $str[0][0] . " is already taken as a(n) " . $str[0][1]]);
        }


        return redirect('/login');
    }
}
