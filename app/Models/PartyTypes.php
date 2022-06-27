<?php

namespace App\Models;

use Eloquent as Model;


class PartyTypes extends Model
{
   

    public $table = 'party_types';
    

   
    public $fillable = [
        'id',
        'name',
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
        'description' => 'string',
        'active' =>'boolean',
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
        //'description' => 'required',
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

   public function discountables()
    {
        return $this->hasMany(\App\Models\Discountable::class, 'coupon_id');
    }
    
     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function party_sub_types(){
        return $this->hasMany(\App\Models\PartySubTypes::class, 'party_type_id');
    }
    
 
}
