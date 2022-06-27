<?php

namespace App\Http\Controllers;

use App\DataTables\SubcategoryDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateSubcategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\SubcategoryRepository;
use App\Repositories\DepartmentsRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;

class SubcategoryController extends Controller
{

    /** @var  SubcategoryRepository */
    private $subcategoryRepository;
    
     /** @var  DepartmentsRepository */
    private $departmentsRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
   private $uploadRepository;

   /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(SubcategoryRepository $subcategoryRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo, CategoryRepository $categoryRepo, DepartmentsRepository $departmentsRepo)
    {
        parent::__construct();
        $this->subcategoryRepository = $subcategoryRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->categoryRepository = $categoryRepo;
        $this->departmentsRepository = $departmentsRepo;
    }
   
     /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(SubcategoryDataTable $subcategoryDataTable)
    {
        return $subcategoryDataTable->render('subcategory.index');
    }

   /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        $category_count  = $this->categoryRepository->max('id');
        $departments  = $this->departmentsRepository->pluck('name', 'id');
        $departments->prepend("Please Select",0);
        // $category->push('Others', 'others');
        $category=["Please Select"];
        $categorySelected=array();

        $hasCustomField = in_array($this->subcategoryRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->subcategoryRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('subcategory.create')->with("departments", $departments)->with("category", $category)->with("categorySelected", $categorySelected)->with('category_count',$category_count)->with("customFields", isset($html) ? $html : false);
    }


    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateSubcategoryRequest $request)
    {
        $input = $request->all();
        try {
            $input['created_by']  = auth()->user()->id;
            $subcategory = $this->subcategoryRepository->create($input);
            if(isset($input['image']) && $input['image']){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($subcategory, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.subcategory')]));
        if ($request->ajax()) {
            return $subcategory;
        }
        return redirect(route('subcategory.index'));
    }

 /**
     * Display the specified Category.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $subcategory = $this->subcategoryRepository->findWithoutFail($id);
        if (empty($subcategory)) {
            Flash::error('Subcategory not found');

            return redirect(route('subcategory.index'));
        }
        if($request->ajax()) {
            return ['status' => 'success', 'data' => $subcategory];           
        }
        return view('subcategory.show')->with('subcategory', $subcategory);
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
        $subcategory = $this->subcategoryRepository->findWithoutFail($id);
        
         $departments  = $this->departmentsRepository->pluck('name', 'id');
         //$departments->prepend("Please Select",0);
         
         $category = $this->categoryRepository->where('id',$subcategory->category_id)->pluck('name', 'id');
         $categorySelected = $subcategory->category_id;
        
         $category = $this->categoryRepository->pluck('name', 'id');

         $category_count  = $this->categoryRepository->max('id');

        if (empty($subcategory)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.subcategory')]));

            return redirect(route('subcategory.index'));
        }
        $customFieldsValues = $subcategory->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->subcategoryRepository->model());
        $hasCustomField = in_array($this->subcategoryRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('subcategory.edit')->with('subcategory', $subcategory)->with('departments', $departments)->with("category", $category)->with("categorySelected", $categorySelected)->with('category_count',$category_count)->with("customFields", isset($html) ? $html : false);
    }


   /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSubcategoryRequest $request)
    {
        $subcategory = $this->subcategoryRepository->findWithoutFail($id);

        if (empty($subcategory)) {
            Flash::error('Subcategory not found');
            return redirect(route('subcategory.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->subcategoryRepository->model());
        try {
            if(isset($request->category_name))
            {
               
                 $category_create=array('name'=>$request->category_name,'description'=>$request->category_name);
                 $category_id = DB::table('categories')->insertGetId($category_create);

                 $input['category_id'] = $category_id;

            }
            $input['updated_by'] = auth()->user()->id;
            $subcategory = $this->subcategoryRepository->update($input, $id);
            
            if(isset($input['image']) && $input['image']){
    $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
    $mediaItem = $cacheUpload->getMedia('image')->first();
    $mediaItem->copy($subcategory, 'image');
}
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $subcategory->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.subcategory')]));

        return redirect(route('subcategory.index'));
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
        $subcategory = $this->subcategoryRepository->findWithoutFail($id);

        if (empty($subcategory)) {
            Flash::error('Subcategory not found');

            return redirect(route('subcategory.index'));
        }

        $this->subcategoryRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.subcategory')]));

        return redirect(route('subcategory.index'));
    }

        /**
     * Remove Media of Category
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $subcategory = $this->subcategoryRepository->findWithoutFail($input['id']);
        try {
            if($subcategory->hasMedia($input['collection'])){
                $subcategory->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
