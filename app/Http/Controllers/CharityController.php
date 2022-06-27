<?php

namespace App\Http\Controllers;

use App\DataTables\CharityDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateCharityRequest;
use App\Http\Requests\UpdateCharityRequest;
use App\Repositories\CharityRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class CharityController extends Controller
{
    /**
     * @var CharityRepository
     */
    private $charityRepository;

    public function __construct(CharityRepository $charityRepo)
    {
        parent::__construct();
        $this->charityRepository = $charityRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CharityDatatable $categoryDataTable
     * @return Response
     */
    public function index(CharityDataTable $charityDataTable)
    {
        return $charityDataTable->render('charity.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        return view('charity.create');
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateCharityRequest $request)
    {
        $input = $request->all();
        try {
            $charity = $this->charityRepository->create($input);            
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success('Saved successfully',['operator' => 'Charity']);
        if ($request->ajax()) {
            return $charity;
        }
        return redirect(route('charity.index'));
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
        $charity = $this->charityRepository->findWithoutFail($id);
        if (empty($charity)) {
            Flash::error('Charity not found');
            return redirect(route('charity.index'));
        }
        return view('charity.show')->with('charity', $charity);
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
        $charity = $this->charityRepository->findWithoutFail($id);
        if (empty($charity)) {
            Flash::error(__('Not found',['operator' => 'Charity']));
            return redirect(route('charity.index'));
        }
        return view('charity.edit')->with('charity', $charity);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id,UpdateCharityRequest $request)
    {
        $charity = $this->charityRepository->findWithoutFail($id);
        if (empty($charity)) {
            Flash::error('Charity not found');
            return redirect(route('charity.index'));
        }
        $input = $request->all();
        try {
            $charity = $this->charityRepository->update($input, $id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success('Updated Successfully',['operator' => 'Charity']);
        return redirect(route('charity.index'));
    }

    /**
     * Remove the specified Charity from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $charity = $this->charityRepository->findWithoutFail($id);
        if (empty($charity)) {
            Flash::error('Charity not found');
            return redirect(route('charity.index'));
        }
        $this->charityRepository->delete($id);
        Flash::success('Deleted Successfully',['operator' => 'Charity']);
        return redirect(route('charity.index'));
    }

    
}
