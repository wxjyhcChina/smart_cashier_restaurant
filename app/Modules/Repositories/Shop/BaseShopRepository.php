<?php

namespace App\Modules\Repositories\Shop;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Shop\Shop;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Modules\Enums\PayMethodType;
use App\Modules\Models\PayMethod\PayMethod;

/**
 * Class BaseShopRepository.
 */
class BaseShopRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Shop::class;


    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }

    public function shopExist($name,$restaurant_id, $updatedShop = null)
    {
        $shopQuery = Shop::where('name', $name)->where('restaurant_id', $restaurant_id);

        if ($updatedShop != null)
        {
            $shopQuery->where('id', '<>', $updatedShop->id);
        }

        if ($shopQuery->first() != null)
        {
            throw new ApiException(ErrorCode::SHOP_ALREADY_EXIST, trans('exceptions.backend.shop.already_exist'));
        }
    }


    /**
     * @param $input
     * @return Shop
     * @throws ApiException
     */
    public function create($input)
    {
        $this->shopExist($input['name'],$input['restaurant_id']);
        $shop = $this->createShopStub($input);

        try {
            DB::beginTransaction();
            /*if ($shop->save())
            {
                return $shop;
            }*/
            $shop->save();
            //创建时同时创建默认支付方式
            $this->createShopPayMethod($input['restaurant_id'],$shop->id);
            DB::commit();
            return $shop;
        }catch(\Exception $e){
            DB::rollBack();
            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.shop.create_error'));
        }
    }

    /**
     * @param Shop $shop
     * @param $input
     * @throws ApiException
     */
    public function update(Shop $shop, $input)
    {
        $this->shopExist($input['name'],$input['restaurant_id'], $shop);
        Log::info("restaurant update param:".json_encode($input));

        try
        {
            DB::beginTransaction();
            if (isset($input['default']) && $shop->default == false)
            {
                Shop::where('default', 1)
                    ->where('restaurant_id', $input['restaurant_id'])
                    ->update(['default' => false]);
            }

            $shop->update($input);

            DB::commit();
            return;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.shop.update_error'));
    }

    /**
     * @param Shop $shop
     * @param $enabled
     * @return bool
     * @throws ApiException
     */
    public function mark(Shop $shop, $enabled)
    {
        $shop->enabled = $enabled;

        if ($shop->save()) {
            return true;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.shop.mark_error'));
    }

    /**
     * @param $input
     * @return Shop
     */
    private function createShopStub($input)
    {
        $shop = new Shop();
        $shop->restaurant_id = $input['restaurant_id'];
        $shop->name = $input['name'];
        $shop->default = isset($input['default']) ? true : false;
        $shop->recharge_flag=1;//默认前台可充值
        $shop->discount_flag=1;//默认前台可打折
        //$shop->face_flag=0;//默认不使用人脸移入paymethod

        $default_shop = Shop::where('default', 1)
            ->where('restaurant_id', $input['restaurant_id'])
            ->first();

        if ($default_shop != null && isset($input['default']))
        {
            $default_shop->default = false;
            $default_shop->save();
        }

        return $shop;
    }

    /**
     * @param $restaurant_id
     * @param $shop_id
     */
    private function createShopPayMethod($restaurant_id,$shop_id)
    {
        $this->createPayMethod($restaurant_id,$shop_id, PayMethodType::CASH, 1);
        $this->createPayMethod($restaurant_id,$shop_id, PayMethodType::CARD, 1);
        $this->createPayMethod($restaurant_id,$shop_id, PayMethodType::ALIPAY, 0);
        $this->createPayMethod($restaurant_id,$shop_id, PayMethodType::WECHAT_PAY, 0);
        $this->createPayMethod($restaurant_id,$shop_id, PayMethodType::FACE, 0);
    }

    /**
     * @param $restaurant_id
     * @param $shop_id
     * @param $method
     * @param $enabled
     */
    private function createPayMethod($restaurant_id,$shop_id, $method, $enabled)
    {
        $payMethod = new PayMethod();
        $payMethod->restaurant_id = $restaurant_id;
        $payMethod->shop_id = $shop_id;
        $payMethod->method = $method;
        $payMethod->enabled = $enabled;
        $payMethod->save();
    }
}
