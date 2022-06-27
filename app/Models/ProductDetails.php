<?php
/**
 * File name: ProductDetails.php
 * Last modified: 2020.05.28 at 19:50:43
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
 * Class ProductDetails
 * @package App\Models
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @property \App\Models\Market market
 * @property \App\Models\Category category
 * @property \Illuminate\Database\Eloquent\Collection[] discountables
 * @property \Illuminate\Database\Eloquent\Collection Option
 * @property \Illuminate\Database\Eloquent\Collection Nutrition
 * @property \Illuminate\Database\Eloquent\Collection ProductsReview
 * @property string id
 * @property string name
 * @property double price
 * @property double discount_price
 * @property string description
 * @property double capacity
 * @property boolean featured
 * @property double package_items_count
 * @property string unit
 * @property integer market_id
 * @property integer category_id
 */
class ProductDetails extends Model implements HasMedia
{
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'product_details';
    public $fillable = [
        'product_id',
        'nutrition_benefit',
        'health_benefit',
        'product_life',
        'ambient_temprature',
        'storage_type',
        'storage_method',
        'range_standard',
        'short_description_product_code',
        'stock_level',
        'stock_purchased_date',
        'alternate_weight_kg',
        'other_key_search_words',
        'source_confirm',
        'reason_discontinuation'        
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'product_id' => 'integer',
        'nutrition_benefit' => 'string',
        'health_benefit' => 'string',
        'product_life' => 'string',
        'ambient_temprature' => 'string',
        'storage_type' => 'string',
        'storage_method' => 'string',
        'range_standard' => 'string',
        'short_description_product_code' => 'string',
        'stock_level' => 'string',
        'stock_purchased_date' => 'string',
        'alternate_weight_kg' => 'string',
        'other_key_search_words' => 'string',
        'source_confirm' => 'string',
        'reason_discontinuation' => 'string'
    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_id' => 'required'
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'has_media'
    ];

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->sharpen(10);

        $this->addMediaConversion('icon')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->sharpen(10);
    }

    /**
     * to generate media url in case of fallback will
     * return the file type icon
     * @param string $conversion
     * @return string url
     */
    public function getFirstMediaUrl($collectionName = 'default', $conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        $array = explode('.', $url);
        $extension = strtolower(end($array));
        if (in_array($extension, config('medialibrary.extensions_has_thumb'))) {
            return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
        } else {
            return asset(config('medialibrary.icons_folder') . '/' . $extension . '.png');
        }
    }

    public function getCustomFieldsAttribute()
    {
        $hasCustomField = in_array(static::class, setting('custom_field_models', []));
        if (!$hasCustomField) {
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields', 'custom_fields.id', '=', 'custom_field_values.custom_field_id')
            ->where('custom_fields.in_table', '=', true)
            ->get()->toArray();

        return convertToAssoc($array, 'name');
    }

    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute()
    {
        return $this->hasMedia('image') ? true : false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }



}
