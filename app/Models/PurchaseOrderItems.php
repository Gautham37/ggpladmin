<?php

namespace App\Models;

use Eloquent as Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItems extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'purchase_order_items';
    
    public $fillable = [
        'purchase_order_id',
        'product_id',
        'product_name',
        'product_hsn_code',
        'mrp',
        'quantity',
        'unit',
        'unit_price',
        'discount',
        'discount_amount',
        'tax',
        'tax_amount',
        'amount',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'purchase_order_id' => 'integer',
        'product_id'  => 'integer',
        'product_name'  => 'string',
        'product_hsn_code'  => 'string',
        'mrp'  => 'double',
        'quantity' => 'double',
        'unit' => 'integer',
        'unit_price' => 'double',
        'discount' => 'double',
        'discount_amount' => 'double',
        'tax' => 'double',
        'tax_amount' => 'double',
        'amount' => 'double',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'purchase_order_id' => 'required',
        'product_id' => 'required',
        'product_name' => 'required',
        'mrp' => 'required',
        'quantity' => 'required',
        'unit' => 'required',
        'unit_price' => 'required',
        'amount' => 'required'
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
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
    public function getFirstMediaUrl($collectionName = 'default',$conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        $array = explode('.', $url);
        $extension = strtolower(end($array));
        if (in_array($extension,config('medialibrary.extensions_has_thumb'))) {
            return asset($this->getFirstMediaUrlTrait($collectionName,$conversion));
        }else{
            return asset(config('medialibrary.icons_folder').'/'.$extension.'.png');
        }
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute()
    {
        return $this->hasMedia('image') ? true : false;
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'id');
    }

    public function purchaseorder()
    {
        return $this->belongsTo(\App\Models\PurchaseOrder::class, 'purchase_order_id', 'id');
    }

    public function createdby()
    {
        return $this->belongsTo(\App\Models\Users::class, 'created_by', 'id');
    }

    public function uom()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'unit', 'id');
    }

}
