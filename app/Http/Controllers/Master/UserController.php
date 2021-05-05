<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class UserController extends Controller
{
    public function index()
    {
    	$title = 'User';

        return view('master.user.'.__FUNCTION__, compact('title'));
    }
}
