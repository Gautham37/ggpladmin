<?php

namespace App\Http\Controllers;

use App\DataTables\ProductSeasonsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateProductSeasonRequest;
use App\Http\Requests\UpdateProductSeasonRequest;
use App\Repositories\ProductSeasonsRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class ProductSeasonController extends Controller
{
    /**
     * @var ProductSeasonsRepository
     */
    private $productSeasonsRepository;

    public function __construct(ProductSeasonsRepository $productSeasonsRepo)
    {
        parent::__construct();
        $this->productSeasonsRepository = $productSeasonsRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(ProductSeasonsDataTable $productSeasonsDataTable)
    {
        return $productSeasonsDataTable->render('product_seasons.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        return view('product_seasons.create');
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateProductSeasonRequest $request)
    {
        $input = $request->all();
        try {
            $product_season = $this->productSeasonsRepository->create($input);            
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success('Saved successfully',['operator' => 'Product Season']);
        if ($request->ajax()) {
            return $product_season;
        }
        return redirect(route('productSeasons.index'));
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
        $product_season = $this->productSeasonsRepository->findWithoutFail($id);
        if (empty($product_season)) {
            Flash::error('Product Season not found');
            return redirect(route('productSeasons.index'));
        }
        return view('product_seasons.show')->with('product_season', $product_season);
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
        $product_season = $this->productSeasonsRepository->findWithoutFail($id);
        if (empty($product_season)) {
            Flash::error(__('Not found',['operator' => 'Product Season']));
            return redirect(route('productSeasons.index'));
        }
        return view('product_seasons.edit')->with('product_season', $product_season);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id,UpdateProductSeasonRequest $request)
    {
        $product_season = $this->productSeasonsRepository->findWithoutFail($id);
        if (empty($product_season)) {
            Flash::error('Product Season not found');
            return redirect(route('productSeasons.index'));
        }
        $input = $request->all();
        try {
            $product_season = $this->productSeasonsRepository->update($input, $id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success('Updated Successfully',['operator' => 'Product Season']);
        return redirect(route('productSeasons.index'));
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
        $product_season = $this->productSeasonsRepository->findWithoutFail($id);
        if (empty($product_season)) {
            Flash::error('Product Season not found');
            return redirect(route('productSeasons.index'));
        }
        $this->productSeasonsRepository->delete($id);
        Flash::success('Deleted Successfully',['operator' => 'Product Season']);
        return redirect(route('productSeasons.index'));
    }

    
}
