<?php

namespace App\Modules\Repositories\ConsumeRule;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\ConsumeRule\ConsumeRule;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class BaseConsumeCategoryRepository.
 */
class BaseConsumeRuleRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = ConsumeRule::class;


    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }

    /**
     * @param ConsumeRule $consumeRule
     * @return ConsumeRule
     */
    public function getConsumeRuleInfo(ConsumeRule $consumeRule)
    {
        return $consumeRule->load('dinning_time', 'consume_categories');
    }

    /**
     * @param $input
     * @return ConsumeRule
     * @throws ApiException
     */
    public function create($input)
    {
        $consumeRule = $this->createConsumeRuleStub($input);

        try
        {
            DB::beginTransaction();

            $consumeRule->save();

            $consumeRule->dinning_time()->attach($input['dinning_time']);
            $consumeRule->consume_categories()->attach($input['consume_categories']);

            DB::commit();

            return $consumeRule->load('dinning_time', 'consume_categories');
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.consumeRule.create_error'));
        }
    }

    /**
     * @param $consumeRule
     * @param $input
     * @return mixed
     * @throws ApiException
     */
    public function update(ConsumeRule $consumeRule, $input)
    {
        if (isset($input['weekday']))
        {
            $input['weekday'] = $this->getWeekday($input['weekday']);
        }

        try
        {
            DB::beginTransaction();
            $consumeRule->update($input);

            if (isset($input['dinning_time']))
            {
                $consumeRule->dinning_time()->detach();
                $consumeRule->dinning_time()->attach($input['dinning_time']);
            }

            if (isset($input['consume_categories']))
            {
                $consumeRule->consume_categories()->detach();
                $consumeRule->consume_categories()->attach($input['consume_categories']);
            }

            DB::commit();

            return $consumeRule->load('dinning_time', 'consume_categories');
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.consumeRule.update_error'));
        }
    }

    /**
     * @param $weekdayArray
     * @return float|int
     */
    private function getWeekday($weekdayArray)
    {
        $value = 0;
        foreach ($weekdayArray as $weekday)
        {
            $value += pow(2, intval($weekday));
        }

        return $value;
    }

    /**
     * @param $input
     * @return ConsumeRule
     */
    private function createConsumeRuleStub($input)
    {
        $consumeRule = new ConsumeRule();
        $consumeRule->restaurant_id = $input['restaurant_id'];
        $consumeRule->name = $input['name'];
        $consumeRule->discount = $input['discount'];
        $consumeRule->weekday = $this->getWeekday($input['weekday']);
        $consumeRule->enabled = isset($input['enabled']) ? $input['enabled'] : 1;

        return $consumeRule;
    }


    /**
     * @param ConsumeRule $consumeRule
     * @return bool
     * @throws \Exception
     */
    public function delete(ConsumeRule $consumeRule)
    {
        $consumeRule->delete();

        return true;
    }
}
