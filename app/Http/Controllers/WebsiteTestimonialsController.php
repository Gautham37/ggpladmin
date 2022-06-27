<?php

namespace App\Http\Controllers;

use App\DataTables\WebsiteTestimonialsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateWebsiteTestimonialsRequest;
use App\Http\Requests\UpdateWebsiteTestimonialsRequest;
use App\Repositories\WebsiteTestimonialsRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class WebsiteTestimonialsController extends Controller
{
    /** @var  WebsiteTestimonialsRepository */
    private $websiteTestimonialsRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
private $uploadRepository;

    public function __construct(WebsiteTestimonialsRepository $websiteTestimonialsRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->websiteTestimonialsRepository = $websiteTestimonialsRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param WebsiteTestimonialsDataTable $categoryDataTable
     * @return Response
     */
    public function index(WebsiteTestimonialsDataTable $websiteTestimonialsDataTable)
    {
        return $websiteTestimonialsDataTable->render('settings.testimonials.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        $hasCustomField = in_array($this->websiteTestimonialsRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->websiteTestimonialsRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('settings.testimonials.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateWebsiteTestimonialsRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->websiteTestimonialsRepository->model());
        try {
            $testimonial = $this->websiteTestimonialsRepository->create($input);
            $testimonial->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
            if(isset($input['image']) && $input['image']){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($testimonial, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.website_testimonials')]));

        return redirect(route('websiteTestimonials.index'));
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
        $testimonials = $this->websiteTestimonialsRepository->findWithoutFail($id);

        if (empty($testimonials)) {
            Flash::error('Testimonial not found');

            return redirect(route('websiteTestimonials.index'));
        }

        return view('settings.testimonials.show')->with('testimonials', $testimonials);
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
        $testimonial = $this->websiteTestimonialsRepository->findWithoutFail($id);
        if (empty($testimonial)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.website_testimonials')]));

            return redirect(route('websiteTestimonials.index'));
        }
        $customFieldsValues = $testimonial->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->websiteTestimonialsRepository->model());
        $hasCustomField = in_array($this->websiteTestimonialsRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('settings.testimonials.edit')->with('testimonial', $testimonial)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateWebsiteTestimonialsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWebsiteTestimonialsRequest $request)
    {
        $testimonial = $this->websiteTestimonialsRepository->findWithoutFail($id);

        if (empty($testimonial)) {
            Flash::error('Testimonial not found');
            return redirect(route('websiteTestimonials.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->websiteTestimonialsRepository->model());
        try {
            $testimonial = $this->websiteTestimonialsRepository->update($input, $id);
            
            if(isset($input['image']) && $input['image']){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($testimonial, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $testimonial->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.website_testimonials')]));

        return redirect(route('websiteTestimonials.index'));
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
        $testimonial = $this->websiteTestimonialsRepository->findWithoutFail($id);

        if (empty($testimonial)) {
            Flash::error('Testimonial not found');

            return redirect(route('websiteTestimonials.index'));
        }

        $this->websiteTestimonialsRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.website_testimonials')]));

        return redirect(route('settings.testimonials.index'));
    }

        /**
     * Remove Media of Category
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $testimonial = $this->websiteTestimonialsRepository->findWithoutFail($input['id']);
        try {
            if($testimonial->hasMedia($input['collection'])){
                $testimonial->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
