<?php

namespace App\Models;

use App\Models\Columns;
use App\Models\Racks;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $inventory_id
 * @property string     $note
 * @property int        $created_at
 * @property int        $updated_at
 */
class StockOpnameItems extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_opname_items';

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
        'stock_opname_id', 'inventory_id', 'stock_on_book', 'stock_on_physic', 'note', 'created_at', 'updated_at', 'rack_id', 'column_id'
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
        'inventory_id' => 'int', 'note' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
        return $this->belongsTo(Inventories::class, 'inventory_id', 'id');
    }

    public function rack()
    {
        return $this->belongsTo(Racks::class, 'rack_id', 'id');
    }

    public function column()
    {
        return $this->belongsTo(Columns::class, 'column_id', 'id');
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
