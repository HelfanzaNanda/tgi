<?php

namespace App\Models;

use DB;
use Redirect;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

	protected $table = 'users';

	protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone'
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
            'name' => ['alias' => $model->table.'.name', 'type' => 'string'],
            'email' => ['alias' => $model->table.'.email', 'type' => 'string'],
            'email_verified_at' => ['alias' => $model->table.'.email_verified_at', 'type' => 'string'],
            'password' => ['alias' => $model->table.'.password', 'type' => 'string'],
            'phone' => ['alias' => $model->table.'.phone', 'type' => 'string'],
			'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
			'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }

    public static function authorizes($params, $method, $request)
    {
        if(Auth::attempt(['username' => $params['username'], 'password' => $params['password']])){
            $user = Auth::user();

            $request->session()->flush();
            $request->session()->put('_login', true);
            $request->session()->put('_id', $user['id']);
            $request->session()->put('_name', $user['name']);
            $request->session()->put('_email', $user['email']);
            $request->session()->put('_username', $user['username']);
            $request->session()->put('_phone', $user['phone']);
            $request->session()->put('_role_id', $user['role_id']);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Login',
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Kombinasi username ataupun password tidak benar',
                'data' => null
            ], 200);
        }
    }
}
