<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerPurchaseMail;
use DB;

class CustomerNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:notification';

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
         //get customer from orders

     $get_all_customer = DB::table('orders')->select('user_id',DB::raw('MAX(created_at) AS created_at'))->groupBy('user_id')->get();

      foreach ($get_all_customer as $key => $value) {
        $created_at = $value->created_at;
        $user_id = $value->user_id;
          
         $created = date('Y-m-d', strtotime($created_at));
        $ten_days_before = date("Y-m-d", strtotime("-10 day"));

      if($created<$ten_days_before){

       $users = DB::table('users')->select('*')->where('id',$user_id)->first();
         $customer_name = $users->name;
         $customer_mail = $users->email;
           //$customer_name = 'sathis';
        // $customer_mail = 'sharmila.docllp@gmail.com';

          $details = ['title' => 'Purchase Notification Mail','body' => 'Not Purchase in last 10 days','customer_name' =>$customer_name];

              \Mail::to($customer_mail)->send(new CustomerPurchaseMail($details));
       
      }
   
      }
    }
}
