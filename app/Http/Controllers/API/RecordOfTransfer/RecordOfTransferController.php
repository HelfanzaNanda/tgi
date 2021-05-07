<?php

namespace App\Http\Controllers\API\RecordOfTransfer;

use Illuminate\Http\Request;
use App\Models\RecordOfTransfers;
use App\Http\Controllers\Controller;
use App\Models\IncomingInventories;
use App\Models\OutcomingInventories;

class RecordOfTransferController extends Controller
{
    public function get(Request $request)
    {
        $data = RecordOfTransfers::where('model_id', $request->model_id)
        ->where('model', $request->model)->first();
        return json_encode($data);
    }

    public function post(Request $request)
    {
        $params = $request->all();
        $params['submitted_by'] = auth()->guard('api')->id();
        $params['number'] = 'BA'.strtotime(now());
        return RecordOfTransfers::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return RecordOfTransfers::createOrUpdate($params, $request->method());
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return RecordOfTransfers::createOrUpdate($params, $request->method());
    }

    public function delete($id, Request $request)
    {
        RecordOfTransfers::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Barang Telah Berhasil Di Hapus'
        ]);
    }

}
