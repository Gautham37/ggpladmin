<?php

namespace App\Http\Controllers;

use App\DataTables\DriverReviewDataTable;
use App\Http\Requests;
use App\Repositories\DriverReviewRepository;
use App\Repositories\OrderRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class DriverReviewController extends Controller
{
    /** @var  LoyalityPointsRepository */
    private $driverReviewRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
private $uploadRepository;

    public function __construct(DriverReviewRepository $driverReviewRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->driverReviewRepository = $driverReviewRepo;
        $this->customFieldRepository   = $customFieldRepo;
        $this->uploadRepository        = $uploadRepo;
    }

    /**
     * Display a listing of the Rewards.
     *
     * @param LoyalityPointDataTable $loyalityPointDataTable
     * @return Response
     */
    public function index(DriverReviewDataTable $driverReviewDataTable)
    {
        return $driverReviewDataTable->render('drivers_reviews.index');
    }

   
}
