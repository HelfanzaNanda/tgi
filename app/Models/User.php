<?php

namespace App\Models;

use DB;
use Redirect;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles;

	protected $table = 'users';

	protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'username',
        'role_id'
    ];

    private $operators = [
        "\$gt" => ">",
        "\$gte" => ">=",
        "\$lte" => "<=",
        "\$lt" => "<",
        "\$like" => "like",
        "\$not" => "<>",
        "\$in" => "in"
    ];

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'id' => ['alias' => $model->table.'.id', 'type' => 'int'],
            'username' => ['alias' => $model->table.'.username', 'type' => 'string'],
            'name' => ['alias' => $model->table.'.name', 'type' => 'string'],
            'email' => ['alias' => $model->table.'.email', 'type' => 'string'],
            'email_verified_at' => ['alias' => $model->table.'.email_verified_at', 'type' => 'string'],
            'phone' => ['alias' => $model->table.'.phone', 'type' => 'string'],
            'role_id' => ['alias' => $model->table.'.role_id', 'type' => 'int'],
			'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
			'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }

    public static function authorizes($params, $method, $request)
    {
        $request->session()->flush();
        $request->session()->put('_login', true);
        $request->session()->put('_id', $params['id']);
        $request->session()->put('_name', $params['name']);
        $request->session()->put('_email', $params['email']);
        $request->session()->put('_username', $params['username']);
        $request->session()->put('_phone', $params['phone']);
        $request->session()->put('_role_id', $params['role_id']);
        $request->session()->put('_access_token', $params['access_token']);

        return response()->json([
            'status' => 'success'
        ]);
    }

    public static function getById($id, $params = null)
    {
        $data = self::where('id', $id)
                    ->first();

        return response()->json($data);
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '')
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $qry = self::select($_select);
        
        $totalFiltered = $qry->count();
        
        if (empty($search)) {
            
            if ($length > 0) {
                $qry->skip($start)
                    ->take($length);
            }

            foreach ($order as $row) {
                $qry->orderBy($row['column'], $row['dir']);
            }

        } else {
            foreach (array_values(self::mapSchema()) as $key => $val) {
                if ($key < 1) {
                    $qry->whereRaw('('.$val['alias'].' LIKE \'%'.$search.'%\'');
                } else if (count(array_values(self::mapSchema())) == ($key + 1)) {
                    $qry->orWhereRaw($val['alias'].' LIKE \'%'.$search.'%\')');
                } else {
                    $qry->orWhereRaw($val['alias'].' LIKE \'%'.$search.'%\'');
                }
            }

            $totalFiltered = $qry->count();

            if ($length > 0) {
                $qry->skip($start)
                    ->take($length);
            }

            foreach ($order as $row) {
                $qry->orderBy($row['column'], $row['dir']);
            }
        }

        return [
            'data' => $qry->get(),
            'totalData' => $totalData,
            'totalFiltered' => $totalFiltered
        ];
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $filename = null;

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {
            if (isset($params['password']) && $params['password']) {
                $params['password'] = bcrypt($params['password']);
            } else {
                unset($params['password']);
            }
            $update = self::where('id', $params['id'])->update($params);

            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Sukses Memperbaharui Pengguna'
            ]);
        }

        $params['password'] = bcrypt($params['password']);
        $role = $params['role'];
        unset($params['role']);
        $save = self::create($params);
        $save->assignRole($role);


        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Sukses Menambah Pengguna',
            'data' => self::getById($save->id)->original
        ]);
    }  

    public static function getAllResult($params)
    {
        unset($params['all']);

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $db = self::select($_select);

        if ($params) {
            foreach (array($params) as $k => $v) {
                foreach (array_keys($v) as $key => $row) {
                    if (isset(self::mapSchema()[$row])) {
                        if (is_array(array_values($v)[$key])) {
                            if ($this->operators[array_keys(array_values($v)[$key])[$key]] != 'ilike') {
                                $db->where(self::mapSchema()[$row], $this->operators[array_keys(array_values($v)[$key])[$key]], array_values(array_values($v)[$key])[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row], 'ilike', '%'.array_values($v)[$key].'%');
                            }
                        } else {
                            if (self::mapSchema()[$row]['type'] === 'int') {
                                $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row]['alias'], 'ilike', '%'.array_values($v)[$key].'%');
                            }
                        }
                    }
                }
            }
        }

        return response()->json($db->get());
    }
}
