<?php

namespace App\Http\Controllers\Backend\RechargeOrder;

use App\Http\Requests\Backend\RechargeOrder\ManageRechargeOrderRequest;
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


    public function __invoke(ManageRechargeOrderRequest $request)
    {
        $user = Auth::User();
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
        $pay_method= $request->get('pay_method');
        $restaurant_user_id= $request->get('restaurant_user_id');

        return DataTables::of(
            $this->rechargeOrderRepo->getByRestaurantWithRelationQuery(
                $user->restaurant_id,
                $start_time,
                $end_time,
                $pay_method,
                $restaurant_user_id))
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
