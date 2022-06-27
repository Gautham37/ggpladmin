<?php

namespace App\Http\Controllers;

use App\DataTables\CustomerFarmerReviewsDataTable;
use App\Http\Requests;
use App\Repositories\CustomerFarmerReviewsRepository;
use App\Repositories\OrderRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class CustomerFarmerReviewsController extends Controller
{
    /** @var  CustomerFarmerReviewsRepository */
    private $customerFarmerReviewsRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
private $uploadRepository;

    public function __construct(CustomerFarmerReviewsRepository $customerFarmerReviewsRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->customerFarmerReviewsRepository = $customerFarmerReviewsRepo;
        $this->customFieldRepository   = $customFieldRepo;
        $this->uploadRepository        = $uploadRepo;
    }

    /**
     * Display a listing of the Rewards.
     *
     * @param LoyalityPointDataTable $loyalityPointDataTable
     * @return Response
     */
    public function index(CustomerFarmerReviewsDataTable $customerFarmerReviewsDataTable)
    {
        return $customerFarmerReviewsDataTable->render('customer_farmer_reviews.index');
    }

   
}
