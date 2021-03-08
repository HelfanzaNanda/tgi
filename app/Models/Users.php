<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $usename
 * @property string     $name
 * @property string     $email
 * @property int        $email_verified_at
 * @property string     $password
 * @property string     $phone
 * @property string     $remember_token
 * @property int        $created_at
 * @property int        $updated_at
 */
class Users extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

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
        'usename', 'name', 'email', 'email_verified_at', 'password', 'phone', 'remember_token', 'created_at', 'updated_at'
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
        'usename' => 'string', 'name' => 'string', 'email' => 'string', 'email_verified_at' => 'timestamp', 'password' => 'string', 'phone' => 'string', 'remember_token' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'email_verified_at', 'created_at', 'updated_at'
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
