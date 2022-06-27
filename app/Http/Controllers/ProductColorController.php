<?php

namespace App\Http\Controllers;

use App\DataTables\ProductColorsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateProductColorRequest;
use App\Http\Requests\UpdateProductColorRequest;
use App\Repositories\ProductColorsRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class ProductColorController extends Controller
{
    /**
     * @var ProductColorsRepository
     */
    private $productColorsRepository;

    public function __construct(ProductColorsRepository $productColorsRepo)
    {
        parent::__construct();
        $this->productColorsRepository = $productColorsRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(ProductColorsDataTable $productColorsDataTable)
    {
        return $productColorsDataTable->render('product_colors.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        return view('product_colors.create');
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateProductColorRequest $request)
    {
        $input = $request->all();
        try {
            $product_color = $this->productColorsRepository->create($input);            
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success('Saved successfully',['operator' => 'Product Color']);
        if ($request->ajax()) {
            return $product_color;
        }
        return redirect(route('productColors.index'));
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
        $product_color = $this->productColorsRepository->findWithoutFail($id);
        if (empty($product_color)) {
            Flash::error('Product Color not found');
            return redirect(route('productColors.index'));
        }
        return view('product_colors.show')->with('product_color', $product_color);
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
        $product_color = $this->productColorsRepository->findWithoutFail($id);
        if (empty($product_color)) {
            Flash::error(__('Not found',['operator' => 'Product Color']));
            return redirect(route('productColors.index'));
        }
        return view('product_colors.edit')->with('product_color', $product_color);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id,UpdateProductColorRequest $request)
    {
        $product_color = $this->productColorsRepository->findWithoutFail($id);
        if (empty($product_color)) {
            Flash::error('Product Color not found');
            return redirect(route('productColors.index'));
        }
        $input = $request->all();
        try {
            $product_color = $this->productColorsRepository->update($input, $id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success('Updated Successfully',['operator' => 'Product Color']);
        return redirect(route('productColors.index'));
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
        $product_color = $this->productColorsRepository->findWithoutFail($id);
        if (empty($product_color)) {
            Flash::error('Product Color not found');
            return redirect(route('productColors.index'));
        }
        $this->productColorsRepository->delete($id);
        Flash::success('Deleted Successfully',['operator' => 'Product Color']);
        return redirect(route('productColors.index'));
    }

    
}
