<?php

namespace App\Http\Controllers;

use App\DataTables\ExpensesDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateExpensesRequest;
use App\Http\Requests\UpdateExpensesRequest;
use App\Repositories\ExpensesCategoryRepository;
use App\Repositories\ExpensesRepository;
use App\Repositories\ExpenseItemsRepository;
use App\Repositories\PaymentModeRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Flash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Models\Expenses;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Input;

class ExpensesController extends Controller
{
    
    /** @var  CategoryRepository */
    private $expensesCategoryRepository;

    /** @var  ExpensesRepository */
    private $expensesRepository;

    /** @var  ExpenseItemsRepository */
    private $expenseItemsRepository;

    /* @var PaymentModeRepository */
    private $paymentModeRepository;

    /* @var UserRepository */
    private $userRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;
    
    private $roleRepository;

    /**
  * @var UploadRepository
  */
    private $uploadRepository;

    public function __construct(ExpensesCategoryRepository $expensesCatRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo, PaymentModeRepository $paymentMRepo, ExpensesRepository $expensesRepo, RoleRepository $roleRepo, UserRepository $userRepo, ExpenseItemsRepository $expenseItemsRepo)
    {
        parent::__construct();
        $this->expensesCategoryRepository    = $expensesCatRepo;
        $this->customFieldRepository         = $customFieldRepo;
        $this->uploadRepository              = $uploadRepo;
        $this->paymentModeRepository         = $paymentMRepo;
        $this->expenseRepository             = $expensesRepo;
        $this->userRepository                = $userRepo;
        $this->roleRepository                = $roleRepo;
        $this->expenseItemsRepository        = $expenseItemsRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExpensesDataTable $expensesDataTable)
    {
        $categories = $this->expensesCategoryRepository->pluck('name', 'id');
        $categories->prepend('Select Expense Category',null);
        $expenses = $expensesDataTable;
        if(Request('start_date')!='' && Request('end_date')!='' ) {
           $expenses->with('start_date',Request('start_date'))->with('end_date',Request('end_date'));  
        }
        if(Request('category')!='') {
           $expenses->with('category',Request('category')); 
        }
        return $expenses->render('expenses.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expenses_category   = $this->expensesCategoryRepository->pluck('name','id');
        $expenses_category->prepend("Please Select", null);
        
        $payment_mode        = $this->paymentModeRepository->pluck('name','id');
        $payment_mode->prepend("Please Select", null);
        
        $users = $this->userRepository->pluck('name','id');
        $users->prepend('Please Select', null);
        
        return view('expenses.create',compact('expenses_category','payment_mode','users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateExpensesRequest $request)
    {
        $input = $request->all();
        try {
            $input['created_by'] = auth()->user()->id;
            $expense             = $this->expenseRepository->create($input);
            
            if($expense) {
                for($i=0; $i<count($input['name']); $i++) :
                    $items = array(
                        'expense_id'  => $expense->id,
                        'name'        => $input['name'][$i],
                        'quantity'    => $input['quantity'][$i],
                        'rate'        => $input['rate'][$i],
                        'amount'      => $input['amount'][$i],
                        'created_by'  => auth()->user()->id,
                    );
                    $expense_item = $this->expenseItemsRepository->create($items);
                endfor;
            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($expense, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Expense')]));
        return redirect(route('expenses.index'));    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $expense = $this->expenseRepository->with('items')->with('expensecategory')->with('paymentmode')->findWithoutFail($id);
        if (empty($expense)) {
            Flash::error(__('Not Found',['operator' => __('Expense')]));
            return redirect(route('expenses.index'));
        }
        if($request->ajax()) {
            return ['status' => 'success', 'data' => $expense]; 
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense = $this->expenseRepository->findWithoutFail($id);
        if (empty($expense)) {
            Flash::error('Expense not found');
            return redirect(route('expenses.index'));
        }

        $expenses_category   = $this->expensesCategoryRepository->pluck('name','id');
        $expenses_category->prepend("Please Select", null);
        
        $payment_mode        = $this->paymentModeRepository->pluck('name','id');
        $payment_mode->prepend("Please Select", null);
        
        $users = $this->userRepository->pluck('name','id');
        $users->prepend('Please Select', null);
        
        return view('expenses.edit',compact('expenses_category','payment_mode','users','expense'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateExpensesRequest $request)
    {
        $input = $request->all();
        try {
            $input['updated_by'] = auth()->user()->id;
            $expense             = $this->expenseRepository->update($input,$id);
            
            if($expense) {
                for($i=0; $i<count($input['name']); $i++) :
                    $items = array(
                        'expense_id'  => $expense->id,
                        'name'        => $input['name'][$i],
                        'quantity'    => $input['quantity'][$i],
                        'rate'        => $input['rate'][$i],
                        'amount'      => $input['amount'][$i],
                        'created_by'  => auth()->user()->id,
                    );
                    if(isset($input['expense_item_id'][$i]) && $input['expense_item_id'][$i] > 0) {
                        $expense_item = $this->expenseItemsRepository->update($items,$input['expense_item_id'][$i]);
                    } else {
                        $expense_item = $this->expenseItemsRepository->create($items);
                    }
                endfor;

                if(isset($input['delete_item_id']) && count($input['delete_item_id']) > 0) {
                    foreach($input['delete_item_id'] as $deleteid) {
                        $this->expenseItemsRepository->delete($deleteid);
                    }
                } 
            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($expense, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Expense')]));
        return redirect(route('expenses.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense = $this->expenseRepository->findWithoutFail($id);
        if (empty($expense)) {
            Flash::error('Expense not found');
            return redirect(route('expenses.index'));
        }
        $this->expenseRepository->delete($id);
        Flash::success(__('Deleted successfully',['operator' => __('Expense')]));
        return redirect(route('expenses.index'));
    }

}
