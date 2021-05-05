<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class InventoryOutController extends Controller
{
    public function index()
    {
    	$title = 'Outbound';

        return view('transaction.inventory_out.'.__FUNCTION__, compact('title'));
    }

    public function create()
    {
    	$title = 'Create Outbound';

        return view('transaction.inventory_out.'.__FUNCTION__, compact('title'));
    }
}
