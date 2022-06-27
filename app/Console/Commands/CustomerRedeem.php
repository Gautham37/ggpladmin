<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerRedeemMail;
use DB;

class CustomerRedeem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:redeem';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Customer not purchase in tha last 10 days notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    
       $get_user_details = DB::table('users')->select('*')->get();

       foreach ($get_user_details as $key => $value) {

       $affiliate_id = $value->affiliate_id;
       $user_name = $value->name;
       $user_email = $value->email;
       $user_id = $value->id;

       $get_loyality_points = DB::table('loyality_points_tracker')->select('*',DB::raw('SUM(points) AS group_points'))->where('affiliate_id',$affiliate_id)->groupBy('affiliate_id')->get();

       if(count($get_loyality_points)>0)
        {
       
       foreach ($get_loyality_points as $key1 => $value1) {
          $group_points = $value1->group_points;
            
        $get_loyality_usage = DB::table('loyality_point_usage')->select('*',DB::raw('SUM(usage_points) AS used_points'))->where('user_id',$user_id)->groupBy('user_id')->get();

        if(count($get_loyality_usage)>0)
        {
       
       foreach ($get_loyality_usage as $key2 => $value2) {

        $usage_points = $value2->used_points;

         $current_points = $group_points-$usage_points;

          }
        }
     else{
          $current_points = $group_points;
      }

        $current_amount = $current_points/10;

        if($current_amount>=20){

          $customer_name = $user_name;

         $details = ['title' => 'Remainder Notification Mail','body' => 'Remainder For 20 Rupees worth of points to redeem','customer_name' =>$customer_name];

             \Mail::to($customer_mail)->send(new CustomerRedeemMail($details));

         }
    }

     }
   }
  }
}
