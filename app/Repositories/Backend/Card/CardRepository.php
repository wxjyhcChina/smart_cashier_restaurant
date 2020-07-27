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
use Illuminate\Support\Facades\Auth;

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

    /**
     * @param $input
     * @return Card
     * @throws GeneralException
     * @throws \App\Exceptions\Api\ApiException
     */
    public function create($input)
    {
        $this->cardExist($input['number'], $input['internal_number']);
        $card = $this->createCardStub($input);

        if ($card->save())
        {
            return $card;
        }

        throw new GeneralException(trans('exceptions.backend.card.create_error'));
    }

    /**
     * @param $input
     * @return Card
     */
    private function createCardStub($input)
    {
        $card = new Card();
        $card->number = $input['number'];
        $card->internal_number = isset($input['internal_number']) ? $input['internal_number'] : '';
        $card->restaurant_id = Auth::User()->restaurant_id;;
        $card->status = CardStatus::UNACTIVATED;

        return $card;
    }
    
}