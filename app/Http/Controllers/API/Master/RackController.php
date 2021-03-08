<?php

namespace App\Http\Controllers\API\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Racks;

class RackController extends Controller
{
    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = Racks::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = Racks::getAllResult($request);
        } else {
            $res = Racks::getPaginatedResult($request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return Racks::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Racks::createOrUpdate($params, $request->method());
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Racks::createOrUpdate($params, $request->method());
    }

    public function delete($id, Request $request)
    {
        Racks::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Penyimpanan Telah Berhasil Di Hapus'
        ]);
    }
}
