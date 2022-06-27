<?php

namespace App\Http\Controllers;

use App\DataTables\ProductTastesDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateProductTasteRequest;
use App\Http\Requests\UpdateProductTasteRequest;
use App\Repositories\ProductTastesRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class ProductTasteController extends Controller
{
    /**
     * @var ProductTastesRepository
     */
    private $productTastesRepository;

    public function __construct(ProductTastesRepository $productTastesRepo)
    {
        parent::__construct();
        $this->productTastesRepository = $productTastesRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(ProductTastesDataTable $productTastesDataTable)
    {
        return $productTastesDataTable->render('product_tastes.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        return view('product_tastes.create');
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateProductTasteRequest $request)
    {
        $input = $request->all();
        try {
            $product_taste = $this->productTastesRepository->create($input);            
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success('Saved successfully',['operator' => 'Product Taste']);
        if ($request->ajax()) {
            return $product_taste;
        }
        return redirect(route('productTastes.index'));
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
        $product_taste = $this->productTastesRepository->findWithoutFail($id);
        if (empty($product_taste)) {
            Flash::error('Product Taste not found');
            return redirect(route('productTastes.index'));
        }
        return view('product_tastes.show')->with('product_taste', $product_taste);
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
        $product_taste = $this->productTastesRepository->findWithoutFail($id);
        if (empty($product_taste)) {
            Flash::error(__('Not found',['operator' => 'Product Taste']));
            return redirect(route('productTastes.index'));
        }
        return view('product_tastes.edit')->with('product_taste', $product_taste);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id,UpdateProductTasteRequest $request)
    {
        $product_taste = $this->productTastesRepository->findWithoutFail($id);
        if (empty($product_taste)) {
            Flash::error('Product Taste not found');
            return redirect(route('productTastes.index'));
        }
        $input = $request->all();
        try {
            $product_taste = $this->productTastesRepository->update($input, $id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success('Updated Successfully',['operator' => 'Product Taste']);
        return redirect(route('productTastes.index'));
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
        $product_taste = $this->productTastesRepository->findWithoutFail($id);
        if (empty($product_taste)) {
            Flash::error('Product Taste not found');
            return redirect(route('productTastes.index'));
        }
        $this->productTastesRepository->delete($id);
        Flash::success('Deleted Successfully',['operator' => 'Product Taste']);
        return redirect(route('productTastes.index'));
    }

    
}
