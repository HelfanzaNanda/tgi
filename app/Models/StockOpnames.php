<?php

namespace App\Models;

use App\Models\InventoryLocations;
use App\Models\StockOpnameItems;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $number
 * @property DateTime   $date
 * @property int        $warehouse_id
 * @property string     $description
 * @property int        $created_at
 * @property int        $updated_at
 */
class StockOpnames extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_opnames';

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
        'number', 'date', 'warehouse_id', 'description', 'created_at', 'updated_at'
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
        'number' => 'string', 'date' => 'datetime', 'warehouse_id' => 'int', 'description' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date', 'created_at', 'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    // Scopes...

    // Functions ...

    // Relations ...
    public function items()
    {
        return $this->hasMany(StockOpnameItems::class, 'stock_opname_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class, 'warehouse_id');
    }

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'id' => ['alias' => $model->table.'.id', 'type' => 'int'],
            'number' => ['alias' => $model->table.'.number', 'type' => 'string'],
            'date' => ['alias' => $model->table.'.date', 'type' => 'string'],
            'warehouse_id' => ['alias' => $model->table.'.warehouse_id', 'type' => 'string'],
            'warehouse_name' => ['alias' => 'warehouses.name AS warehouse_name', 'type' => 'string'],
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
                    ->with('warehouse')
                    ->with('items')
                    ->with('items.inventory')
                    ->with('items.rack')
                    ->with('items.column')
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

        $qry = self::select($_select)->join('warehouses', 'warehouses.id', '=', 'stock_opnames.warehouse_id');
        
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
        $items = [];

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['items']) && $params['items']) {
            $items = $params['items'];
            unset($params['items']);
        }

        if (isset($params['id']) && $params['id']) {
            $update = self::where('id', $params['id'])->update($params);

            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Sukses Memperbaharui Stok Opname'
            ]);
        }

        $params['number'] = 'SO'.strtotime('now');
        $params['description'] = 'Penyesuaian Stok Gudang';
        if (isset($params['description']) && $params['description']) {
            $params['description'] = $params['description'];
        }

        $save = self::create($params);

        if ($save) {
            foreach($items['inventory_id'] as $key => $val) {
                $so_item['stock_opname_id'] = $save->id;
                $so_item['inventory_id'] = $items['inventory_id'][$key];
                $so_item['stock_on_book'] = $items['stock_on_book'][$key];
                $so_item['stock_on_physic'] = $items['stock_on_physic'][$key];
                $so_item['note'] = $items['note'][$key];
                $so_item['rack_id'] = $items['rack_id'][$key];
                $so_item['column_id'] = $items['column_id'][$key];

                $save_so_item = StockOpnameItems::create($so_item);

                if ($save_so_item) {
                    InventoryLocations::
                        where('inventory_id', $items['inventory_id'][$key])->
                        where('warehouse_id', $params['warehouse_id'])->
                        where('rack_id', $items['rack_id'][$key])->
                        where('column_id', $items['column_id'][$key])->
                        update([
                            'stock' => $items['stock_on_physic'][$key]
                        ]);
                }
            }
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Sukses Menambah Stok Opname',
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

        $db = self::select($_select)->join('warehouses', 'warehouses.id', '=', 'stock_opnames.warehouse_id');;

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
