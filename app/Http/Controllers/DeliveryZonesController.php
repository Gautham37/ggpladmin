<?php

namespace App\Http\Controllers;

use App\DataTables\DeliveryZonesDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateDeliveryZonesRequest;
use App\Http\Requests\UpdateDeliveryZonesRequest;
use App\Repositories\DeliveryZonesRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class DeliveryZonesController extends Controller
{
    /** @var  DeliveryZonesRepository */
    private $deliveryZonesRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
private $uploadRepository;

    public function __construct(DeliveryZonesRepository $deliveryZonesRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->deliveryZonesRepository = $deliveryZonesRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the DeliveryZones.
     *
     * @param CategoryDataTable $deliveryZonesDataTable
     * @return Response
     */
    public function index(DeliveryZonesDataTable $deliveryZonesDataTable)
    {
        return $deliveryZonesDataTable->render('delivery_zones.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {   
        $hasCustomField = in_array($this->deliveryZonesRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->deliveryZonesRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('delivery_zones.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateDeliveryZonesRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->deliveryZonesRepository->model());
        try {
            $input['created_by']  = auth()->user()->id;
            $category = $this->deliveryZonesRepository->create($input);
            $category->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
            if(isset($input['image']) && $input['image']){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($category, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.delivery_zones')]));

        return redirect(route('deliveryZones.index'));
    }

    /**
     * Display the specified delivery_zones.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $deliveryZones = $this->deliveryZonesRepository->findWithoutFail($id);

        if (empty($deliveryZones)) {
            Flash::error('Delivery Zone not found');

            return redirect(route('deliveryZones.index'));
        }

        return view('delivery_zones.show')->with('delivery_zones', $deliveryZones);
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
        $deliveryZones = $this->deliveryZonesRepository->findWithoutFail($id);
        if (empty($deliveryZones)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.delivery_zones')]));

            return redirect(route('deliveryZones.index'));
        }
        $customFieldsValues = $deliveryZones->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->deliveryZonesRepository->model());
        $hasCustomField = in_array($this->deliveryZonesRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('delivery_zones.edit')->with('delivery_zones', $deliveryZones)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDeliveryZonesRequest $request)
    {
        $deliveryZones = $this->deliveryZonesRepository->findWithoutFail($id);

        if (empty($deliveryZones)) {
            Flash::error('Delivery Zone not found');
            return redirect(route('deliveryZones.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->deliveryZonesRepository->model());
        try {
            $input['updated_by'] = auth()->user()->id;
            $category = $this->deliveryZonesRepository->update($input, $id);
            
            if(isset($input['image']) && $input['image']){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($category, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $category->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully',['operator' => __('lang.delivery_zones')]));

        return redirect(route('deliveryZones.index'));
    }

    /**
     * Remove the specified deliveryZones from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $deliveryZones = $this->deliveryZonesRepository->findWithoutFail($id);

        if (empty($deliveryZones)) {
            Flash::error('Delivert Zones not found');

            return redirect(route('deliveryZones.index'));
        }

        $this->deliveryZonesRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.delivery_zones')]));

        return redirect(route('deliveryZones.index'));
    }

}
