<?php

namespace App\Http\Controllers;

use App\DataTables\QualityGradeDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateQualityGradeRequest;
use App\Http\Requests\UpdateQualityGradeRequest;
use App\Repositories\QualityGradeRepository;
use App\Repositories\CustomFieldRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class QualityGradeController extends Controller
{
    /**
     * @var QualityGradeRepository
     */
    private $qualityGradeRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;


    public function __construct(CustomFieldRepository $customFieldRepo , QualityGradeRepository $qualityGradeRepo)
    {
        parent::__construct();
        $this->customFieldRepository = $customFieldRepo;
        $this->qualityGradeRepository = $qualityGradeRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(QualityGradeDataTable $qualityGradeDataTable)
    {
        return $qualityGradeDataTable->render('quality_grade.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
   public function create()
    {
        
        $hasCustomField = in_array($this->qualityGradeRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->qualityGradeRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('quality_grade.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateQualityGradeRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->qualityGradeRepository->model());
        try {
            $input['created_by']  = auth()->user()->id;
            $quality_grade = $this->qualityGradeRepository->create($input);
            $quality_grade->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
            
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.quality_grade')]));

        if ($request->ajax()) {
            return $quality_grade;
        }
        return redirect(route('qualityGrade.index'));
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
        $quality_grade = $this->qualityGradeRepository->findWithoutFail($id);

        if (empty($quality_grade)) {
            Flash::error('Quality Grade not found');

            return redirect(route('qualityGrade.index'));
        }

        return view('quality_grade.show')->with('qualityGrade', $qualityGrade);
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
        $quality_grade = $this->qualityGradeRepository->findWithoutFail($id);
        
        if (empty($quality_grade)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.quality_grade')]));

            return redirect(route('qualityGrade.index'));
        }
        $customFieldsValues = $quality_grade->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->qualityGradeRepository->model());
        $hasCustomField = in_array($this->qualityGradeRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('quality_grade.edit')->with('quality_grade', $quality_grade)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id,UpdateQualityGradeRequest $request)
    {
        $quality_grade = $this->qualityGradeRepository->findWithoutFail($id);

        if (empty($quality_grade)) {
            Flash::error('Quality Grade not found');
            return redirect(route('qualityGrade.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->qualityGradeRepository->model());
        try {
            $input['updated_by'] = auth()->user()->id;
            $quality_grade = $this->qualityGradeRepository->update($input, $id);
            
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $quality_grade->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.quality_grade')]));

        return redirect(route('qualityGrade.index'));
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
        $quality_grade = $this->qualityGradeRepository->findWithoutFail($id);

        if (empty($quality_grade)) {
            Flash::error('Quality Grade not found');

            return redirect(route('qualityGrade.index'));
        }

        $this->qualityGradeRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.quality_grade')]));

        return redirect(route('qualityGrade.index'));
    }

    
}
