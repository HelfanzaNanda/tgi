<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryGroups;
use Illuminate\Http\Request;
use Session;

class InventoryGroupController extends Controller
{
    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = InventoryGroups::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = InventoryGroups::getAllResult($request);
        } else {
            $res = InventoryGroups::getPaginatedResult($request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return InventoryGroups::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return InventoryGroups::createOrUpdate($params, $request->method());
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return InventoryGroups::createOrUpdate($params, $request->method());
    }

    public function delete($id, Request $request)
    {
        InventoryGroups::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pemasok Telah Berhasil Di Hapus'
        ]);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('api')->user();

        $columns = [
            0 => 'inventory_groups.id'
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

        $res = InventoryGroups::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['name'] = $row['name'];
                $nestedData['code'] = $row['code'];
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
}
