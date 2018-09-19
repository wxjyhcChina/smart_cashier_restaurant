<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\DinningTime;

use App\Exceptions\GeneralException;
use App\Modules\Models\DinningTime\DinningTime;
use App\Modules\Repositories\DinningTime\BaseDinningTimeRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class DinningTimeRepository
 * @package App\Repositories\Backend\DinningTime
 */
class DinningTimeRepository extends BaseDinningTimeRepository
{
    private function isTimeConflict($restaurant_id, $start_time, $end_time, $time_id = null)
    {
        $query = DinningTime::where('restaurant_id', $restaurant_id);
        if ($time_id != null)
        {
            $query = $query->where('id', '<>', $time_id);
        }

        $time = $query
            ->where(function ($query) use ($start_time) {
                $query->where('start_time', '<=', $start_time)
                    ->Where('end_time', '>', $start_time);
            })
            ->orWhere(function ($query) use ($end_time) {
                $query->where('start_time', '<=', $end_time)
                    ->Where('end_time', '>', $end_time);
            })->first();

        if ($time != null)
        {
            return true;
        }

        return false;
    }

    /**
     * @param $input
     * @return DinningTime
     * @throws GeneralException
     */
    public function create($input)
    {
        $dinningTime = $this->createDinningTimeStub($input);

        if ($this->isTimeConflict($input['restaurant_id'], $input['start_time'], $input['end_time']))
        {
            throw new GeneralException(trans('exceptions.backend.dinningTime.time_error'));
        }

        if ($dinningTime->save())
        {
            return $dinningTime;
        }

        throw new GeneralException(trans('exceptions.backend.dinningTime.create_error'));
    }

    /**
     * @param DinningTime $dinningTime
     * @param $input
     * @throws GeneralException
     */
    public function update(DinningTime $dinningTime, $input)
    {
        Log::info("restaurant update param:".json_encode($input));

        if ($this->isTimeConflict($dinningTime->restaurant_id, $input['start_time'], $input['end_time'], $dinningTime->id))
        {
            throw new GeneralException(trans('exceptions.backend.dinningTime.time_error'));
        }

        try
        {
            DB::beginTransaction();
            $dinningTime->update($input);

            DB::commit();
            return;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
        }

        throw new GeneralException(trans('exceptions.backend.dinningTime.update_error'));
    }

    /**
     * @param DinningTime $dinningTime
     * @param $enabled
     * @return bool
     * @throws GeneralException
     */
    public function mark(DinningTime $dinningTime, $enabled)
    {
        $dinningTime->enabled = $enabled;

        if ($dinningTime->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.dinningTime.mark_error'));
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