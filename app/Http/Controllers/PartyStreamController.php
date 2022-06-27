<?php

namespace App\Http\Controllers;


use App\DataTables\PartyStreamDataTable;
use App\Http\Requests;
use App\Repositories\CustomFieldRepository;
use App\Repositories\PartyStreamRepository;
use App\Repositories\UploadRepository;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;

class PartyStreamController extends Controller
{
    /** @var  PartystreamRepository */
    private $partystreamRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(PartyStreamRepository $partystreamRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->partystreamRepository = $partystreamRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

    public function index(PartyStreamDataTable $partyStreamDataTable)
    {
        return $partyStreamDataTable->render('partystream.index');
    }

    public function create()
    {

        $hasCustomField = in_array($this->partystreamRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField){
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->partystreamRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('partystream.create')->with("customFields", isset($html) ? $html : false);
    }


    public function store(Requests\CreatePartyStreamRequest $request)
    {

        $input = $request->all();

        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->partystreamRepository->model());
        try {
            $input['created_by']  = auth()->user()->id;
            $partystream = $this->partystreamRepository->create($input);
            $partystream->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
            // if(isset($input['image']) && $input['image']) {
            //     $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
            //     $mediaItem = $cacheUpload->getMedia('image')->first();
            //     $mediaItem->copy($expenses_category, 'image');
            // }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.partystream')]));
        if ($request->ajax()) {
            return $partystream;
        }
        // return redirect(route('partystream.index'));
        Return Redirect()->back();
    }


    public function show($id)
    {
        $partystreams = $this->partystreamRepository->findWithoutFail($id);

        if (empty($partystreams)) {
            Flash::error('Party stream not found');
            return redirect(route('partystream.index'));
        }

        return view('partystream.show')->with('partystream', $partystreams);
    }

    public function edit($id)
    {
        $partystream = $this->partystreamRepository->findWithoutFail($id);

        if (empty($partystream)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.category')]));

            return redirect(route('categories.index'));
        }
        $customFieldsValues = $partystream->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->partystreamRepository->model());
        $hasCustomField = in_array($this->partystreamRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('partystream.edit')->with('partystream', $partystream)->with("customFields", isset($html) ? $html : false);
    }


    public function update($id, Requests\UpdatePartyStreamRequest $request)
    {
        $partystream = $this->partystreamRepository->findWithoutFail($id);

        if (empty($partystream)) {
            Flash::error('Stream not found');
            return redirect(route('partystream.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->partystreamRepository->model());
        try {
            $input['updated_by'] = auth()->user()->id;
            $partystream = $this->partystreamRepository->update($input, $id);

            // if(isset($input['image']) && $input['image']){
            //     $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
            //     $mediaItem = $cacheUpload->getMedia('image')->first();
            //     $mediaItem->copy($customer_groups, 'image');
            // }
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $partystream->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.partystream')]));

        return redirect(route('partystream.index'));
    }


    public function destroy($id)
    {
        $partystream = $this->partystreamRepository->findWithoutFail($id);

        if (empty($partystream)) {
            Flash::error('Party stream not found');

            return redirect(route('partystream.index'));
        }

        $this->partystreamRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.partystream')]));

        return redirect(route('partystream.index'));
    }


    // public function removeMedia(Request $request)
    // {
    //     $input = $request->all();
    //     $category = $this->customerLevelRepository->findWithoutFail($input['id']);
    //     try {
    //         if($category->hasMedia($input['collection'])){
    //             $category->getFirstMedia($input['collection'])->delete();
    //         }
    //     } catch (\Exception $e) {
    //         Log::error($e->getMessage());
    //     }
    // }
}
