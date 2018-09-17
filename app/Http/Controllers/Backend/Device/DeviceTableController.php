<?php

namespace App\Http\Controllers\Backend\Device;

use App\Repositories\Backend\Device\DeviceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DeviceTableController extends Controller
{
    /**
     * @var DeviceRepository
     */
    private $deviceRepo;

    /**
     * CardController constructor.
     * @param $deviceRepo
     */
    public function __construct(DeviceRepository $deviceRepo)
    {
        $this->deviceRepo = $deviceRepo;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function __invoke()
    {
        $user = Auth::User();

        return DataTables::of($this->deviceRepo->getByRestaurantQuery($user->restaurant_id))
            ->addColumn('actions', function ($device) {
                return $device->restaurant_action_buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
