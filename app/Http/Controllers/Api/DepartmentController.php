<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Api\Department\DepartmentRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    /**
     * @var DepartmentRepository
     */
    private $departmentRepo;

    /**
     * DepartmentController constructor.
     * @param $departmentRepo
     */
    public function __construct(DepartmentRepository $departmentRepo)
    {
        $this->departmentRepo = $departmentRepo;
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

        //$departments = $this->departmentRepo->getByRestaurant($user->restaurant_id);
        $departments = $this->departmentRepo->getByShopQuery($user->shop_id);

        return $this->responseSuccess($departments);
    }
}
