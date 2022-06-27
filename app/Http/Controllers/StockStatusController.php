<?php

namespace App\Http\Controllers;

use App\DataTables\StockStatusDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateStockStatusRequest;
use App\Http\Requests\UpdateStockStatusRequest;
use App\Repositories\StockStatusRepository;
use App\Repositories\CustomFieldRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class StockStatusController extends Controller
{
    /**
     * @var StockStatusRepository
     */
    private $stockStatusRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;


    public function __construct(CustomFieldRepository $customFieldRepo , StockStatusRepository $stockStatusRepo)
    {
        parent::__construct();
        $this->customFieldRepository = $customFieldRepo;
        $this->stockStatusRepository = $stockStatusRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(StockStatusDataTable $stockStatusDataTable)
    {
        return $stockStatusDataTable->render('stock_status.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
   public function create()
    {
        
        $hasCustomField = in_array($this->stockStatusRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->stockStatusRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('stock_status.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateStockStatusRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->stockStatusRepository->model());
        try {
            $input['created_by']  = auth()->user()->id;
            $stock_status = $this->stockStatusRepository->create($input);
            $stock_status->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
            
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.stock_status')]));

        if ($request->ajax()) {
            return $stock_status;
        }
        return redirect(route('stockStatus.index'));
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
        $stock_status = $this->stockStatusRepository->findWithoutFail($id);

        if (empty($stock_status)) {
            Flash::error('Stock Status not found');

            return redirect(route('stockStatus.index'));
        }

        return view('stock_status.show')->with('stockStatus', $stockStatus);
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
        $stock_status = $this->stockStatusRepository->findWithoutFail($id);
        
        if (empty($stock_status)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.stock_status')]));

            return redirect(route('stockStatus.index'));
        }
        $customFieldsValues = $stock_status->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->stockStatusRepository->model());
        $hasCustomField = in_array($this->stockStatusRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('stock_status.edit')->with('stock_status', $stock_status)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id,UpdateStockStatusRequest $request)
    {
        $stock_status = $this->stockStatusRepository->findWithoutFail($id);

        if (empty($stock_status)) {
            Flash::error('Stock Status not found');
            return redirect(route('stockStatus.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->stockStatusRepository->model());
        try {
            $input['updated_by'] = auth()->user()->id;
            $stock_status = $this->stockStatusRepository->update($input, $id);
            
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $stock_status->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.stock_status')]));

        return redirect(route('stockStatus.index'));
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
        $stock_status = $this->stockStatusRepository->findWithoutFail($id);

        if (empty($stock_status)) {
            Flash::error('Stock Status not found');

            return redirect(route('stockStatus.index'));
        }

        $this->stockStatusRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.stock_status')]));

        return redirect(route('stockStatus.index'));
    }

    
}
