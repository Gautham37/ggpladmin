<?php

namespace App\Http\Controllers;

use App\DataTables\ExpensesCategoryDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateExpensesCategoryRequest;
use App\Http\Requests\UpdateExpensesCategoryRequest;
use App\Repositories\ExpensesCategoryRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class ExpensesCategoryController extends Controller
{
    /** @var  CategoryRepository */
    private $categoryRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
private $uploadRepository;

    public function __construct(ExpensesCategoryRepository $categoryRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->categoryRepository = $categoryRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     */
    public function index(ExpensesCategoryDataTable $expensesCategoryDataTable)
    {
        return $expensesCategoryDataTable->render('expenses_category.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        
        
        $hasCustomField = in_array($this->categoryRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('expenses_category.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateExpensesCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateExpensesCategoryRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
        try {
            $input['created_by']  = auth()->user()->id;
            $expenses_category = $this->categoryRepository->create($input);
            $expenses_category->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
            if(isset($input['image']) && $input['image']){
    $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
    $mediaItem = $cacheUpload->getMedia('image')->first();
    $mediaItem->copy($expenses_category, 'image');
}
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.category')]));

       // return redirect(route('expensesCategory.index'));
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
        $expenses_category = $this->categoryRepository->findWithoutFail($id);

        if (empty($expenses_category)) {
            Flash::error('Expenses Category not found');

            return redirect(route('expensesCategory.index'));
        }

        return view('expenses_category.show')->with('expenses_category', $expenses_category);
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
        $expenses_category = $this->categoryRepository->findWithoutFail($id);
        
        

        if (empty($expenses_category)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.category')]));

            return redirect(route('categories.index'));
        }
        $customFieldsValues = $expenses_category->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
        $hasCustomField = in_array($this->categoryRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('expenses_category.edit')->with('expenses_category', $expenses_category)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateExpensesCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateExpensesCategoryRequest $request)
    {
        $expenses_category = $this->categoryRepository->findWithoutFail($id);

        if (empty($expenses_category)) {
            Flash::error('Category not found');
            return redirect(route('expensesCategory.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->categoryRepository->model());
        try {
            $input['updated_by'] = auth()->user()->id;
            $expenses_category = $this->categoryRepository->update($input, $id);
            
            if(isset($input['image']) && $input['image']){
    $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
    $mediaItem = $cacheUpload->getMedia('image')->first();
    $mediaItem->copy($expenses_category, 'image');
}
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $expenses_category->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.category')]));

        return redirect(route('expensesCategory.index'));
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
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('expensesCategory.index'));
        }

        $this->categoryRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.category')]));

        return redirect(route('expensesCategory.index'));
    }

        /**
     * Remove Media of Category
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $category = $this->categoryRepository->findWithoutFail($input['id']);
        try {
            if($category->hasMedia($input['collection'])){
                $category->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
