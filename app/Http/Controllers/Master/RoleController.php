<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return view('master.role.'.__FUNCTION__,[
            'title' => 'Role'
        ]);
    }
}
