<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Api\ConsumeCategory\ConsumeCategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

        $consumeCategories = $this->consumeCategoryRepo->getByRestaurant($user->restaurant_id);

        return $this->responseSuccess($consumeCategories);
    }
}
