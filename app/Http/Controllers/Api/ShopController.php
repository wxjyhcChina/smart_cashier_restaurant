<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Api\Shop\ShopRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * @var ShopRepository
     */
    private $shopRepo;

    /**
     * ShopController constructor.
     * @param $shopRepo
     */
    public function __construct(ShopRepository $shopRepo)
    {
        $this->shopRepo = $shopRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::User();

        $shops = $this->shopRepo->getByRestaurant($user->restaurant_id);

        return $this->responseSuccess($shops);
    }
}
