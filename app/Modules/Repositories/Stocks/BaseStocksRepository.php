<?php

namespace App\Modules\Repositories\Stocks;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Stocks\Stocks;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseShopRepository.
 */
class BaseStocksRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Stocks::class;


    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }

    public function getByShopQuery($shop_id)
    {
        return $this->query()
            ->select('stocks.*','materials.name as name','materials.main_supplier as main_supplier')
            ->leftJoin('materials', 'materials.id', '=', 'stocks.material_id')
            ->where('stocks.shop_id', $shop_id);
    }

    public function dailyConsumeByShop($shop_id,$start_time,$end_time){
        return $this->query()
            ->select('stocks_detail.*','materials.name as name')
            ->rightJoin('stocks_detail', 'stocks_detail.material_id', '=', 'stocks.material_id')
            ->leftJoin('materials', 'materials.id', '=', 'stocks.material_id')
            ->where('stocks.shop_id', $shop_id)
            ->where('stocks_detail.created_at', '>=', $start_time)
            ->where('stocks_detail.created_at', '<=', $end_time);
    }


}
