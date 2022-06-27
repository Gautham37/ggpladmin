<?php

namespace App\Repositories;

use App\Models\Market;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class MarketRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method Market findWithoutFail($id, $columns = ['*'])
 * @method Market find($id, $columns = ['*'])
 * @method Market first($columns = ['*'])
 */
class MarketRepository extends BaseRepository implements CacheableInterface
{

    use CacheableRepository;
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
        'preferred_language',
        'created_via',
        'active',
        'created_by',
        'updated_by'
        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Market::class;
    }

    /**
     * get my markets
     */

    /*public function myMarkets()
    {
        return Market::join("user_markets", "market_id", "=", "markets.id")
            ->where('user_markets.user_id', auth()->id())->get();
    }

    public function myActiveMarkets()
    {
        return Market::join("user_markets", "market_id", "=", "markets.id")
            ->where('user_markets.user_id', auth()->id())
            ->where('markets.active','=','1')->get();
    }*/

}
