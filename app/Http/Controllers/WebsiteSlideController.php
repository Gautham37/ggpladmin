<?php
/**
 * File name: WebsiteSlideController.php
 * Last modified: 2020.09.12 at 20:01:58
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers;

use App\DataTables\WebsiteSlideDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateWebsiteSlideRequest;
use App\Http\Requests\UpdateWebsiteSlideRequest;
use App\Repositories\WebsiteSlideRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class WebsiteSlideController extends Controller
{
    /** @var  WebsiteSlideRepository */
    private $websiteSlideRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(WebsiteSlideRepository $websiteSlideRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->websiteSlideRepository = $websiteSlideRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the Slide.
     *
     * @param WebsiteSlideDataTable $websiteSlideDataTable
     * @return Response
     */
    public function index(WebsiteSlideDataTable $websiteSlideDataTable)
    {
        return $websiteSlideDataTable->render('website_slides.index');
    }

    /**
     * Show the form for creating a new Slide.
     *
     * @return Response
     */
    public function create()
    {
        $hasCustomField = in_array($this->websiteSlideRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->websiteSlideRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('website_slides.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created Slide in storage.
     *
     * @param CreateWebsiteSlideRequest $request
     *
     * @return Response
     */
    public function store(CreateWebsiteSlideRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->websiteSlideRepository->model());
        try {
            $websiteSlide = $this->websiteSlideRepository->create($input);
            $websiteSlide->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($websiteSlide, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.slide')]));

        return redirect(route('websiteSlides.index'));
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
        $website_slide = $this->websiteSlideRepository->findWithoutFail($id);

        if (empty($websiteSlide)) {
            Flash::error('Slide not found');

            return redirect(route('websiteSlides.index'));
        }

        return view('website_slides.show')->with('website_slide', $website_slide);
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
        $website_slide = $this->websiteSlideRepository->findWithoutFail($id);
        if (empty($website_slide)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.slide')]));

            return redirect(route('websiteSlides.index'));
        }
        $customFieldsValues = $website_slide->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->websiteSlideRepository->model());
        $hasCustomField = in_array($this->websiteSlideRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('website_slides.edit')->with('slide', $website_slide)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Slide in storage.
     *
     * @param int $id
     * @param UpdateSlideRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWebsiteSlideRequest $request)
    {
        $website_slide = $this->websiteSlideRepository->findWithoutFail($id);

        if (empty($website_slide)) {
            Flash::error('Slide not found');
            return redirect(route('websiteSlides.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->websiteSlideRepository->model());
        try {
            $website_slide = $this->websiteSlideRepository->update($input, $id);

            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($website_slide, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $website_slide->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.slide')]));

        return redirect(route('websiteSlides.index'));
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
        $website_slide = $this->websiteSlideRepository->findWithoutFail($id);

        if (empty($website_slide)) {
            Flash::error('Slide not found');

            return redirect(route('websiteSlides.index'));
        }

        $this->websiteSlideRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.slide')]));

        return redirect(route('websiteSlides.index'));
    }

    /**
     * Remove Media of Slide
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $website_slide = $this->websiteSlideRepository->findWithoutFail($input['id']);
        try {
            if ($website_slide->hasMedia($input['collection'])) {
                $website_slide->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
