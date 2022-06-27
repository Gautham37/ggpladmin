<?php

namespace App\Models;

use Eloquent as Model;

class Payment extends Model
{

    public $table = 'payments';
    


    public $fillable = [
        'order_id',
        'user_id',
        'price',
        'description',
        'status',
        'method',
        'client_secret',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'order_id' => 'integer',
        'user_id' => 'integer',
        'price' => 'double',
        'description' => 'string',
        'status' => 'string',
        'method' => 'string',
        'client_secret' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'order_id' => 'required|exists:orders,id',
        'user_id' => 'required|exists:users,id',
        'price' => 'required',
        'description' => 'required',
        'status' => 'required',
        'method' => 'required'
    ];

    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id', 'id');
    }
    
}
