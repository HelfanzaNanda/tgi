<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventories;
use App\Models\InventoryLocations;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = Inventories::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = Inventories::getAllResult($request);
        } else {
            $res = Inventories::getPaginatedResult($request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return Inventories::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Inventories::createOrUpdate($params, $request->method());
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Inventories::createOrUpdate($params, $request->method());
    }

    public function delete($id, Request $request)
    {
        Inventories::where('id', $id)->delete();
        InventoryLocations::where('inventory_id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Barang Telah Berhasil Di Hapus'
        ]);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('api')->user();

        $columns = [
            0 => 'inventories.id'
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

        $res = Inventories::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['code'] = $row['code'];
                $nestedData['name'] = $row['name'];
                $nestedData['category_name'] = $row['category_name'];
                $nestedData['product_description'] = $row['product_description'];
                $nestedData['buy_price'] = number_format($row['buy_price']);
                $nestedData['supplier_name'] = $row['supplier_name'];
                $nestedData['unit_name'] = $row['unit_name'];
                $nestedData['action'] = '';
                $nestedData['action'] .= '<span class="dropdown">';
                $nestedData['action'] .= '    <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">Aksi</button>';
                $nestedData['action'] .= '    <div class="dropdown-menu dropdown-menu-end" style="margin: 0px;">';
                $nestedData['action'] .= '      <a class="dropdown-item" href="'.url('/inventories/'.$row['id'].'/galleries').'" target="_blank">';
                $nestedData['action'] .= '        Gambar';
                $nestedData['action'] .= '      </a>';
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

    public function countTotalRecords(Request $request)
    {
        $params = $request->all();

        $count = Inventories::count();

        $number = sprintf('%04d', $count + 1);
        
        return response()->json($number);
    }
}
