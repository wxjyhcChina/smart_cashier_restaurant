<?php

namespace App\Modules\Repositories\Card;

use App\Exceptions\Api\ApiException;
use App\Exceptions\GeneralException;
use App\Modules\Enums\CardStatus;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Card\Card;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * @param $number
     * @param $internalNumber
     * @param null $updateCard
     * @throws ApiException
     */
    public function cardExist($number, $internalNumber, $updateCard = null)
    {
        $cardQuery = Card::query();

        if ($updateCard != null)
        {
            $cardQuery = $cardQuery->where('id', '<>', $updateCard->id);
        }

        $cardQuery = $cardQuery->where(function ($query) use ($number, $internalNumber){
            $query->where('number', $number)->orWhere('internal_number', $internalNumber);
        });

        if ($cardQuery->first() != null)
        {
            throw new ApiException(ErrorCode::CARD_ALREADY_EXIST, trans('exceptions.backend.card.already_exist'));
        }
    }

    /**
     * @param $internalNumber
     * @return mixed
     * @throws ApiException
     */
    public function getByInternalNumber($restaurant_id, $internalNumber)
    {
        $card = $this->query()
            ->where('restaurant_id', $restaurant_id)
            ->where('internal_number', $internalNumber)->first();

        if ($card == null)
        {
            throw  new ApiException(ErrorCode::CARD_NOT_EXIST, trans('api.error.card_not_exist'));
        }

        return $card;
    }

    /**
     * @param Card $card
     * @param $input
     * @throws ApiException
     * @throws GeneralException
     */
    public function update(Card $card, $input)
    {
        $this->cardExist($input['number'], $input['internal_number'], $card);
        Log::info("restaurant update param:".json_encode($input));

        try
        {
            DB::beginTransaction();
            $card->update($input);

            DB::commit();
            return;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
        }

        throw new GeneralException(trans('exceptions.backend.card.update_error'));
    }
}
