<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Inventories;
use Illuminate\Http\Request;

class ScheduledArrivalController extends Controller
{
    public function index()
    {
        $inventories = Inventories::all();
        $customers = Customers::all();
        return view('transaction.scheduled_arrival.index', [
            'title' => 'Scheduled Arrival',
            'inventories' => $inventories,
            'customers' => $customers
        ]);
    }
}
