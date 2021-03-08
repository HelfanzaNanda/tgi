<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $name
 * @property string     $scopes
 * @property boolean    $revoked
 * @property int        $created_at
 * @property int        $updated_at
 * @property DateTime   $expires_at
 */
class OauthAccessTokens extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oauth_access_tokens';

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
        'user_id', 'client_id', 'name', 'scopes', 'revoked', 'created_at', 'updated_at', 'expires_at'
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
        'name' => 'string', 'scopes' => 'string', 'revoked' => 'boolean', 'created_at' => 'timestamp', 'updated_at' => 'timestamp', 'expires_at' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'expires_at'
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
