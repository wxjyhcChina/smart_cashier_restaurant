<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\Shop;

use App\Exceptions\GeneralException;
use App\Modules\Models\Shop\Shop;
use App\Modules\Repositories\Shop\BaseShopRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class ShopRepository
 * @package App\Repositories\Backend\Shop
 */
class ShopRepository extends BaseShopRepository
{
    /**
     * @param $input
     * @return Shop
     * @throws GeneralException
     */
    public function create($input)
    {
        $shop = $this->createShopStub($input);

        if ($shop->save())
        {
            return $shop;
        }

        throw new GeneralException(trans('exceptions.backend.shop.create_error'));
    }

    /**
     * @param Shop $shop
     * @param $input
     * @throws GeneralException
     */
    public function update(Shop $shop, $input)
    {
        Log::info("restaurant update param:".json_encode($input));

        try
        {
            DB::beginTransaction();
            $shop->update($input);

            DB::commit();
            return;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
        }

        throw new GeneralException(trans('exceptions.backend.shop.update_error'));
    }

    /**
     * @param Shop $shop
     * @param $enabled
     * @return bool
     * @throws GeneralException
     */
    public function mark(Shop $shop, $enabled)
    {
        $shop->enabled = $enabled;

        if ($shop->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.shop.mark_error'));
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

        return $shop;
    }
}