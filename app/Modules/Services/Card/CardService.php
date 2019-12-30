<?php

namespace App\Modules\Services\Card;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Card\Card;

/**
 * Class CardService.
 */
class CardService
{
    /**
     * @param $internal_number
     * @return mixed
     * @throws ApiException
     */
    public function getCardByInternalNumber($internal_number)
    {
        $card = Card::where('internal_number', $internal_number)->first();

        if ($card == null)
        {
            throw new ApiException(ErrorCode::CARD_NOT_EXIST, trans('api.error.card_not_exist'));
        }

        return $card;
    }


    /**
     * @param $card
     * @return mixed
     * @throws ApiException
     */
    public function getCustomerByCard($card)
    {
        $customer = $card->customer;

        if ($customer == null)
        {
            throw new ApiException(ErrorCode::CARD_STATUS_INCORRECT, trans('api.error.card_status_incorrect'));
        }

        return $customer;
    }

}
