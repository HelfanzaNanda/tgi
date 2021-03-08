<?php

namespace App\Http\Controllers\API\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Racks;
use App\Models\Warehouses;

class WarehouseController extends Controller
{
    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = Warehouses::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = Warehouses::getAllResult($request);
        } else {
            $res = Warehouses::getPaginatedResult($request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return Warehouses::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Warehouses::createOrUpdate($params, $request->method());
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Warehouses::createOrUpdate($params, $request->method());
    }

    public function delete($id, Request $request)
    {
        Warehouses::where('id', $id)->delete();
        Racks::where('warehouse_id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Penyimpanan Telah Berhasil Di Hapus'
        ]);
    }
}
