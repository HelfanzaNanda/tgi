<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $number
 * @property string     $model
 * @property string     $model_id
 * @property string     $type
 * @property Date       $date
 * @property int        $inspected_by
 * @property int        $approved_by
 */
class Inspections extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inspections';

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
        'number', 'model', 'model_id', 'type', 'date', 'inspected_by', 'approved_by'
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
        'number' => 'string', 'model' => 'string', 'model_id' => 'string', 'type' => 'string', 'date' => 'date', 'inspected_by' => 'int', 'approved_by' => 'int'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date'
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
