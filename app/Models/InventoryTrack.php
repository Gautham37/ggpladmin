<?php

namespace App\Models;

use Eloquent as Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Models\Media;

class InventoryTrack extends Model implements HasMedia
{
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'inventory_track';

    public $fillable = [
        'product_id',
        'market_id',
        'category',
        'type',
        'date',
        'quantity',
        'unit',
        'description',
        'usage',
        'sales_invoice_item_id',
        'sales_return_item_id',
        'purchase_invoice_item_id',
        'purchase_return_item_id',
        'product_order_item_id',
        'created_by',
        'updated_by'
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'product_id' => 'integer',
        'market_id' => 'integer',
        'category' => 'string',
        'type' => 'string',
        'date' => 'date',
        'quantity' => 'double',
        'unit' => 'integer',
        'description' => 'string',
        'usage' => 'string',
        'sales_invoice_item_id' => 'integer',
        'sales_return_item_id' => 'integer',
        'purchase_invoice_item_id' => 'integer',
        'purchase_return_item_id' => 'integer',
        'product_order_item_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];
    
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_id' => 'required',
        'category' => 'required',
        'type' => 'required',
        'date' => 'required',
        'quantity' => 'required',
        'unit' => 'required'
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
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'id');
    }

    public function market()
    {
        return $this->belongsTo(\App\Models\Market::class, 'market_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'unit', 'id');
    }

    public function salesinvoiceitem()
    {
        return $this->belongsTo(\App\Models\SalesInvoiceItems::class, 'sales_invoice_item_id', 'id');
    }

    public function salesreturnitem()
    {
        return $this->belongsTo(\App\Models\SalesReturnItems::class, 'sales_return_item_id', 'id');
    }

    public function purchaseinvoiceitem()
    {
        return $this->belongsTo(\App\Models\PurchaseInvoiceItems::class, 'purchase_invoice_item_id', 'id');
    }

    public function purchasereturnitem()
    {
        return $this->belongsTo(\App\Models\PurchaseReturnItems::class, 'purchase_return_item_id', 'id');
    }

    public function productorderitem()
    {
        return $this->belongsTo(\App\Models\ProductOrder::class, 'product_order_item_id', 'id');
    }

    public function createdby()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    public function disposal()
    {
        return $this->belongsTo(\App\Models\WastageDisposal::class, 'id', 'inventory_id');
    }

}
