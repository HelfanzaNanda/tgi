<?php

namespace App\Models;

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
        'transaction_id', 'inventory_id', 'warehouse_id', 'rack_id', 'qty', 'created_at', 'updated_at'
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
        'transaction_id' => 'string', 'inventory_id' => 'string', 'warehouse_id' => 'string', 'rack_id' => 'string', 'qty' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
    public $timestamps = false;

    // Scopes...

    // Functions ...

    // Relations ...
}
