<?php

namespace App\Http\Controllers;

use App\DataTables\DepartmentsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateDepartmentsRequest;
use App\Http\Requests\UpdateDepartmentsRequest;
use App\Repositories\DepartmentsRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class DepartmentsController extends Controller
{
    /** @var  CategoryRepository */
    private $departmentsRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
private $uploadRepository;

    public function __construct(DepartmentsRepository $departmentsRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->departmentsRepository = $departmentsRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(DepartmentsDataTable $departmentsDataTable)
    {
        return $departmentsDataTable->render('departments.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        
        
        $hasCustomField = in_array($this->departmentsRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->departmentsRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('departments.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateDepartmentsRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->departmentsRepository->model());
        try {
            $input['created_by']  = auth()->user()->id;
            $departments = $this->departmentsRepository->create($input);
            $departments->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
            if(isset($input['image']) && $input['image']){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($departments, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.department')]));
        if ($request->ajax()) {
            return $departments;
        }
        return Redirect()->back();
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
        $departments = $this->departmentsRepository->findWithoutFail($id);

        if (empty($departments)) {
            Flash::error('Departments not found');

            return redirect(route('departments.index'));
        }

        return view('departments.show')->with('departments', $departments);
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
        $departments = $this->departmentsRepository->findWithoutFail($id);
        
        if (empty($departments)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.department')]));

            return redirect(route('departments.index'));
        }
        $customFieldsValues = $departments->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->departmentsRepository->model());
        $hasCustomField = in_array($this->departmentsRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('departments.edit')->with('departments', $departments)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDepartmentsRequest $request)
    {
        $departments = $this->departmentsRepository->findWithoutFail($id);

        if (empty($departments)) {
            Flash::error('Departments not found');
            return redirect(route('departments.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->departmentsRepository->model());
        try {
            $input['updated_by'] = auth()->user()->id;
            $departments = $this->departmentsRepository->update($input, $id);
            
            if(isset($input['image']) && $input['image']){
    $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
    $mediaItem = $cacheUpload->getMedia('image')->first();
    $mediaItem->copy($departments, 'image');
}
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $departments->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.department')]));

        return redirect(route('departments.index'));
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
        $departments = $this->departmentsRepository->findWithoutFail($id);

        if (empty($departments)) {
            Flash::error('Departments not found');

            return redirect(route('departments.index'));
        }

        $this->departmentsRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.department')]));

        return redirect(route('departments.index'));
    }

        /**
     * Remove Media of Category
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $departments = $this->departmentsRepository->findWithoutFail($input['id']);
        try {
            if($departments->hasMedia($input['collection'])){
                $departments->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
