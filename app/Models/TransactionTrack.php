<?php

namespace App\Models;

use Eloquent as Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionTrack extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'transaction_track';
    
    public $fillable = [
        'sales_invoice_id',
        'sales_return_id',
        'purchase_invoice_id',
        'purchase_return_id',
        'payment_in_id',
        'payment_out_id',
        'order_id',
        'category',
        'type',
        'date',
        'market_id',
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
        'sales_invoice_id' => 'integer',
        'sales_return_id' => 'integer',
        'purchase_invoice_id' => 'integer',
        'purchase_return_id' => 'integer',
        'payment_in_id' => 'integer',
        'payment_out_id' => 'integer',
        'order_id' => 'integer',
        'category' => 'string',
        'type' => 'string',
        'date' => 'date',
        'market_id' => 'integer',
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
        'category' => 'required',
        'type' => 'required',
        'date' => 'required',
        'market_id' => 'required',
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

    public function market()
    {
        return $this->belongsTo(\App\Models\Market::class, 'market_id', 'id');
    }

    public function salesinvoice()
    {
        return $this->belongsTo(\App\Models\SalesInvoice::class, 'sales_invoice_id', 'id');
    }

    public function salesreturn()
    {
        return $this->belongsTo(\App\Models\SalesReturn::class, 'sales_return_id', 'id');
    }

    public function purchaseinvoice()
    {
        return $this->belongsTo(\App\Models\PurchaseInvoice::class, 'purchase_invoice_id', 'id');
    }

    public function purchasereturn()
    {
        return $this->belongsTo(\App\Models\PurchaseReturn::class, 'purchase_return_id', 'id');
    }

    public function paymentin()
    {
        return $this->belongsTo(\App\Models\PaymentIn::class, 'payment_in_id', 'id');
    }

    public function paymentout()
    {
        return $this->belongsTo(\App\Models\PaymentOut::class, 'payment_out_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id', 'id');
    }

    public function createdby() 
    {  
        return $this->hasOne('App\Models\User','id','created_by');
    }

}
