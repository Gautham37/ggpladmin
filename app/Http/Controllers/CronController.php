<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Insurancerenewel;
//use App\Repositories\InsuranceRenewelsRepository;
//use App\Repositories\InsuranceReceiptsRepository;
//use App\Repositories\InsurancePoliciesRepository;
//use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Mail;
//use App\Mail\RemewalRemainder;
//use App\Mail\SubscriptionRenewalRemainder;
//use Stripe\Token;
//use Stripe\Stripe;
//use Stripe\Charge;
//use Stripe\Stripe_CardError;
//use Stripe\Stripe_InvalidRequestError;
//use App\Mail\RenewelReceiptGeneration;
//use App\Mail\RenewelPaymentDeclained;
//use App\Models\InsuranceLogs;
use Carbon\Carbon;
use DB;

class CronController extends Controller
{
	
	public function calcMonthlyRewards() {
	    //Caculate previous month credit rewards and reduce current month used points 
	    $start  = date('Y-m-d',strtotime(new Carbon('first day of last month')));
        $end    = date('Y-m-d',strtotime(new Carbon('last day of last month')));

        //dd($start.$end);
	    
	    $start_date = date('Y-m-d',strtotime(Carbon::now()->startOfMonth()));
	    $end_date   = date('Y-m-d',strtotime(Carbon::now()->endOfMonth()));
	    
	    $users = DB::table('users')->get();
	    foreach($users as $user) {
    		if($user) {
    			
    			$total_points   = DB::table('loyality_points_tracker')
    								->where('affiliate_id',$user->affiliate_id)
    								->whereDate('created_at','>=', $start)
                                    ->whereDate('created_at','<=', $end)
    								->sum('points');
    
    			$point_usage    = DB::table('loyality_point_usage')
    								->where('user_id',$user->id)
    								->whereDate('created_at','>=', $start_date)
                                    ->whereDate('created_at','<=', $end_date)
    								->sum('usage_points');
    
    			$balance = $total_points - $point_usage;
    			$data    = array(
    		        'points' => ($balance > 0) ? $balance : 0 
    		    );
    		    //Update new reward point
    		    $update  = DB::table('users')->where('id',$user->id)->update($data);
    		    //return $update;						
    		}
	    }
	    return response()->json(['status'=>'success', 'message'=>'Updated Successfully']);;
	}
	
}