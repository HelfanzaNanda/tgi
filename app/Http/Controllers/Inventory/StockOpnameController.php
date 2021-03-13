<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class StockOpnameController extends Controller
{
    public function index()
    {
    	$title = 'Pemasok';

        return view('inventory.stock_opname.'.__FUNCTION__, compact('title'));
    }
}
