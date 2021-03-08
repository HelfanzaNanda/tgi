<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $uuid
 * @property string     $connection
 * @property string     $queue
 * @property string     $payload
 * @property string     $exception
 * @property int        $failed_at
 */
class FailedJobs extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'failed_jobs';

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
        'uuid', 'connection', 'queue', 'payload', 'exception', 'failed_at'
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
        'uuid' => 'string', 'connection' => 'string', 'queue' => 'string', 'payload' => 'string', 'exception' => 'string', 'failed_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'failed_at'
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
