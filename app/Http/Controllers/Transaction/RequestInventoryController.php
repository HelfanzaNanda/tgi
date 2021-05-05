<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class RequestInventoryController extends Controller
{
    public function index()
    {
    	$title = 'Request Product';

        return view('transaction.request_inventory.'.__FUNCTION__, compact('title'));
    }
}
