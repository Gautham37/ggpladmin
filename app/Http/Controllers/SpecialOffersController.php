<?php
/**
 * File name: SpecialOffersController.php
 * Last modified: 2020.09.12 at 20:01:58
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers;

use App\DataTables\SpecialOffersDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateSpecialOffersRequest;
use App\Http\Requests\UpdateSpecialOffersRequest;
use App\Repositories\SpecialOffersRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class SpecialOffersController extends Controller
{
    /** @var  specialOffersRepository */
    private $specialOffersRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(SpecialOffersRepository $specialOffersRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->specialOffersRepository = $specialOffersRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the Slide.
     *
     * @param SpecialOffersDataTable $SpecialOffersDataTable
     * @return Response
     */
    public function index(SpecialOffersDataTable $specialOffersDataTable)
    {
        return $specialOffersDataTable->render('special_offers.index');
    }

    /**
     * Show the form for creating a new Slide.
     *
     * @return Response
     */
    public function create()
    {
        $hasCustomField = in_array($this->specialOffersRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->specialOffersRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('special_offers.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created Slide in storage.
     *
     * @param CreateSpecialOffersRequest $request
     *
     * @return Response
     */
    public function store(CreateSpecialOffersRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->specialOffersRepository->model());
        try {
            $specialOffers = $this->specialOffersRepository->create($input);
            $specialOffers->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($specialOffers, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.slide')]));

        return redirect(route('specialOffers.index'));
    }

    /**
     * Display the specified Slide.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $specialOffers = $this->specialOffersRepository->findWithoutFail($id);

        if (empty($specialOffers)) {
            Flash::error('Special Offers not found');

            return redirect(route('specialOffers.index'));
        }

        return view('special_offers.show')->with('special_offers', $specialOffers);
    }

    /**
     * Show the form for editing the specified Slide.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $special_offer = $this->specialOffersRepository->findWithoutFail($id);
        if (empty($special_offer)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.slide')]));

            return redirect(route('specialOffers.index'));
        }
        $customFieldsValues = $special_offer->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->specialOffersRepository->model());
        $hasCustomField = in_array($this->specialOffersRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('special_offers.edit')->with('special_offers', $special_offer)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Slide in storage.
     *
     * @param int $id
     * @param UpdateSlideRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSpecialOffersRequest $request)
    {
        $special_offer = $this->specialOffersRepository->findWithoutFail($id);

        if (empty($special_offer)) {
            Flash::error('Special Offer not found');
            return redirect(route('specialOffers.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->specialOffersRepository->model());
        try {
            $special_offer = $this->specialOffersRepository->update($input, $id);

            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($special_offer, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $special_offer->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.slide')]));

        return redirect(route('specialOffers.index'));
    }

    /**
     * Remove the specified Slide from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $special_offer = $this->specialOffersRepository->findWithoutFail($id);

        if (empty($special_offer)) {
            Flash::error('Special Offer not found');

            return redirect(route('specialOffers.index'));
        }

        $this->specialOffersRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.slide')]));

        return redirect(route('specialOffers.index'));
    }

    /**
     * Remove Media of Slide
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $special_offer = $this->specialOffersRepository->findWithoutFail($input['id']);
        try {
            if ($special_offer->hasMedia($input['collection'])) {
                $special_offer->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
