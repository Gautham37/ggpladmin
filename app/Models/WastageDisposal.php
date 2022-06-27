<?php

namespace App\Models;

use Eloquent as Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WastageDisposal extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'wastage_disposal';
    
    public $fillable = [
        'inventory_id',
        'product_id',
        'type',
        'market_id',
        'quantity',
        'unit',
        'description',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'inventory_id' => 'integer',
        'product_id' => 'integer',
        'type'  => 'string',
        'market_id' => 'integer',
        'quantity' => 'double',
        'unit' => 'integer',
        'description' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'inventory_id' => 'required',
        'product_id' => 'required',
        'quantity' => 'required',
        'unit' => 'required',
        'description' => 'required',
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

    public function inventory()
    {
        return $this->belongsTo(\App\Models\InventoryTrack::class, 'inventory_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'unit', 'id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseInvoiceItems::class, 'purchase_invoice_id');
    }

    public function createdby()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    public function charity()
    {
        return $this->belongsTo(\App\Models\Charity::class, 'charity_id', 'id');
    }

    public function market()
    {
        return $this->belongsTo(\App\Models\Market::class, 'market_id', 'id');
    }

}
