<?php

namespace App\Http\Controllers;

use App\User;
use App\Caster;
use Illuminate\Http\Request;

use Log;
use DB;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::getAllUsers();
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User;
        if ($user->createUser($request)) {
            return response()->json("success", 200);
        } else {
            return response()->json("error", 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::getById($id);

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('users.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::getById($id);

        // TODO -- figure out how we're going to do this
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $number_affected = User::deleteById($id);

        if ($number_affected < 1) {
            return response()->json("no user found--none deleted", 200);
        } else if ($number_affected > 1) {
            return response()->json("this shouldn't ever get here. you probably have an error on your hands", 500);
        } else {
            return response()->json('user deleted successfully', 200);
        }
    }

    /**
     * Shows the "Profile" view for editing the currently logged-in User
     * @return View
     */
    public function showEditUserData()
    {
        $user = Auth::user();
        return view('users.editdata')->with(['user' => $user]);
    }

    /**
     * Shows the Admin's edit User view (for any User)
     * @param  integer $id The ID of the User to be edited
     * @return View
     */
    public function showAdminEditUserData($id)
    {
        $user = User::getById($id);
        $user = Caster::cast('App\User', $user[0]);
        return view('admin.edituser')->with(['user' => $user]);
    }

    /**
     * Entry method to edit User data
     * @param  Request $request         Request object from the frontend
     * @param  integer  $id             The ID of the object to be edited
     *                                      (defaults to null)
     * @param  boolean $isAdminPanel    Flag determining whether the call came from
     *                                      the admin panel or the profile page
     *                                      (defaults to false)
     * @return Redirect                 
     */
    public function updateUserData(Request $request, $id = null, $isAdminPanel = false)
    {
        //dd($id);
        if (is_null($id)) {
            $id = Auth::user()->id;
        }

        switch ($request->user_data_type) {
            case 'email':
                $user = User::getById($id);
                $user = Caster::cast('App\User', $user[0]);
                $user->updateEmail($request->email, $id);
                break;

            case 'password':
                $user = User::getById($id);
                $user = Caster::cast('App\User', $user[0]);
                $user->updatePassword($request->password, $id);
                break;

            case 'first_name':
                $user = User::getById($id);
                $user = Caster::cast('App\User', $user[0]);
                $user->updateFirstName($request->first_name, $id);
                break;

            case 'last_name':
                $user = User::getById($id);
                $user = Caster::cast('App\User', $user[0]);
                $user->updateLastName($request->last_name, $id);
                break;

            case 'is_admin':
                $user = User::getById($id);
                $user = Caster::cast('App\User', $user[0]);
                Log::info($request->is_admin);
                Log::info($id);
                Log::info($user);
                $user->updateAdminPrivileges($request->is_admin, $id);
                break;

            default:
                return response()->json("We're sorry! You shouldn't have gotten here! Go back to safety!!", 500);
        }

        if($isAdminPanel) {
            return redirect()->action('UserController@showAdminEditUserData', ['id' => $id]);
        }
        return redirect()->action('UserController@showEditUserData');
    }
}
