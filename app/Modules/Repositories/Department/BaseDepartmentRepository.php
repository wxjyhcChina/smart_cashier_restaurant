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

    public function getByShopQuery($shop_id)
    {
        return $this->query()->where('shop_id', $shop_id);
    }

    private function departmentExist($code, $name, $updateDepartment = null,$shop_id)
    {
        $departmentQuery = Department::query();

        if ($updateDepartment != null)
        {
            $departmentQuery = $departmentQuery->where('id', '<>', $updateDepartment->id);
        }

        $departmentQuery = $departmentQuery->where(function ($query) use ($name, $code,$shop_id){
            $query->where('code', $code)->where('name', $name)->where('shop_id',$shop_id);
        });

        if ($departmentQuery->first() != null)
        {
            throw new ApiException(ErrorCode::DEPARTMENT_ALREADY_EXIST, trans('exceptions.backend.department.already_exist'));
        }
    }

    /**
     * @param $input
     * @return Department
     * @throws ApiException
     */
    public function create($input)
    {
        $this->departmentExist($input['code'], $input['name'],null,$input['shop_id']);
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
        $this->departmentExist($input['code'], $input['name'], $department,$input['shop_id']);
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
        $depatment->shop_id = $input['shop_id'];

        return $depatment;
    }
}
