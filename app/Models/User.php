<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * @package App\Models
 * @version July 10, 2018, 11:44 am UTC
 *
 * @property \App\Models\Cart[] cart
 * @property string name
 * @property string email
 * @property string password
 * @property string api_token
 * @property string device_token
 */
class User extends Authenticatable implements HasMedia
{
    use Notifiable;
    use Billable;
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }
    use HasRoles;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        //'roles' => 'required|min:1',
        //'customer_group_id' => 'required'
        // 'password' => 'required',
    ];
    public $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'name',
        'email',
        'password',
        'api_token',
        'customer_group_id',
        'gender',
        'date_of_birth',
        'device_token',
        'date_of_birth',
        'referred_by',
        'affiliate_id',
        'is_staff',
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'api_token' => 'string',
        'customer_group_id' => 'string',
        'gender' => 'string',
        'date_of_birth' => 'date',
        'device_token' => 'string',
        'remember_token' => 'string',
        'referred_by' => 'integer',
        'affiliate_id' => 'string',
        'is_staff' => 'integer',
    ];
    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'has_media'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Specifies the user's FCM token
     *
     * @return string
     */
    public function routeNotificationForFcm($notification)
    {
        return $this->device_token;
    }

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
        if ($url) {
            $array = explode('.', $url);
            $extension = strtolower(end($array));
            if (in_array($extension, config('medialibrary.extensions_has_thumb'))) {
                return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
            } else {
                return asset(config('medialibrary.icons_folder') . '/' . $extension . '.png');
            }
        }else{
            return asset('images/avatar_default.png');
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
//            ->where('custom_fields.in_table', '=', true)
            ->select(['value', 'view', 'name'])
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
        return $this->hasMedia('avatar') ? true : false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function markets()
    {
        return $this->belongsToMany(\App\Models\Market::class, 'user_markets');
    }

    public function market()
    {
        return $this->belongsTo(\App\Models\Market::class, 'id', 'user_id');
    }

    public function cart()
    {
        return $this->hasMany(\App\Models\Cart::class, 'user_id');
    }

    public function deliveryaddress()
    {
        return $this->hasMany(\App\Models\DeliveryAddress::class, 'user_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    public function attendanceToday()
    {
        return $this->hasOne(Attendance::class, 'user_id')->whereDate('clock_in_time',date('Y-m-d'));
    }

    public function attendancePresentthisMonth()
    {
        return $this->hasMany(Attendance::class, 'user_id')->whereMonth('clock_in_time',date('m'));
    }
    
    public function attendancePresentbyMonth($month,$year,$user_id)
    {
        return count(Attendance::where('user_id',$user_id)->whereMonth('clock_in_time',$month)->whereYear('clock_in_time',$year)->get());
    }

    public function attendanceLopofthismonth()
    {   
        return $this->hasMany(Leaves::class, 'user_id')->whereMonth('leave_date',date('m'))->where('status','approved');
    }
    
    public function attendanceLopbymonth($month,$year,$user_id)
    {   
        return count(Leaves::where('user_id',$user_id)->whereMonth('leave_date',date('m'))->where('leave_type_id','3')->where('status','approved')->get());
    }

    public function attendanceTodayAll()
    {
        return Attendance::whereDate('clock_in_time',date('Y-m-d'))->get();
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class, 'user_id');
    }

}
