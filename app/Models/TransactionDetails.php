<?php

namespace App\Models;

use App\Models\Inventories;
use App\Models\InventoryLocations;
use App\Models\Racks;
use App\Models\Warehouses;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $transaction_id
 * @property string     $inventory_id
 * @property string     $warehouse_id
 * @property string     $rack_id
 * @property string     $qty
 * @property int        $created_at
 * @property int        $updated_at
 */
class TransactionDetails extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transaction_details';

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
        'transaction_id', 'inventory_id', 'warehouse_id', 'rack_id', 'qty', 'created_at', 'updated_at', 'note'
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
        'transaction_id' => 'string', 'inventory_id' => 'string', 'warehouse_id' => 'string', 'rack_id' => 'string', 'qty' => 'string'
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
    public function rack()
    {
        return $this->belongsTo(Racks::class, 'rack_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class, 'warehouse_id', 'id');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventories::class, 'inventory_id', 'id');
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $filename = null;
        $is_skip_return = false;
        $type = 'in';
        $status = 'requested';

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

        if (isset($params['status']) && $params['status']) {
            $status = $params['status'];
            unset($params['status']);
        }

        if (isset($params['id']) && $params['id']) {
            $update = self::where('id', $params['id'])->update($params);

            return response()->json([
                'status' => 'success',
                'message' => 'Sukses Memperbaharui Item'
            ]);
        }

        if (isset($params['edit']) && $params['edit']) {
            $save = self::create($params);

            if ($save && $status != 'requested') {
                $params['is_skip_return'] = true;
                $params['type'] = $type;

                InventoryLocations::createOrUpdate($params, $method, $request);
            }

            DB::commit();
            
            if (!$is_skip_return) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Sukses Menambah Item',
                    'data' => self::getById($save->id)->original
                ]);
            }

            return;
        }

        $save = self::create($params);

        if ($save && $status != 'requested') {
            $params['is_skip_return'] = true;
            $params['type'] = $type;

            InventoryLocations::createOrUpdate($params, $method, $request);
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
