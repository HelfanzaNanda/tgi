<?php

namespace App\Models;

use App\Models\Variants;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $variant_id
 * @property string     $name
 * @property string     $key
 * @property string     $type
 * @property boolean    $is_active
 * @property int        $created_at
 * @property int        $updated_at
 */
class SubVariants extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sub_variants';

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
        'variant_id', 'name', 'key', 'type', 'is_active', 'created_at', 'updated_at'
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
        'variant_id' => 'int', 'name' => 'string', 'key' => 'string', 'type' => 'string', 'is_active' => 'boolean', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
