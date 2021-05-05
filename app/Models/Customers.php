<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $name
 * @property string     $country
 * @property string     $province
 * @property string     $city
 * @property string     $phone
 * @property string     $email
 * @property int        $created_at
 * @property int        $updated_at
 */
class Customers extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';

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
        'name', 'country', 'province', 'city', 'phone', 'email', 'created_at', 'updated_at'
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
        'name' => 'string', 'country' => 'string', 'province' => 'string', 'city' => 'string', 'phone' => 'string', 'email' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
