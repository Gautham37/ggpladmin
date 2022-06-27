<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use DB;
use Carbon\Carbon;

class CustomHelper
{
    /*public  function shout(string $string)
    {
        return strtoupper($string);
    }*/

    public static function updatePartyBalance($market_id) {

	    $total_credit = DB::table('transaction_track')
	                    ->where('transaction_track_market_id',$market_id)
	                    ->where('transaction_track_type','credit')
	                    ->sum('transaction_track_amount');
	    $total_debit  = DB::table('transaction_track')
	                    ->where('transaction_track_market_id',$market_id)
	                    ->where('transaction_track_type','debit')
	                    ->sum('transaction_track_amount');
	    $balance = $total_credit - $total_debit;
	    $data    = array(
	        'balance' => $balance
	    );
	    $update  = DB::table('markets')->where('id',$market_id)->update($data);
	    return $update;
	}



	public static function deleteAndUpdatePurchase($id) {
		
		$purchase_items       = DB::table('purchase_invoice_detail')->where('purchase_invoice_id', '=', $id)->get();
		$purchase_transaction_credit = DB::table('transaction_track')
										->where('transaction_number', '=', $id)
										->where('transaction_track_category', '=', 'purchase')
										->where('transaction_track_type', '=', 'credit')
										->delete();

		$purchase_transaction_debit  = DB::table('transaction_track')
										->where('transaction_number', '=', $id)
										->where('transaction_track_category', '=', 'purchase')
										->where('transaction_track_type', '=', 'debit')
										->delete();
		if(count($purchase_items) > 0) {
			foreach ($purchase_items as $key => $value) {

				$purchase_inventory   = DB::table('inventory_track')
											->where('purchase_invoice_id', '=', $purchase_items[$key]->id)
											->where('inventory_track_category', '=', 'purchase_stock')
											->where('inventory_track_type', '=', 'add')
											->delete();
				$product_stock = self::productStockUpdate($purchase_items[$key]->purchase_detail_product_id);

			}
		}
		$purchase_items_delete  = DB::table('purchase_invoice_detail')->where('purchase_invoice_id', '=', $id)->delete();
		return true;
	}


	public static function deleteAndUpdatePurchaseReturn($id) {
		
		$purchase_items       = DB::table('purchase_return_detail')->where('purchase_return_id', '=', $id)->get();
		$purchase_transaction_credit = DB::table('transaction_track')
										->where('transaction_number', '=', $id)
										->where('transaction_track_category', '=', 'purchase_return')
										->where('transaction_track_type', '=', 'credit')
										->delete();

		$purchase_transaction_debit  = DB::table('transaction_track')
										->where('transaction_number', '=', $id)
										->where('transaction_track_category', '=', 'purchase_return')
										->where('transaction_track_type', '=', 'debit')
										->delete();
		if(count($purchase_items) > 0) {
			foreach ($purchase_items as $key => $value) {

				$purchase_inventory   = DB::table('inventory_track')
											->where('purchase_invoice_id', '=', $purchase_items[$key]->id)
											->where('inventory_track_category', '=', 'purchase_return_stock')
											->where('inventory_track_type', '=', 'reduce')
											->delete();
				$product_stock = self::productStockUpdate($purchase_items[$key]->purchase_detail_product_id);

			}
		}
		$purchase_items_delete  = DB::table('purchase_return_detail')->where('purchase_return_id', '=', $id)->delete();
		return true;
	}


	public static function deleteAndUpdateSalesInvoice($id) {
		
		$sales_items       = DB::table('sales_invoice_detail')->where('sales_invoice_id', '=', $id)->get();
		$sales_transaction_credit = DB::table('transaction_track')
										->where('transaction_number', '=', $id)
										->where('transaction_track_category', '=', 'sales')
										->where('transaction_track_type', '=', 'credit')
										->delete();

		$sales_transaction_debit  = DB::table('transaction_track')
										->where('transaction_number', '=', $id)
										->where('transaction_track_category', '=', 'sales')
										->where('transaction_track_type', '=', 'debit')
										->delete();
		if(count($sales_items) > 0) {
			foreach ($sales_items as $key => $value) {

				$sales_inventory   = DB::table('inventory_track')
											->where('purchase_invoice_id', '=', $sales_items[$key]->id)
											->where('inventory_track_category', '=', 'sales_stock')
											->where('inventory_track_type', '=', 'reduce')
											->delete();
				$product_stock = self::productStockUpdate($sales_items[$key]->sales_detail_product_id);

			}
		}
		$sales_items_delete  = DB::table('sales_invoice_detail')->where('sales_invoice_id', '=', $id)->delete();
		$sales_reward_delete = DB::table('loyality_points_tracker')
									->where('point_type', '=', 'purchase')
									->where('purchase_id', '=', $id)
									->delete();
		return true;
	}
	

	public static function deleteAndUpdateSalesReturn($id) {
		
		$sales_items       = DB::table('sales_return_detail')->where('sales_return_id', '=', $id)->get();
		$sales_transaction_credit = DB::table('transaction_track')
										->where('transaction_number', '=', $id)
										->where('transaction_track_category', '=', 'sales_return')
										->where('transaction_track_type', '=', 'credit')
										->delete();

		$sales_transaction_debit  = DB::table('transaction_track')
										->where('transaction_number', '=', $id)
										->where('transaction_track_category', '=', 'sales_return')
										->where('transaction_track_type', '=', 'debit')
										->delete();
		if(count($sales_items) > 0) {
			foreach ($sales_items as $key => $value) {

				$sales_inventory   = DB::table('inventory_track')
											->where('purchase_invoice_id', '=', $sales_items[$key]->id)
											->where('inventory_track_category', '=', 'sales_return_stock')
											->where('inventory_track_type', '=', 'add')
											->delete();
				$product_stock = self::productStockUpdate($sales_items[$key]->sales_detail_product_id);

			}
		}
		$sales_items_delete  = DB::table('sales_return_detail')->where('sales_return_id', '=', $id)->delete();
		return true;
	}	


	public static function productStockUpdate($product_id) {

		$product = DB::table('products')->where('id',$product_id)->first();
	    
	    $unit_total_add    = DB::table('inventory_track')
			                    ->where('inventory_track_product_id',$product_id)
			                    ->where('inventory_track_type','add')
			                    ->where('inventory_track_product_uom',$product->unit)
			                    ->sum('inventory_track_product_quantity');

		$secu_total_add    = DB::table('inventory_track')
			                    ->where('inventory_track_product_id',$product_id)
			                    ->where('inventory_track_type','add')
			                    ->where('inventory_track_product_uom',$product->secondary_unit)
			                    ->sum('inventory_track_product_quantity');

		($product->secondary_unit!='') ? $total_add = $unit_total_add + ($secu_total_add / $product->conversion_rate) : $total_add = $unit_total_add;     	                    
	    
	    $unit_total_reduce  = DB::table('inventory_track')
			                    ->where('inventory_track_product_id',$product_id)
			                    ->where('inventory_track_type','reduce')
			                    ->where('inventory_track_product_uom',$product->unit)
			                    ->sum('inventory_track_product_quantity');

		$secu_total_reduce  = DB::table('inventory_track')
			                    ->where('inventory_track_product_id',$product_id)
			                    ->where('inventory_track_type','reduce')
			                    ->where('inventory_track_product_uom',$product->secondary_unit)
			                    ->sum('inventory_track_product_quantity');

		($product->secondary_unit!='') ? $total_reduce = $unit_total_reduce + ($secu_total_reduce / $product->conversion_rate) : $total_reduce = $unit_total_reduce;	                    	                   

	    $stock   = $total_add - $total_reduce;
	    $data    = array(
	        'stock' => $stock
	    );
	    $update  = DB::table('products')->where('id',$product_id)->update($data);
	    return $update;
	}


	public static function productCurrentstockupdate($product_id,$pre_stock,$pre_stock_unit,$product_qty,$product_unit,$type,$method) {
		$product = DB::table('products')->where('id',$product_id)->first();
		
		if($product->unit==$product_unit) {
			if($method=='insert') {
				if($type=='add') :
					$stock   = $product->stock + $product_qty;
				elseif($type=='remove') :
					$stock   = $product->stock - $product_qty;
				endif;
			} elseif('update') {
				if($type=='add') :
					$stock   = ($product->stock - $pre_stock) + $product_qty;
				elseif($type=='remove') :
					$stock   = ($product->stock + $pre_stock) - $product_qty;
				endif;
			}
		} elseif($product->secondary_unit==$product_unit) {
			if($method=='insert') {			
				if($type=='add') :
					$stock   = $product->stock + ($product_qty / $product->conversion_rate);
				elseif($type=='remove') :
					$stock   = $product->stock - ($product_qty / $product->conversion_rate);
				endif;
			} elseif('update') {
				if($type=='add') :
					$stock   = ($product->stock - ($pre_stock / $product->conversion_rate)) + ($product_qty / $product->conversion_rate);
				elseif($type=='remove') :
					$stock   = ($product->stock + ($pre_stock / $product->conversion_rate)) - ($product_qty / $product->conversion_rate);
				endif;
			}
		}
		
		$data 	 = array('stock' => $stock); 
		$update  = DB::table('products')->where('id',$product_id)->update($data);
	    return $update;
	}



	
	public static function deleteAndUpdatePaymentIn($id) {

		$settleInvoices 			= DB::table('invoice_settle')
										->where('payment_out_id',$id)
										->where('invoice_settle_type','sales_invoice')
										->get();
		$paymentIntransactionTrack  = DB::table('transaction_track')
										->where('transaction_number', '=', $id)
										->where('transaction_track_category', '=', 'payment_in')
										->where('transaction_track_type', '=', 'credit')
										->delete();
		if(count($settleInvoices) > 0) {
			foreach ($settleInvoices as $key => $value) {
				$sales_invoice = DB::table('sales_invoice')->where('id',$settleInvoices[$key]->invoice_settle_invoice_id)->first();
				if($sales_invoice) {

					$settleAmount = $settleInvoices[$key]->invoice_settle_amount;
					$cash_paid    = $sales_invoice->sales_cash_paid - $settleAmount;
					$balance      = $sales_invoice->sales_total_amount - $cash_paid;
					$data  = array(
						'sales_cash_paid' => $cash_paid,
						'sales_balance_amount' => $balance 
					);
					$update = DB::table('sales_invoice')
								->where('id', $settleInvoices[$key]->invoice_settle_invoice_id)
								->update($data);

				}
			}
		}
		$deletesettleInvoices 		= DB::table('invoice_settle')
										->where('payment_out_id',$id)
										->where('invoice_settle_type','sales_invoice')
										->delete();
		return true;								
	}
	
	public static function deleteAndUpdatePaymentOut($id) {

		$settleInvoices 			= DB::table('invoice_settle')
										->where('payment_out_id',$id)
										->where('invoice_settle_type','purchase_invoice')
										->get();
		$paymentOuttransactionTrack = DB::table('transaction_track')
										->where('transaction_number', '=', $id)
										->where('transaction_track_category', '=', 'payment_out')
										->where('transaction_track_type', '=', 'debit')
										->delete();
		if(count($settleInvoices) > 0) {
			foreach ($settleInvoices as $key => $value) {
				$purchase_invoice = DB::table('purchase_invoice')->where('id',$settleInvoices[$key]->invoice_settle_invoice_id)->first();
				if($purchase_invoice) {

					$settleAmount = $settleInvoices[$key]->invoice_settle_amount;
					$cash_paid    = $purchase_invoice->purchase_cash_paid - $settleAmount;
					$balance      = $purchase_invoice->purchase_total_amount - $cash_paid;
					$data  = array(
						'purchase_cash_paid' => $cash_paid,
						'purchase_balance_amount' => $balance 
					);
					$update = DB::table('purchase_invoice')
								->where('id', $settleInvoices[$key]->invoice_settle_invoice_id)
								->update($data);

				}
			}
		}
		$deletesettleInvoices 		= DB::table('invoice_settle')
										->where('payment_out_id',$id)
										->where('invoice_settle_type','purchase_invoice')
										->delete();
		return true;								
	}
    
    public static function destroyCustomerdatas($id) {
		
    		//Supplier Request Delete
    		$sr_datas = DB::table('supplier_request')->where('market_id',$id)->get();
    		if(count($sr_datas) > 0) :
    			foreach($sr_datas as $sr_data) {
    				$sr_data_detail = DB::table('supplier_request_detail')
    									->where('supplier_request_id',$sr_data->id)
    									->delete();
    			}
    			$result = DB::table('supplier_request')->where('market_id',$id)->delete();
    		endif;
    		//Supplier Request Delete
    
    
    		//Sales Invoice Delete
    		$si_datas = DB::table('sales_invoice')->where('market_id',$id)->get();
    		if(count($si_datas) > 0) :
    			foreach($si_datas as $si_data) {
    				$si_data_detail = DB::table('sales_invoice_detail')
    									->where('sales_invoice_id',$si_data->id)
    									->delete();
    			}
    			$result = DB::table('sales_invoice')->where('market_id',$id)->delete();
    		endif;
    		//Sales Invoice Delete
    
    
    		//Sales Return Delete
    		$srt_datas = DB::table('sales_return')->where('market_id',$id)->get();
    		if(count($srt_datas) > 0) :
    			foreach($srt_datas as $srt_data) {
    				$srt_data_detail = DB::table('sales_return_detail')
    									->where('sales_return_id',$srt_data->id)
    									->delete();
    			}
    			$result = DB::table('sales_return')->where('market_id',$id)->delete();
    		endif;
    		//Sales Return Delete
    
    
    		//Delivery challan Delete
    		$dc_datas = DB::table('delivery_challan')->where('market_id',$id)->get();
    		if(count($dc_datas) > 0) :
    			foreach($dc_datas as $dc_data) {
    				$dc_data_detail = DB::table('delivery_challan_detail')
    									->where('delivery_challan_id',$dc_data->id)
    									->delete();
    			}
    			$result = DB::table('delivery_challan')->where('market_id',$id)->delete();
    		endif;
    		//Delivery challan Delete
    
    		//Purchse Order Delete
    		$po_datas = DB::table('purchase_order')->where('market_id',$id)->get();
    		if(count($po_datas) > 0) :
    			foreach($po_datas as $po_data) {
    				$po_data_detail = DB::table('purchase_order_detail')
    									->where('purchase_order_id',$po_data->id)
    									->delete();
    			}
    			$result = DB::table('purchase_order')->where('market_id',$id)->delete();
    		endif;
    		//Purchse Order Delete
    
    		//Purchse Invoice Delete
    		$pi_datas = DB::table('purchase_invoice')->where('market_id',$id)->get();
    		if(count($pi_datas) > 0) :
    			foreach($pi_datas as $pi_data) {
    				$pi_data_detail = DB::table('purchase_invoice_detail')
    									->where('purchase_invoice_id',$pi_data->id)
    									->delete();
    			}
    			$result = DB::table('purchase_invoice')->where('market_id',$id)->delete();
    		endif;
    		//Purchse Invoice Delete
    
    		//Purchse Return Delete
    		$pr_datas = DB::table('purchase_return')->where('market_id',$id)->get();
    		if(count($pr_datas) > 0) :
    			foreach($pr_datas as $pr_data) {
    				$pr_data_detail = DB::table('purchase_return_detail')
    									->where('purchase_return_id',$pr_data->id)
    									->delete();
    			}
    			$result = DB::table('purchase_return')->where('market_id',$id)->delete();
    		endif;
    		//Purchse Return Delete
    
    		//Purchse Return Delete
    		$pr_datas = DB::table('purchase_return')->where('market_id',$id)->get();
    		if(count($pr_datas) > 0) :
    			foreach($pr_datas as $pr_data) {
    				$pr_data_detail = DB::table('purchase_return_detail')
    									->where('purchase_return_id',$pr_data->id)
    									->delete();
    			}
    			$result = DB::table('purchase_return')->where('market_id',$id)->delete();
    		endif;
    		//Purchse Return Delete
    
    
    		//Payment In
    		$pyn_datas = DB::table('payment_in')->where('payment_in_party',$id)->get();
    		if(count($pyn_datas) > 0) :
    			foreach($pyn_datas as $pyn_data) {
    				$pyn_settles = DB::table('invoice_settle')
    									->where('payment_out_id',$pyn_data->id)
    									->delete();
    			}
    			$result = DB::table('payment_in')->where('payment_in_party',$id)->delete();
    		endif;
    		//Payment In
    
    
    		//Payment Out
    		$pyo_datas = DB::table('payment_out')->where('payment_out_party',$id)->get();
    		if(count($pyo_datas) > 0) :
    			foreach($pyo_datas as $pyo_data) {
    				$pyo_settles = DB::table('invoice_settle')
    									->where('payment_out_id',$pyo_data->id)
    									->delete();
    			}
    			$result = DB::table('payment_out')->where('payment_out_party',$id)->delete();
    		endif;
    		//Payment Out
    
    
    		//Transaction Delete
    		$trk_datas = DB::table('transaction_track')->where('transaction_track_market_id',$id)->get();
    		if(count($trk_datas) > 0) :
    			$result = DB::table('transaction_track')->where('transaction_track_market_id',$id)->delete();
    		endif;
    		//Transaction Delete
    
    		return true;

	}
	
	
	public static function unique_code_generate($table = '',$prefix = '') {
		$count = autoIncrementId($table);
		$unique_code = '';
		$text = $prefix;
		$code = '';
		if($count > 0 && $count <= 9){
			$code = '0000'.$count;
		} else if($count >= 10 && $count <= 99){
			$code = '000'.$count;
		} else if($count >= 100 && $count <= 999){
			$code = '00'.$count;
		} else if($count >= 1000 && $count <= 9999){
			$code = '0'.$count;
		} else {
			$code = $count;
		}
		$unique_code = $text.$code;
		return $unique_code;
    }
    
    public static function unique_party_code_generate($code_count = '',$prefix = '') {
		//$count = autoIncrementId($table);
		$count = $code_count;
		$unique_code = '';
		$text = $prefix;
		$code = '';
		if($count > 0 && $count <= 9){
			$code = '000000'.$count;
		} else if($count >= 10 && $count <= 99){
			$code = '00000'.$count;
		} else if($count >= 100 && $count <= 999){
			$code = '0000'.$count;
		} else if($count >= 1000 && $count <= 9999){
			$code = '000'.$count;
		} else if($count >= 10000 && $count <= 99999){
			$code = '00'.$count;
		} else if($count >= 100000 && $count <= 999999){
			$code = '0'.$count;
		} else {
			$code = $count;
		}
		$unique_code = $text.$code;
		return $unique_code;
    }
    
    public static function calculateRewards($affiliate_id = '') {
		$user = DB::table('users')->where('affiliate_id',$affiliate_id)->get();
		if(count($user) > 0) {
			
			$total_points   = DB::table('loyality_points_tracker')
								->where('affiliate_id',$affiliate_id)
								->sum('points');

			$point_usage    = DB::table('loyality_point_usage')
								->where('user_id',$user[0]->id)
								->sum('usage_points');

			$balance = $total_points - $point_usage;
			$data    = array(
		        'points' => $balance
		    );
		    $update  = DB::table('users')->where('id',$user[0]->id)->update($data);
		    return $update;						
		} else {
			return false;
		}
	}

	public static function purchaseRewards($market_id,$total_amount,$invoice_id) {

		$user_markets = DB::table('user_markets')
								->join('users','user_markets.user_id','=','users.id')
								->where('market_id',$market_id)->first();
    	
    	$total_online_order  = DB::table('orders')
    							->join('product_orders','orders.id','=','product_orders.order_id')
			                    ->where('orders.user_id',$user_markets->user_id)
			                    ->whereMonth('orders.created_at', date('m'))
								->whereYear('orders.created_at', date('Y'))
			                    ->sum(\DB::raw('product_orders.price + product_orders.tax_amount'));

		$total_sales_invoice = DB::table('sales_invoice')
			                    ->where('market_id',$market_id)
			                    ->whereMonth('sales_date', date('m'))
								->whereYear('sales_date', date('Y'))
			                    ->sum('sales_total_amount');

		$total_sales_return  = DB::table('sales_return')
			                    ->where('market_id',$market_id)
			                    ->whereMonth('sales_date', date('m'))
								->whereYear('sales_date', date('Y'))
			                    ->sum('sales_total_amount');

		$monthly_purchase    = ($total_online_order + $total_sales_invoice) - $total_sales_return;

		if($monthly_purchase > 0) {
			$customer_levels  = DB::table('customer_levels')->orderBy('monthly_spend','asc')->get();
			foreach ($customer_levels as $customer_level) {
			  	
			  	if($monthly_purchase >= $customer_level->monthly_spend) {
			  		$earned_points = $total_amount * $customer_level->group_points;
			  		$customer_lvl  = $customer_level->id;		  		
			  		$point_data = array(
			  			'user_id'      => $user_markets->id,
			  			'affiliate_id' => $user_markets->affiliate_id,
			  			'point_type'   => 'purchase',
			  			'points' 	   => $earned_points,
			  			'purchase_id'  => $invoice_id,
			  			'created_at'   => date('Y-m-d H:i:s')
			  		);
			  	} else {
			  		$fist_month_spend  = DB::table('customer_levels')->orderBy('monthly_spend','asc')->first();
			  		if($monthly_purchase <= $fist_month_spend->monthly_spend) {
			  			$earned_points  = $total_amount * $fist_month_spend->group_points;
			  			$customer_lvl   = $fist_month_spend->id;		  		
				  		$point_data 	= array(
				  			'user_id'      => $user_markets->id,
				  			'affiliate_id' => $user_markets->affiliate_id,
				  			'point_type'   => 'purchase',
				  			'points' 	   => $earned_points,
				  			'purchase_id'  => $invoice_id,
				  			'created_at'   => date('Y-m-d H:i:s')
				  		);
			  		}
			  	}

			}
				
			if(count($point_data) > 0 && $point_data['points'] > 0) {
				
				$user_level_data = array('level' => $customer_lvl);
				$userlevel 	     = DB::table('users')->where('id',$user_markets->user_id)->update($user_level_data);

				$insert_poits = DB::table('loyality_points_tracker')->insertGetId($point_data);	
		  		//Update Points
		  		self::calculateRewards($user_markets->affiliate_id);
		  		//Update Points 
	  		}

		}

		return true;	
    }
    
    public static function deleteItems($detail_item_id,$table,$field_name,$stock_cat,$stock_type) {
    	
    	$detail_item 		   = DB::table($table)->where('id',$detail_item_id)->first();
    	$delete_inventory_item = DB::table('inventory_track')
                                    ->where('purchase_invoice_id',$detail_item_id)
                                    ->where('inventory_track_category',$stock_cat)
                                    ->where('inventory_track_type',$stock_type)
                                    ->delete();
        $product_stock 		   = self::productStockUpdate($detail_item->{$field_name});
        $delete_item     	   = DB::table($table)->where('id',$detail_item_id)->delete();
        return $delete_item;                            

    }
    
    public static function addRewardusage($market_id, $order_id, $points_worth, $used_points) {
        
        $user_markets  = DB::table('user_markets')
							->join('users','user_markets.user_id','=','users.id')
							->where('market_id',$market_id)->first();
		$reward_data   = array(
		        'user_id'       => $user_markets->user_id,
		        'usage_points'  => $used_points,
		        'usage_amount'  => $points_worth,
		        'order_id'      => $order_id,
		        'created_at'    => date('Y-m-d H:i:s')
		);					
		$reward_usage  = DB::table('loyality_point_usage')->insertGetId($reward_data);
		//Update Points
  		    //self::calculateRewards($user_markets->affiliate_id);
  		//Update Points 
		return $reward_usage;
    }

}