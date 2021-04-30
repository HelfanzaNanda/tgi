<?php

namespace App\Http\Controllers\API\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function get($id)
    {
        return Role::find($id);
    }
    
    public function updateOrCreate(Request $request)
    {
        Role::updateOrCreate(['id' => $request->id], [
                'name' => $request->name,
                'guard_name' => 'web'
        ]);
        if ($request->id) {
            $message = 'Sukses Memperbaharui Item';
        }else{
            $message = 'Sukses Menambah Item';
        }
        return response()->json([
            'message' => $message,
            'status' => true
        ]);
    }

    public function delete($id)
    {
        Role::destroy($id);
        return response()->json([
            'message' => 'Sukses Menghapus Item',
            'status' => true
        ]);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('api')->user();

        $columns = [
            0 => 'roles.id'
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

        $res = Role::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['name'] = $row['name'];
                //$nestedData['description'] = $row['description'];
                $nestedData['action'] = '';
                $nestedData['action'] .= '<span class="dropdown">';
                $nestedData['action'] .= '    <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">Aksi</button>';
                $nestedData['action'] .= '    <div class="dropdown-menu dropdown-menu-end" style="margin: 0px;">';
                if ($user->can('roles.edit')) {
                    $nestedData['action'] .= '      <a class="dropdown-item" href="#" id="edit-data" data-id="'.$row['id'].'">';
                    $nestedData['action'] .= '        Edit';
                    $nestedData['action'] .= '      </a>';
                }
                
                if ($user->can('roles.delete')) {
                    $nestedData['action'] .= '      <a class="dropdown-item" href="#" id="delete-data" data-id="'.$row['id'].'">';
                    $nestedData['action'] .= '        Delete';
                    $nestedData['action'] .= '      </a>';
                }
                
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
