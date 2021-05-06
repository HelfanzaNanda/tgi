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
        $query = ScheduledProductArrivals::with('customer');

        if ($request->dispatch_date) {
            $dispatch_date = explode(' to ', $request->dispatch_date);
            if (count($dispatch_date) < 2) {
                $query->whereDate('dispatch_date', $dispatch_date);
            }else{
                $start_dispatch_date = $dispatch_date[0];
                $end_dispatch_date = $dispatch_date[1];
                $query->whereBetween('dispatch_date', [$start_dispatch_date, $end_dispatch_date]);
            }
        }
        if ($request->eta) {
            $eta = explode(' to ', $request->eta);
            if (count($eta) < 2) {
                $query->whereDate('estimated_time_of_arrival', $eta);
            }else{
                $start_eta = $eta[0];
                $end_eta = $eta[1];
                $query->whereBetween('estimated_time_of_arrival', [$start_eta, $end_eta]);
            }
        }
        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        $pdf = PDF::loadView('report.scheduled_arrival.pdf', [
            'scheduled_arrivals' => $query->get()->groupBy('customer.name')
        ]);

        return $pdf->download('scheduled product arrival.pdf');

        // return view('report.scheduled_arrival.pdf', [
        //     'scheduled_arrivals' => $scheduled_arrivals
        // ]);
    }
}
