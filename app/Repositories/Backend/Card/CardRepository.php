<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\Card;

use App\Exceptions\GeneralException;
use App\Modules\Enums\CardStatus;
use App\Modules\Models\Card\Card;
use App\Modules\Repositories\Card\BaseCardRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class CardRepository
 * @package App\Repositories\Backend\Card
 */
class CardRepository extends BaseCardRepository
{
    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function availableCard($restaurant_id)
    {
        return $this->query()
            ->where('restaurant_id', $restaurant_id)
            ->whereNull('customer_id')
            ->where('status', CardStatus::UNACTIVATED);
    }
    
}