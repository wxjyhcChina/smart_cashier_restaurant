<?php

namespace App\Http\Controllers\Backend\Statistics;

use App\Common\Utils;
use App\Modules\Enums\ConsumeOrderStatus;
use App\Modules\Enums\PayMethodType;
use App\Modules\Models\ConsumeCategory\ConsumeCategory;
use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Modules\Models\Department\Department;
use App\Modules\Models\DinningTime\DinningTime;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Shop\Shop;
use App\Repositories\Backend\ConsumeOrder\ConsumeOrderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StatisticsController extends Controller
{
    /**
     * @var ConsumeOrderRepository
     */
    private $consumeOrderRepo;

    /**
     * ConsumeOrderTableController constructor.
     * @param $consumeOrderRepo
     */
    public function __construct(ConsumeOrderRepository $consumeOrderRepo)
    {
        $this->consumeOrderRepo = $consumeOrderRepo;
    }

    //
    public function departmentStatistics(Request $request)
    {
        $start_time = date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d H:i:s');

        return view('backend.statistics.departmentStatistics')
            ->withStartTime($start_time)
            ->withEndTime($end_time);
    }

    public function getDepartmentStatistics(Request $request)
    {
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');

        $orders = ConsumeOrder::where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->where('status', ConsumeOrderStatus::COMPLETE)
            ->whereNotNull('department_id')
            ->get();

        $user = Auth::User();
        $departments = Department::where('restaurant_id', $user->restaurant_id)->get();

        $departmentData = [];
        foreach ($departments as $department)
        {
            $data = [
                'id' => $department->id,
                'name' => $department->name,
                'total' => 0,
                'total_count' => 0,
                'alipay' => 0,
                'alipay_count' => 0,
                'wechat' => 0,
                'wechat_count' => 0,
                'cash' => 0,
                'cash_count' => 0,
                'card' => 0,
                'card_count' => 0
            ];

            foreach ($orders as $order)
            {
                if ($order->department_id == $department->id)
                {
                    $data['total'] += $order->discount_price;
                    $data['total_count'] ++;

                    switch ($order->pay_method)
                    {
                        case PayMethodType::CASH:
                            $data['cash'] += $order->discount_price;
                            $data['cash_count'] ++;
                            break;
                        case PayMethodType::CARD:
                            $data['card'] += $order->discount_price;
                            $data['card_count'] ++;
                            break;
                        case PayMethodType::ALIPAY:
                            $data['alipay'] += $order->discount_price;
                            $data['alipay_count'] ++;
                            break;
                        case PayMethodType::WECHAT_PAY:
                            $data['wechat'] += $order->discount_price;
                            $data['wechat_count'] ++;
                            break;
                    }
                }
            }

            array_push($departmentData, $data);
        }

        return $departmentData;
    }

    public function getDepartmentStatisticsOrder(Request $request)
    {
        $user = Auth::User();

        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');

        return DataTables::of(
            $this->consumeOrderRepo->getByRestaurantWithRelationQuery($user->restaurant_id,
                $start_time,
                $end_time,
                null,
                null,
                null,
                [ConsumeOrderStatus::COMPLETE],
                true))
            ->addColumn('show_status', function ($consumeOrder) {
                return $consumeOrder->getShowStatusAttribute();
            })
            ->rawColumns(['show_status'])
            ->make(true);
    }

    public function consumeCategoryStatistics()
    {
        $start_time = date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d H:i:s');

        return view('backend.statistics.consumeCategoryStatistics')
            ->withStartTime($start_time)
            ->withEndTime($end_time);
    }

    public function getConsumeCategoryStatistics(Request $request)
    {
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');

        $orders = ConsumeOrder::where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->where('status', ConsumeOrderStatus::COMPLETE)
            ->whereNotNull('consume_category_id')
            ->get();

        $user = Auth::User();
        $consumeCategories = ConsumeCategory::where('restaurant_id', $user->restaurant_id)->get();

        $consumeCategoryData = [];
        foreach ($consumeCategories as $consumeCategory)
        {
            $data = [
                'id' => $consumeCategory->id,
                'name' => $consumeCategory->name,
                'total' => 0,
                'total_count' => 0,
                'alipay' => 0,
                'alipay_count' => 0,
                'wechat' => 0,
                'wechat_count' => 0,
                'cash' => 0,
                'cash_count' => 0,
                'card' => 0,
                'card_count' => 0
            ];

            foreach ($orders as $order)
            {
                if ($order->consume_category_id == $consumeCategory->id)
                {
                    $data['total'] += $order->discount_price;
                    $data['total_count'] ++;

                    switch ($order->pay_method)
                    {
                        case PayMethodType::CASH:
                            $data['cash'] += $order->discount_price;
                            $data['cash_count'] ++;
                            break;
                        case PayMethodType::CARD:
                            $data['card'] += $order->discount_price;
                            $data['card_count'] ++;
                            break;
                        case PayMethodType::ALIPAY:
                            $data['alipay'] += $order->discount_price;
                            $data['alipay_count'] ++;
                            break;
                        case PayMethodType::WECHAT_PAY:
                            $data['wechat'] += $order->discount_price;
                            $data['wechat_count'] ++;
                            break;
                    }
                }
            }

            array_push($consumeCategoryData, $data);
        }

        return $consumeCategoryData;
    }

    public function getConsumeCategoryStatisticsOrder(Request $request)
    {
        $user = Auth::User();

        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');

        return DataTables::of(
            $this->consumeOrderRepo->getByRestaurantWithRelationQuery($user->restaurant_id,
                $start_time,
                $end_time,
                null,
                null,
                null,
                false,
                [ConsumeOrderStatus::COMPLETE],
                true))
            ->addColumn('show_status', function ($consumeOrder) {
                return $consumeOrder->getShowStatusAttribute();
            })
            ->rawColumns(['show_status'])
            ->make(true);
    }

    public function dinningTimeStatistics(Request $request)
    {
        $start_time = date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d H:i:s');

        return view('backend.statistics.dinningTimeStatistics')
            ->withStartTime($start_time)
            ->withEndTime($end_time);
    }

    public function getDinningTimeStatistics(Request $request)
    {
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');

        $orders = ConsumeOrder::where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->where('status', ConsumeOrderStatus::COMPLETE)
            ->whereNotNull('dinning_time_id')
            ->get();

        $user = Auth::User();
        $dinningTimes = DinningTime::where('restaurant_id', $user->restaurant_id)->get();

        $dinningTimeData = [];
        foreach ($dinningTimes as $dinningTime)
        {
            $data = [
                'id' => $dinningTime->id,
                'name' => $dinningTime->name,
                'total' => 0,
                'total_count' => 0,
                'alipay' => 0,
                'alipay_count' => 0,
                'wechat' => 0,
                'wechat_count' => 0,
                'cash' => 0,
                'cash_count' => 0,
                'card' => 0,
                'card_count' => 0
            ];

            foreach ($orders as $order)
            {
                if ($order->dinning_time_id == $dinningTime->id)
                {
                    $data['total'] += $order->discount_price;
                    $data['total_count'] ++;

                    switch ($order->pay_method)
                    {
                        case PayMethodType::CASH:
                            $data['cash'] += $order->discount_price;
                            $data['cash_count'] ++;
                            break;
                        case PayMethodType::CARD:
                            $data['card'] += $order->discount_price;
                            $data['card_count'] ++;
                            break;
                        case PayMethodType::ALIPAY:
                            $data['alipay'] += $order->discount_price;
                            $data['alipay_count'] ++;
                            break;
                        case PayMethodType::WECHAT_PAY:
                            $data['wechat'] += $order->discount_price;
                            $data['wechat_count'] ++;
                            break;
                    }
                }
            }

            array_push($dinningTimeData, $data);
        }

        return $dinningTimeData;
    }

    public function getDinningTimeStatisticsOrder(Request $request)
    {
        $user = Auth::User();

        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');

        return DataTables::of(
            $this->consumeOrderRepo->getByRestaurantWithRelationQuery($user->restaurant_id,
                $start_time,
                $end_time,
                null,
                null,
                null,
                [ConsumeOrderStatus::COMPLETE]
            ))
            ->addColumn('show_status', function ($consumeOrder) {
                return $consumeOrder->getShowStatusAttribute();
            })
            ->rawColumns(['show_status'])
            ->make(true);
    }

    public function shopStatistics()
    {
        $start_time = date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d H:i:s');

        return view('backend.statistics.shopStatistics')
            ->withStartTime($start_time)
            ->withEndTime($end_time);
    }

    public function getShopStatistics(Request $request)
    {
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');

        $orders = ConsumeOrder::where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->where('status', ConsumeOrderStatus::COMPLETE)
            ->with('goods')
            ->get();

        $user = Auth::User();
        $shops = Shop::where('restaurant_id', $user->restaurant_id)->get();

        $shopData = [];
        foreach ($shops as $shop)
        {
            $shopGoods = Goods::where('shop_id', $shop->id)->get();
            $data = [
                'id' => $shop->id,
                'name' => $shop->name,
                'total' => 0,
                'total_count' => 0,
                'alipay' => 0,
                'alipay_count' => 0,
                'wechat' => 0,
                'wechat_count' => 0,
                'cash' => 0,
                'cash_count' => 0,
                'card' => 0,
                'card_count' => 0
            ];

            foreach ($orders as $order)
            {
                $goods = $order->goods;

                foreach ($goods as $orderGoods)
                {
                    foreach ($shopGoods as $g)
                    {
                        if ($orderGoods->id == $g->id)
                        {
                            $price = Utils::round_up($orderGoods->pivot->price * $this->geteOrderDiscount($order)/10, 3);

                            $data['total'] += $price;

                            switch ($order->pay_method)
                            {
                                case PayMethodType::CASH:
                                    $data['cash'] += $price;
                                    break;
                                case PayMethodType::CARD:
                                    $data['card'] += $price;
                                    break;
                                case PayMethodType::ALIPAY:
                                    $data['alipay'] += $price;
                                    break;
                                case PayMethodType::WECHAT_PAY:
                                    $data['wechat'] += $price;
                                    break;
                            }
                            break;
                        }
                    }
                }
            }

            array_push($shopData, $data);
        }

        return $shopData;
    }

    public function goodsStatistics()
    {
        $start_time = date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d H:i:s');

        return view('backend.statistics.goodsStatistics')
            ->withStartTime($start_time)
            ->withEndTime($end_time);
    }

    public function getGoodsStatistics(Request $request)
    {
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');

        $orders = ConsumeOrder::where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->where('status', ConsumeOrderStatus::COMPLETE)
            ->with('goods')
            ->get();

        $user = Auth::User();
        $allGoods = Goods::where('restaurant_id', $user->restaurant_id)
            ->where('is_temp', false)
            ->get();

        $goodsData = [];
        foreach ($allGoods as $goods)
        {
            $data = [
                'id' => $goods->id,
                'name' => $goods->name,
                'total' => 0,
                'total_count' => 0,
                'alipay' => 0,
                'alipay_count' => 0,
                'wechat' => 0,
                'wechat_count' => 0,
                'cash' => 0,
                'cash_count' => 0,
                'card' => 0,
                'card_count' => 0
            ];

            foreach ($orders as $order)
            {
                $allOrderGoods = $order->goods;

                foreach ($allOrderGoods as $orderGoods)
                {
                    if ($orderGoods->id == $goods->id)
                    {

                        $price = Utils::round_up($orderGoods->pivot->price * $this->geteOrderDiscount($order)/10, 3);

                        $data['total'] += $price;
                        $data['total_count'] ++;

                        switch ($order->pay_method)
                        {
                            case PayMethodType::CASH:
                                $data['cash'] += $price;
                                $data['cash_count'] ++;
                                break;
                            case PayMethodType::CARD:
                                $data['card'] += $price;
                                $data['card_count'] ++;
                                break;
                            case PayMethodType::ALIPAY:
                                $data['alipay'] += $price;
                                $data['alipay_count'] ++;
                                break;
                            case PayMethodType::WECHAT_PAY:
                                $data['wechat'] += $price;
                                $data['wechat_count'] ++;
                                break;
                        }
                    }
                }
            }

            array_push($goodsData, $data);
        }

        return $goodsData;
    }

    private function geteOrderDiscount($order)
    {
        if ($order->discount != null)
        {
            return $order->discount;
        }
        else if ($order->force_discount != null)
        {
            return $order->force_discount;
        }
        else
        {
            return 10;
        }
    }
}
