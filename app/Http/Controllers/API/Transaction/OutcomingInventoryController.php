<?php

namespace App\Http\Controllers\API\Transaction;

use App\Http\Controllers\Controller;
use App\Models\OutcomingInventories;
use Illuminate\Http\Request;

class OutcomingInventoryController extends Controller
{
    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = OutcomingInventories::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = OutcomingInventories::getAllResult($request);
        } else {
            $res = OutcomingInventories::getPaginatedResult($request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return OutcomingInventories::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return OutcomingInventories::createOrUpdate($params, $request->method());
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return OutcomingInventories::createOrUpdate($params, $request->method());
    }

    public function delete($id, Request $request)
    {
        OutcomingInventories::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Barang Telah Berhasil Di Hapus'
        ]);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('api')->user();

        $columns = [
            0 => 'outcoming_inventories.id'
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

        $filter = $request->filter;

        $res = OutcomingInventories::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['code'] = $row['code'];
                $nestedData['created_by_name'] = $row['created_by_name'];
                $nestedData['date'] = $row['date'];
                $nestedData['status'] = $row['status'];
                $nestedData['action'] = '';
                $nestedData['action'] .= '<span class="dropdown">';
                $nestedData['action'] .= '    <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">Aksi</button>';
                $nestedData['action'] .= '    <div class="dropdown-menu dropdown-menu-end" style="margin: 0px;">';
                $nestedData['action'] .= '      <a class="dropdown-item" href="#" id="edit-data" data-id="'.$row['id'].'">';
                $nestedData['action'] .= '        Edit';
                $nestedData['action'] .= '      </a>';
                $nestedData['action'] .= '      <a class="dropdown-item" href="#" id="delete-data" data-id="'.$row['id'].'">';
                $nestedData['action'] .= '        Delete';
                $nestedData['action'] .= '      </a>';
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
        $data['code'] = $data['id'] ? OutcomingInventories::where('id', $data['id'])->value('code') : null;
        $data['created_by'] = $user->id;
        $data['note'] = isset($params['note']) && $params['note'] ? $params['note'] : null;
        $data['status'] = isset($params['status']) && $params['status'] ? $params['status'] : 'requested';
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
                'note' => isset($params['item_notes']) && $params['item_notes'] ? $params['item_notes'][$key] : '-'
            ];
        }

        return OutcomingInventories::createOrUpdate($data, $request->method(), $request);
    }
}
