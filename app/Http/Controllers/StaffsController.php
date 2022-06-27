<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\DataTables\StaffDataTable;
use App\Repositories\UserRepository;
use App\Repositories\MarketRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DataTables;
use Carbon\Carbon;
use DB;
use DateTime;
use Config;

class StaffsController extends Controller
{   
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var MarketRepository
     */
    private $marketRepository;
    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(MarketRepository $marketRepo, UserRepository $userRepo, UploadRepository $uploadRepo) {
        $this->marketRepository             = $marketRepo;
        $this->userRepository               = $userRepo; 
        $this->uploadRepository             = $uploadRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StaffDataTable $staffDataTable)
    {
        return $staffDataTable->render('staffs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
