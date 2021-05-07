<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\IncomingInventories;
use Illuminate\Http\Request;
use Session;

class InventoryInController extends Controller
{
    public function index()
    {
    	$title = 'Inbound';
        return view('transaction.inventory_in.'.__FUNCTION__, compact('title'));
    }

    public function create()
    {
    	$title = 'Create Inbound';

        return view('transaction.inventory_in.'.__FUNCTION__, compact('title'));
    }
}
