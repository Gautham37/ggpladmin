<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;
use App\Mail\BirthdayDiscountMail;
use DB;

class BirthdayDiscount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday:discount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Birthday special discount notification';

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
         $get_discount = setting('birthday_discount');
        $users = DB::table('users')->select('*')->get();
        foreach ($users as $key => $value) {
          $date_of_birth = $value->date_of_birth;

            $current_day = date('d');
            $current_mon = date('m');
        
        if($date_of_birth!=''){
        $dob_day = date('d', strtotime($date_of_birth));
        $dob_mon = date('m', strtotime($date_of_birth));
       
      if($current_day==$dob_day && $current_mon==$dob_mon){

        $customer_name = $value->name;
         $customer_mail = $value->email;
       
         $details = ['title' => 'Birthday Special Discount Mail','body' => 'Birthday Special '.$get_discount.'% Discount Mail','customer_name' =>$customer_name];

             \Mail::to($customer_mail)->send(new BirthdayDiscountMail($details));
      }

        }

         } 
    }
}
