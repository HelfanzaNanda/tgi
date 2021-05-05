<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $invoice_number
 * @property int        $inventory_id
 * @property string     $customer_order_number
 * @property Date       $dispatch_date
 * @property Date       $estimated_time_of_arrival
 * @property int        $created_at
 * @property int        $updated_at
 */
class ScheduledProductArrivals extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'scheduled_product_arrivals';

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
        'invoice_number', 'inventory_id', 'quantity', 'customer_order_number', 'dispatch_date', 'estimated_time_of_arrival', 'created_at', 'updated_at'
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
        'invoice_number' => 'string', 'inventory_id' => 'int', 'customer_order_number' => 'string', 'dispatch_date' => 'date', 'estimated_time_of_arrival' => 'date', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'dispatch_date', 'estimated_time_of_arrival', 'created_at', 'updated_at'
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
