<?php

namespace App\Models;

use Eloquent as Model;


class PartySubTypes extends Model
{
   

    public $table = 'party_sub_types';
    

    public function discountables()
    {
        return $this->morphMany('App\Models\Discountable', 'discountable');
    }


    public $fillable = [
        'party_type_id',
        'role_id',
        'name',
        'prefix_value',
        'description',
        'active',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'role_id' => 'integer',
        'prefix_value' => 'string',
        'description' => 'string',
        'active' =>'boolean',
        'party_type_id' => 'double',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'prefix_value' => 'required',
        //'description' => 'required',
        'party_type_id' => 'required|not_in:0',
        'role_id' => 'required|not_in:0',
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
    public function party_types()
    {
        return $this->belongsTo(\App\Models\PartyTypes::class, 'party_type_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'role_id', 'id');
    }

}
