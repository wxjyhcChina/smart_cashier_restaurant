<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\ConsumeOrder;

use App\Modules\Models\Stocks\Stocks;
use Illuminate\Support\Facades\Auth;
use App\Common\Util\OrderUtil;
use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ConsumeOrderStatus;
use App\Modules\Enums\ErrorCode;
use App\Modules\Enums\PayMethodType;
use App\Modules\Enums\StockDetailStatus;
use App\Modules\Models\Card\Card;
use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Modules\Models\ConsumeRule\ConsumeRule;
use App\Modules\Models\DinningTime\DinningTime;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Label\Label;
use App\Modules\Models\PayMethod\PayMethod;
use App\Modules\Models\Stocks\StocksDetail;
use App\Modules\Repositories\ConsumeOrder\BaseConsumeOrderRepository;
use App\Modules\Services\Account\Facades\Account;
use App\Modules\Services\Card\Facades\CardService;
use App\Modules\Services\Pay\Facades\Pay;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp;
/**
 * Class ConsumeOrderRepository
 * @package App\Repositories\Backend\ConsumeOrder
 */
class ConsumeOrderRepository extends BaseConsumeOrderRepository
{
    /**
     * @param $restaurant_id
     * @param $input
     * @return mixed
     */
    public function getByRestaurant($restaurant_id, $input)
    {
        $query = $this->getByRestaurantQuery($restaurant_id);
        if (isset($input['start_time']) && isset($input['end_time']))
        {
            $query = $query->whereBetween('created_at', [$input['start_time'].' 00:00:00', $input['end_time']." 23:59:59"]);
        }

        if (isset($input['key'])) {
            $query->where(function ($query) use ($input) {
                $query->where('id', 'like', '%' . $input['key'] . '%')
                    ->orWhereHas('customer', function ($query) use ($input) {
                        $query->where('user_name', 'like', '%' . $input['key'] . '%');
                    })
                    ->orWhereHas('card', function ($query) use ($input) {
                        $query->where('number', 'like', '%' . $input['key'] . '%');
                    });
            });
        }

        if (isset($input['dinning_time_id']))
        {
            $query = $query->where('dinning_time_id', $input['dinning_time_id']);
        }

        if (isset($input['pay_method']))
        {
            $query = $query->where('pay_method', $input['pay_method']);
        }

        $query->where('status', '<>', ConsumeOrderStatus::WAIT_PAY)
            ->where('status', '<>', ConsumeOrderStatus::PAY_IN_PROGRESS)
            ->where('status', '<>', ConsumeOrderStatus::CLOSED);
        return $query->orderBy('consume_orders.created_at', 'desc')->paginate(15);
    }

    public function getByShop($shop_id, $input)
    {
        $query = $this->getByShopQuery($shop_id);
        if (isset($input['start_time']) && isset($input['end_time']))
        {
            $query = $query->whereBetween('created_at', [$input['start_time'].' 00:00:00', $input['end_time']." 23:59:59"]);
        }

        if (isset($input['key'])) {
            $query->where(function ($query) use ($input) {
                $query->where('id', 'like', '%' . $input['key'] . '%')
                    ->orWhereHas('customer', function ($query) use ($input) {
                        $query->where('user_name', 'like', '%' . $input['key'] . '%');
                    })
                    ->orWhereHas('card', function ($query) use ($input) {
                        $query->where('number', 'like', '%' . $input['key'] . '%');
                    });
            });
        }

        if (isset($input['dinning_time_id']))
        {
            $query = $query->where('dinning_time_id', $input['dinning_time_id']);
        }

        if (isset($input['pay_method']))
        {
            $query = $query->where('pay_method', $input['pay_method']);
        }

        $query->where('status', '<>', ConsumeOrderStatus::WAIT_PAY)
            ->where('status', '<>', ConsumeOrderStatus::PAY_IN_PROGRESS)
            ->where('status', '<>', ConsumeOrderStatus::CLOSED);
        return $query->orderBy('consume_orders.created_at', 'desc')->paginate(15);
    }

    /**
     * @param $restaurant_user_id
     * @param $input
     * @return mixed
     */
    public function statistics($restaurant_user_id, $input)
    {
        $query = $this->query()
            ->select('pay_method', DB::raw('SUM(discount_price) as money'), DB::raw('count(*) as count'))
            ->where('consume_orders.restaurant_user_id', $restaurant_user_id)
            ->where('status', ConsumeOrderStatus::COMPLETE)
            ->where('shop_id',$input['shop_id']);

        if (isset($input['start_time']) && isset($input['end_time']))
        {
            $query = $query->whereBetween('created_at', [$input['start_time'].' 00:00:00', $input['end_time']." 23:59:59"]);
        }

        if (isset($input['dinning_time_id'])){
            $query = $query->where('dinning_time_id', $input['dinning_time_id']);
        }

        $payMethods = $query->groupBy('pay_method')->get();

        $ret = [
            ['pay_method' => PayMethodType::ALIPAY, 'money' => 0, 'count' => 0],
            ['pay_method' => PayMethodType::CARD, 'money' => 0, 'count' => 0],
            ['pay_method' => PayMethodType::CASH, 'money' => 0, 'count' => 0],
            ['pay_method' => PayMethodType::WECHAT_PAY, 'money' => 0, 'count' => 0],
        ];

        foreach ($payMethods as $payMethod)
        {
           for ($i = 0; $i < count($ret); $i++)
           {
               if ($ret[$i]['pay_method'] == $payMethod['pay_method'])
               {
                   $ret[$i]['money'] = $payMethod['money'];
                   $ret[$i]['count'] = $payMethod['count'];
                   break;
               }
           }
        }

        return $ret;
    }

    /**
     * @param $shop_id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     * @throws ApiException
     */
    private function getCurrentDinningTime($shop_id)
    {
        $current_time = Carbon::now()->format("H:i");
        //Log::info("current_time:".json_encode($current_time));
        $dinningTime = DinningTime::where('enabled', 1)
            ->where('start_time', '<=' , $current_time)
            ->where('end_time', '>', $current_time)
            //->where('restaurant_id', $restaurant_id)
            ->where('shop_id', $shop_id)
            ->first();
        //Log::info("dinningTime:".json_encode($dinningTime));
        if ($dinningTime == null)
        {
            throw new ApiException(ErrorCode::NOT_IN_DINNING_TIME, trans('api.error.not_in_dinning_time'));
        }

        return $dinningTime;
    }

    /**
     * @param $restaurant_id
     * @param $labelId
     * @param $dinningTimeId
     * @return mixed
     * @throws ApiException
     */
    private function getLabelCategoryGoods($restaurant_id, $labelId, $dinningTimeId)
    {
        $label = Label::where('rfid', $labelId)
            ->first();

        if ($label == null)
        {
            throw new ApiException(ErrorCode::LABEL_NOT_EXIST, trans('api.error.label_not_exist'));
        }

        $labelCategory = $label->label_category()
            ->where('restaurant_id', $restaurant_id)
            ->first();
        if ($labelCategory == null)
        {
            throw new ApiException(ErrorCode::LABEL_CATEGORY_NOT_BINDED, trans('api.error.label_category_not_binded'));
        }

        $goods = $labelCategory->goods()
            ->whereHas('dinning_time', function ($query) use ($dinningTimeId) {
                $query->where('dinning_time_id', $dinningTimeId);
            })->first();
        if ($goods == null)
        {
            throw new ApiException(ErrorCode::LABEL_CATEGORY_NOT_BIND_GOOD, trans('api.error.label_category_not_bind_good'));
        }

        $goods->label_id = $label->id;
        return $goods;
    }


    /**
     * @param $restaurant_id
     * @param $goodsId
     * @return mixed|static
     * @throws ApiException
     */
    private function getGoods($restaurant_id, $goodsId)
    {
        $goods = Goods::where('restaurant_id', $restaurant_id)
            ->where('id', $goodsId)
            ->first();

        if ($goods == null)
        {
            throw new ApiException(ErrorCode::GOODS_NOT_EXIST, $goodsId.trans('api.error.goods_not_exist'));
        }

        return $goods;
    }

    /**
     * @param $restaurant_id
     * @param $labels
     * @param $tempGoods
     * @param $forceDiscount
     * @param $shop_id
     * @return array
     * @throws ApiException
     */
    private function getOrderOrderInfo($restaurant_id, $labels, $tempGoods, $forceDiscount,$shop_id)
    {
        $dinningTime = $this->getCurrentDinningTime($shop_id);

        $excludeLabels = $this->excludeLabels($shop_id, $dinningTime->id);

        $price = 0;
        $goodsArray = [];
        foreach ($labels as $labelId)
        {
            $goods = $this->getLabelCategoryGoods($restaurant_id, $labelId, $dinningTime->id);

            $foundLabel = $excludeLabels->where('id', $goods->label_id)->first();

            if ($foundLabel == null)
            {
                $price = bcadd($price, $goods->price, 2);
                array_push($goodsArray, $goods);
            }
        }

        foreach ($tempGoods as $goodsId)
        {
            $goods = $this->getGoods($restaurant_id, $goodsId);

            $price = bcadd($price, $goods->price, 2);
            $goods->label_id = null;
            array_push($goodsArray, $goods);
        }

        $discountPrice = $price;
        if ($forceDiscount != null)
        {
            $discountPrice = bcmul($price, bcdiv($forceDiscount, 10, 2), 2);
        }

        if (count($goodsArray) == 0)
        {
            throw  new ApiException(ErrorCode::ORDER_GOODS_NOT_EXIST, trans('api.error.order_goods_not_exist'));
        }

        $response = [];
        $response['goods_count'] = count($goodsArray);
        $response['dinning_time_id'] = $dinningTime->id;
        $response['price'] = doubleval($price);
        $response['force_discount'] = $forceDiscount != null ? doubleval($forceDiscount) : $forceDiscount;
        $response['discount_price'] = doubleval($discountPrice);
        $response['goods'] = $goodsArray;

        return $response;
    }

    /**
     * @param $input
     * @return array
     * @throws ApiException
     */
    public function preCreate($input)
    {
        $labels = isset($input['labels']) ? $input['labels'] : array();
        $tempGoods = isset($input['temp_goods']) ? $input['temp_goods'] : array();
        Log::info('tempGoods, input'.json_encode($tempGoods));
        $forceDiscount = isset($input['discount']) ? $input['discount'] : null;
        $restaurant_id = $input['restaurant_id'];
        $shop_id = $input['shop_id'];

        return $this->getOrderOrderInfo($restaurant_id, $labels, $tempGoods, $forceDiscount,$shop_id);
    }

    /**
     * @param $input
     * @return ConsumeOrder
     * @throws ApiException
     */
    public function create($input)
    {
        $labels = isset($input['labels']) ? $input['labels'] : array();
        $tempGoods = isset($input['temp_goods']) ? $input['temp_goods'] : array();

        $forceDiscount = isset($input['discount']) ? $input['discount'] : null;
        $restaurant_id = $input['restaurant_id'];
        $shop_id = $input['shop_id'];
        $restaurant_user_id = isset($input['restaurant_user_id']) ? $input['restaurant_user_id'] : null;

        $response = $this->getOrderOrderInfo($restaurant_id, $labels, $tempGoods, $forceDiscount,$shop_id);

        try
        {
            DB::beginTransaction();

            $consumeOrder = new ConsumeOrder();
            $consumeOrder->order_id = OrderUtil::generateConsumeOrderId();
            $consumeOrder->restaurant_id = $restaurant_id;
            $consumeOrder->shop_id = $shop_id;
            $consumeOrder->restaurant_user_id = $restaurant_user_id;
            $consumeOrder->dinning_time_id = $response['dinning_time_id'];
            $consumeOrder->price = $response['price'];
            $consumeOrder->discount_price = $response['discount_price'];
            $consumeOrder->force_discount = $response['force_discount'];
            $consumeOrder->goods_count = $response['goods_count'];
            $consumeOrder->status = ConsumeOrderStatus::WAIT_PAY;
            $consumeOrder->save();

            $goodsIds = [];
            foreach ($response['goods'] as $goods)
            {
                $consumeOrder->goods()->attach([$goods->id => ['label_id' => $goods->label_id, 'price'=>$goods->price]]);
            }

            DB::commit();

            return $consumeOrder->load('goods', 'customer', 'card');
        }
        catch (\Exception $exception)
        {
            DB::rollBack();

            throw $exception;
//            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('api.error.database_error'));
        }
    }

    /**
     * @param $shop_id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function latestOrder($shop_id)
    {
        $order = ConsumeOrder::where('shop_id', $shop_id)
            ->orderBy('id', 'desc')
            ->with('goods')
            ->first();

        return $order;
    }

    /**
     * @param $shop_id
     * @param $dinning_time_id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    private function excludeLabels($shop_id, $dinning_time_id)
    {
        $now = Carbon::now();
        $excludeTime = Carbon::now()->subSeconds(config('constants.order.exclude_time'));

        $order = ConsumeOrder::where('shop_id', $shop_id)
            ->where('dinning_time_id', $dinning_time_id)
            ->whereBetween('updated_at', [$excludeTime, $now])
            ->where('status','<>', ConsumeOrderStatus::CLOSED)
            ->orderBy('id', 'desc')
            ->first();

        $labels = collect();
        if ($order != null)
        {
            $labels = $order->labels;
        }

        return $labels;
    }

    /**
     * @param ConsumeOrder $order
     * @return ConsumeOrder
     */
    private function payWithCash(ConsumeOrder $order)
    {
        $order->pay_method = PayMethodType::CASH;
        $order->online_pay = false;
        $order->status = ConsumeOrderStatus::COMPLETE;
        $order->save();

        return $order;
    }

    private function payWithDisabledThird(ConsumeOrder $order, $payMethod)
    {
        $order->pay_method = $payMethod;
        $order->online_pay = false;
        $order->status = ConsumeOrderStatus::COMPLETE;
        $order->save();

        return $order;
    }

    /**
     * @param $order
     * @param $customer
     * @return mixed|null
     */
    private function getCardDiscount($order, $customer)
    {
        $discount = null;
        if ($order->force_discount == null && $customer->consume_category_id != null)
        {
            $weekday = pow(2, Carbon::parse($order->created_at)->dayOfWeek);

            //calculate discount
            $rule = ConsumeRule::whereHas('dinning_time', function ($query) use ($order){
                $query->where('dinning_time.id', $order->dinning_time_id);
            })->whereHas('consume_categories', function ($query) use ($customer){
                $query->where('consume_categories.id', $customer->consume_category_id);
            })->whereRaw('weekday & '.$weekday.' > 0')->where('enabled', 1)->first();

            if ($rule != null)
            {
                $discount = $rule->discount;
            }
        }

        return $discount;
    }

    /**
     * @param ConsumeOrder $order
     * @param $cardId
     * @return ConsumeOrder
     * @throws ApiException
     */
    private function payWithFace(ConsumeOrder $order, $cardId)
    {
        $card = CardService::getCardByInternalNumber($cardId);

        if ($order->restaurant_id != $card->restaurant_id)
        {
            throw new ApiException(ErrorCode::INVALID_CARD, trans('api.error.invalid_card'));
        }

        $customer = CardService::getCustomerByCard($card);

        $discount = $this->getCardDiscount($order, $customer);

        //check account balance
        $account = $customer->account;
        $original_price = $order->price;
        $discount_price = $original_price;

        $orderDiscount = $order->force_discount;
        if ($orderDiscount == null)
        {
            $orderDiscount = $discount;
        }

        if ($orderDiscount != null)
        {
            $discount_price = bcmul($original_price, bcdiv($orderDiscount, 10, 2), 2);
        }

        Account::compareBalance($account, $discount_price);

        try
        {
            DB::beginTransaction();

            //add account record
            Account::payAccount($order->id, $account, $discount_price);

            //modify order status
            $order->customer_id = $customer->id;
            $order->card_id = $card->id;
            $order->department_id = $customer->department_id;
            $order->consume_category_id = $customer->consume_category_id;
            $order->discount_price = doubleval($discount_price);
            $order->discount = $discount;
            $order->pay_method = PayMethodType::FACE;
            $order->online_pay = false;
            $order->status = ConsumeOrderStatus::COMPLETE;
            $order->save();

            DB::commit();
        }
        catch (\Exception $exception)
        {
            DB::rollBack();

            if ($exception instanceof ApiException)
            {
                throw $exception;
            }

            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('api.error.database_error'));
        }

        return $order->load('customer');
    }

    /**
     * @param ConsumeOrder $order
     * @param $cardId
     * @return ConsumeOrder
     * @throws ApiException
     */
    private function payWithCard(ConsumeOrder $order, $cardId)
    {
        $card = CardService::getCardByInternalNumber($cardId);

        if ($order->restaurant_id != $card->restaurant_id)
        {
            throw new ApiException(ErrorCode::INVALID_CARD, trans('api.error.invalid_card'));
        }

        $customer = CardService::getCustomerByCard($card);

        $discount = $this->getCardDiscount($order, $customer);

        //check account balance
        $account = $customer->account;
        $original_price = $order->price;
        $discount_price = $original_price;

        $orderDiscount = $order->force_discount;
        if ($orderDiscount == null)
        {
            $orderDiscount = $discount;
        }

        if ($orderDiscount != null)
        {
            $discount_price = bcmul($original_price, bcdiv($orderDiscount, 10, 2), 2);
        }

        Account::compareBalance($account, $discount_price);

        try
        {
            DB::beginTransaction();

            //add account record
            Account::payAccount($order->id, $account, $discount_price);

            //modify order status
            $order->customer_id = $customer->id;
            $order->card_id = $card->id;
            $order->department_id = $customer->department_id;
            $order->consume_category_id = $customer->consume_category_id;
            $order->discount_price = doubleval($discount_price);
            $order->discount = $discount;
            $order->pay_method = PayMethodType::CARD;
            $order->online_pay = false;
            $order->status = ConsumeOrderStatus::COMPLETE;
            $order->save();

            DB::commit();
        }
        catch (\Exception $exception)
        {
            DB::rollBack();

            if ($exception instanceof ApiException)
            {
                throw $exception;
            }

            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('api.error.database_error'));
        }

        return $order->load('customer');
    }

    /**
     * @param ConsumeOrder $order
     * @param $barcode
     * @return ConsumeOrder
     * @throws ApiException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function payWithBarcode(ConsumeOrder $order, $barcode)
    {
        //check alipay code or wechat code
        if (Pay::isWechatPay($barcode))
        {
            $response = Pay::barcodeWechatPay($order->order_id, $barcode, $order->discount_price, '消费');
        }
        else if (Pay::isAliPay($barcode))
        {
            $response = Pay::barcodeAlipay($order->order_id, $barcode, $order->discount_price, '消费');
        }
        else
        {
            throw new ApiException(ErrorCode::PAY_METHOD_NOT_SUPPORTED, trans('api.error.pay_method_not_supported'));
        }

        if ($response->getTradeStatus() == "SUCCESS")
        {
            $this->onlinePaySuccess($order, Pay::isWechatPay($barcode) ? PayMethodType::WECHAT_PAY : PayMethodType::ALIPAY, $response->getTradeNo());
            return $order;
        }
        else if ($response->getTradeStatus() == "CLOSED")
        {
            $this->payClosed($order, Pay::isWechatPay($barcode) ? PayMethodType::WECHAT_PAY : PayMethodType::ALIPAY);
            throw new ApiException(ErrorCode::RECHARGE_ORDER_CANCELED, trans('api.error.recharge_order_canceled'));
        }
        else
        {
            $this->payClosed($order, Pay::isWechatPay($barcode) ? PayMethodType::WECHAT_PAY : PayMethodType::ALIPAY);
            throw new ApiException(ErrorCode::PAY_FAILED, $response->getErrorMessage());
        }
    }

    /**
     * @param ConsumeOrder $order
     * @param $pay_method
     * @param $trade_no
     */
    public function onlinePaySuccess(ConsumeOrder $order, $pay_method, $trade_no=null)
    {
        $order->status = ConsumeOrderStatus::COMPLETE;
        $order->pay_method = $pay_method;
        $order->online_pay = true;
        $order->external_pay_no = $trade_no;
        $order->save();
    }

    /**
     * @param ConsumeOrder $order
     * @return ConsumeOrder
     */
    public function close(ConsumeOrder $order)
    {
        $order->status = ConsumeOrderStatus::CLOSED;
        $order->save();

        return $order;
    }

    /**
     * @param ConsumeOrder $order
     * @param $pay_method
     */
    public function payClosed(ConsumeOrder $order, $pay_method)
    {
        $order->status = ConsumeOrderStatus::CLOSED;
        $order->pay_method = $pay_method;
        $order->save();
    }

    /**
     * @param ConsumeOrder $order
     * @param $input
     * @return ConsumeOrder
     * @throws ApiException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function pay(ConsumeOrder $order, $input)
    {
        Log::info('pay, input'.json_encode($input));
        if ($order->status != ConsumeOrderStatus::WAIT_PAY)
        {
            throw  new ApiException(ErrorCode::ORDER_STATUS_INCORRECT, trans('api.error.order_status_incorrect'));
        }

        if (isset($input['pay_method']))
        {
            $payMethod = $input['pay_method'];
            $method = PayMethod::where('method', $payMethod)
                ->where('shop_id', $order->shop_id)
                ->first();
            if ($method == null
                || (($payMethod == PayMethodType::CASH || $payMethod == PayMethodType::CARD)
                    && $method->enabled == false)
            )
            {
                throw new ApiException(ErrorCode::PAY_METHOD_NOT_SUPPORTED, trans('api.error.pay_method_not_supported'));
            }

            Log::info('pay, pay method is '.$method->toJson());

            if ($payMethod == PayMethodType::CASH)
            {
                $order = $this->payWithCash($order);
            }
            else if ($payMethod == PayMethodType::CARD)
            {
                if (!isset($input['card_id']))
                {
                    throw new ApiException(ErrorCode::INPUT_INCOMPLETE, trans('api.error.input_incomplete'));
                }

                $order = $this->payWithCard($order, $input['card_id']);
            }else if ($payMethod == PayMethodType::FACE)
            {
                if (!isset($input['card_id']))
                {
                    throw new ApiException(ErrorCode::INPUT_INCOMPLETE, trans('api.error.input_incomplete'));
                }

                $order = $this->payWithFace($order, $input['card_id']);
            }
            else
            {
                if ($method->enabled == false)
                {
                    $order = $this->payWithDisabledThird($order, $payMethod);
                }
                else
                {
                    $order = $this->payWithBarcode($order, $input['barcode']);
                }
            }
        }
        else
        {
            throw new ApiException(ErrorCode::PAY_METHOD_NOT_PROVIDED, trans('api.error.pay_method_not_provided'));
        }
        //消耗食材部分
        $data=DB::table('consume_order_goods')->where('consume_order_id','=',$order->id)->get();
        //Log::info("order param:".json_encode($data));
        foreach ($data as $goods){
            //Log::info("ordergoods param:".json_encode($goods));
            $material_goods=DB::table('material_goods')->where('goods_id',$goods->goods_id)->get();
            if($material_goods ->first() != null){
                foreach($material_goods as $material){
                    //修改库存表stock
                    $stock=Stocks::where("material_id",$material->material_id)->first();
                    $stock->count=$stock->count-($material->number)/1000;
                    $stock->save();
                    //记录stock_detail表
                    $detail=$this->createConsumeMaterials($material,$order->shop_id);
                    $detail->save();
                }
            }
        }
        return $order->load('goods', 'customer', 'card');
    }

    private function createConsumeMaterials($material,$shop_id){
        $user = Auth::User();
        $detail=new StocksDetail();
        $detail->material_id=$material->material_id;
        $detail->number=$material->number;
        $detail->status=StockDetailStatus::CONSUME;
        if($user!=null){
            $detail->restaurant_user_id=$user->id;
        }
        $detail->shop_id=$shop_id;
        return $detail;
    }
}