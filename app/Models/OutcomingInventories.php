<?php

namespace App\Models;

use App\Models\InspectionAnswers;
use App\Models\Inspections;
use App\Models\Media;
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
        'code' => 'string', 'created_by' => 'int', 'note' => 'string', 'status' => 'string'
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
            'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'int'],
            'code' => ['column' => $model->table.'.code', 'alias' => 'code', 'type' => 'string'],
            'created_by' => ['column' => $model->table.'.created_by', 'alias' => 'created_by', 'type' => 'int'],
            'created_by_name' => ['column' => 'users.name', 'alias' => 'created_by_name', 'type' => 'string'],
            'date' => ['column' => 'transactions.date', 'alias' => 'date', 'type' => 'string'],
            'note' => ['column' => $model->table.'.note', 'alias' => 'note', 'type' => 'string'],
            'status' => ['column' => $model->table.'.status', 'alias' => 'status', 'type' => 'string'],
            'record_of_transfer_id' => ['column' => 'record_of_transfers.id', 'alias' => 'record_of_transfer_id', 'type' => 'int'],
            'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'string'],
            'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'string'],
        ];
    }

    private static function joinSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            ['table'=>'users','type'=>'inner','on'=>['users.id','=','outcoming_inventories.created_by']],
            ['table'=>'transactions','type'=>'inner','on'=>['transactions.code','=','outcoming_inventories.code']],
            ['table'=>'record_of_transfers','type'=>'left','on'=>['record_of_transfers.model_id','=','outcoming_inventories.id'], 'where' => ['record_of_transfers.model', self::class]],
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = [])
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['column'] . ' as '. $select['alias'];
        }

        $qry = self::select($_select);
        
        foreach(self::joinSchema() as $join) {
            if ($join['type'] == 'left') {
                $qry->leftJoin($join['table'], [$join['on']]);
                if (isset($join['where'])) {
                    $qry->where($join['where'][0], $join['where'][1])
                    ->orWhereNull($join['where'][0]);
                }
            } else {
                $qry->join($join['table'], [$join['on']]);
            }
        }

        foreach($filter as $filteredKey => $filteredVal) {
            $qry->where(self::mapSchema()[$filteredKey]['column'], $filteredVal);
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
            $_select[] = $select['column'] . ' as '. $select['alias'];
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
            $_select[] = $select['column'] . ' as '. $select['alias'];
        }

        $data = self::select($_select)->where('outcoming_inventories.id', $id)
                        ->with('transaction.transactionDetails.rack')
                        ->with('transaction.transactionDetails.warehouse')
                        ->with('transaction.transactionDetails.inventory.media');
                        // ->with(['transaction.transactionDetails.inventory' => function ($query) {
                        //     return $query->select('id', 'name')->append('media');
                        // }]);
        
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
            $_select[] = $select['column'] . ' as '. $select['alias'];
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

        return response()->json([
            'data' => $db->get()
        ]);
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $filename = null;
        $transaction = [];
        $inspections = [];
        $files = [];

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['transaction']) && $params['transaction']) {
            $transaction = $params['transaction'];
            unset($params['transaction']);
        }

        if (isset($params['inspections']) && $params['inspections']) {
            $inspections = $params['inspections'];
            unset($params['inspections']);
        }

        if (isset($params['files']) && $params['files']) {
            $files = $params['files'];
            unset($params['files']);
        }

        if (isset($params['id']) && $params['id']) {
            $update = self::where('id', $params['id'])->update($params);

            if ($update) {
                $new = self::where('id', $params['id'])->first();

                $transaction['is_skip_return'] = true;
                $transaction['type'] = 'out';
                $transaction['status'] = $new['status'];

                Transactions::createOrUpdate($transaction, $method, $request);
            }

            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Sukses Memperbaharui Item'
            ]);
        }

        $params['code'] = 'OUT'.strtotime('now');
        $params['status'] = isset($params['status']) && $params['status'] ? $params['status'] : 'requested';

        $save = self::create($params);

        if ($save) {
            $transaction['code'] = $params['code'];
            $transaction['type'] = 'out';
            $transaction['approved_by'] = 0;
            $transaction['is_skip_return'] = true;
            $transaction['status'] = $params['status'];

            Transactions::createOrUpdate($transaction, $method, $request);

            $save_inspection = Inspections::create([
                'number' => 'INS'.strtotime('now'),
                'model' => __CLASS__,
                'model_id' => $save->id,
                'type' => 'out',
                'date' => date('Y-m-d H:i:s'),
                'inspected_by' => $params['created_by'],
                'approved_by' => 0,
            ]);

            foreach ($inspections as $iK => $iV) {
                InspectionAnswers::create([
                    'inspection_id' => $save_inspection->id,
                    'inspection_question_id' => $iK,
                    'answer' => $iV,
                ]);
            }

            $media_params['type'] = 'inspection_out';
            $media_params['model'] = __CLASS__;
            $media_params['model_id'] = $save->id;
            Media::createOrUpdate($media_params, $method, $request);
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Sukses Menambah Item',
            'data' => self::getById($save->id)->original
        ]);
    }
}
