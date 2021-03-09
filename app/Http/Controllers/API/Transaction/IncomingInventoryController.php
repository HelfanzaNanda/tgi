<?php

namespace App\Http\Controllers\API\Transaction;

use App\Http\Controllers\Controller;
use App\Models\IncomingInventories;
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
}
