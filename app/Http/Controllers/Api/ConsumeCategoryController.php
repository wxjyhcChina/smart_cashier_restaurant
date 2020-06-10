<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Api\ConsumeCategory\ConsumeCategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConsumeCategoryController extends Controller
{
    /**
     * @var ConsumeCategoryRepository
     */
    private $consumeCategoryRepo;

    /**
     * ConsumeCategoryController constructor.
     * @param $consumeCategoryRepo
     */
    public function __construct(ConsumeCategoryRepository $consumeCategoryRepo)
    {
        $this->consumeCategoryRepo = $consumeCategoryRepo;
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
        //Log::info("user:".$user->shop_id);
        $consumeCategories = $this->consumeCategoryRepo->getByShop($user->shop_id);

        return $this->responseSuccess($consumeCategories);
    }
}
