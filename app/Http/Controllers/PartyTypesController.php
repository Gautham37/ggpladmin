<?php

namespace App\Http\Controllers;

use App\DataTables\PartyTypesDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePartyTypesRequest;
use App\Http\Requests\UpdatePartyTypesRequest;
use App\Repositories\PartyTypesRepository;
use App\Repositories\CustomFieldRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;

class PartyTypesController extends Controller
{

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

   /**
     * @var PartyTypesRepository
     */
    private $partyTypesRepository;

    public function __construct(CustomFieldRepository $customFieldRepo, PartyTypesRepository $partyTypesRepo)
    {
        parent::__construct();
        $this->customFieldRepository = $customFieldRepo;
        $this->partyTypesRepository = $partyTypesRepo;
    }
   
     /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(PartyTypesDataTable $partyTypesDataTable)
    {
        return $partyTypesDataTable->render('party_types.index');
    }

   /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {

        $hasCustomField = in_array($this->partyTypesRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->partyTypesRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('party_types.create')->with("customFields", isset($html) ? $html : false);
    }


    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreatePartyTypesRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->partyTypesRepository->model());
        try {
            $input['created_by']  = auth()->user()->id;
            $partyTypes = $this->partyTypesRepository->create($input);
            $partyTypes->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
           
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.party_type')]));
        if ($request->ajax()) {
            return $partyTypes;
        }
       // return redirect(route('partyTypes.index'));
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
        $partyTypes = $this->partyTypesRepository->findWithoutFail($id);

        if (empty($partyTypes)) {
            Flash::error('Party Types not found');

            return redirect(route('partyTypes.index'));
        }

        return view('partyTypes.show')->with('partyTypes', $partyTypes);
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
        $partyTypes = $this->partyTypesRepository->findWithoutFail($id);
      
        if (empty($partyTypes)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.party_type')]));

            return redirect(route('partyTypes.index'));
        }
        $customFieldsValues = $partyTypes->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->partyTypesRepository->model());
        $hasCustomField = in_array($this->partyTypesRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('party_types.edit')->with('partyTypes', $partyTypes)->with("customFields", isset($html) ? $html : false);
    }


   /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePartyTypesRequest $request)
    {
        $partyTypes = $this->partyTypesRepository->findWithoutFail($id);

        if (empty($partyTypes)) {
            Flash::error('Party Types not found');
            return redirect(route('partyTypes.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->partyTypesRepository->model());
        try {
            $input['updated_by'] = auth()->user()->id;
            $partyTypes = $this->partyTypesRepository->update($input, $id);
           
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $partyTypes->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.party_type')]));

        return redirect(route('partyTypes.index'));
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
        $partyTypes = $this->partyTypesRepository->findWithoutFail($id);

        if (empty($partyTypes)) {
            Flash::error('Party Types not found');

            return redirect(route('partyTypes.index'));
        }

        $this->partyTypesRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.party_type')]));

        return redirect(route('partyTypes.index'));
    }

}
