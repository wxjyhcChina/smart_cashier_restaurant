<?php

namespace App\Http\Controllers\Backend\DinningTime;

use App\Repositories\Backend\DinningTime\DinningTimeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DinningTimeTableController extends Controller
{
    /**
     * @var DinningTimeRepository
     */
    private $dinningTimeRepo;

    /**
     * DinningTimeController constructor.
     * @param $dinningTimeRepo
     */
    public function __construct(DinningTimeRepository $dinningTimeRepo)
    {
        $this->dinningTimeRepo = $dinningTimeRepo;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function __invoke()
    {
        $user = Auth::User();

        return DataTables::of($this->dinningTimeRepo->getByRestaurantQuery($user->restaurant_id))
            ->addColumn('actions', function ($dinningTime) {
                return $dinningTime->restaurant_action_buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
