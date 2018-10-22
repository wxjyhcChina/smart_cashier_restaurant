<?php

namespace App\Modules\Repositories\Card;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Card\Card;
use App\Modules\Repositories\BaseRepository;

/**
 * Class BaseCardRepository.
 */
class BaseCardRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Card::class;


    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }

    /**
     * @param $internalNumber
     * @return mixed
     * @throws ApiException
     */
    public function getByInternalNumber($internalNumber)
    {
        $card = $this->query()->where('internal_number', $internalNumber)->first();

        if ($card == null)
        {
            throw  new ApiException(ErrorCode::CARD_NOT_EXIST, trans('api.error.device_not_exist'));
        }

        return $card;
    }
}
