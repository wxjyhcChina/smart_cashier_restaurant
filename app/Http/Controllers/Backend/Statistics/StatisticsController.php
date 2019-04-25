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
use App\Modules\Models\Restaurant\Restaurant;
use App\Modules\Models\Restaurant\RestaurantUser;
use App\Modules\Models\Shop\Shop;
use App\Repositories\Backend\ConsumeOrder\ConsumeOrderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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

    private function fetchDepartmentOrders($start_time, $end_time)
    {
        $orders = ConsumeOrder::where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->where('status', ConsumeOrderStatus::COMPLETE)
            ->whereNotNull('department_id')
            ->get();

        return $orders;
    }

    private function fetchDepartment($orders)
    {
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

    public function getDepartmentStatistics(Request $request)
    {
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');

        $orders = $this->fetchDepartmentOrders($start_time, $end_time);
        return $this->fetchDepartment($orders);
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

    public function departmentStatisticsExport(Request $request)
    {
        $timeArray = explode(' - ', $request->get('search_time'));

        $start_time = $timeArray[0];
        $end_time = $timeArray[1];

        $orders = $this->fetchDepartmentOrders($start_time, $end_time);
        $detail = $this->fetchDepartment($orders);

        $this->exportOrder($start_time, $end_time, $orders, $detail, '部门');
    }
    
    
    public function consumeCategoryStatistics()
    {
        $start_time = date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d H:i:s');

        return view('backend.statistics.consumeCategoryStatistics')
            ->withStartTime($start_time)
            ->withEndTime($end_time);
    }

    private function fetchConsumeCategoryOrders($start_time, $end_time)
    {
        $orders = ConsumeOrder::where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->where('status', ConsumeOrderStatus::COMPLETE)
            ->whereNotNull('consume_category_id')
            ->get();

        return $orders;
    }

    private function fetchConsumeCategory($orders)
    {
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

    public function getConsumeCategoryStatistics(Request $request)
    {
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');

        $orders = $this->fetchConsumeCategoryOrders($start_time, $end_time);

        return $this->fetchConsumeCategory($orders);
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

    public function consumeCategoryStatisticsExport(Request $request)
    {
        $timeArray = explode(' - ', $request->get('search_time'));

        $start_time = $timeArray[0];
        $end_time = $timeArray[1];

        $orders = $this->fetchConsumeCategoryOrders($start_time, $end_time);
        $detail = $this->fetchConsumeCategory($orders);

        $this->exportOrder($start_time, $end_time, $orders, $detail, '消费类别');
    }

    private function appendNullOption($array)
    {
        $result = [];
        $result[null] = '请选择';

        $result = $result + $array;
        return $result;
    }

    public function dinningTimeStatistics(Request $request)
    {
        $start_time = date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d H:i:s');

        $user = Auth::User();
        $restaurantUser = RestaurantUser::where('restaurant_id', $user->restaurant_id)->get()->pluck('username', 'id')->toArray();
        $restaurantUser = $this->appendNullOption($restaurantUser);

        return view('backend.statistics.dinningTimeStatistics')
            ->withStartTime($start_time)
            ->withEndTime($end_time)
            ->withRestaurantUser($restaurantUser);
    }

    public function fetchDinningTimeOrder($start_time, $end_time, $restaurant_user_id)
    {
        $orderQuery = ConsumeOrder::where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->where('status', ConsumeOrderStatus::COMPLETE)
            ->whereNotNull('dinning_time_id');

        if ($restaurant_user_id != null)
        {
            $orderQuery = $orderQuery->where('restaurant_user_id', $restaurant_user_id);
        }

        return $orderQuery->get();
    }

    public function fetchDinningTime($orders)
    {
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
    
    public function getDinningTimeStatistics(Request $request)
    {
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
        $restaurant_user_id = $request->get('restaurant_user_id');

        $orders = $this->fetchDinningTimeOrder($start_time, $end_time, $restaurant_user_id);
        return $this->fetchDinningTime($orders);
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
                $request->get('restaurant_user_id'),
                [ConsumeOrderStatus::COMPLETE]
            ))
            ->addColumn('show_status', function ($consumeOrder) {
                return $consumeOrder->getShowStatusAttribute();
            })
            ->rawColumns(['show_status'])
            ->make(true);
    }

    public function dinningTimeStatisticsExport(Request $request)
    {
        $timeArray = explode(' - ', $request->get('search_time'));

        $start_time = $timeArray[0];
        $end_time = $timeArray[1];
        $restaurant_user_id = $request->get('restaurant_user_id');

        $orders = $this->fetchDinningTimeOrder($start_time, $end_time, $restaurant_user_id);
        $detail = $this->fetchDinningTime($orders);

        $this->exportOrder($start_time, $end_time, $orders, $detail, '用餐时间');
    }

    private function exportOrder($start_time, $end_time, $orders, $detail, $name)
    {
        $restaurant = Restaurant::where('id', Auth::User()->restaurant_id)->first();
        Excel::create('消费记录记录', function($excel) use ($restaurant, $detail, $orders, $start_time, $end_time, $name) {
            $excel->sheet('消费记录记录', function ($sheet) use ($restaurant, $detail, $orders, $start_time, $end_time, $name) {

                $sheet->setAutoSize(true);
                $sheet->mergeCells('A1:L1');
                $sheet->mergeCells('A2:L2');

                $sheet->cells('A1:H1', function ($cells) {
                    // manipulate the cell
                    $cells->setAlignment('center');
                });

                $sheet->cells('A2:H2', function ($cells) {
                    // manipulate the cell
                    $cells->setAlignment('center');
                });

                $sheet->row(1, array($restaurant->name.'消费统计'));
                $sheet->row(2, array('开始时间'.$start_time.' '.'结束时间'.$end_time));

                $sheet->row(3, array(
                    '编号', $name,'现金金额','现金人次',
                    '卡金额','卡人次','支付宝金额','支付宝人次',
                    '微信支付金额','微信人次','合计','合计人次'
                ));

                $rowNumber = 3;
                foreach ($detail as $statistics)
                {
                    $rowNumber++;
                    $sheet->appendRow(array(
                        $statistics['id'], $statistics['name'],
                        $statistics['cash'], $statistics['cash_count'],
                        $statistics['card'], $statistics['card_count'],
                        $statistics['alipay'], $statistics['alipay_count'],
                        $statistics['wechat'], $statistics['wechat_count'],
                        $statistics['total'], $statistics['total_count'],
                    ));
                }

                $rowNumber = $rowNumber + 2;
                $sheet->appendRow(array(''));
                $sheet->appendRow(array(''));

                $rowNumber++;
                $sheet->mergeCells("B$rowNumber:C$rowNumber");
                $sheet->row($rowNumber, array(
                    '编号','订单编号','', '用户编号','卡编号',
                    '价格','消费类别','支付方式','用餐时间',
                    '部门','消费时间','营业员'
                ));

                foreach ($orders as $order)
                {
                    $rowNumber++;
                    $sheet->mergeCells("B$rowNumber:C$rowNumber");
                    $sheet->row($rowNumber, array(
                        $order->id, $order->order_id, '',
                        $order->customer != null ? $order->customer->user_name : '',
                        $order->card != null ? $order->card->number : '',
                        $order->discount_price, $order->consume_category != null ? $order->consume_category->name : '',
                        $order->getShowPayMethodAttribute(), $order->dinning_time->name,
                        $order->department != null ? $order->department->name : '', $order->created_at,
                        $order->restaurant_user->username
                    ));
                }

                $rowNumber++;
                $sheet->mergeCells("A$rowNumber:L$rowNumber");
                $sheet->cells("A$rowNumber:H$rowNumber", function($cells) {
                    // manipulate the cell
                    $cells->setAlignment('right');
                });
                $sheet->row($rowNumber, array('制表时间:'.date('Y-m-d H:i:s')));
            })->export('xls');
        });
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

    public function shopStatisticsExport(Request $request)
    {

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

    public function goodsStatisticsExport()
    {
        
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
