<?php

namespace App\Modules\Repositories\DinningTime;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\DinningTime\DinningTime;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseDinningTimeRepository.
 */
class BaseDinningTimeRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = DinningTime::class;


    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }


    /**
     * @param $restaurant_id
     * @param $start_time
     * @param $end_time
     * @param null $time_id
     * @return bool
     */
    protected function isTimeConflict($restaurant_id, $start_time, $end_time, $time_id = null)
    {
        $query = DinningTime::where('restaurant_id', $restaurant_id);
        if ($time_id != null)
        {
            $query = $query->where('id', '<>', $time_id);
        }

        $time = $query
            ->where(function ($query) use ($start_time, $end_time){
                $query->where(function ($query) use ($start_time) {
                    $query->where('start_time', '<=', $start_time)
                        ->Where('end_time', '>', $start_time);
                })->orWhere(function ($query) use ($end_time) {
                    $query->where('start_time', '<', $end_time)
                        ->Where('end_time', '>=', $end_time);
                })->orWhere(function ($query) use ($start_time, $end_time) {
                    $query->where('start_time', '>', $start_time)
                        ->Where('end_time', '<', $end_time);
                });
            })
            ->first();

        if ($time != null)
        {
            return true;
        }

        return false;
    }

    /**
     * @param $input
     * @return DinningTime
     * @throws ApiException
     */
    public function create($input)
    {
        $dinningTime = $this->createDinningTimeStub($input);

        if ($this->isTimeConflict($input['restaurant_id'], $input['start_time'], $input['end_time']))
        {
            throw new ApiException(ErrorCode::DINNING_TIME_CONFLICT, trans('exceptions.backend.dinningTime.time_error'));
        }

        if ($dinningTime->save())
        {
            return $dinningTime;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.dinningTime.create_error'));
    }

    /**
     * @param DinningTime $dinningTime
     * @param $input
     * @return DinningTime
     * @throws ApiException
     */
    public function update(DinningTime $dinningTime, $input)
    {
        Log::info("restaurant update param:".json_encode($input));

        $start_time = $dinningTime->start_time;
        if (isset($input['start_time']))
        {
            $start_time = $input['start_time'];
        }

        $end_time = $dinningTime->end_time;
        if (isset($input['end_time']))
        {
            $end_time = $input['end_time'];
        }

        if ($this->isTimeConflict($dinningTime->restaurant_id, $start_time, $end_time, $dinningTime->id))
        {
            throw new ApiException(ErrorCode::DINNING_TIME_CONFLICT, trans('exceptions.backend.dinningTime.time_error'));
        }

        try
        {
            DB::beginTransaction();
            $dinningTime->update($input);

            DB::commit();
            return $dinningTime;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.dinningTime.update_error'));
    }

    /**
     * @param DinningTime $dinningTime
     * @param $enabled
     * @return DinningTime
     * @throws ApiException
     */
    public function mark(DinningTime $dinningTime, $enabled)
    {
        $dinningTime->enabled = $enabled;

        if ($dinningTime->save()) {
            return $dinningTime;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.dinningTime.mark_error'));
    }

    /**
     * @param $input
     * @return DinningTime
     */
    private function createDinningTimeStub($input)
    {
        $dinningTime = new DinningTime();
        $dinningTime->restaurant_id = $input['restaurant_id'];
        $dinningTime->name = $input['name'];
        $dinningTime->start_time = $input['start_time'];
        $dinningTime->end_time = $input['end_time'];

        return $dinningTime;
    }
}
