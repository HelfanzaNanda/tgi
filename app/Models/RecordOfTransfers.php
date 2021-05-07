<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class RecordOfTransfers extends Model
{
    protected $table = 'record_of_transfers';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number', 'model', 'model_id', 'type', 'date', 'description', 'submitted_by', 'received_by',
        'created_at', 'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'number' => 'string', 'model' => 'string', 'model_id' => 'string', 
        'type' => 'string', 'date' => 'date', 'description' => 'string',  
        'submitted_by' => 'int', 'received_by' => 'int',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'int'],
            'number' => ['column' => $model->table.'.number', 'alias' => 'number', 'type' => 'string'],
            'model' => ['column' => $model->table.'.model', 'alias' => 'model', 'type' => 'string'],
            'model_id' => ['column' => $model->table.'.model_id', 'alias' => 'model_id', 'type' => 'string'],
            'type' => ['column' => $model->table.'.type', 'alias' => 'type', 'type' => 'string'],
            'date' => ['column' => $model->table.'.date', 'alias' => 'date', 'type' => 'string'],
            'description' => ['column' => $model->table.'.description', 'alias' => 'description', 'type' => 'string'],
            'submitted_by' => ['column' => $model->table.'.submitted_by', 'alias' => 'submitted_by', 'type' => 'int'],
            'received_by' => ['column' => $model->table.'.received_by', 'alias' => 'received_by', 'type' => 'int'],
            'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'string'],
            'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'string'],
        ];
    }

    public static function getById($id, $params = null)
    {
        $data = self::where('id', $id)->first();

        return response()->json($data);
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $filename = null;

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }
        

        if (isset($params['id']) && $params['id']) {
            $update = self::where('id', $params['id'])->update($params);

            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Sukses Memperbaharui BA'
            ]);
        }

        $save = self::create($params);

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Sukses Menambah BA',
            'data' => self::getById($save->id)->original
        ]);
    }

    public function incoming_inventory()
    {
        return $this->hasOne(IncomingInventories::class, 'id', 'model_id');
    }

    public function outcoming_inventory()
    {
        return $this->hasOne(OutcomingInventories::class, 'id', 'model_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'received_by');
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'received_by');
    }
}