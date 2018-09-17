<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\Department;

use App\Exceptions\GeneralException;
use App\Modules\Models\Department\Department;
use App\Modules\Repositories\Department\BaseDepartmentRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class DepartmentRepository
 * @package App\Repositories\Backend\Department
 */
class DepartmentRepository extends BaseDepartmentRepository
{
    /**
     * @param $input
     * @return Department
     * @throws GeneralException
     */
    public function create($input)
    {
        $department = $this->createDepartmentStub($input);

        if ($department->save())
        {
            return $department;
        }

        throw new GeneralException(trans('exceptions.backend.department.create_error'));
    }

    /**
     * @param Department $department
     * @param $input
     * @throws GeneralException
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

        throw new GeneralException(trans('exceptions.backend.department.update_error'));
    }

    /**
     * @param Department $department
     * @param $enabled
     * @return bool
     * @throws GeneralException
     */
    public function mark(Department $department, $enabled)
    {
        $department->enabled = $enabled;

        if ($department->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.department.mark_error'));
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