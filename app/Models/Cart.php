<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Cart
 * @package App\Models
 * @version September 4, 2019, 3:38 pm UTC
 *
 * @property \App\Models\Product product
 * @property \App\Models\User user
 * @property \Illuminate\Database\Eloquent\Collection options
 * @property integer product_id
 * @property integer user_id
 * @property integer quantity
 */
class Cart extends Model
{

    public $table = 'carts';
    


    public $fillable = [
        'product_id',
        'unit_id',
        'user_id',
        'quantity',
        'price',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'product_id' => 'integer',
        'unit_id' => 'integer',
        'user_id' => 'integer',
        'quantity' => 'double',
        'price' => 'double',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_id' => 'required|exists:products,id',
        'unit_id' => 'required|exists:uom,id',
        'user_id' => 'required|exists:users,id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function uom()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'unit_id', 'id');
    }
}
