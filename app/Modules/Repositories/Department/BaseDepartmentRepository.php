<?php

namespace App\Modules\Repositories\Department;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Department\Department;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseDepartmentRepository.
 */
class BaseDepartmentRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Department::class;


    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }

    /**
     * @param $input
     * @return Department
     * @throws ApiException
     */
    public function create($input)
    {
        $department = $this->createDepartmentStub($input);

        if ($department->save())
        {
            return $department;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.department.create_error'));
    }

    /**
     * @param Department $department
     * @param $input
     * @throws ApiException
     */
    public function update(Department $department, $input)
    {
        Log::info("restaurant update param:".json_encode($input));

        try
        {
            DB::beginTransaction();
            $department->update($input);

            DB::commit();
            return;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.department.update_error'));
    }

    /**
     * @param Department $department
     * @param $enabled
     * @return bool
     * @throws ApiException
     */
    public function mark(Department $department, $enabled)
    {
        $department->enabled = $enabled;

        if ($department->save()) {
            return true;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.department.mark_error'));
    }

    /**
     * @param $input
     * @return Department
     */
    private function createDepartmentStub($input)
    {
        $depatment = new Department();
        $depatment->restaurant_id = $input['restaurant_id'];
        $depatment->code = $input['code'];
        $depatment->name = $input['name'];

        return $depatment;
    }
}
