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
}
