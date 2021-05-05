<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    private $headers = ['Home', 'Master Data', 'Penyimpanan', 'Barang', 'Transaksi', 'Permissions', 'Reports'];
    private $master_data = [ 
        'suppliers', 'suppliers.add', 'suppliers.edit', 'suppliers.delete', 
        'users', 'users.add', 'users.edit', 'users.delete',
        'customers', 'customers.add', 'customers.edit', 'customers.delete', 
    ];
    private $storage = [
        'warehouses', 'warehouses.qrcode', 'warehouses.add', 'warehouses.edit', 'warehouses.delete',
        'racks', 'racks.qrcode', 'racks.add', 'racks.edit', 'racks.delete',
    ];
    private $inventory = [
        'categories', 'categories.add', 'categories.edit', 'categories.delete',
        'units', 'units.add', 'units.edit', 'units.delete',
        'inventories', 'inventories.qrcode', 'inventories.add', 'inventories.edit', 'inventories.delete', 'inventories.image',
        'stock_opnames', 'stock_opnames.add', 'stock_opnames.edit', 'stock_opnames.delete',
    ];
    private $mutation = [
        'incoming_inventories', 'incoming_inventories.add', 'incoming_inventories.edit', 'incoming_inventories.delete',
        'outcoming_inventories', 'outcoming_inventories.add', 'outcoming_inventories.edit', 'outcoming_inventories.delete',
        'request_inventories', 'request_inventories.add', 'request_inventories.add_inventories', 'request_inventories.edit', 'request_inventories.delete',
        'scheduled_arrivals', 'scheduled_arrivals.add', 'scheduled_arrivals.edit', 'scheduled_arrivals.delete',
    ];
    private $permission = [
        'roles', 'roles.add', 'roles.edit', 'roles.delete',
        'permissions.change'
    ];

    private $reports = [
        'report_product_mutations',
        'report_scheduled_arrivals', 'report_scheduled_arrivals.pdf',
        'report_stock_minimums'
    ];


    public function index()
    {
        $permissions = Permission::with('roles:name')->groupBy('id')->get();
        
        $result = [];
        
        foreach ($permissions as $permission) {
            if (in_array($permission->name, $this->master_data)) {
                $result[$this->headers[1]][] = $permission;
            }elseif(in_array($permission->name, $this->storage)){
                $result[$this->headers[2]][] = $permission;
            }elseif(in_array($permission->name, $this->inventory)){
                $result[$this->headers[3]][] = $permission;
            }elseif(in_array($permission->name, $this->mutation)){
                $result[$this->headers[4]][] = $permission;
            }elseif(in_array($permission->name, $this->permission)){
                $result[$this->headers[5]][] = $permission;
            }elseif(in_array($permission->name, $this->reports)){
                $result[$this->headers[6]][] = $permission;
            }
        }
        $result = collect($result)->paginate(2);
        return view('master.permission.'.__FUNCTION__, [
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
