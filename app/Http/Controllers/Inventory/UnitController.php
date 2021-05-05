<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class UnitController extends Controller
{
    public function index()
    {
    	$title = 'Unit';

        return view('inventory.unit.'.__FUNCTION__, compact('title'));
    }
}
