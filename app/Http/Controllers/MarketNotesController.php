<?php

namespace App\Http\Controllers;

use App\Models\MarketNotes;
use App\Helper\Reply;
use App\Http\Requests\CreateMarketNoteRequest;
use App\Http\Requests\UpdateMarketNoteRequest;
use App\Repositories\MarketNotesRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DataTables;

class MarketNotesController extends Controller
{   
    /**
     * @var MarketNotesRepository
     */
    private $marketNotesRepository;
    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(MarketNotesRepository $marketNotesRepo, UploadRepository $uploadRepo) {
        $this->marketNotesRepository     = $marketNotesRepo;
        $this->uploadRepository          = $uploadRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $id   = $request->market_id;
            $data = $this->marketNotesRepository->where('market_id',$id)->orderBy('created_at','desc')->get();
            $view =  view('market_notes.summary_data', compact('data'))->render();
            return ['status' => 'success', 'data' => $view];
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMarketNoteRequest $request)
    {
        $input = $request->all();
        try {
            $input['created_by'] = auth()->user()->id;
            $marketNote = $this->marketNotesRepository->create($input);
            
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($marketNote, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Market Note')]));
        return redirect(route('marketNotes.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectNotes $projectNotes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $marketNote = $this->marketNotesRepository->findWithoutFail($id);
        if (empty($marketNote)) {
            Flash::error(__('Not Found',['operator' => __('Market Note')]));
            return redirect(route('marketNotes.index'));
        }
        return view('marketNotes.index')->with('marketNote',$marketNote);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateMarketNoteRequest $request)
    {
        $market_note = $this->marketNotesRepository->findWithoutFail($id);
        if (empty($market_note)) {
            Flash::error('Market Note not found');
            return redirect(route('marketNotes.index'));
        }
        $input = $request->all();
        try {
            $market_note = $this->marketNotesRepository->update($input, $id);
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($market_note, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('Updated successfully',['operator' => __('Market Notes')]));
        return redirect(route('marketNotes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $market_note = $this->marketNotesRepository->findWithoutFail($id);
        if (empty($market_note)) {
            Flash::error('Market Note not found');
            return redirect(route('marketNotes.index'));
        }
        $this->marketNotesRepository->delete($id);

        Flash::success(__('Deleted successfully',['operator' => __('Market Notes')]));
        return redirect(route('marketNotes.index'));
    }
}
