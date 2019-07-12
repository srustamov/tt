<?php

/**
 * App\Controllers\Auth\RegisterController file.
 *
 * @category Controller
 *
 * @package TT
 * @author  SamirRustamov <rustemovv96@gmail.com>
 * @link  https://github.com/srustamov/TT
 */

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use System\Engine\Http\Request;
use System\Facades\Validator;
use System\Facades\Redirect;
use System\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{


     /**
     * RegisterController show method.Show register form page
     *
     * @return \System\Libraries\View\View
     */
    public function show()
    {
        return view('auth.register');
    }



    /**
    * RegisterController register method.Post data validate and Create user
    *
    * @param \System\Engine\Http\Request
    *
    * @return \System\Libraries\Redirect
    */
    public function register(Request $request)
    {
        $validation =  Validator::make($request->all(), [
                'email'    => 'required|email|unique:users',
                'password' => 'required|min:6',
                'name'     => 'required|min:5|unique:users',
            ]);

        if (!$validation->check()) {
            return Redirect::to('/auth/register')->withErrors(Validator::messages());
        } else {
            $create = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            if ($create) {
                return Redirect::to('auth/login')->with('register', 'Register successfully');
            } else {
                return Redirect::to('auth/register')->with('register', 'User register error occurred.Please try again');
            }
        }
    }
}
