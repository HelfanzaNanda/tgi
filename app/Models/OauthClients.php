<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $name
 * @property string     $secret
 * @property string     $provider
 * @property string     $redirect
 * @property boolean    $personal_access_client
 * @property boolean    $password_client
 * @property boolean    $revoked
 * @property int        $created_at
 * @property int        $updated_at
 */
class OauthClients extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oauth_clients';

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
        'user_id', 'name', 'secret', 'provider', 'redirect', 'personal_access_client', 'password_client', 'revoked', 'created_at', 'updated_at'
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
        'name' => 'string', 'secret' => 'string', 'provider' => 'string', 'redirect' => 'string', 'personal_access_client' => 'boolean', 'password_client' => 'boolean', 'revoked' => 'boolean'
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
