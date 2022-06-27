<?php

namespace App\Http\Controllers;

use App\DataTables\ValueAddedServiceAffiliatedDataTable;
use App\Helper\Reply;
use App\Http\Requests;
use App\Http\Requests\CreateValueAddedServiceAffiliatedRequest;
use App\Http\Requests\UpdateValueAddedServiceAffiliatedRequest;
use App\Repositories\ValueAddedServiceAffiliatedRepository;
use App\Repositories\CustomFieldRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class ValueAddedServiceAffiliatedController extends Controller
{
    /**
     * @var ValueAddedServiceAffiliatedRepository
     */
    private $valueAddedServiceAffiliatedRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;


    public function __construct(CustomFieldRepository $customFieldRepo , ValueAddedServiceAffiliatedRepository $valueAddedServiceAffiliatedRepo)
    {
        parent::__construct();
        $this->customFieldRepository = $customFieldRepo;
        $this->valueAddedServiceAffiliatedRepository = $valueAddedServiceAffiliatedRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(ValueAddedServiceAffiliatedDataTable $valueAddedServiceAffiliatedDataTable, Request $request)
    {   
        if ($request->ajax()) {
            $data = $this->valueAddedServiceAffiliatedRepository->get();
            return ['status' => 'success', 'data' => $data];
        }
        return $valueAddedServiceAffiliatedDataTable->render('value_added_service_affiliated.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
   public function create()
    {
        
        $hasCustomField = in_array($this->valueAddedServiceAffiliatedRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->valueAddedServiceAffiliatedRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('value_added_service_affiliated.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateValueAddedServiceAffiliatedRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->valueAddedServiceAffiliatedRepository->model());
        try {
            $input['created_by']  = auth()->user()->id;
            $value_added_service_affiliated = $this->valueAddedServiceAffiliatedRepository->create($input);
            $value_added_service_affiliated->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
            
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.value_added_service_affiliated')]));
        if ($request->ajax()) {
            return $value_added_service_affiliated;
        }
        return redirect(route('valueAddedServiceAffiliated.index'));
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
        $value_added_service_affiliated = $this->valueAddedServiceAffiliatedRepository->findWithoutFail($id);

        if (empty($value_added_service_affiliated)) {
            Flash::error('Value Added Servic eAffiliated not found');

            return redirect(route('valueAddedServiceAffiliated.index'));
        }

        return view('value_added_service_affiliated.show')->with('valueAddedServiceAffiliated', $valueAddedServiceAffiliated);
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
        $value_added_service_affiliated = $this->valueAddedServiceAffiliatedRepository->findWithoutFail($id);
        
        if (empty($value_added_service_affiliated)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.value_added_service_affiliated')]));

            return redirect(route('valueAddedServiceAffiliated.index'));
        }
        $customFieldsValues = $value_added_service_affiliated->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->valueAddedServiceAffiliatedRepository->model());
        $hasCustomField = in_array($this->valueAddedServiceAffiliatedRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('value_added_service_affiliated.edit')->with('value_added_service_affiliated', $value_added_service_affiliated)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id,UpdateValueAddedServiceAffiliatedRequest $request)
    {
        $value_added_service_affiliated = $this->valueAddedServiceAffiliatedRepository->findWithoutFail($id);

        if (empty($value_added_service_affiliated)) {
            Flash::error('Value Added Servic eAffiliated not found');
            return redirect(route('valueAddedServiceAffiliated.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->valueAddedServiceAffiliatedRepository->model());
        try {
            $input['updated_by'] = auth()->user()->id;
            $value_added_service_affiliated = $this->valueAddedServiceAffiliatedRepository->update($input, $id);
            
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $value_added_service_affiliated->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.value_added_service_affiliated')]));

        return redirect(route('valueAddedServiceAffiliated.index'));
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
        $value_added_service_affiliated = $this->valueAddedServiceAffiliatedRepository->findWithoutFail($id);

        if (empty($value_added_service_affiliated)) {
            Flash::error('Stock Status not found');

            return redirect(route('valueAddedServiceAffiliated.index'));
        }

        $this->valueAddedServiceAffiliatedRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.value_added_service_affiliated')]));

        return redirect(route('valueAddedServiceAffiliated.index'));
    }

    
}
