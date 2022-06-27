<?php

namespace App\Models;

use Eloquent as Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseReturn extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'purchase_return';
    
    public $fillable = [
        'market_id',
        'purchase_invoice_id',
        'code',
        'date',
        'valid_date',
        'sub_total',
        'additional_charge_description',
        'additional_charge',
        'delivery_charge',
        'discount_total',
        'tax_total',
        'round_off',
        'total',
        'cash_paid',
        'payment_method',
        'amount_due',
        'notes',
        'terms_and_conditions',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'market_id' => 'integer',
        'purchase_invoice_id' => 'integer',
        'code' => 'string',
        'date' => 'date',
        'valid_date' => 'date',
        'sub_total' => 'double',
        'additional_charge_description' => 'string',
        'additional_charge' => 'double',
        'delivery_charge' => 'double',
        'discount_total' => 'double',
        'tax_total' => 'double',
        'round_off' => 'double',
        'total' => 'double',
        'cash_paid' => 'double',
        'payment_method' => 'integer',
        'amount_due' => 'double',
        'notes' => 'string',
        'terms_and_conditions' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'market_id' => 'required',
        'code' => 'required',
        'date' => 'required',
        'valid_date' => 'required',
        'sub_total' => 'required',
        'total' => 'required'
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

    public function paymentmethod()
    {
        return $this->belongsTo(\App\Models\PaymentMode::class, 'payment_method', 'id');
    }

    public function createdby()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseReturnItems::class, 'purchase_return_id');
    }

    public function amountsettle()
    {
        return $this->hasMany(PaymentInSettle::class, 'purchase_return_id');
    }

    public function totalsettle($type,$column_name,$column_value) {
        return PaymentInSettle::where($column_name,$column_value)->where('settle_type',$type)->sum('amount');
    }

}
