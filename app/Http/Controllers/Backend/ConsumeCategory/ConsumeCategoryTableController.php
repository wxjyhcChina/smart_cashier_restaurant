<?php

namespace App\Http\Controllers\Backend\ConsumeCategory;

use App\Repositories\Backend\ConsumeCategory\ConsumeCategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ConsumeCategoryTableController extends Controller
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
     * @return mixed
     * @throws \Exception
     */
    public function __invoke()
    {
        $user = Auth::User();

        return DataTables::of($this->consumeCategoryRepo->getByRestaurantQuery($user->restaurant_id))
            ->addColumn('actions', function ($consumeCategory) {
                return $consumeCategory->restaurant_action_buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
