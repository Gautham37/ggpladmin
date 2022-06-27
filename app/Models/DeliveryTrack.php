<?php

namespace App\Models;

use Eloquent as Model;

class DeliveryTrack extends Model
{

    public $table = 'delivery_track';

    public $fillable = [
        'user_id',
        'type',
        'category',
        'order_id',
        'sales_invoice_id',
        'status',
        'active',
        'notes',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'type' => 'string',
        'category' => 'string',
        'order_id' => 'integer',
        'sales_invoice_id' => 'integer',
        'status' => 'string',
        'active' => 'integer',
        'notes' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'type' => 'required',
        'category' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id', 'id');
    }

    public function salesinvoice()
    {
        return $this->belongsTo(\App\Models\SalesInvoice::class, 'sales_invoice_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function createdby()
    {
        return $this->belongsTo(User::class, 'created_by')->withoutGlobalScopes(['active']);
    }
    
}
