<?php

namespace App\Models;

use Eloquent as Model;

class Order extends Model
{

    public $table = 'orders';

    public $fillable = [
        'order_code',
        'refund_order_code',
        'user_id',
        'order_status_id',
        'tax',
        'delivery_fee',
        'delivery_distance',
        'redeem_amount',
        'coupon_amount',
        'contribution_amount',
        'order_amount',
        'hint',
        'active',
        'driver_id',
        'delivery_address_id',
        'payment_id',
        'is_deleted',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'order_code' => 'string',
        'refund_order_code'  => 'string',
        'user_id'  => 'integer',
        'order_status_id'  => 'integer',
        'tax'  => 'double',
        'delivery_fee'  => 'double',
        'delivery_distance' => 'double',
        'redeem_amount' => 'double',
        'coupon_amount' => 'double',
        'contribution_amount' => 'double',
        'order_amount' => 'double',
        'hint' => 'string',
        'active' => 'boolean',
        'driver_id' => 'integer',
        'delivery_address_id' => 'integer',
        'payment_id' => 'integer',
        'is_deleted' => 'boolean',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required|exists:users,id',
        'order_status_id' => 'required|exists:order_statuses,id',
        'order_amount' => 'required'
    ];

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
    public function driver()
    {
        return $this->belongsTo(\App\Models\User::class, 'driver_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function orderStatus()
    {
        return $this->belongsTo(\App\Models\OrderStatus::class, 'order_status_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function productOrders()
    {
        return $this->hasMany(\App\Models\ProductOrder::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function products()
    {
        return $this->belongsToMany(\App\Models\Product::class, 'product_orders');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    /*public function payment()
    {
        return $this->belongsTo(\App\Models\Payment::class, 'payment_id', 'id');
    }*/

    public function payment()
    {
        return $this->belongsTo(\App\Models\Payment::class, 'id', 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function deliveryAddress()
    {
        return $this->belongsTo(\App\Models\DeliveryAddress::class, 'delivery_address_id', 'id');
    }
    
      /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function Staffs()
    {
        return $this->belongsTo(\App\Models\Staffs::class, 'driver_id', 'user_id');
    }
    
    public function redeempoints()
    {
        return $this->belongsTo(\App\Models\LoyalityPoints::class, 'id', 'order_id')->where('type','redeem');
    }
    
    public function activity()
    {
        return $this->belongsTo(OrderActivity::class, 'order_id');
    }
    
    public function activities()
    {
        return $this->hasMany(OrderActivity::class, 'order_id');
    }

    public function deliverytrack()
    {
        return $this->belongsTo(\App\Models\DeliveryTrack::class, 'id', 'order_id');
    }

}
