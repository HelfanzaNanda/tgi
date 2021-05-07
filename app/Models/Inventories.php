<?php

namespace App\Models;

use App\Models\InventoryGroups;
use App\Models\InventoryLocations;
use App\Models\InventoryVariants;
use App\Models\Media;
use App\Models\SubVariants;
use App\Models\Variants;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $code
 * @property string     $name
 * @property int        $category_id
 * @property int        $supplier_id
 * @property int        $unit_id
 * @property int        $created_at
 * @property int        $updated_at
 */
class Inventories extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inventories';

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
        'code', 'name', 'category_id', 'buy_price', 'supplier_id', 'unit_id', 'created_at', 'updated_at', 'product_description', 'inventory_group_id'
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
        'code' => 'string', 'name' => 'string', 'category_id' => 'int', 'supplier_id' => 'int', 'unit_id' => 'int'
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

    // protected $appends = ['media'];

    // Scopes...

    // Functions ...

    // Relations ...
    public function inventoryLocations()
    {
        return $this->hasMany(InventoryLocations::class, 'inventory_id', 'id');
    }

    public function inventoryVariant()
    {
        return $this->hasOne(InventoryVariants::class, 'inventory_id')
        ->where('variant_name', 'Color');
    }

    public function inventoryVariants()
    {
        return $this->hasMany(InventoryVariants::class, 'inventory_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'model_id', 'id')->where('model', __CLASS__);
    }

    public function getMediaAttribute()
    {
        $media = Media::where('model_id', $this->id)->where('model', __CLASS__)->get();
        return $media;
    }

    public function getMediaCoverAttribute()
    {
        $media = Media::where('model_id', $this->id)->where('model', __CLASS__)->first();
        return $media;
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
            'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'int'],
            'code' => ['column' => $model->table.'.code', 'alias' => 'code', 'type' => 'string'],
            'name' => ['column' => $model->table.'.name', 'alias' => 'name', 'type' => 'string'],
            'category_id' => ['column' => $model->table.'.category_id', 'alias' => 'category_id', 'type' => 'int'],
            'category_name' => ['column' => 'categories.name', 'alias' => 'category_name', 'type' => 'string'],
            'buy_price' => ['column' => $model->table.'.buy_price', 'alias' => 'buy_price', 'type' => 'string'],
            'supplier_id' => ['column' => $model->table.'.supplier_id', 'alias' => 'supplier_id', 'type' => 'int'],
            'supplier_name' => ['column' => 'suppliers.name', 'alias' => 'supplier_name', 'type' => 'string'],
            'unit_id' => ['column' => $model->table.'.unit_id', 'alias' => 'unit_id', 'type' => 'int'],
            'unit_name' => ['column' => 'units.name', 'alias' => 'unit_name', 'type' => 'string'],
            'product_description' => ['column' => $model->table.'.product_description', 'alias' => 'product_description', 'type' => 'string'],
            'inventory_group_id' => ['column' => $model->table.'.inventory_group_id', 'alias' => 'inventory_group_id', 'type' => 'int'],
            'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'string'],
            'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'string'],
        ];
    }

    private static function joinSchema($params = [], $user = [])
    {
        $model = new self;
        return [
            ['table'=>'categories','type'=>'inner','on'=>['categories.id','=','inventories.category_id']],
            ['table'=>'suppliers','type'=>'inner','on'=>['suppliers.id','=','inventories.supplier_id']],
            ['table'=>'units','type'=>'inner','on'=>['units.id','=','inventories.unit_id']],
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '')
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['column'].' as '.$select['alias'];
        }

        $qry = self::select($_select);
        
        foreach(self::joinSchema() as $join) {
            if ($join['type'] == 'left') {
                $qry->leftJoin($join['table'], [$join['on']]);
            } else {
                $qry->join($join['table'], [$join['on']]);
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
                    $qry->whereRaw('('.$val['column'].' LIKE \'%'.$search.'%\'');
                } else if (count(array_values(self::mapSchema())) == ($key + 1)) {
                    $qry->orWhereRaw($val['column'].' LIKE \'%'.$search.'%\')');
                } else {
                    $qry->orWhereRaw($val['column'].' LIKE \'%'.$search.'%\'');
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

    public static function getPaginatedResult($params)
    {
        $or = [];
        if (isset($params['or']) && $params['or']) {
            $or = $params['or'];
            unset($params['or']);
        }
        $paramsPage = isset($params['page']) ? $params['page'] : 0;

        unset($params['page']);

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['column'].' as '.$select['alias'];
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
                                $db->where(self::mapSchema()[$row]['column'], $this->operators[array_keys(array_values($v)[$key])[$key]], array_values(array_values($v)[$key])[$key]);
                            } else {
                                if (self::mapSchema()[$row]['type'] === 'int') {
                                    $db->where(self::mapSchema()[$row]['column'], array_values($v)[$key]);
                                } else {
                                    $db->where(self::mapSchema()[$row]['column'], 'like', '%'.array_values($v)[$key].'%');
                                }
                            }
                        } else {
                            if (self::mapSchema()[$row]['type'] === 'int') {
                                $db->where(self::mapSchema()[$row]['column'], array_values($v)[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row]['column'], 'like', '%'.array_values($v)[$key].'%');
                            }
                        }
                    }
                }
            }
        }

        $n = 0;
        if ($or) {
            foreach($or as $orKey => $orVal) {
                if (isset(self::mapSchema()[$orKey])) {
                    if (self::mapSchema()[$orKey]['type'] === 'int') {
                        if ($n < 1) {
                            $db->whereRaw('( '.self::mapSchema()[$orKey]['column'] . ' = ' .$orVal);
                        } else {
                            $db->orWhereRaw(self::mapSchema()[$orKey]['column'] . ' = ' .$orVal);
                        }
                    } else {
                        if ($n < 1) {
                            $db->whereRaw('( '.self::mapSchema()[$orKey]['column'] . ' like \'%'.$orVal.'%\'');
                        } else if ($n == count($or)) {
                            $db->orWhereRaw(self::mapSchema()[$orKey]['column'] . ' like \'%'.$orVal.'%\'');
                        } else {
                            $db->orWhereRaw(self::mapSchema()[$orKey]['column'] . ' like \'%'.$orVal.'%\')');
                        }
                    }
                    $n++;
                }
            }
            // $db->whereRaw(' )');
        }

        // $db->leftJoin('tabel_konsultan_pengawas', 'tabel_konsultan_pengawas.id_konsultan_pengawas', '=', 'sys_mop.company_id');

        $countAll = $db->count();
        $currentPage = $paramsPage > 0 ? $paramsPage - 1 : 0;
        $page = $paramsPage > 0 ? $paramsPage + 1 : 2; 
        $nextPage = env('APP_URL').'/api/inventories?page='.$page;
        $prevPage = env('APP_URL').'/api/inventories?page='.($currentPage < 1 ? 1 : $currentPage);
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
            'data' => $db->get()->append('media_cover')
        ]);
    }

    public static function getSql($query)
    {
        $bindings = $query->getBindings();

        return preg_replace_callback('/\?/', function ($match) use (&$bindings, $query) {
            return $query->getConnection()->getPdo()->quote(array_shift($bindings));
        }, $query->toSql());
    }

    public static function getById($id, $params = null)
    {
        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['column'].' as '.$select['alias'];
        }

        $data = self::select($_select)->where('inventories.id', $id)->with('inventoryLocations.warehouse')->with('inventoryLocations.rack')->with('inventoryVariants');
        
        foreach(self::joinSchema() as $join) {
            if ($join['type'] == 'left') {
                $data->leftJoin($join['table'], [$join['on']]);
            } else {
                $data->join($join['table'], [$join['on']]);
            }
        }

        return response()->json($data->first()->append('media'));
    }

    public static function getAllResult($params)
    {
        unset($params['all']);

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['column'].' as '.$select['alias'];
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
                                $db->where(self::mapSchema()[$row]['column'], array_values($v)[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row]['column'], 'ilike', '%'.array_values($v)[$key].'%');
                            }
                        }
                    }
                }
            }
        }

        return response()->json($db->get()->append('media_cover'));
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $filename = null;
        $location = [];
        $variant = [];

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['location']) && $params['location']) {
            $location = $params['location'];
            unset($params['location']);
        }

        if (isset($params['inventory_group_id']) && $params['inventory_group_id']) {
            $params['name'] = InventoryGroups::where('id', $params['inventory_group_id'])->value('name');
            unset($params['inventory_group_id']);
        }

        if (isset($params['variant']) && $params['variant']) {
            $variant = $params['variant'];
            unset($params['variant']);
        }

        if (isset($params['id']) && $params['id']) {
            $update = self::where('id', $params['id'])->update($params);

            if ($update && count($variant) > 0) {
                InventoryVariants::where('inventory_id', $params['id'])->delete();
                foreach($variant as $key_variant => $val_variant) {
                    $find_variant = Variants::where('id', $key_variant)->first();
                    $find_sub_variant = SubVariants::where('id', $val_variant)->first();
                    $params_variant['inventory_id'] = $params['id'];
                    $params_variant['variant_id'] = $find_variant['id'];
                    $params_variant['variant_name'] = $find_variant['name'];
                    $params_variant['sub_variant_id'] = $find_sub_variant['id'];
                    $params_variant['sub_variant_name'] = $find_sub_variant['name'];

                    InventoryVariants::create($params_variant);
                }
            }

            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Sukses Memperbaharui Item'
            ]);
        }

        $save = self::create($params);

        if ($save && count($location) > 0) {
            $location['inventory_id'] = $save->id;
            $location['is_skip_return'] = true;
            InventoryLocations::createOrUpdate($location, $method, $request);
        }

        if ($save && count($variant) > 0) {
            foreach($variant as $key_variant => $val_variant) {
                $find_variant = Variants::where('id', $key_variant)->first();
                $find_sub_variant = SubVariants::where('id', $val_variant)->first();
                $params_variant['inventory_id'] = $save->id;
                $params_variant['variant_id'] = $find_variant['id'];
                $params_variant['variant_name'] = $find_variant['name'];
                $params_variant['sub_variant_id'] = $find_sub_variant['id'];
                $params_variant['sub_variant_name'] = $find_sub_variant['name'];

                InventoryVariants::create($params_variant);
            }
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Sukses Menambah Item',
            'data' => self::getById($save->id)->original
        ]);
    }
}
