<?php

namespace App\Http\Controllers\API\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ScheduledProductArrivals;

class ScheduledArrivalController extends Controller
{
    
    public function datatables(Request $request)
    {
        $user = auth()->guard('api')->user();

        $columns = [
            0 => 'scheduled_product_arrivals.id'
        ];

        $dataOrder = [];

        $limit = $request->length;

        $start = $request->start;

        foreach ($request->order as $row) {
            $nestedOrder['column'] = $columns[$row['column']];
            $nestedOrder['dir'] = $row['dir'];

            $dataOrder[] = $nestedOrder;
        }

        $order = $dataOrder;

        $dir = $request->order[0]['dir'];

        $search = $request->search['value'];

        $filter = $request->only(['sDate', 'eDate', 'dispatch_date', 'eta', 'customer_id']);

        $res = ScheduledProductArrivals::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['invoice_number'] = $row['invoice_number'];
                $nestedData['inventory_code'] = $row['inventory_code'];
                $nestedData['inventory_description'] = $row['inventory_description'];
                $nestedData['qty'] = intval($row['quantity']);
                $nestedData['customer_order_number'] = $row['customer_order_number'];
                $nestedData['dispatch_date'] = $row['dispatch_date']->format('d-M-Y');
                $nestedData['estimated_time_of_arrival'] = $row['estimated_time_of_arrival']->format('d-M-Y');
                $data[] = $nestedData;
            }
        }

        $json_data = [
            'draw'  => intval($request->draw),
            'recordsTotal'  => intval($res['totalData']),
            'recordsFiltered' => intval($res['totalFiltered']),
            'data'  => $data,
            'order' => $order
        ];

        return json_encode($json_data);
    }
}
