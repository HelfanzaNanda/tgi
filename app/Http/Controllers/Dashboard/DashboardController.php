<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.'.__FUNCTION__);
    }
}
