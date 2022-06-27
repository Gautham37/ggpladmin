<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Stripe;

/**
 * Class CartController
 * @package App\Http\Controllers\API
 */

class StripeAPIController extends Controller
{
    /** @var  CartRepository */
    /*private $cartRepository;

    public function __construct(CartRepository $cartRepo)
    {
        $this->cartRepository = $cartRepo;
    }*/
   
   public function index(Request $request)
    {   
        // Enter Your Stripe Secret
        \Stripe\Stripe::setApiKey(setting('stripe_secret'));
        		
		$amount = $request->amount;
		$amount *= 100;
        $amount = (int) $amount;
        
        $payment_intent = \Stripe\PaymentIntent::create([
			'description' => 'Stripe Test Payment',
			'amount' => $amount,
			'currency' => 'INR',
			'description' => 'Payment From Codehunger',
			'payment_method_types' => ['card'],
		]);
		return $this->sendResponse($payment_intent, 'Stripe payment');
    }


   /* public function index(Request $request)
    {
        $key['stripe_secret'] = setting('stripe_secret');
        $key['stripe_key'] = setting('stripe_key');
        
        return $this->sendResponse($key, 'Stripe keys');
        
   
    }*/
    
   /* public function store(Request $request){
        $amount = ($request->amount)*100;
        $stripeToken = $request->$stripeToken;
        Stripe\Stripe::setApiKey($key);
       $data =  Stripe\Charge::create ([
                "amount" => $amount,
                "currency" => "inr",
                "source" => $stripeToken,
                "description" => "Test payment from ggpl."
        ]);
        return $this->sendResponse($data, 'Stripe payment Details'); 
    }*/
    
}
