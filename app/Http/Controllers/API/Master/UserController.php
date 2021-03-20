<?php

namespace App\Http\Controllers\API\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = User::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = User::getAllResult($request);
        } else {
            $res = User::getPaginatedResult($request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return User::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return User::createOrUpdate($params, $request->method());
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return User::createOrUpdate($params, $request->method());
    }

    public function delete($id, Request $request)
    {
        User::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pengguna Telah Berhasil Di Hapus'
        ]);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('api')->user();

        $columns = [
            0 => 'users.id'
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

        $res = User::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        $role = '-';

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                if ($row['role_id'] == 1) {
                    $role = 'Admin';
                } else if ($row['role_id'] == 2) {
                    $role = 'Staff';
                } else if ($row['role_id'] == 3) {
                    $role = 'Staff Gudang';
                }

                $nestedData['id'] = $row['id'];
                $nestedData['name'] = $row['name'];
                $nestedData['username'] = $row['username'];
                $nestedData['email'] = $row['email'];
                $nestedData['phone'] = $row['phone'];
                $nestedData['role'] = $role;
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
