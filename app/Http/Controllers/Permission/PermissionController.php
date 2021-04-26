<?php

namespace App\Http\Controllers\Permission;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles:name')->groupBy('id')->get();
        $headers = ['Home', 'Master Data', 'Penyimpanan', 'Barang', 'Transaksi', 'Permissions'];
        $result = [];
        
        foreach ($permissions as $permission) {
            if (in_array($permission->name, ['pemasok', 'pengguna'])) {
                $result[$headers[1]][] = $permission;
            }elseif(in_array($permission->name, ['gudang', 'rak'])){
                $result[$headers[2]][] = $permission;
            }elseif(in_array($permission->name, ['kategori', 'unit', 'barang', 'stok opname'])){
                $result[$headers[3]][] = $permission;
            }elseif(in_array($permission->name, ['barang masuk', 'barang keluar', 'permintaan barang'])){
                $result[$headers[4]][] = $permission;
            }elseif(in_array($permission->name, ['hak akses', 'izin pengguna'])){
                $result[$headers[5]][] = $permission;
            }
        }
        $result = collect($result)->paginate(2);
        return view('permission.index', [
            'permissions' => $result,
            'roles' => Role::all(),
            'title' => 'Izin Pengguna'
        ]);
    }

    public function update(Request $request)
    {
        $role = Role::findByName($request->role);
        $request->bool == "true" 
        ? $role->givePermissionTo($request->perm) 
        : $role->revokePermissionTo($request->perm);
        
        return [
            'status' => true,
            'msg' => 'berhasil'
        ];
    }
}
