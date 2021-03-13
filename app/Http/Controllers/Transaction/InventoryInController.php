<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class InventoryInController extends Controller
{
    public function index()
    {
    	$title = 'Pemasok';

        return view('transaction.inventory_in.'.__FUNCTION__, compact('title'));
    }
}
