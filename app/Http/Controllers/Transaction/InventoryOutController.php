<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class InventoryOutController extends Controller
{
    public function index()
    {
    	$title = 'Barang Keluar';

        return view('transaction.inventory_out.'.__FUNCTION__, compact('title'));
    }
}
