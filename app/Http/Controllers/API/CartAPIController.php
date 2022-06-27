<?php

namespace App\Http\Controllers\API;


use App\Http\Requests\CreateCartRequest;
use App\Http\Requests\CreateFavoriteRequest;
use App\Models\Cart;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Validator;

/**
 * Class CartController
 * @package App\Http\Controllers\API
 */

class CartAPIController extends Controller
{
    /** @var  CartRepository */
    private $cartRepository;

    /** @var  ProductRepository */
    private $productRepository;

    public function __construct(CartRepository $cartRepo, ProductRepository $productRepo)
    {
        $this->cartRepository = $cartRepo;
        $this->productRepository = $productRepo;
    }

    /**
     * Display a listing of the Cart.
     * GET|HEAD /carts
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->cartRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $carts = $this->cartRepository->where('user_id',auth()->user()->id)->get();
        return $this->sendResponse($carts->toArray(), 'Carts retrieved successfully');
    }

    /**
     * Display a listing of the Cart.
     * GET|HEAD /carts
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(Request $request)
    {
        try{
            $this->cartRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $count = $this->cartRepository->where('user_id',auth()->user()->id)->count();
        return $this->sendResponse($count, 'Count retrieved successfully');
    }

    /**
     * Display the specified Cart.
     * GET|HEAD /carts/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Cart $cart */
        if (!empty($this->cartRepository)) {
            $cart = $this->cartRepository->findWithoutFail($id);
        }
        if (empty($cart)) {
            return $this->sendError('Cart not found');
        }
        return $this->sendResponse($cart->toArray(), 'Cart retrieved successfully');
    }
    /**
     * Store a newly created Cart in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {   

        $validator = Validator::make($request->toArray(),[
            'user_id'        => 'required|exists:users,id',
            'product_id'     => 'required|exists:products,id',
            'unit_id'        => 'required|exists:uom,id',
            'quantity'       => 'required'
        ]);
        
        if ($validator->fails()) {    
            return $this->sendError($validator->messages());
        }

        $input = $request->all();
        try {
            if(isset($input['reset']) && $input['reset'] == '1'){
                // delete all items in the cart of current user
                $this->cartRepository->deleteWhere(['user_id'=> $input['user_id']]);
            }
            $check_exists = $this->cartRepository
                                 ->where('user_id',$request->user_id)
                                 ->where('product_id',$request->product_id)
                                 ->where('unit_id',$request->unit_id)
                                 ->count();
            if ($check_exists>0) {
                return $this->sendError('Product already exists in cart.');
            }

            $product = $this->productRepository->findWithoutFail($input['product_id']);
            $units   = $product->allunits($product->id);

            foreach($units as $unit) {
                if($unit['id'] == $input['unit_id']) {
                    $discount_price = ($product->discount_price > 0) ? $product->discount_price : $product->price ;
                    $input['price'] = number_format(($discount_price * $unit['quantity']),2,'.','');
                }
            }

            $cart = $this->cartRepository->create($input);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($cart->toArray(), 'Cart Added successfully');
    }

    /**
     * Update the specified Cart in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {   
        $cart = $this->cartRepository->findWithoutFail($id);
        if (empty($cart)) {
            return $this->sendError('Cart not found');
        }
        $input = $request->all();
        try {
            $cart = $this->cartRepository->update($input, $id);

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($cart->toArray(), 'Cart Added successfully');
    }

    /**
     * Remove the specified Favorite from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $cart = $this->cartRepository->findWithoutFail($id);
        if (empty($cart)) {
            return $this->sendError('Cart not found');
        }
        $cart = $this->cartRepository->delete($id);
        return $this->sendResponse($cart, 'Deleted successfully');
    }

}
