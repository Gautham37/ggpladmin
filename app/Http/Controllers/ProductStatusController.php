<?php

namespace App\Http\Controllers;

use App\DataTables\ProductStatusDataTable;
use App\Http\Requests;
//use App\Http\Requests\CreateDepartmentsRequest;
//use App\Http\Requests\UpdateDepartmentsRequest;
use App\Repositories\ProductStatusRepository;
use App\Repositories\CustomFieldRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class ProductStatusController extends Controller
{
    /**
     * @var ProductStatusRepository
     */
    private $productStatusRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;


    public function __construct(CustomFieldRepository $customFieldRepo , ProductStatusRepository $productStatusRepo)
    {
        parent::__construct();
        $this->customFieldRepository = $customFieldRepo;
        $this->productStatusRepository = $productStatusRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(ProductStatusDataTable $productStatusDataTable)
    {
        return $productStatusDataTable->render('product_status.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
   public function create()
    {
        
        $hasCustomField = in_array($this->productStatusRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productStatusRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('product_status.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productStatusRepository->model());
        try {
            $input['created_by']  = auth()->user()->id;
            $product_status = $this->productStatusRepository->create($input);
            $product_status->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
            
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.product_status')]));

        return redirect(route('productStatus.index'));
        //return Redirect()->back();
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
        $product_status = $this->productStatusRepository->findWithoutFail($id);

        if (empty($product_status)) {
            Flash::error('Product Status not found');

            return redirect(route('productStatus.index'));
        }

        return view('product_status.show')->with('productStatus', $productStatus);
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
        $product_status = $this->productStatusRepository->findWithoutFail($id);
        
        if (empty($product_status)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.product_status')]));

            return redirect(route('productStatus.index'));
        }
        $customFieldsValues = $product_status->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->productStatusRepository->model());
        $hasCustomField = in_array($this->productStatusRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('product_status.edit')->with('product_status', $product_status)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id,Request $request)
    {
        $product_status = $this->productStatusRepository->findWithoutFail($id);

        if (empty($product_status)) {
            Flash::error('Product Status not found');
            return redirect(route('productStatus.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productStatusRepository->model());
        try {
            $input['updated_by'] = auth()->user()->id;
            $product_status = $this->productStatusRepository->update($input, $id);
            
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $product_status->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.product_status')]));

        return redirect(route('productStatus.index'));
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
        $product_status = $this->productStatusRepository->findWithoutFail($id);

        if (empty($product_status)) {
            Flash::error('Product Status not found');

            return redirect(route('productStatus.index'));
        }

        $this->productStatusRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.product_status')]));

        return redirect(route('productStatus.index'));
    }

    
}
