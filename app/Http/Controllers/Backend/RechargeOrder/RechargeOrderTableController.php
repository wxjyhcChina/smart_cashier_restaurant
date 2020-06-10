<?php

namespace App\Http\Controllers\Backend\RechargeOrder;

use App\Http\Requests\Backend\RechargeOrder\ManageRechargeOrderRequest;
use App\Modules\Enums\RechargeOrderStatus;
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
        $restaurant_user_id= $request->get('restaurant_user_id');

        return DataTables::of(
            $this->rechargeOrderRepo->getByShopWithRelationQuery(
                $user->shop_id,
                $start_time,
                $end_time,
                null,
                $restaurant_user_id,
                [RechargeOrderStatus::COMPLETE]))
            ->addColumn('actions', function ($rechargeOrder) {
                return $rechargeOrder->restaurant_action_buttons;
            })
            ->addColumn('show_status', function ($rechargeOrder) {
                return $rechargeOrder->getShowStatusAttribute();
            })
            ->addColumn('show_pay_method', function ($consumeOrder) {
                return $consumeOrder->getShowPayMethodAttribute();
            })
            ->rawColumns(['actions', 'show_status'])
            ->make(true);
    }
}
