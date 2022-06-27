<?php

namespace App\Http\Controllers;

use App\DataTables\CustomerGroupsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateCustomerGroupsRequest;
use App\Http\Requests\UpdateCustomerGroupsRequest;
use App\Repositories\CustomerGroupsRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class CustomerGroupsController extends Controller
{
	/** @var  CustomerGroupsRepository */
    private $customerGroupRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
private $uploadRepository;

    public function __construct(CustomerGroupsRepository $customerGroupRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->customerGroupRepository = $customerGroupRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

     public function index(CustomerGroupsDataTable $CustomerGroupsDataTable)
    {
        return $CustomerGroupsDataTable->render('customer_groups.index');
    }

     public function create()
    {
       
        $hasCustomField = in_array($this->customerGroupRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField){
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->customerGroupRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('customer_groups.create')->with("customFields", isset($html) ? $html : false);
    }

   
    public function store(CreateCustomerGroupsRequest $request)
    {

        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->customerGroupRepository->model());
        try {
            $input['created_by']  = auth()->user()->id;
            $expenses_category = $this->customerGroupRepository->create($input);
            $expenses_category->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
            if(isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($expenses_category, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.customer_group')]));
        if ($request->ajax()) {
            return $expenses_category;
        }
        //  return redirect(route('CustomerGroups.index'));
        return Redirect()->back();
    }

   
    public function show($id)
    {
        $customer_groups = $this->customerGroupRepository->findWithoutFail($id);

        if (empty($customer_groups)) {
            Flash::error('Expenses Category not found');
            return redirect(route('CustomerGroups.index'));
        }

        return view('customer_groups.show')->with('customer_groups', $customer_groups);
    }

    public function edit($id)
    {
        $customer_groups = $this->customerGroupRepository->findWithoutFail($id);
        
        if (empty($customer_groups)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.category')]));

            return redirect(route('categories.index'));
        }
        $customFieldsValues = $customer_groups->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->customerGroupRepository->model());
        $hasCustomField = in_array($this->customerGroupRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('customer_groups.edit')->with('customer_groups', $customer_groups)->with("customFields", isset($html) ? $html : false);
    }

   
    public function update($id, UpdateCustomerGroupsRequest $request)
    {
        $customer_groups = $this->customerGroupRepository->findWithoutFail($id);

        if (empty($customer_groups)) {
            Flash::error('Category not found');
            return redirect(route('CustomerGroups.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->customerGroupRepository->model());
        try {
            $input['updated_by'] = auth()->user()->id;
            $customer_groups = $this->customerGroupRepository->update($input, $id);
            
            if(isset($input['image']) && $input['image']){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($customer_groups, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $customer_groups->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.customer_group')]));

        return redirect(route('CustomerGroups.index'));
    }

    
    public function destroy($id)
    {
        $category = $this->customerGroupRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('CustomerGroups.index'));
        }

        $this->customerGroupRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.customer_group')]));

        return redirect(route('CustomerGroups.index'));
    }

 
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $customer_groups = $this->customerGroupRepository->findWithoutFail($input['id']);
        try {
            if($customer_groups->hasMedia($input['collection'])){
                $customer_groups->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
