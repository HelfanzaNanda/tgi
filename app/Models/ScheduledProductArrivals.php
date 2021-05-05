<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $invoice_number
 * @property int        $inventory_id
 * @property string     $customer_order_number
 * @property Date       $dispatch_date
 * @property Date       $estimated_time_of_arrival
 * @property int        $created_at
 * @property int        $updated_at
 */
class ScheduledProductArrivals extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'scheduled_product_arrivals';

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
        'invoice_number', 'inventory_id', 'customer_id', 'quantity', 'customer_order_number', 'dispatch_date', 'estimated_time_of_arrival', 'created_at', 'updated_at'
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
        'invoice_number' => 'string', 'inventory_id' => 'int', 'customer_id' => 'int',  'customer_order_number' => 'string', 'dispatch_date' => 'date', 'estimated_time_of_arrival' => 'date', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'dispatch_date', 'estimated_time_of_arrival', 'created_at', 'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'id' => ['alias' => $model->table.'.id', 'type' => 'int'],
            'invoice_number' => ['alias' => $model->table.'.invoice_number', 'type' => 'string'],
            'inventory_id' => ['alias' => $model->table.'.inventory_id', 'type' => 'integer'],
            'customer_id' => ['alias' => $model->table.'.customer_id', 'type' => 'integer'],
            'quantity' => ['alias' => $model->table.'.quantity', 'type' => 'decimal'],
            'customer_order_number' => ['alias' => $model->table.'.customer_order_number', 'type' => 'string'],
            'dispatch_date' => ['alias' => $model->table.'.dispatch_date', 'type' => 'string'],
            'estimated_time_of_arrival' => ['alias' => $model->table.'.estimated_time_of_arrival', 'type' => 'string'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }

    // Scopes...

    // Functions ...

    // Relations ...

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

        $qry = self::select($_select)
        ->addSelect(['inventories.id as inventory_id', 'inventories.code as inventory_code', 
        'inventories.product_description as inventory_description'])
        ->join('inventories', 'scheduled_product_arrivals.inventory_id', '=', 'inventories.id');

        if (isset($filter['dispatch_date'])  && isset($filter['eta']) && isset($filter['customer_id'])) {
            if ($filter['dispatch_date'] && $filter['eta'] && $filter['customer_id']) {
                $dispatch_date = explode(' to ', $filter['dispatch_date']);
                $eta = explode(' to ', $filter['eta']);
                $start_dispatch_date = $dispatch_date[0];
                $end_dispatch_date = $dispatch_date[1];
                $start_eta = $eta[0];
                $end_eta = $eta[1];
    
                $qry->whereBetween('dispatch_date', [$start_dispatch_date, $end_dispatch_date])
                ->whereBetween('estimated_time_of_arrival', [$start_eta, $end_eta])
                ->where('customer_id', $filter['customer_id']);
            }
        }
       
        
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
            $update = self::where('id', $params['id'])->update($params);

            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Sukses Memperbaharui Scheduled Arrival'
            ]);
        }

        $save = self::create($params);

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Sukses Menambah Scheduled Arrival',
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

    public function inventory()
    {
        return $this->belongsTo(Inventories::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }
}
