<?php

namespace App\Http\Controllers\API;


use App\Models\FaqCategory;
use App\Repositories\FaqCategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;

/**
 * Class FaqCategoryController
 * @package App\Http\Controllers\API
 */

class FaqCategoryAPIController extends Controller
{
    /** @var  FaqCategoryRepository */
    private $faqCategoryRepository;

    public function __construct(FaqCategoryRepository $faqCategoryRepo)
    {
        $this->faqCategoryRepository = $faqCategoryRepo;
    }

    /**
     * Display a listing of the FaqCategory.
     * GET|HEAD /faqCategories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->faqCategoryRepository->pushCriteria(new RequestCriteria($request));
            $this->faqCategoryRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $faqCategories = $this->faqCategoryRepository->all();

        return $this->sendResponse($faqCategories->toArray(), 'Faq Categories retrieved successfully');
    }

    /**
     * Display the specified FaqCategory.
     * GET|HEAD /faqCategories/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var FaqCategory $faqCategory */
        if (!empty($this->faqCategoryRepository)) {
            $faqCategory = $this->faqCategoryRepository->findWithoutFail($id);
        }

        if (empty($faqCategory)) {
            return $this->sendError('Faq Category not found');
        }

        return $this->sendResponse($faqCategory->toArray(), 'Faq Category retrieved successfully');
    }
    
    public function getFaqs(Request $request)
    {
         try {
            $this->validate($request, [
                'app_type' => 'required',
            ]);
            
         $app_type = $request->input('app_type');
        
         $faqs = $this->faqCategoryRepository->leftJoin('faqs', 'faq_categories.id', '=', 'faqs.faq_category_id')->where('app_type',$app_type)->get();
         
          return $this->sendResponse($faqs->toArray(), 'Faq Categories retrieved successfully');
          
          } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }
      
    }
}
