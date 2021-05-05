<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\ScheduledProductArrivals;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use PDF;

class ScheduledArrivalController extends Controller
{
    public function index()
    {
        $customers = Customers::all();
        return view('report.scheduled_arrival.index', [
            'title' => 'Report Scheduled Arrival',
            'customers' => $customers
        ]);
    }

    public function pdf(Request $request)
    {
        if ($request->customer_id && $request->dispatch_date && $request->eta) {
            $dispatch_date = explode(' to ', $request->dispatch_date);
            $eta = explode(' to ', $request->eta);
            $start_dispatch_date = $dispatch_date[0];
            $end_dispatch_date = $dispatch_date[1];
            $start_eta = $eta[0];
            $end_eta = $eta[1];
            if ($request->customer_id) {
                $scheduled_arrivals =  ScheduledProductArrivals::with('customer')
                ->where('customer_id', $request->customer_id)
                ->whereBetween('dispatch_date', [$start_dispatch_date, $end_dispatch_date])
                ->whereBetween('estimated_time_of_arrival', [$start_eta, $end_eta])
                ->get()->groupBy('customer.name');
            }else{
                $scheduled_arrivals =  ScheduledProductArrivals::with('customer')
                ->whereBetween('dispatch_date', [$start_dispatch_date, $end_dispatch_date])
                ->whereBetween('estimated_time_of_arrival', [$start_eta, $end_eta])
                ->get()->groupBy('customer.name');
                
            }
        }else{
            $scheduled_arrivals =  ScheduledProductArrivals::with('customer')
                ->get()->groupBy('customer.name');
        }
        
        //return json_encode($scheduled_arrivals);
        $pdf = PDF::loadView('report.scheduled_arrival.pdf', [
            'scheduled_arrivals' => $scheduled_arrivals
        ]);

        return $pdf->download('scheduled product arrival.pdf');

        // return view('report.scheduled_arrival.pdf', [
        //     'scheduled_arrivals' => $scheduled_arrivals
        // ]);
    }
}
