<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $code
 * @property int        $created_by
 * @property string     $note
 * @property string     $status
 * @property int        $created_at
 * @property int        $updated_at
 */
class OutcomingInventories extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'outcoming_inventories';

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
        'code', 'created_by', 'note', 'status', 'created_at', 'updated_at'
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
        'code' => 'string', 'created_by' => 'int', 'note' => 'string', 'status' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
    public function transaction()
    {
        return $this->hasOne(Transactions::class, 'code', 'code');
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
            'code' => ['alias' => $model->table.'.code', 'type' => 'string'],
            'created_by' => ['alias' => $model->table.'.created_by', 'type' => 'int'],
            'created_by_name' => ['alias' => 'users.name as created_by_name', 'type' => 'string'],
            'note' => ['alias' => $model->table.'.note', 'type' => 'string'],
            'status' => ['alias' => $model->table.'.status', 'type' => 'string'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }

    private static function joinSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            ['table'=>'users','type'=>'inner','on'=>['users.id','=','outcoming_inventories.created_by']],
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '')
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $qry = self::select($_select);
        
        foreach(self::joinSchema() as $join) {
            if ($join['type'] == 'left') {
                $db->leftJoin($join['table'], [$join['on']]);
            } else {
                $db->join($join['table'], [$join['on']]);
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
            'data' => $db->get()
        ]);
    }

    public static function getById($id, $params = null)
    {
        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $data = self::select($_select)->where('outcoming_inventories.id', $id)
                        ->with('transaction.transactionDetails.rack')
                        ->with('transaction.transactionDetails.warehouse')
                        ->with('transaction.transactionDetails.inventory');
        
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

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $filename = null;
        $transaction = [];

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['transaction']) && $params['transaction']) {
            $transaction = $params['transaction'];
            unset($params['transaction']);
        }

        if (isset($params['id']) && $params['id']) {
            $update = self::where('id', $params['id'])->update($params);

            return response()->json([
                'status' => 'success',
                'message' => 'Sukses Memperbaharui Item'
            ]);
        }

        $params['code'] = 'OUT'.strtotime('now');
        $params['status'] = 'requested';

        $save = self::create($params);

        if ($save) {
            $transaction['code'] = $params['code'];
            $transaction['type'] = 'out';
            $transaction['approved_by'] = 0;
            $transaction['is_skip_return'] = true;

            Transactions::createOrUpdate($transaction, $method, $request);
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Sukses Menambah Item',
            'data' => self::getById($save->id)->original
        ]);
    }
}
