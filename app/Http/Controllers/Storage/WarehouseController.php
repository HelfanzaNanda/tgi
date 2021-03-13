<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class WarehouseController extends Controller
{
    public function index()
    {
    	$title = 'Gudang';

        return view('storage.warehouse.'.__FUNCTION__, compact('title'));
    }
}
