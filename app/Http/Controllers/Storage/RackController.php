<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class RackController extends Controller
{
    public function index()
    {
    	$title = 'Rak';

        return view('storage.rack.'.__FUNCTION__, compact('title'));
    }
}
