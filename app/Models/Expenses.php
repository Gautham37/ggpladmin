<?php

namespace App\Models;

use Eloquent as Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Models\Media;

class Expenses extends Model implements HasMedia
{
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'expenses';

    public $fillable = [
        'expense_category_id',
        'date',
        'payment_mode',
        'total_amount',
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
        'expense_category_id' => 'integer',
        'date' => 'date',
        'payment_mode' => 'integer',
        'total_amount' => 'double',
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
        'expense_category_id' => 'required',
        'date' => 'required',
        'payment_mode' => 'required',
        'total_amount' => 'required'
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

    public function expensecategory()
    {
        return $this->belongsTo(\App\Models\ExpensesCategory::class, 'expense_category_id', 'id');
    }

    public function paymentmode()
    {
        return $this->belongsTo(\App\Models\PaymentMode::class, 'payment_mode', 'id');
    }

    public function createdby()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    public function items()
    {
        return $this->hasMany(ExpenseItems::class, 'expense_id');
    }

}
