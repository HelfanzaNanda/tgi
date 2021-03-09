<?php

namespace App\Models;

use App\Models\Inventories;
use App\Models\Racks;
use App\Models\Warehouses;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $inventory_id
 * @property int        $warehouse_id
 * @property int        $rack_id
 * @property int        $stock
 * @property int        $created_at
 * @property int        $updated_at
 */
class InventoryLocations extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inventory_locations';

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
        'inventory_id', 'warehouse_id', 'rack_id', 'stock', 'created_at', 'updated_at'
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
        'inventory_id' => 'int', 'warehouse_id' => 'int', 'rack_id' => 'int', 'stock' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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

    // Scopes...

    // Functions ...

    // Relations ...
    public function inventory()
    {
        return $this->belongsTo(Inventories::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class);
    }

    public function rack()
    {
        return $this->belongsTo(Racks::class);
    }

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
            'inventory_id' => ['alias' => $model->table.'.inventory_id', 'type' => 'string'],
            'warehouse_id' => ['alias' => $model->table.'.warehouse_id', 'type' => 'string'],
            'rack_id' => ['alias' => $model->table.'.rack_id', 'type' => 'int'],
            'stock' => ['alias' => $model->table.'.stock', 'type' => 'string'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }

    private static function joinSchema($params = [], $user = [])
    {
        $model = new self;

        return [];
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $filename = null;
        $is_skip_return = false;
        $type = 'in';

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['is_skip_return']) && $params['is_skip_return']) {
            $is_skip_return = true;
            unset($params['is_skip_return']);
        }

        if (isset($params['type']) && $params['type']) {
            $type = $params['type'];
            unset($params['type']);
        }

        if (isset($params['qty']) && $params['qty']) {
            $params['stock'] = $params['qty'];
            unset($params['qty']);
        }

        if (isset($params['id']) && $params['id']) {
            $update = self::where('id', $params['id'])->update($params);

            return response()->json([
                'status' => 'success',
                'message' => 'Sukses Memperbaharui Item'
            ]);
        }

        $save = self::firstOrCreate([
            'inventory_id' => $params['inventory_id'], 
            'warehouse_id' => $params['warehouse_id'], 
            'rack_id' => $params['rack_id']
        ]);

        if ($save) {
            if ($type == 'in') {
                self::where('id', $save->id)->increment('stock', $params['stock']);
            } else {
                self::where('id', $save->id)->decrement('stock', $params['stock']);
            }
        }

        DB::commit();
        
        if (!$is_skip_return) {
            return response()->json([
                'status' => 'success',
                'message' => 'Sukses Menambah Item',
                'data' => self::getById($save->id)->original
            ]);
        }
    }

    public static function getPaginatedResult($params)
    {
        $paramsPage = isset($params['page']) ? $params['page'] : 0;

        unset($params['page']);

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $db = self::select($_select);

        foreach(self::joinSchema() as $join) {
            if ($join['type'] == 'left') {
                $db->leftJoin($join['table'], [$join['on']]);
            } else {
                $db->join($join['table'], [$join['on']]);
            }
        }

        if ($params) {
            foreach (array($params) as $k => $v) {
                foreach (array_keys($v) as $key => $row) {
                    if (isset(self::mapSchema()[$row])) {
                        if (is_array(array_values($v)[$key])) {
                            if ($this->operators[array_keys(array_values($v)[$key])[$key]] != 'like') {
                                $db->where(self::mapSchema()[$row]['alias'], $this->operators[array_keys(array_values($v)[$key])[$key]], array_values(array_values($v)[$key])[$key]);
                            } else {
                                if (self::mapSchema()[$row]['type'] === 'int') {
                                    $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                                } else {
                                    $db->where(self::mapSchema()[$row]['alias'], 'like', '%'.array_values($v)[$key].'%');
                                }
                            }
                        } else {
                            if (self::mapSchema()[$row]['type'] === 'int') {
                                $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row]['alias'], 'like', '%'.array_values($v)[$key].'%');
                            }
                        }
                    }
                }
            }
        }

        // $db->leftJoin('tabel_konsultan_pengawas', 'tabel_konsultan_pengawas.id_konsultan_pengawas', '=', 'sys_mop.company_id');

        $countAll = $db->count();
        $currentPage = $paramsPage > 0 ? $paramsPage - 1 : 0;
        $page = $paramsPage > 0 ? $paramsPage + 1 : 2; 
        $nextPage = env('APP_URL').'/api/inventory_locations?page='.$page;
        $prevPage = env('APP_URL').'/api/inventory_locations?page='.($currentPage < 1 ? 1 : $currentPage);
        $totalPage = ceil((int)$countAll / 10);

        $db->skip($currentPage * 10)
           ->take(10);

        return response()->json([
            'nav' => [
                'totalData' => $countAll,
                'nextPage' => $nextPage,
                'prevPage' => $prevPage,
                'totalPage' => $totalPage
            ],
            'data' => $db->get()
        ]);
    }

    public static function getById($id, $params = null)
    {
        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $data = self::select($_select)->where('inventory_locations.id', $id)->with('warehouse')->with('rack')->with('inventory');
        
        foreach(self::joinSchema() as $join) {
            if ($join['type'] == 'left') {
                $data->leftJoin($join['table'], [$join['on']]);
            } else {
                $data->join($join['table'], [$join['on']]);
            }
        }

        return response()->json($data->first());
    }

    public static function getAllResult($params)
    {
        unset($params['all']);

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $db = self::select($_select);

        foreach(self::joinSchema() as $join) {
            if ($join['type'] == 'left') {
                $db->leftJoin($join['table'], [$join['on']]);
            } else {
                $db->join($join['table'], [$join['on']]);
            }
        }

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

        return response()->json([
            'data' => $db->get()
        ]);
    }
}
