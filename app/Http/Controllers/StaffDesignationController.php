<?php

namespace App\Http\Controllers;



use App\DataTables\StaffDepartmentDataTable;
use App\DataTables\StaffDesignationDataTable;
use App\Http\Requests;
use App\Repositories\CustomFieldRepository;
use App\Repositories\StaffDepartmentRepository;
use App\Repositories\StaffDesignationRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;

class StaffDesignationController extends Controller
{
    /** @var  StaffDesignationRepository */
    private $staffdesignationRepository;
    
      /** @var  StaffDepartmentRepository */
    private $staffDepartmentRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;
    
     /**
     * @var RoleRepository
     */
    private $roleRepository;

    public function __construct(StaffDesignationRepository $staffdesignationRepo, StaffDepartmentRepository $staffDepartmentRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo, RoleRepository $roleRepo)
    {
        parent::__construct();
        $this->staffdesignationRepository = $staffdesignationRepo;
        $this->staffDepartmentRepository = $staffDepartmentRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->roleRepository = $roleRepo;
    }

    public function index(StaffDesignationDataTable $staffDesignationDataTable)
    {
        return $staffDesignationDataTable->render('staffdesignation.index');
    }

    public function create()
    {
        return view('staffdesignation.create');
    }


    public function store(Requests\CreateStaffDesignationRequest $request)
    {

        $input = $request->all();
        try {
            $input['created_by']  = auth()->user()->id;
            $staffdesignation = $this->staffdesignationRepository->create($input);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        if ($request->ajax()) {
            return $staffdesignation;
        }
        Flash::success(__('lang.saved_successfully',['operator' => 'Staff Designation']));
        return redirect(route('staffdesignation.index'));
    }


    public function show($id)
    {
        $staffdesignation = $this->staffdesignationRepository->findWithoutFail($id);

        if (empty($staffdesignation)) {
            Flash::error('Staff Department not found');
            return redirect(route('staffdesignation.index'));
        }

        return view('staffdesignation.show')->with('staffdesignation', $staffdesignation);
    }

    public function edit($id)
    {
        $staffdesignation = $this->staffdesignationRepository->findWithoutFail($id);
        
        if (empty($staffdesignation)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.category')]));
            return redirect(route('staffdesignation.index'));
        }
        return view('staffdesignation.edit')->with('staffdesignation', $staffdesignation);
    }


    public function update($id, Requests\UpdateStaffDesignationRequest $request)
    {
        
        $staffdesignation = $this->staffdesignationRepository->findWithoutFail($id);
        if (empty($staffdesignation)) {
            Flash::error('Staff Designation not found');
            return redirect(route('staffdesignation.index'));
        }
        $input = $request->all();
        try {
            $input['updated_by'] = auth()->user()->id;
            $staffdesignation = $this->staffdesignationRepository->update($input, $id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('lang.updated_successfully',['operator' => 'Staff Designation']));
        return redirect(route('staffdesignation.index'));
    }


    public function destroy($id)
    {
        $staffdesignation= $this->staffdesignationRepository->findWithoutFail($id);
        if (empty($staffdesignation)) {
            Flash::error('Staff Designation not found');
            return redirect(route('staffdesignation.index'));
        }
        $this->staffdesignationRepository->delete($id);
        
        Flash::success(__('lang.deleted_successfully',['operator' => 'Staff Designation']));
        return redirect(route('staffdesignation.index'));
    }



}
