<?php

namespace App\Http\Controllers;

use App\DataTables\FarmerSlideDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateFarmerSlideRequest;
use App\Http\Requests\UpdateFarmerSlideRequest;
use App\Repositories\FarmerSlideRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class FarmerSlideController extends Controller
{
    /** @var  FarmerSlideRepository */
    private $farmerSlideRepository;
    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(FarmerSlideRepository $farmerSlideRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->farmerSlideRepository = $farmerSlideRepo;
        $this->uploadRepository      = $uploadRepo;
    }

    /**
     * Display a listing of the Slide.
     *
     * @param WebsiteSlideDataTable $websiteSlideDataTable
     * @return Response
     */
    public function index(FarmerSlideDataTable $farmerSlideDataTable)
    {
        return $farmerSlideDataTable->render('farmer_slides.index');
    }

    /**
     * Show the form for creating a new Slide.
     *
     * @return Response
     */
    public function create()
    {
        return view('farmer_slides.create');
    }

    /**
     * Store a newly created Slide in storage.
     *
     * @param CreateWebsiteSlideRequest $request
     *
     * @return Response
     */
    public function store(CreateFarmerSlideRequest $request)
    {
        $input = $request->all();
        try {
            $farmerSlide = $this->farmerSlideRepository->create($input);
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($farmerSlide, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.slide')]));
        return redirect(route('farmerSlides.index'));
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
        $farmer_slide = $this->farmerSlideRepository->findWithoutFail($id);

        if (empty($farmerSlide)) {
            Flash::error('Farmer not found');
            return redirect(route('farmerSlides.index'));
        }

        return view('farmer_slides.show')->with('farmer_slide', $farmer_slide);
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
        $farmer_slide = $this->farmerSlideRepository->findWithoutFail($id);
        if (empty($farmer_slide)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.slide')]));
            return redirect(route('farmerSlides.index'));
        }
        return view('farmer_slides.edit')->with('slide', $farmer_slide);
    }

    /**
     * Update the specified Slide in storage.
     *
     * @param int $id
     * @param UpdateSlideRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFarmerSlideRequest $request)
    {
        $farmer_slide = $this->farmerSlideRepository->findWithoutFail($id);

        if (empty($farmer_slide)) {
            Flash::error('Slide not found');
            return redirect(route('farmerSlides.index'));
        }
        $input = $request->all();
        try {

            $farmer_slide = $this->farmerSlideRepository->update($input, $id);
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($farmer_slide, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.slide')]));

        return redirect(route('farmerSlides.index'));
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
        $farmer_slide = $this->farmerSlideRepository->findWithoutFail($id);

        if (empty($farmer_slide)) {
            Flash::error('Slide not found');
            return redirect(route('farmerSlides.index'));
        }

        $this->farmerSlideRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.slide')]));
        return redirect(route('farmerSlides.index'));
    }

    /**
     * Remove Media of Slide
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $farmer_slide = $this->farmerSlideRepository->findWithoutFail($input['id']);
        try {
            if ($farmer_slide->hasMedia($input['collection'])) {
                $farmer_slide->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
