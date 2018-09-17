<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\Card;

use App\Exceptions\GeneralException;
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
     * @return mixed
     */
    public function getForDataTable()
    {
        return $this->query();
    }


    /**
     * @param $input
     * @return Card
     * @throws GeneralException
     */
    public function create($input)
    {
        $card = $this->createCardStub($input);

        if ($card->save())
        {
            return $card;
        }

        throw new GeneralException(trans('exceptions.backend.card.create_error'));
    }

    /**
     * @param $sheets
     * @throws GeneralException
     * @return mixed
     */
    public function createMultipleCards($cards)
    {
        try{
            DB::beginTransaction();

            foreach ($cards as $input)
            {
                if ($input['number'] == null)
                {
                    continue;
                }
                $card = $this->createCardStub($input);

                $card->save();
            }

            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            throw new GeneralException(trans('exceptions.backend.card.create_error'));
        }
    }

    /**
     * @param Card $card
     * @param $input
     * @throws GeneralException
     */
    public function update(Card $card, $input)
    {
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

    /**
     * @param Card $card
     * @param $enabled
     * @return bool
     * @throws GeneralException
     */
    public function mark(Card $card, $enabled)
    {
        $card->enabled = $enabled;

        if ($card->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.card.mark_error'));
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
        $card->status = 0;

        return $card;
    }
}