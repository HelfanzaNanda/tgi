<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryLocations;
use Illuminate\Http\Request;

class InventoryLocationController extends Controller
{
    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = InventoryLocations::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = InventoryLocations::getAllResult($request);
        } else {
            $res = InventoryLocations::getPaginatedResult($request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return InventoryLocations::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return InventoryLocations::createOrUpdate($params, $request->method());
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return InventoryLocations::createOrUpdate($params, $request->method());
    }

    public function delete($id, Request $request)
    {
        InventoryLocations::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Barang Telah Berhasil Di Hapus'
        ]);
    }
}
