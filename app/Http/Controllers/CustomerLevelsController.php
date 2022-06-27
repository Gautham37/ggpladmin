<?php

namespace App\Http\Controllers;

use App\DataTables\CustomerLevelsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateCustomerLevelsRequest;
use App\Http\Requests\UpdateCustomerLevelsRequest;
use App\Repositories\CustomerLevelsRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class CustomerLevelsController extends Controller
{
	/** @var  CustomerGroupsRepository */
    private $customerLevelsRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
private $uploadRepository;

    public function __construct(CustomerLevelsRepository $customerLevelsRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->customerLevelsRepository = $customerLevelsRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

     public function index(CustomerLevelsDataTable $CustomerLevelsDataTable)
    {
        return $CustomerLevelsDataTable->render('customer_levels.index');
    }

     public function create()
    {
       
        $hasCustomField = in_array($this->customerLevelsRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField){
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->customerLevelsRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('customer_levels.create')->with("customFields", isset($html) ? $html : false);
    }

   
    public function store(CreateCustomerLevelsRequest $request)
    {

        $input = $request->all();
        try {
            $input['created_by']  = auth()->user()->id;
            $customer_levels      = $this->customerLevelsRepository->create($input);
            //$customer_levels->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
            // if(isset($input['image']) && $input['image']) {
            //     $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
            //     $mediaItem = $cacheUpload->getMedia('image')->first();
            //     $mediaItem->copy($expenses_category, 'image');
            // }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.customer_levels')]));
        return redirect(route('CustomerLevels.index'));
    }

   
    public function show($id)
    {
        $customer_levels = $this->customerLevelsRepository->findWithoutFail($id);

        if (empty($customer_levels)) {
            Flash::error('Customer Levels not found');
            return redirect(route('CustomerLevels.index'));
        }

        return view('customer_levels.show')->with('customer_levels', $customer_levels);
    }

    public function edit($id)
    {
        $customer_levels = $this->customerLevelsRepository->findWithoutFail($id);
        
        if (empty($customer_levels)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.category')]));

            return redirect(route('categories.index'));
        }
        $customFieldsValues = $customer_levels->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->customerLevelsRepository->model());
        $hasCustomField = in_array($this->customerLevelsRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('customer_levels.edit')->with('customer_levels', $customer_levels)->with("customFields", isset($html) ? $html : false);
    }

   
    public function update($id, UpdateCustomerLevelsRequest $request)
    {
        $customer_levels = $this->customerLevelsRepository->findWithoutFail($id);

        if (empty($customer_levels)) {
            Flash::error('Category not found');
            return redirect(route('CustomerLevels.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->customerLevelsRepository->model());
        try {
            $input['updated_by'] = auth()->user()->id;
            $customer_levels = $this->customerLevelsRepository->update($input, $id);
            
            // if(isset($input['image']) && $input['image']){
            //     $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
            //     $mediaItem = $cacheUpload->getMedia('image')->first();
            //     $mediaItem->copy($customer_groups, 'image');
            // }
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $customer_levels->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.customer_levels')]));

        return redirect(route('CustomerLevels.index'));
    }

    
    public function destroy($id)
    {
        $category = $this->customerLevelsRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('CustomerGroups.index'));
        }

        $this->customerLevelsRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.customer_levels')]));

        return redirect(route('CustomerLevels.index'));
    }

 
    // public function removeMedia(Request $request)
    // {
    //     $input = $request->all();
    //     $category = $this->customerLevelRepository->findWithoutFail($input['id']);
    //     try {
    //         if($category->hasMedia($input['collection'])){
    //             $category->getFirstMedia($input['collection'])->delete();
    //         }
    //     } catch (\Exception $e) {
    //         Log::error($e->getMessage());
    //     }
    // }
}
