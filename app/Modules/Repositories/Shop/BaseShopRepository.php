<?php

namespace App\Modules\Repositories\Shop;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Shop\Shop;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    /**
     * @param $input
     * @return Shop
     * @throws ApiException
     */
    public function create($input)
    {
        $shop = $this->createShopStub($input);

        if ($shop->save())
        {
            return $shop;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.shop.create_error'));
    }

    /**
     * @param Shop $shop
     * @param $input
     * @throws ApiException
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

        return $shop;
    }
}
