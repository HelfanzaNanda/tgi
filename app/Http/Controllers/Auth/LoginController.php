<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class LoginController extends Controller
{
    public function __construct()
    {
        if (Session::get('_login')) {
            return redirect('/');
        }
    }

    public function index()
    {
        return view('auth.login');
    }

    public function authorizes(Request $request)
    {
        $params = $request->all();

        return User::authorizes($params, $request->method(), $request);
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        
        return redirect('/');
    }

    public function forgotPassword(Request $request)
    {
        $params = $request->all();
        return User::resetPassword($params);
    }
}
