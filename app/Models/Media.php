<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $model
 * @property int        $model_id
 * @property string     $type
 * @property string     $original_name
 * @property string     $filepath
 * @property string     $filename
 * @property string     $mime_type
 * @property string     $extension
 * @property int        $created_at
 * @property int        $updated_at
 */
class Media extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'media';

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
        'model', 'model_id', 'type', 'original_name', 'filepath', 'filename', 'mime_type', 'extension', 'created_at', 'updated_at'
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
        'model' => 'string', 'model_id' => 'int', 'type' => 'string', 'original_name' => 'string', 'filepath' => 'string', 'filename' => 'string', 'mime_type' => 'string', 'extension' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
