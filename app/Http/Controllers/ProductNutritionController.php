<?php

namespace App\Http\Controllers;

use App\DataTables\ProductNutritionsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateProductNutritionRequest;
use App\Http\Requests\UpdateProductNutritionRequest;
use App\Repositories\ProductNutritionsRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class ProductNutritionController extends Controller
{
    /**
     * @var ProductNutritionsRepository
     */
    private $productNutritionsRepository;

    public function __construct(ProductNutritionsRepository $productNutritionsRepo)
    {
        parent::__construct();
        $this->productNutritionsRepository = $productNutritionsRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(ProductNutritionsDataTable $productNutritionsDataTable)
    {
        return $productNutritionsDataTable->render('product_nutritions.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        return view('product_nutritions.create');
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateProductNutritionRequest $request)
    {
        $input = $request->all();
        try {
            $product_nutrition = $this->productNutritionsRepository->create($input);            
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success('Saved successfully',['operator' => 'Product Nutrition']);
        if ($request->ajax()) {
            return $product_nutrition;
        }
        return redirect(route('productNutritions.index'));
    }

    /**
     * Display the specified Category.
     *
     * @param  int $id
     *
     * @return Response
     */
   public function show($id)
    {
        $product_nutrition = $this->productNutritionsRepository->findWithoutFail($id);
        if (empty($product_nutrition)) {
            Flash::error('Product Nutrition not found');
            return redirect(route('productNutritions.index'));
        }
        return view('product_nutritions.show')->with('product_nutrition', $product_nutrition);
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
        $product_nutrition = $this->productNutritionsRepository->findWithoutFail($id);
        if (empty($product_nutrition)) {
            Flash::error(__('Not found',['operator' => 'Product Nutrition']));
            return redirect(route('productNutritions.index'));
        }
        return view('product_nutritions.edit')->with('product_nutrition', $product_nutrition);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id,UpdateProductNutritionRequest $request)
    {
        $product_nutrition = $this->productNutritionsRepository->findWithoutFail($id);
        if (empty($product_nutrition)) {
            Flash::error('Product Nutrition not found');
            return redirect(route('productNutritions.index'));
        }
        $input = $request->all();
        try {
            $product_nutrition = $this->productNutritionsRepository->update($input, $id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success('Updated Successfully',['operator' => 'Product Nutrition']);
        return redirect(route('productNutritions.index'));
    }

    /**
     * Remove the specified Category from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $product_nutrition = $this->productNutritionsRepository->findWithoutFail($id);
        if (empty($product_nutrition)) {
            Flash::error('Product Nutrition not found');
            return redirect(route('productNutritions.index'));
        }
        $this->productNutritionsRepository->delete($id);
        Flash::success('Deleted Successfully',['operator' => 'Product Nutrition']);
        return redirect(route('productNutritions.index'));
    }

    
}
