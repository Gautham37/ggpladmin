<?php

namespace App\Http\Controllers\API;


use App\Http\Requests\UpdateFavoriteRequest;
use App\Models\Favorite;
use App\Repositories\FavoriteRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class FavoriteController
 * @package App\Http\Controllers\API
 */

class FavoriteAPIController extends Controller
{
    /** @var  FavoriteRepository */
    private $favoriteRepository;

    public function __construct(FavoriteRepository $favoriteRepo)
    {
        $this->favoriteRepository = $favoriteRepo;
    }

    /**
     * Display a listing of the Favorite.
     * GET|HEAD /favorites
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->favoriteRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $favorites = $this->favoriteRepository->all();

        return $this->sendResponse($favorites->toArray(), 'Favorites retrieved successfully');
    }


    /**
     * Display the specified Favorite.
     * GET|HEAD /favorites/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Favorite $favorite */
        if (!empty($this->favoriteRepository)) {
            $favorite = $this->favoriteRepository->findWithoutFail($id);
        }
        if (empty($favorite)) {
            return $this->sendError('Favorite not found');
        }
        return $this->sendResponse($favorite->toArray(), 'Favorite retrieved successfully');
    }

    /**
     * Store a newly created Favorite in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        try {
            $favorite = $this->favoriteRepository->updateOrCreate($request->only('user_id','product_id'),$input);
        } catch (ValidatorException $e) {
            return $this->sendError('Favorite not found');
        }

        return $this->sendResponse($favorite->toArray(),'Favorite stored successfully');
    }

    /**
     * Store a newly created Favorite in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function exist(Request $request)
    {
        $input = $request->only('product_id','user_id');
        try {
            $favorites = $this->favoriteRepository->findWhere($input);
        } catch (ValidatorException $e) {
            return $this->sendError('Favorite not found');
        }
        return $this->sendResponse($favorites->first(), 'Favorite already exist');
    }

    /**
     * Update the specified Favorite in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $favorite = $this->favoriteRepository->findWithoutFail($id);
        if (empty($favorite)) {
            return $this->sendError('Favorite not found');
        }
        $input = $request->all();
        try {
            $favorite = $this->favoriteRepository->update($input, $id);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($favorite->toArray(),'Favorite Updated Successfully');

    }

    /**
     * Remove the specified Favorite from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $favorite = $this->favoriteRepository->findWithoutFail($id);
        if (empty($favorite)) {
            return $this->sendError('Favorite not found');
        }
        $this->favoriteRepository->delete($id);
        return $this->sendResponse($favorite, 'Favorite Deleted Successfully');

    }
}
