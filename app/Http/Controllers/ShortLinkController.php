<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateShortLinkRequest;
use App\Repositories\ShortLinkRepository;
use App\Repositories\CustomFieldRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class ShortLinkController extends Controller
{   

    /** @var  ShortLinkRepository */
    private $shortlinkRepository;

    public function __construct(ShortLinkRepository $shortLinkRepo)
    {
        parent::__construct();
        $this->shortlinkRepository = $shortLinkRepo;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateShortLinkRequest $request)
    {
        //$input = $request->all();
        $input['link'] = $request->link;
        $input['code'] = str_random(6);
        try {
            $shortLink = $this->shortlinkRepository->create($input);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        //Flash::success(__('lang.saved_successfully',['operator' => __('lang.category')]));
        //return redirect(route('categories.index'));
    }

    public function shortenLink($code)
    {
        $find = $this->shortlinkRepository->where('code', $code)->first();
        //if(strtotime($find->expiry_date) > strtotime(date('Y-m-d'))) {
            return redirect($find->link);    
        //} else {
        //    return $this->sendResponse('failiure', 'Link Expired');
        //}   
    }

}
