<?php
/**
 * File name: Category.php
 * Last modified: 2020.05.02 at 08:39:42
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Models;

use Eloquent as Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class QualityGrade
 * @package App\Models
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection Product
 * @property \Illuminate\Database\Eloquent\Collection[] discountables
 * @property string name
 * @property string description
 */
class StockStatus extends Model
{
   
    public $table = 'stock_status';
    

    public function discountables()
    {
        return $this->morphMany('App\Models\Discountable', 'discountable');
    }


    public $fillable = [
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'status' => 'required',
        //'description' => 'required'
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    // public function products()
    // {
    //     return $this->hasMany(\App\Models\Product::class, 'quality_grade');
    // }


 
    
}
