<?php

namespace App\Http\Controllers;

use App\DataTables\DeliveryTipsDataTable;
use App\Http\Requests;
use App\Repositories\DeliveryTipsRepository;
use App\Repositories\OrderRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class DeliveryTipsController extends Controller
{
    /** @var  DeliveryTipsRepository */
    private $deliveryTipsRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
private $uploadRepository;

    public function __construct(DeliveryTipsRepository $deliveryTipsRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->deliveryTipsRepository = $deliveryTipsRepo;
        $this->customFieldRepository   = $customFieldRepo;
        $this->uploadRepository        = $uploadRepo;
    }

    /**
     * Display a listing of the Rewards.
     *
     * @param LoyalityPointDataTable $loyalityPointDataTable
     * @return Response
     */
    public function index(DeliveryTipsDataTable $deliveryTipsDataTable)
    {
        return $deliveryTipsDataTable->render('delivery_tips.index');
    }

   
}
