<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $code
 * @property string     $receipt_number
 * @property int        $received_by
 * @property string     $note
 * @property string     $status
 * @property int        $created_at
 * @property int        $updated_at
 */
class IncomingInventories extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'incoming_inventories';

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
        'code', 'receipt_number', 'received_by', 'note', 'status', 'created_at', 'updated_at'
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
        'code' => 'string', 'receipt_number' => 'string', 'received_by' => 'int', 'note' => 'string', 'status' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
