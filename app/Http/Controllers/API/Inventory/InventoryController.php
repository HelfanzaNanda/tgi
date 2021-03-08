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
}
