<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\DB;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Models\Media;

class Market extends Model implements HasMedia
{
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'markets';
    

    public function discountables()
    {
        return $this->morphMany('App\Models\Discountable', 'discountable');
    }


    public $fillable = [
        'user_id',
        'name',
        'code',
        'code_count',
        'description',
        'street_no',
        'street_name',
        'street_type',
        'landmark_1',
        'landmark_2',
        'address_line_1',
        'address_line_2',
        'town',
        'city',
        'state',
        'pincode',
        'manual_address',
        'current_location_address',
        'gender',
        'date_of_birth',
        'latitude',
        'longitude',
        'email',
        'phone',
        'mobile',
        'information',
        'type',
        'sub_type',
        'stream',
        'gstin',
        'balance',
        'customer_group_id',
        'customer_level_id',
        'hear_about_us',
        'email_subscription',
        'sms_subscription',
        'party_alert',
        'party_alert_type',
        'party_alert_end_date',
        'policy_and_terms',
        'verified_by',
        'party_size',
        'supply_point',
        'membership_type',
        'referred_by',
        'staff_designation_id',
        'date_of_joining',
        'probation_ended_on',
        'termination_date',
        'salary',
        'salary_agreed',
        'preferred_language',
        'created_via',
        'active',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'name'  => 'string',
        'code'  => 'string',
        'code_count' => 'string',
        'description' => 'string',
        'street_no' => 'string',
        'street_name' => 'string',
        'street_type' => 'string',
        'landmark_1' => 'string',
        'landmark_2' => 'string',
        'address_line_1' => 'string',
        'address_line_2' => 'string',
        'town' => 'string',
        'city' => 'string',
        'state' => 'string',
        'pincode' => 'string',
        'manual_address' => 'string',
        'current_location_address' => 'string',
        'gender' => 'string',
        'date_of_birth' => 'date',
        'latitude' => 'string',
        'longitude' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'mobile' => 'string',
        'information' => 'string',
        'type' => 'integer',
        'sub_type' => 'integer',
        'stream' => 'integer',
        'gstin' => 'string',
        'balance' => 'double',
        'customer_group_id' => 'integer',
        'customer_level_id' => 'integer',
        'hear_about_us' => 'string',
        'email_subscription' => 'boolean',
        'sms_subscription' => 'boolean',
        'party_alert' => 'boolean',
        'party_alert_type' => 'string',
        'party_alert_end_date' => 'date',
        'policy_and_terms' => 'string',
        'verified_by' => 'integer',
        'party_size' => 'string',
        'supply_point' => 'string',
        'membership_type' => 'string',
        'referred_by' => 'string',
        'staff_designation_id' => 'integer',
        'date_of_joining' => 'date',
        'probation_ended_on' => 'date',
        'termination_date' => 'date',
        'salary' => 'double',
        'salary_agreed' => 'string',
        'preferred_language' => 'string',
        'created_via' => 'string',
        'active' => 'boolean',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $adminRules = [
        'name' => 'required',
        //'code' => 'required',
        'email' => 'required|email',
        'delivery_fee' => 'nullable|numeric|min:0',
        //'address' => 'required',
        //'longitude' => 'required|numeric',
        //'latitude' => 'required|numeric',
        //'admin_commission' => 'required|numeric|min:0',
        //'customer_group_id' => 'required|numeric',
        'mobile' => 'required|numeric|digits:10',
        'phone' => 'nullable|numeric|digits:11',
        'type' => 'required|not_in:0',
        'sub_type' => 'required|not_in:0',
        'stream' => 'required|not_in:0',
    ];

    public static $rules = [
        'name' => 'required',
        'email' => 'required|email',
        'delivery_fee' => 'nullable|numeric|min:0',
        'mobile' => 'required|numeric|digits:10',
        'phone' => 'nullable|numeric|digits:11',
        'type' => 'required|not_in:0',
        'sub_type' => 'required|not_in:0',
        'stream' => 'required|not_in:0',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $managerRules = [
        'name' => 'required',
        'code' => 'required',
        'description' => 'required',
        'delivery_fee' => 'nullable|numeric|min:0',
        'address' => 'required',
        'longitude' => 'required|numeric',
        'latitude' => 'required|numeric',
        'mobile' => 'required|numeric|digits:10',
        'type' => 'required|not_in:0',
        'sub_type' => 'required|not_in:0',
        'stream' => 'required|not_in:0',
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'has_media',
        'rate'
        
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

    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
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

    public function getCustomFieldsAttribute()
    {
        $hasCustomField = in_array(static::class,setting('custom_field_models',[]));
        if (!$hasCustomField){
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields','custom_fields.id','=','custom_field_values.custom_field_id')
            ->where('custom_fields.in_table','=',true)
            ->get()->toArray();

        return convertToAssoc($array,'name');
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
     * Add Media to api results
     * @return bool
     */
    public function getRateAttribute()
    {
        //return $this->marketReviews()->select(DB::raw('round(AVG(market_reviews.rate),1) as rate'))->first('rate')->rate;
        return true;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'market_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function galleries()
    {
        return $this->hasMany(\App\Models\Gallery::class, 'market_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function marketReviews()
    {
        return $this->hasMany(\App\Models\MarketReview::class, 'market_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    /*public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_markets');
    }*/
    
    public function users()
    {
        return true;
    }
    
    
    public function musers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_markets');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }
    
    public function createdby() 
    {  
        return $this->hasOne('App\Models\User','id','created_by');
    }

    public function verifiedby() 
    {  
        return $this->hasOne('App\Models\User','id','verified_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function drivers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'driver_markets');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function fields()
    {
        return $this->belongsToMany(\App\Models\Field::class, 'market_fields');
    }

    public function party_type()
    {
        return $this->belongsTo(PartyTypes::class, 'type');
    }

    public function party_sub_type()
    {
        return $this->belongsTo(PartySubTypes::class, 'sub_type');
    }

    public function party_stream()
    {
        return $this->belongsTo(PartyStreams::class, 'stream');
    }
    
    public function customer_group()
    {
        return $this->belongsTo(CustomerGroups::class, 'customer_group_id');
    }

    public function customer_level()
    {
        return $this->belongsTo(CustomerLevels::class, 'customer_level_id');
    }

    public function supplypoint()
    {
        return $this->belongsTo(SupplyPoints::class, 'supply_point');
    }

    public function notes()
    {
        return $this->hasMany(MarketNotes::class, 'market_id');
    }

    public function pendingsalesinvoices()
    {
        return $this->hasMany(SalesInvoice::class, 'market_id')->where('amount_due','>',0);
    }

    public function pendingsalesreturns()
    {
        return $this->hasMany(SalesReturn::class, 'market_id')->where('amount_due','>',0);
    }

    public function pendingpurchaseinvoices()
    {
        return $this->hasMany(PurchaseInvoice::class, 'market_id')->where('amount_due','>',0);
    }

    public function pendingpurchasereturns()
    {
        return $this->hasMany(PurchaseReturn::class, 'market_id')->where('amount_due','>',0);
    }

    public function activity()
    {
        return $this->belongsTo(MarketActivity::class, 'market_id');
    }
    
    public function activities()
    {
        return $this->hasMany(MarketActivity::class, 'market_id');
    }

    public function transaction()
    {
        //return $this->belongsTo(\App\Models\TransactionTrack::class, 'market_id', 'id')->orderBy('id','desc');
        return $this->hasOne(\App\Models\TransactionTrack::class,'market_id','id');
    }

    public function designation()
    {
        return $this->belongsTo(StaffDesignation::class, 'staff_designation_id');
    }

    public function salesinvoice()
    {
        return $this->hasMany(SalesInvoice::class, 'market_id');
    }

    public function saleunitlist() {
        return $this->hasMany(InventoryTrack::class, 'market_id')->select('unit')->distinct();
    }

    public function salesbyunit($market_id,$unit) {
        return InventoryTrack::where('market_id',$market_id)->where('unit',$unit)->sum('quantity');
    }
}
