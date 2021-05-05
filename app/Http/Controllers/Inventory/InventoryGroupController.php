<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class InventoryGroupController extends Controller
{
    public function index()
    {
    	$title = 'Product Group';

        return view('inventory.group.'.__FUNCTION__, compact('title'));
    }
}
