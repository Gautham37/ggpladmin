<?php

namespace App\Models;

use Eloquent as Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockTakeItems extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'stock_take_items';
    
    public $fillable = [
        'stock_take_id',
        'product_id',
        'product_name',
        'product_code',
        'current',
        'current_unit',
        'counted',
        'counted_unit',
        'missing',
        'missing_unit',
        'wastage',
        'wastage_unit',
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
        'stock_take_id' => 'integer',
        'product_id' => 'integer',
        'product_name' => 'string',
        'product_code' => 'string',
        'current' => 'double',
        'current_unit' => 'integer',
        'counted' => 'double',
        'counted_unit' => 'integer',
        'missing' => 'double',
        'missing_unit' => 'integer',
        'wastage' => 'double',
        'wastage_unit' => 'integer',
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
        'stock_take_id' => 'required',
        'product_id' => 'required',
        'product_name' => 'required',
        'quantity' => 'required',
        'unit' => 'required'
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

    public function stocktake()
    {
        return $this->belongsTo(\App\Models\StockTake::class, 'stock_take_id', 'id');
    }

    public function createdby()
    {
        return $this->belongsTo(\App\Models\Users::class, 'created_by', 'id');
    }

    public function currentunit()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'current_unit', 'id');
    }

    public function countedunit()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'counted_unit', 'id');
    }

    public function missingunit()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'missing_unit', 'id');
    }

    public function wastageunit()
    {
        return $this->belongsTo(\App\Models\Uom::class, 'wastage_unit', 'id');
    }

}
