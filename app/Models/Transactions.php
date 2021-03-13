<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $code
 * @property DateTime   $date
 * @property string     $type
 * @property string     $approved_by
 * @property int        $created_at
 * @property int        $updated_at
 */
class Transactions extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transactions';

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
        'code', 'date', 'type', 'approved_by', 'created_at', 'updated_at'
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
        'code' => 'string', 'date' => 'datetime', 'type' => 'string', 'approved_by' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetails::class, 'transaction_id', 'id');
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $filename = null;
        $is_skip_return = false;
        $transaction_items = [];

        if (isset($params['is_skip_return']) && $params['is_skip_return']) {
            $is_skip_return = true;
            unset($params['is_skip_return']);
        }

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['items']) && $params['items']) {
            $transaction_items = $params['items'];
            unset($params['items']);
        }

        if (isset($params['id']) && $params['id']) {
            $update = self::where('id', $params['id'])->update($params);

            return response()->json([
                'status' => 'success',
                'message' => 'Sukses Memperbaharui Item'
            ]);
        }

        $params['status'] = 'requested';

        $save = self::create($params);

        if ($save) {
            foreach($transaction_items as $transaction_item) {
                $transaction_item['transaction_id'] = $save->id;
                $transaction_item['is_skip_return'] = true;
                $transaction_item['type'] = $params['type'];
                $transaction_item['status'] = $params['status'];

                TransactionDetails::createOrUpdate($transaction_item, $method, $request);
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
}
