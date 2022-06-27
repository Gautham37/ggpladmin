<?php

namespace App\Models;

use Eloquent as Model;

class OrderActivity extends Model
{

    public $table = 'order_activity';

    public $fillable = [
        'order_id',
        'action',
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
        'order_id' => 'integer',
        'action' => 'string',
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
        'order_id' => 'required',
        'action' => 'required',
        'notes' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id', 'id');
    }

    public function createdby()
    {
        return $this->belongsTo(User::class, 'created_by')->withoutGlobalScopes(['active']);
    }
    
}
