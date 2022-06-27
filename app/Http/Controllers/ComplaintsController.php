<?php

namespace App\Http\Controllers;

use App\DataTables\ComplaintsDataTable;
use App\Http\Requests;
use App\Repositories\ComplaintsRepository;
use App\Repositories\StaffsRepository;
use App\Repositories\ComplaintsCommentsRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductOrderRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\CustomFieldRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;

class ComplaintsController extends Controller
{
  
    /** @var  StaffsRepository */
    private $staffsRepository;
    
    /** @var  ComplaintsRepository */
    private $complaintsRepository;
    
    /** @var  ComplaintsCommentsRepository */
    private $complaintsCommentsRepository;
    
    /** @var  UserRepository */
    private $userRepository;
    
     /** @var  ProductRepository */
    private $productRepository;
    
    /** @var  OrderRepository */
    private $orderRepository;
    
     /** @var  ProductOrderRepository */
    private $productOrderRepository;
    
    /** @var  InventoryRepository */
    private $inventoryRepository;
  
    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
private $uploadRepository;

    public function __construct(CustomFieldRepository $customFieldRepo, StaffsRepository $staffsRepo, ComplaintsRepository $complaintsRepo, ComplaintsCommentsRepository $complaintsCommentsRepo, UserRepository $userRepo, ProductRepository $productRepo, OrderRepository $orderRepo, ProductOrderRepository $productOrderRepo, InventoryRepository $inventoryRepo)
    {
        parent::__construct();
       
        $this->customFieldRepository = $customFieldRepo;
        $this->staffsRepository = $staffsRepo;
        $this->complaintsRepository = $complaintsRepo;
        $this->complaintsCommentsRepository = $complaintsCommentsRepo;
        $this->userRepository = $userRepo;
        $this->productRepository = $productRepo;
        $this->orderRepository = $orderRepo;
        $this->productOrderRepository = $productOrderRepo;
        $this->inventoryRepository       = $inventoryRepo; 
    }

    /**
     * Display a listing of the Complaints.
     *
     * @param ComplaintsDataTable $complaintsDataTable
     * @return Response
     */
    public function index(ComplaintsDataTable $complaintsDataTable)
    {
        return $complaintsDataTable->render('complaints.index');
    }

    

    /**
     * Show the form for editing the specified Category.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
         $complaints = $this->complaintsRepository->findWithoutFail($id);
        
         $staffs = $this->staffsRepository->leftJoin('users', 'staffs.user_id', '=', 'users.id')->pluck('name', 'staffs.id');
         $staffs->prepend("Please Select",0);
         
         $staff_members = $this->staffsRepository->leftJoin('users', 'staffs.user_id', '=', 'users.id')->pluck('name', 'staffs.id');
         
         $selected_staff_members = explode(',', $complaints->staff_members);
         
         $complaintsComments = $this->complaintsCommentsRepository->where('complaints_id',$complaints->id)->get();
         
        if (empty($complaints)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.complaint')]));

            return redirect(route('complaints.index'));
        }
        $customFieldsValues = $complaints->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->complaintsRepository->model());
        $hasCustomField = in_array($this->complaintsRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('complaints.edit')->with("complaints", $complaints)->with("staffs", $staffs)->with("staff_members", $staff_members)->with("complaintsComments", $complaintsComments)->with("selected_staff_members", $selected_staff_members)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $complaints = $this->complaintsRepository->findWithoutFail($id);

        if (empty($complaints)) {
            Flash::error('Complaints not found');
            return redirect(route('complaints.index'));
        }
        $input = $request->all();
        if($request->staff_members!=''){
        $staff_members = implode(',',  $request->staff_members);
        }else{
            $staff_members = 0;
        }
     
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->complaintsRepository->model());
        try {
            
          $complaints_update = $this->complaintsRepository->where('id', $id)->update(['staff_id' => $request->staff_id,'staff_members' => $staff_members,'feedback' => $request->feedback]);
            //$complaints = $this->complaintsRepository->update($input, $id);
            
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $complaints->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.complaints')]));

        return redirect(route('complaints.index'));
    }
    
    public function complaintsComments($id)
    {
         $complaints = $this->complaintsRepository->findWithoutFail($id);
         
         $staff_members = explode(',', $complaints->staff_members);
         $selected_staff_members = '';
         foreach($staff_members as $val)
         {
            $users =  $this->staffsRepository->leftJoin('users', 'staffs.user_id', '=', 'users.id')->where('staffs.id',$val)->first(); 
            if($users) {
                $selected_staff_members .= $users->name.', ';
            }
         }
       
         $complaints->selected_staff_members = rtrim($selected_staff_members, ', ');
         
         $complaintsComments = $this->complaintsCommentsRepository->select('users.name','complaints_comments.created_at','complaints_comments.comments')->leftJoin('staffs', 'complaints_comments.staff_id', '=', 'staffs.id')->leftJoin('users', 'staffs.user_id', '=', 'users.id')->where('complaints_id',$id)->get();
        
        if (empty($complaints)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.complaint')]));

            return redirect(route('complaints.index'));
        }
        $customFieldsValues = $complaints->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->complaintsRepository->model());
        $hasCustomField = in_array($this->complaintsRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('complaints.comments')->with("complaints", $complaints)->with("complaintsComments", $complaintsComments)->with("customFields", isset($html) ? $html : false);
    }
    
     public function viewComments($id, Request $request) 
     {
       
       $complaints = $this->complaintsRepository->findWithoutFail($id);
       
        $staff_members = explode(',', $complaints->staff_members);
         $selected_staff_members = '';
         foreach($staff_members as $val)
         {
          
            $users =  $this->staffsRepository->leftJoin('users', 'staffs.user_id', '=', 'users.id')->where('staffs.id',$val)->first(); 
            $selected_staff_members .= $users->name.', ';
         }
       
         $complaints->selected_staff_members = rtrim($selected_staff_members, ', ');
       
        $validator = $request->validate([
            'comments' => 'required',
        ]); 
      if($validator) {
          
        $input = array(
            'complaints_id' => $id,
            'comments' => $request->comments,
            'staff_id'    => auth()->user()->id
        );
        
        $comments = $this->complaintsCommentsRepository->create($input);
        
        $complaintsComments = $this->complaintsCommentsRepository->select('users.name','complaints_comments.created_at','complaints_comments.comments')->leftJoin('staffs', 'complaints_comments.staff_id', '=', 'staffs.id')->leftJoin('users', 'staffs.user_id', '=', 'users.id')->where('complaints_id',$id)->get();
       
        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.complaint_comments')]));
        
        return view('complaints.comments')->with("complaints", $complaints)->with("complaintsComments", $complaintsComments)->with("customFields", isset($html) ? $html : false);
            
      }else
      {
           Flash::error($e->getMessage());
            return redirect()->back();
      }

    }
    
    public function closeComplaints($id)
    {
       
         $complaints = $this->complaintsRepository->findWithoutFail($id);
         
         $selected_members = explode(',', $complaints->staff_members);
         
         $staff_members = $this->staffsRepository->leftJoin('users', 'staffs.user_id', '=', 'users.id')->whereIn('staffs.id', $selected_members)->pluck('name', 'staffs.id');
         $staff_members->prepend("Please Select",0);
         
         $productOrder = $this->productOrderRepository->where('order_id',$complaints->free_order_id)->get();

        if (empty($complaints)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.complaint')]));

            return redirect(route('complaints.index'));
        }
        $customFieldsValues = $complaints->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->complaintsRepository->model());
        $hasCustomField = in_array($this->complaintsRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('complaints.close_complaints')->with("complaints", $complaints)->with('productOrder',$productOrder)->with("staff_members", $staff_members)->with("customFields", isset($html) ? $html : false);
    }
    
    public function saveCloseComplaints($id, Request $request) 
    {
          if($request->option_type==2){
            $complaints = $this->complaintsRepository->where('id',$id)->first();
            $order_id = $complaints->order_id;
            
            $orders = $this->orderRepository->where('id',$order_id)->first();

            $max_id = $this->orderRepository->max('id') + 1;
            $order_code_prefix = "GGPL" . date('y') . (date('y') + 1) . "ID";
             $order_code = null;
            if ($max_id <= 9) {
                $order_code = $order_code_prefix . "000000" . $max_id;
            } else if ($max_id <= 99) {
                $order_code = $order_code_prefix . "00000" . $max_id;
            } else if ($max_id <= 999) {
                $order_code = $order_code_prefix . "0000" . $max_id;
            } else if ($max_id <= 9999) {
                $order_code = $order_code_prefix . "000" . $max_id;
            } else if ($max_id <= 99999) {
                $order_code = $order_code_prefix . "00" . $max_id;
            } else if ($max_id <= 999999) {
                $order_code = $order_code_prefix . "0" .$max_id;
            } else {
                $order_code = $order_code_prefix . $max_id;
            }
              
         //Insert Order
           $order_data = array(
              'order_code'          => $order_code,
              'user_id'             => $orders->user_id,
              'refund_order_code'   => $orders->order_code,
              'order_status_id'     => 1,
              'tax'                 => 0,
              'payment_id'          => 0,
              'delivery_address_id' => $orders->delivery_address_id,
              'delivery_fee'        => 0,
              'delivery_distance'   => $orders->delivery_distance,
              'redeem_amount'       => 0,
              'coupon_amount'       => 0,
              'contribution_amount' => 0,
              'order_amount'        => 0,
              'hint' => $orders->hint,
            );
           
            $order = $this->orderRepository->create($order_data);
          
          //Insert Product order
          for($i=0; $i<count($request->product_id); $i++) {
            $product_order_data = array(
                'product_id'        =>  $request->product_id[$i],
                'order_id'          =>  $order->id,
                'quantity'          =>  $request->product_quantity[$i],
                'unit'              =>  $request->product_unit[$i],
                'price'           =>  $request->product_mrp[$i],
                'created_at'    =>  date('Y-m-d H:i:s'),
            );
             $productOrder = $this->productOrderRepository->create($product_order_data);
             
              //Insert Inventory item
         $inventory_item   = array(
            'purchase_invoice_id'               => $productOrder->id,
            'inventory_track_category'          => 'online_stock',
            'inventory_track_type'              => 'reduce',
            'inventory_track_date'              => date('Y-m-d'),
            'inventory_track_product_id'        => $request->product_id[$i],
            'inventory_track_product_quantity'  => $request->product_quantity[$i],
            'inventory_track_product_uom'       => $request->product_unit[$i],
        );
        $insert_inventory = $this->inventoryRepository->create($inventory_item);
        
         //update stock
        $get_products = DB::table('products')->where('id',$request->product_id[$i])->first();
        $stock =  $get_products->stock - $request->product_quantity[$i];
        $product_data 	 = array('stock' => $stock); 
        $update  = DB::table('products')->where('id',$request->product_id[$i])->update($product_data);

        }
        
          }
        //update complaints
        
         $complaints_update = $this->complaintsRepository->where('id', $id)->update(['option_type' => $request->option_type,'deduction_staff_id' => $request->staff_members,'deduction_amount' => $request->deduction_amount,'free_order_id' => $order->id,'status' => 1]);
                
         return redirect(route('complaints.index'));      
               
    }
    
    public function loadComplaintsProducts(Request $request) {
        $products = $this->productRepository->get();
        return view('layouts.all_products_modal')->with('products',$products);
    }
    
    public function loadComplaintsItems(Request $request) {
       
        $items    = $request->itemId;
        $quantity = $request->itemQuantity;
        $unit     = $request->itemUnit;
        $output=array();
        $count = 0; 
        foreach($items as $key => $item) :
            $products = DB::table('products')->where('id',$item)->first();
            $products->s_no = ++$count;
            $products->quantity = $quantity[$key];
            $products->tax_rates= DB::table('tax_rates')->get();
            $products->p_price = $products->price;
            
            if($unit[$key]==$products->unit) {
                $products->unit = $products->unit;
                $products->purchase_price = $products->purchase_price; 
            } elseif($unit[$key]==$products->secondary_unit) {
                $products->unit = $products->secondary_unit;
                $products->purchase_price = $products->purchase_price / $products->conversion_rate;                
            }
          
            array_push($output,$products);
        endforeach;

        echo json_encode($output);
    }
    
    /*public function getComplaintsCommentsID(Request $request) {
    
        $id = $request->complaints_id;
        $complaints = $this->complaintsRepository->where('id',$id)->first();
         $staff_members = explode(',', $complaints->staff_members);
         $selected_staff_members = '';
         foreach($staff_members as $val)
         {
          
            $users =  $this->staffsRepository->leftJoin('users', 'staffs.user_id', '=', 'users.id')->where('staffs.id',$val)->first(); 
            $selected_staff_members .= $users->name.', ';
         }
       
         $complaints->selected_staff_members = rtrim($selected_staff_members, ', ');
    
        return json_encode($complaints);
    }
    
     public function addComplaintsComments(Request $request) {

        $input = array(
            'complaints_id' => $request->complaints_id,
            'comments' => $request->comments,
            'staff_id'    => auth()->user()->id
        );
        try {
            $complaintsComments = $this->complaintsCommentsRepository->create($input);
           
            Flash::success(__('lang.saved_successfully', ['operator' => __('lang.complaint_comments')]));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        return redirect(route('complaints.index'));
    }*/


}
