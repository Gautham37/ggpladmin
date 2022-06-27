<?php

namespace App\Http\Controllers\API;


use App\Models\FarmerSlide;
use App\Repositories\FarmerSlideRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;

/**
 * Class FarmerSlideAPIController
 * @package App\Http\Controllers\API
 */

class FarmerSlideAPIController extends Controller
{
    /** @var  FarmerSlideRepository */
    private $farmerSlideRepository;

    public function __construct(FarmerSlideRepository $farmerSlideRepo)
    {
        $this->farmerSlideRepository = $farmerSlideRepo;
    }

    /**
     * Display a listing of the Slide.
     * GET|HEAD /slides
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->farmerSlideRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            Flash::error($e->getMessage());
        }
        $slides = $this->farmerSlideRepository->all();

        return $this->sendResponse($slides->toArray(), 'Farmer Slides retrieved successfully');
    }

    /**
     * Display the specified Slide.
     * GET|HEAD /farmer_slides/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Slide $slide */
        if (!empty($this->farmerSlideRepository)) {
            $slide = $this->farmerSlideRepository->findWithoutFail($id);
        }

        if (empty($slide)) {
            return $this->sendError('Slide not found');
        }

        return $this->sendResponse($slide->toArray(), 'Farmer Slide retrieved successfully');
    }
}
