<?php

namespace App\Http\Controllers;

use App\DataTables\PartySubTypesDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePartySubTypesRequest;
use App\Http\Requests\UpdatePartySubTypesRequest;
use App\Repositories\PartyTypesRepository;
use App\Repositories\PartySubTypesRepository;
use App\Repositories\RoleRepository;
use App\Repositories\CustomFieldRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;

class PartySubTypesController extends Controller
{

    /** @var  PartySubTypesRepository */
    private $partySubTypesRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /*** @var PartyTypesRepository*/
    private $partyTypesRepository;

    /*** @var RoleRepository*/
    private $roleRepository;

    public function __construct(PartySubTypesRepository $partySubTypesRepo, CustomFieldRepository $customFieldRepo, PartyTypesRepository $partyTypesRepo, RoleRepository $roleRepo)
    {
        parent::__construct();
        $this->partySubTypesRepository = $partySubTypesRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->partyTypesRepository = $partyTypesRepo;
        $this->roleRepository = $roleRepo;
    }
   
     /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(PartySubTypesDataTable $partySubTypesDataTable)
    {
        return $partySubTypesDataTable->render('party_sub_types.index');
    }

   /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        $partyTypes_count  = $this->partyTypesRepository->max('id');
        $partyTypes  = $this->partyTypesRepository->pluck('name', 'id');
        $partyTypes->prepend("Please Select",0);

        $roles  = $this->roleRepository->pluck('name', 'id');
        $roles->prepend("Please Select",0);

        $hasCustomField = in_array($this->partySubTypesRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->partySubTypesRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('party_sub_types.create')->with("partyTypes", $partyTypes)->with('partyTypes_count',$partyTypes_count)->with("customFields", isset($html) ? $html : false)->with("roles", $roles);
    }


    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreatePartySubTypesRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->partySubTypesRepository->model());
        try {
            $input['created_by']  = auth()->user()->id;
            $partySubTypes = $this->partySubTypesRepository->create($input);
            $partySubTypes->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
          
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.party_sub_type')]));
        if ($request->ajax()) {
            return $partySubTypes;
        }
        //  return redirect(route('partySubTypes.index'));
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
        $partySubTypes = $this->partySubTypesRepository->findWithoutFail($id);

        if (empty($partySubTypes)) {
            Flash::error('Party Sub Types not found');

            return redirect(route('partySubTypes.index'));
        }

        return view('party_sub_types.show')->with('partySubTypes', $partySubTypes);
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
        $partySubTypes = $this->partySubTypesRepository->findWithoutFail($id);
        
         $partyTypes = $this->partyTypesRepository->pluck('name', 'id');

         $partyTypes_count  = $this->partyTypesRepository->max('id');

        if (empty($partySubTypes)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.party_sub_type')]));

            return redirect(route('partySubTypes.index'));
        }
        $customFieldsValues = $partySubTypes->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->partySubTypesRepository->model());
        $hasCustomField = in_array($this->partySubTypesRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        $roles  = $this->roleRepository->pluck('name', 'id');
        $roles->prepend("Please Select",0);

        return view('party_sub_types.edit')->with('partySubTypes', $partySubTypes)->with("partyTypes", $partyTypes)->with('partyTypes_count',$partyTypes_count)->with("customFields", isset($html) ? $html : false)->with('roles',$roles);
    }


   /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePartySubTypesRequest $request)
    {
        $partySubTypes = $this->partySubTypesRepository->findWithoutFail($id);

        if (empty($partySubTypes)) {
            Flash::error('Party Sub Types not found');
            return redirect(route('partySubTypes.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->partySubTypesRepository->model());
        try {
            $input['updated_by'] = auth()->user()->id;
            $partySubTypes = $this->partySubTypesRepository->update($input, $id);
           
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $partySubTypes->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.party_sub_type')]));

        return redirect(route('partySubTypes.index'));
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
        $partySubTypes = $this->partySubTypesRepository->findWithoutFail($id);

        if (empty($partySubTypes)) {
            Flash::error('Party Sub Types not found');

            return redirect(route('partySubTypes.index'));
        }

        $this->partySubTypesRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.party_sub_types')]));

        return redirect(route('partySubTypes.index'));
    }

      
}
