<?php

namespace App\Http\Controllers\API\Transaction;

use App\Http\Controllers\Controller;
use App\Models\IncomingInventories;
use App\Models\Transactions;
use Illuminate\Http\Request;

class IncomingInventoryController extends Controller
{
    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = IncomingInventories::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = IncomingInventories::getAllResult($request);
        } else {
            $res = IncomingInventories::getPaginatedResult($request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return IncomingInventories::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return IncomingInventories::createOrUpdate($params, $request->method());
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return IncomingInventories::createOrUpdate($params, $request->method());
    }

    public function delete($id, Request $request)
    {
        IncomingInventories::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Barang Telah Berhasil Di Hapus'
        ]);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('api')->user();

        $columns = [
            0 => 'incoming_inventories.id'
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

        $filter = $request->only(['sDate', 'eDate']);

        $res = IncomingInventories::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['code'] = $row['code'];
                $nestedData['receipt_number'] = $row['receipt_number'];
                $nestedData['received_by_name'] = $row['received_by_name'];
                $nestedData['date'] = $row['date'];
                $nestedData['total_item'] = $row['total_item'];
                $nestedData['action'] = '';
                $nestedData['action'] .= '<span class="dropdown">';
                $nestedData['action'] .= '    <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">Aksi</button>';
                $nestedData['action'] .= '    <div class="dropdown-menu dropdown-menu-end" style="margin: 0px;">';
                if ($user->can('incoming_inventories.edit')) {
                    $nestedData['action'] .= '      <a class="dropdown-item" href="#" id="edit-data" data-id="'.$row['id'].'">';
                    $nestedData['action'] .= '        Edit';
                    $nestedData['action'] .= '      </a>';
                }
                if ($user->can('incoming_inventories.delete')) {
                    $nestedData['action'] .= '      <a class="dropdown-item" href="#" id="delete-data" data-id="'.$row['id'].'">';
                    $nestedData['action'] .= '        Delete';
                    $nestedData['action'] .= '      </a>';
                }
                $nestedData['action'] .= '    </div>';
                $nestedData['action'] .= '</span>';
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

    public function formPost(Request $request)
    {
        $user = auth()->guard('api')->user();

        $params = $request->all();
        $data['id'] = isset($params['id']) && $params['id'] ? $params['id'] : null;
        $data['code'] = $data['id'] ? IncomingInventories::where('id', $data['id'])->value('code') : null;
        $data['receipt_number'] = isset($params['code']) && $params['code'] ? $params['code'] : null;
        $data['received_by'] = $user->id;
        $data['note'] = isset($params['note']) && $params['note'] ? $params['note'] : null;
        $data['status'] = 'completed';
        $data['transaction']['id'] = $data['code'] ? Transactions::where('code', $data['code'])->value('id') : null;
        if (isset($params['date']) && $params['date']) {
            $data['transaction']['date'] = $params['date'];
        }
        foreach($params['inventory_id'] as $key => $val) {
            $data['transaction']['items'][$key] = [
                'inventory_id' => $params['inventory_id'][$key],
                'warehouse_id' => $params['warehouse_id'][$key],
                'rack_id' => $params['rack_id'][$key],
                'qty' => $params['qty'][$key],
                'batch_number' => $params['batch_number'][$key],
                'expired_date' => $params['expired_date'][$key],
            ];
        }

        foreach ($params['inspection_checklist'] as $icK => $icV) {
            $data['inspections'][$icK] = $icV;
        }

        foreach ($params['files'] as $file) {
            $data['files'][] = $file;
        }

        return IncomingInventories::createOrUpdate($data, $request->method(), $request);
    }
}
