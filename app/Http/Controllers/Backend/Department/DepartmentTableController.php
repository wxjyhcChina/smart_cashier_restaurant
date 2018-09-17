<?php

namespace App\Http\Controllers\Backend\Department;

use App\Repositories\Backend\Department\DepartmentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DepartmentTableController extends Controller
{
    /**
     * @var DepartmentRepository
     */
    private $departmentRepo;

    /**
     * CardController constructor.
     * @param $departmentRepo
     */
    public function __construct(DepartmentRepository $departmentRepo)
    {
        $this->departmentRepo = $departmentRepo;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function __invoke()
    {
        $user = Auth::User();

        return DataTables::of($this->departmentRepo->getByRestaurantQuery($user->restaurant_id))
            ->addColumn('actions', function ($card) {
                return $card->restaurant_action_buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
