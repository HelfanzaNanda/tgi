<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $inventory_id
 * @property int        $variant_id
 * @property string     $variant_name
 * @property int        $sub_variant_id
 * @property string     $sub_variant_name
 * @property int        $created_at
 * @property int        $updated_at
 */
class InventoryVariants extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inventory_variants';

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
        'inventory_id', 'variant_id', 'variant_name', 'sub_variant_id', 'sub_variant_name', 'created_at', 'updated_at'
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
        'inventory_id' => 'int', 'variant_id' => 'int', 'variant_name' => 'string', 'sub_variant_id' => 'int', 'sub_variant_name' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
}
