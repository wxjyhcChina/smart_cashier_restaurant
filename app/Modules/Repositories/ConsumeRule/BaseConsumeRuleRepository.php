<?php

namespace App\Modules\Repositories\ConsumeRule;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\ConsumeRule\ConsumeRule;
use App\Modules\Models\DinningTime\DinningTime;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * @param $restaurant_id
     * @param $weekdayArray
     * @param $dinningTimeArray
     * @param $consumeCategories
     * @param null $rule_id
     * @return bool
     * @throws ApiException
     */
    private function isRuleConflict($restaurant_id, $weekdayArray, $dinningTimeArray, $consumeCategories, $rule_id = null)
    {
        $weekday = $this->getWeekday($weekdayArray);

        foreach ($dinningTimeArray as $dinning_time)
        {
            $dinning_time = DinningTime::find($dinning_time);
            if ($dinning_time == null)
            {
                throw new ApiException(ErrorCode::DINNING_TIME_NOT_EXIST, trans('api.error.dinning_time_not_exist'));
            }

            $query = $dinning_time->consume_rules()
                ->where('restaurant_id', $restaurant_id)
                ->whereRaw('weekday & '.$weekday.' > 0');

            if ($rule_id != null)
            {
                $query->where('consume_rules.id', '<>', $rule_id);
            }

            foreach ($consumeCategories as $consumeCategory)
            {
                $rule = $query->whereHas('consume_categories', function ($query) use ($consumeCategory){
                    $query->where('consume_categories.id', $consumeCategory);
                })->first();

                if ($rule != null)
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param $input
     * @return ConsumeRule
     * @throws ApiException
     */
    public function create($input)
    {
        $isConflict = $this->isRuleConflict($input['restaurant_id'], $input['weekday'], $input['dinning_time'], $input['consume_categories']);
        if ($isConflict)
        {
            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('api.error.consume_rule_conflict'));
        }

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
        $isConflict = $this->isRuleConflict($input['restaurant_id'], $input['weekday'], $input['dinning_time'], $input['consume_categories'], $consumeRule->id);
        if ($isConflict)
        {
            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('api.error.consume_rule_conflict'));
        }

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
