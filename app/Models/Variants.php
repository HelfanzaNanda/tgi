<?php

namespace App\Models;

use DB;
use App\Models\SubVariants;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $name
 * @property string     $key
 * @property string     $type
 * @property int        $created_at
 * @property int        $updated_at
 */
class Variants extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'variants';

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
        'name', 'key', 'type', 'created_at', 'updated_at', 'is_protected'
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
        'name' => 'string', 'key' => 'string', 'type' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
    public function sub_variants()
    {
        return $this->hasMany(SubVariants::class, 'variant_id');
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
            'key' => ['column' => $model->table.'.key', 'alias' => 'key', 'type' => 'string'],
            'name' => ['column' => $model->table.'.name', 'alias' => 'name', 'type' => 'string'],
            'type' => ['column' => $model->table.'.type', 'alias' => 'type', 'type' => 'string'],
            'is_protected' => ['column' => $model->table.'.is_protected', 'alias' => 'is_protected', 'type' => 'int'],
            'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'string'],
            'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'string'],
        ];
    }

    private static function joinSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            
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

        $countAll = $db->count();
        $currentPage = $paramsPage > 0 ? $paramsPage - 1 : 0;
        $page = $paramsPage > 0 ? $paramsPage + 1 : 2; 
        $nextPage = env('APP_URL').'/api/inventory_groups?page='.$page;
        $prevPage = env('APP_URL').'/api/inventory_groups?page='.($currentPage < 1 ? 1 : $currentPage);
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
        $data = self::where('id', $id)
                    ->first();

        return response()->json($data);
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

        return response()->json($db->with('sub_variants')->get());
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $filename = null;
        $option = [];

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['option']) && $params['option']) {
            $option = $params['option'];
            unset($params['option']);
        }

        if (isset($params['id']) && $params['id']) {
            $update = self::where('id', $params['id'])->update($params);

            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Sukses Memperbaharui Variant'
            ]);
        }

        $save = self::create($params);

        if ($save) {
            if (isset($option['name']) && isset($option['key'])) {
                foreach($option['name'] as $key => $val) {
                    $sub_variant_params['variant_id'] = $save->id;
                    $sub_variant_params['name'] = $option['name'][$key];
                    $sub_variant_params['key'] = $option['key'][$key];
                    $sub_variant_params['type'] = 'string';
                    $sub_variant_params['is_active'] = 1;

                    SubVariants::create($sub_variant_params);
                }
            }
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Sukses Menambah Variant',
            'data' => self::getById($save->id)->original
        ]);
    }
}
