<?php

namespace App\Models;

use Eloquent as Model;

class ProductOrder extends Model
{

    public $table = 'product_orders';

    public $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_code',
        //'product_hsn_code',
        'quantity',
        'unit',
        'unit_price',
        'discount',
        'discount_amount',
        'tax',
        'tax_amount',
        'amount',
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
        'product_id' => 'integer',
        'product_name' => 'string',
        'product_code' => 'string',
        //'product_hsn_code' => 'string',
        'quantity' => 'double',
        'unit' => 'integer',
        'unit_price' => 'double',
        'discount' => 'double',
        'discount_amount' => 'double',
        'tax' => 'double',
        'tax_amount' => 'double',
        'amount' => 'double',
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
        'product_id' => 'required|exists:products,id',
        'product_name' => 'required',
        'product_code' => 'required',
        //'product_hsn_code',
        'quantity' => 'required',
        'unit' => 'required',
        'unit_price' => 'required',
        'amount' => 'required'
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
    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'unit', 'id');
    }

}
