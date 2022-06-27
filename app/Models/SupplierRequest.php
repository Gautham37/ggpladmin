<?php
/**
 * File name: SupplierRequest.php
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
 * Class SupplierRequest
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
class SupplierRequest extends Model implements HasMedia
{
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'sr_code' => 'required',
        'sr_date' => 'required',
        'sr_valid_date' => 'required',
        'market_id' => 'required',
        'sr_taxable_amount' => 'required|numeric|min:0',
        'sr_notes' => 'required',
    ];

    public $table = 'supplier_request';
    public $fillable = [
        'sr_code',
		'sr_date',
		'sr_valid_date',
		'market_id',
		'sr_taxable_amount',
		'sr_notes',
		'purchase_invoice_id',
		'sr_status',
		'sr_driver',
		'created_by',
        'updated_by',
        'is_deleted',
		
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
    	'sr_code'	=> 'string',
		'sr_date' => 'date',
		'sr_valid_date' => 'date',
		'market_id' => 'integer',
		'sr_taxable_amount' => 'double',
		'sr_notes' => 'string',
		'purchase_invoice_id' => 'integer',
		'sr_status' => 'integer',
		'sr_driver' => 'integer',
		'created_by' => 'integer',
        'updated_by' => 'integer',
        'is_deleted' => 'integer',
    ];
    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'has_media',
        'market'
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
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function options()
    {
        return $this->hasMany(\App\Models\Option::class, 'product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function optionGroups()
    {
        return $this->belongsToMany(\App\Models\OptionGroup::class,'options');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function productReviews()
    {
        return $this->hasMany(\App\Models\ProductReview::class, 'product_id');
    }

    /**
     * get market attribute
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsTo|object|null
     */
    public function getMarketAttribute()
    {
        return $this->market()->first(['id', 'name', 'delivery_fee', 'address', 'phone','default_tax']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function market()
    {
        return $this->belongsTo(\App\Models\Market::class, 'market_id', 'id');
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->discount_price > 0 ? $this->discount_price : $this->price;
    }

    /**
     * @return float
     */
    public function applyCoupon($coupon): float
    {
        $price = $this->getPrice();
        if(isset($coupon) && count($this->discountables) + count($this->category->discountables) + count($this->market->discountables) > 0){
            if ($coupon->discount_type == 'fixed') {
                $price -= $coupon->discount;
            } else {
                $price = $price - ($price * $coupon->discount / 100);
            }
            if ($price < 0) $price = 0;
        }
        return $price;
    }

    public function discountables()
    {
        return $this->morphMany('App\Models\Discountable', 'discountable');
    }



}
