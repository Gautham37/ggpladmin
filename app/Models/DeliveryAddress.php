<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class DeliveryAddress
 * @package App\Models
 * @version December 6, 2019, 1:57 pm UTC
 *
 * @property \App\Models\User user
 * @property string description
 * @property string address
 * @property string latitude
 * @property string longitude
 * @property boolean is_default
 * @property integer user_id
 */
class DeliveryAddress extends Model
{

    public $table = 'delivery_addresses';
    


    public $fillable = [
        'user_id',
        'street_no',
        'street_type',
        'landmark_1',
        'landmark_2',
        'address_line_1',
        'address_line_2',
        'town',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',
        'is_default',
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
        'street_no' => 'string',
        'street_type' => 'string',
        'landmark_1' => 'string',
        'landmark_2' => 'string',
        'address_line_1' => 'string',
        'address_line_2' => 'string',
        'town' => 'string',
        'city' => 'string',
        'state' => 'string',
        'pincode' => 'string',
        'latitude' => 'string',
        'longitude' => 'string',
        'is_default' => 'boolean',
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
        'user_id' => 'required|exists:users,id',
        'address_line_1' => 'required',
        'town' => 'required',
        'city' => 'required',
        'state' => 'required',
        'pincode' => 'required'
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        
    ];

    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    public function getCustomFieldsAttribute()
    {
        $hasCustomField = in_array(static::class,setting('custom_field_models',[]));
        if (!$hasCustomField){
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields','custom_fields.id','=','custom_field_values.custom_field_id')
            ->where('custom_fields.in_table','=',true)
            ->get()->toArray();

        return convertToAssoc($array,'name');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
    
}
