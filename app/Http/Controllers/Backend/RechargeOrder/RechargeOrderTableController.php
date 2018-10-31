<?php

namespace App\Http\Controllers\Backend\RechargeOrder;

use App\Repositories\Backend\RechargeOrder\RechargeOrderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RechargeOrderTableController extends Controller
{
    /**
     * @var RechargeOrderRepository
     */
    private $rechargeOrderRepo;

    /**
     * RechargeOrderTableController constructor.
     * @param $rechargeOrderRepo
     */
    public function __construct(RechargeOrderRepository $rechargeOrderRepo)
    {
        $this->rechargeOrderRepo = $rechargeOrderRepo;
    }


    public function __invoke()
    {
        $user = Auth::User();

        return DataTables::of($this->rechargeOrderRepo->getByRestaurantWithRelationQuery($user->restaurant_id))
            ->addColumn('actions', function ($rechargeOrder) {
                return $rechargeOrder->restaurant_action_buttons;
            })
            ->addColumn('show_status', function ($rechargeOrder) {
                return $rechargeOrder->getShowStatusAttribute();
            })
            ->rawColumns(['actions', 'show_status'])
            ->make(true);
    }
}
