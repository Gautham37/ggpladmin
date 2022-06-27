<?php

namespace App\Http\Controllers;


use App\DataTables\PartyStreamDataTable;
use App\DataTables\StaffDepartmentDataTable;
use App\Http\Requests;
use App\Repositories\CustomFieldRepository;
use App\Repositories\PartyStreamRepository;
use App\Repositories\StaffDepartmentRepository;
use App\Repositories\UploadRepository;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;

class StaffDepartmentController extends Controller
{
    /** @var  StaffDepartmentRepository */
    private $staffdepartmentRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(StaffDepartmentRepository $staffdepartmentRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->staffdepartmentRepository = $staffdepartmentRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

    public function index(StaffDepartmentDataTable $staffDepartmentDataTable)
    {
        return $staffDepartmentDataTable->render('staffdepartment.index');
    }

    public function create()
    {

        $hasCustomField = in_array($this->staffdepartmentRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField){
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->staffdepartmentRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('staffdepartment.create')->with("customFields", isset($html) ? $html : false);
    }


    public function store(Requests\CreateStaffDepartmentRequest $request)
    {

        $input = $request->all();

        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->staffdepartmentRepository->model());
        try {
            $input['created_by']  = auth()->user()->id;
            $staffdepartment = $this->staffdepartmentRepository->create($input);
            $staffdepartment->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
            // if(isset($input['image']) && $input['image']) {
            //     $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
            //     $mediaItem = $cacheUpload->getMedia('image')->first();
            //     $mediaItem->copy($expenses_category, 'image');
            // }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.staffdepartment')]));

        return redirect(route('staffdepartment.index'));
    }


    public function show($id)
    {
        $staffdepartment = $this->staffdepartmentRepository->findWithoutFail($id);

        if (empty($partystreams)) {
            Flash::error('Staff Department not found');
            return redirect(route('staffdepartment.index'));
        }

        return view('staffdepartment.show')->with('staffdepartment', $staffdepartment);
    }

    public function edit($id)
    {
        $staffdepartment = $this->staffdepartmentRepository->findWithoutFail($id);

        if (empty($staffdepartment)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.category')]));

            return redirect(route('staffdepartment.index'));
        }
        $customFieldsValues = $staffdepartment->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->staffdepartmentRepository->model());
        $hasCustomField = in_array($this->staffdepartmentRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('staffdepartment.edit')->with('staffdepartment', $staffdepartment)->with("customFields", isset($html) ? $html : false);
    }


    public function update($id, Requests\UpdateStaffDepartmentRequest $request)
    {
        $staffdepartment = $this->staffdepartmentRepository->findWithoutFail($id);

        if (empty($staffdepartment)) {
            Flash::error('Staff Department not found');
            return redirect(route('staffdepartment.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->staffdepartmentRepository->model());
        try {
            $input['updated_by'] = auth()->user()->id;
            $staffdepartment = $this->staffdepartmentRepository->update($input, $id);

            // if(isset($input['image']) && $input['image']){
            //     $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
            //     $mediaItem = $cacheUpload->getMedia('image')->first();
            //     $mediaItem->copy($customer_groups, 'image');
            // }
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $staffdepartment->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.staffdepartment')]));

        return redirect(route('staffdepartment.index'));
    }


    public function destroy($id)
    {
        $staffdepartment = $this->staffdepartmentRepository->findWithoutFail($id);

        if (empty($staffdepartment)) {
            Flash::error('Staff Department not found');

            return redirect(route('staffdepartment.index'));
        }

        $this->staffdepartmentRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.staffdepartment')]));

        return redirect(route('staffdepartment.index'));
    }



}
