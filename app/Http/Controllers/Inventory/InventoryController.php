<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class InventoryController extends Controller
{
    public function index()
    {
    	$title = 'Pemasok';

        return view('inventory.inventory.'.__FUNCTION__, compact('title'));
    }
}
