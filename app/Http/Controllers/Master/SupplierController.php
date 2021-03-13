<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class SupplierController extends Controller
{
    public function index()
    {
    	$title = 'Pemasok';

        return view('master.supplier.'.__FUNCTION__, compact('title'));
    }
}
