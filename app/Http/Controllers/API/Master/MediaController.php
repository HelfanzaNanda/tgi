<?php

namespace App\Http\Controllers\API\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;

class MediaController extends Controller
{
    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = Media::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = Media::getAllResult($request);
        } else {
            $res = Media::getPaginatedResult($request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return Media::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Media::createOrUpdate($params, $request->method());
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Media::createOrUpdate($params, $request->method());
    }

    public function delete($id, Request $request)
    {
        Media::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Foto Telah Berhasil Di Hapus'
        ]);
    }
}
