<?php

namespace App\Models;

use Eloquent as Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class Product
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
class Product extends Model implements HasMedia
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
        'name' => 'required',
        'category_id' => 'required',
        //'subcategory_id' => 'required|not_in:0',
        'department_id' => 'required|not_in:0',
        'price' => 'required|numeric|min:0',
        'purchase_price' => 'numeric|min:0',
        'hsn_code' => 'required',
        'description' => 'required',
        'product_type' => 'required',
        //'market_id' => 'required|exists:markets,id',
        //'category_id' => 'required|min:1|exists:categories,id',
        //'bar_code' => 'required'
    ];

    public $table = 'products';
    public $fillable = [
        'name',
        'area',
        'quality_grade',
        'product_status',
        'stock_status',
        /*'value_added_service_affiliated',
        'vas_charges_amt',
        'vas_charges_unit_quantity',
        'vas_charges_unit_type',*/
        'name_lang_1',
        'name_lang_2',
        'category_id',
        'subcategory_id',
        'department_id',
        'season_id',
        'color_id',
        //'nutrition_id',
        'taste_id',
        'alpha',
        'product_code_short',
        'product_varient',
        'product_varient_number',
        'con',
        'product_code',
        'short_product_code',
        'price',
        'purchase_price',
        'discount_price',
        'hsn_code',
        'tax',
        'description',
        'unit',
        'stock',
        'alternative_unit',
        'secondary_unit',
        'secondary_unit_quantity',
        'tertiary_unit',
        'tertiary_unit_quantity',
        'custom_unit',
        'custom_unit_quantity',
        'bulk_buy_unit',
        'bulk_buy_unit_quantity',
        'wholesale_purchase_unit',
        'wholesale_purchase_unit_quantity',
        'pack_weight_unit',
        'pack_weight_unit_quantity',
        'product_size',
        'spare',
        'spare_2',
        'ave_weight_if_known',
        'ave_p_u_1_weight',
        'featured',
        'deliverable',
        'online_store',
        'low_stock_warning',
        'sugar_level',
        'weight_loss',
        'freeze_well',
        'grows_on_tree',
        'salad_vegetable',
        'low_stock_unit',

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
        'reason_discontinuation',

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
        'area' => 'string',
        'quality_grade' => 'integer',
        'product_status' => 'string',
        'stock_status' => 'integer',
        /*'value_added_service_affiliated' => 'integer',
        'vas_charges_amt' => 'double',
        'vas_charges_unit_quantity' => 'double',
        'vas_charges_unit_type' => 'string',*/
        'name_lang_1' => 'string',
        'name_lang_2' => 'string',
        'category_id' => 'integer',
        'subcategory_id' => 'integer',
        'department_id' => 'integer',
        'season_id' => 'integer',
        'color_id' => 'integer',
        //'nutrition_id' => 'integer',
        'taste_id' => 'integer',
        'alpha' => 'string',
        'product_code_short' => 'string',
        'product_varient' => 'string',
        'product_varient_number' => 'string',
        'con' => 'string',
        'product_code' => 'string',
        'short_product_code' => 'string',
        'price' => 'double',
        'purchase_price' => 'double',
        'discount_price' => 'double',
        'hsn_code' => 'string',
        'tax' => 'double',
        'description' => 'string',
        'unit' => 'integer',
        'stock' => 'double',
        'alternative_unit' => 'string',
        'secondary_unit' => 'integer',
        'secondary_unit_quantity' => 'double',
        'tertiary_unit' => 'integer',
        'tertiary_unit_quantity' => 'double',
        'custom_unit' => 'integer',
        'custom_unit_quantity' => 'double',
        'bulk_buy_unit' => 'integer',
        'bulk_buy_unit_quantity' => 'double',
        'wholesale_purchase_unit' => 'integer',
        'wholesale_purchase_unit_quantity' => 'double',
        'pack_weight_unit' => 'integer',
        'pack_weight_unit_quantity' => 'double',
        'product_size' => 'double',
        'spare' => 'double',
        'spare_2' => 'double',
        'ave_weight_if_known' => 'double',
        'ave_p_u_1_weight' => 'double',
        'featured' => 'boolean',
        'deliverable' => 'boolean',
        'online_store' => 'boolean',
        'low_stock_warning' => 'boolean',
        'sugar_level' => 'string',
        'weight_loss' => 'boolean',
        'freeze_well' => 'boolean',
        'grows_on_tree' => 'boolean',
        'salad_vegetable' => 'boolean',
        'low_stock_unit' => 'double',

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
        'reason_discontinuation' => 'string',

        'created_by' => 'integer',
        'updated_by' => 'integer'
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function subcategory()
    {
        return $this->belongsTo(\App\Models\Subcategory::class, 'subcategory_id', 'id');
    }
    
     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function department()
    {
        return $this->belongsTo(\App\Models\Departments::class, 'department_id', 'id');
    }

    public function qualitygrade()
    {
        return $this->belongsTo(\App\Models\QualityGrade::class, 'quality_grade', 'id');
    }

    public function valueaddedservicesaffiliated()
    {
        return $this->belongsTo(\App\Models\ValueAddedServiceAffiliated::class, 'value_added_service_affiliated', 'id');
    }

    public function stockstatus()
    {
        return $this->belongsTo(\App\Models\StockStatus::class, 'stock_status', 'id');
    }

    public function season()
    {
        return $this->belongsTo(\App\Models\ProductSeasons::class, 'season_id', 'id');
    }

    public function color()
    {
        return $this->belongsTo(\App\Models\ProductColors::class, 'color_id', 'id');
    }

    public function nutrition()
    {
        return $this->belongsTo(\App\Models\ProductNutritions::class, 'nutrition_id', 'id');
    }

    public function nutritions()
    {
        return $this->hasMany(\App\Models\ProductNutritionDetail::class, 'product_id');;
    }

    public function taste()
    {
        return $this->belongsTo(\App\Models\ProductTastes::class, 'taste_id', 'id');
    }

    public function productdetail()
    {
        return $this->belongsTo(\App\Models\ProductDetails::class, 'id', 'product_id');
    }

    public function pricevaritations()
    {
        return $this->hasMany(\App\Models\ProductPriceVariation::class, 'product_id');
    }

    public function vasservices()
    {
        return $this->hasMany(\App\Models\ProductVas::class, 'product_id');
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
        //return $this->market()->first(['id', 'name', 'delivery_fee', 'address', 'phone','default_tax']);
        return true;
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

    public function productInventory()
    {
        return $this->hasOne(InventoryTrack::class, 'product_id');
    }

    public function primaryunit()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'unit', 'id');
    }

    public function secondaryunit()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'secondary_unit', 'id');
    }

    public function tertiaryunit()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'tertiary_unit', 'id');
    }

    public function customunit()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'custom_unit', 'id');
    }

    public function bulkbuyunit()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'bulk_buy_unit', 'id');
    }

    public function wholesalepurchaseunit()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'wholesale_purchase_unit', 'id');
    }

    public function packweightunit()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'pack_weight_unit', 'id');
    }

    /*public function allunits() {
        $products = $this->hasOne(Product::class, 'id');
        $product  = $products->first();
        $units = [];
        if($product->primaryunit) {
            $units[] = [
                'id'        => $product->primaryunit->id,
                'name'      => $product->primaryunit->name,
                'quantity'  => 1
            ];        
        }
        if($product->secondaryunit && $product->secondary_unit_quantity > 0) {
            $units[] = [
                'id'        => $product->secondaryunit->id,
                'name'      => $product->secondaryunit->name,
                'quantity'  => $product->secondary_unit_quantity
            ];        
        }
        if($product->tertiaryunit && $product->tertiary_unit_quantity > 0) {
            $units[] = [
                'id'        => $product->tertiaryunit->id,
                'name'      => $product->tertiaryunit->name,
                'quantity'  => $product->tertiary_unit_quantity
            ];        
        }
        if($product->customunit && $product->custom_unit_quantity > 0) {
            $units[] = [
                'id'        => $product->customunit->id,
                'name'      => $product->customunit->name,
                'quantity'  => $product->custom_unit_quantity
            ];        
        }
        if($product->bulkbuyunit && $product->bulk_buy_unit_quantity > 0) {
            $units[] = [
                'id'        => $product->bulkbuyunit->id,
                'name'      => $product->bulkbuyunit->name,
                'quantity'  => $product->bulk_buy_unit_quantity
            ];        
        }
        if($product->wholesalepurchaseunit && $product->wholesale_purchase_unit_quantity > 0) {
            $units[] = [
                'id'        => $product->wholesalepurchaseunit->id,
                'name'      => $product->wholesalepurchaseunit->name,
                'quantity'  => $product->wholesale_purchase_unit_quantity
            ];        
        }
        if($product->packweightunit && $product->pack_weight_unit_quantity > 0) {
            $units[] = [
                'id'        => $product->packweightunit->id,
                'name'      => $product->packweightunit->name,
                'quantity'  => $product->pack_weight_unit_quantity
            ];        
        }
        return $units;        
    }*/
    
    public function allunits($product_id) {
        $product = Product::where('id',$product_id)->first();
        $units = [];
        if($product->primaryunit) {
            $units[] = [
                'id'        => $product->primaryunit->id,
                'name'      => $product->primaryunit->name,
                'quantity'  => 1
            ];        
        }
        if($product->secondaryunit && $product->secondary_unit_quantity > 0) {
            $units[] = [
                'id'        => $product->secondaryunit->id,
                'name'      => $product->secondaryunit->name,
                'quantity'  => $product->secondary_unit_quantity
            ];        
        }
        if($product->tertiaryunit && $product->tertiary_unit_quantity > 0) {
            $units[] = [
                'id'        => $product->tertiaryunit->id,
                'name'      => $product->tertiaryunit->name,
                'quantity'  => $product->tertiary_unit_quantity
            ];        
        }
        if($product->customunit && $product->custom_unit_quantity > 0) {
            $units[] = [
                'id'        => $product->customunit->id,
                'name'      => $product->customunit->name,
                'quantity'  => $product->custom_unit_quantity
            ];        
        }
        if($product->bulkbuyunit && $product->bulk_buy_unit_quantity > 0) {
            $units[] = [
                'id'        => $product->bulkbuyunit->id,
                'name'      => $product->bulkbuyunit->name,
                'quantity'  => $product->bulk_buy_unit_quantity
            ];        
        }
        if($product->wholesalepurchaseunit && $product->wholesale_purchase_unit_quantity > 0) {
            $units[] = [
                'id'        => $product->wholesalepurchaseunit->id,
                'name'      => $product->wholesalepurchaseunit->name,
                'quantity'  => $product->wholesale_purchase_unit_quantity
            ];        
        }
        if($product->packweightunit && $product->pack_weight_unit_quantity > 0) {
            $units[] = [
                'id'        => $product->packweightunit->id,
                'name'      => $product->packweightunit->name,
                'quantity'  => $product->pack_weight_unit_quantity
            ];        
        }
        return $units;        
    }

}
