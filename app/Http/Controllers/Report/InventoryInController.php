<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class InventoryInController extends Controller
{
    public function index()
    {
    	$title = 'Pemasok';

        return view('report.inventory_in.'.__FUNCTION__, compact('title'));
    }
}
