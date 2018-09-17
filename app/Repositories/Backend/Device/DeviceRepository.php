<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\Device;

use App\Exceptions\GeneralException;
use App\Modules\Models\Device\Device;
use App\Modules\Repositories\Device\BaseDeviceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class DeviceRepository
 * @package App\Repositories\Backend\Device
 */
class DeviceRepository extends BaseDeviceRepository
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
     * @return Device
     * @throws GeneralException
     */
    public function create($input)
    {
        $device = $this->createDeviceStub($input);

        if ($device->save())
        {
            return $device;
        }

        throw new GeneralException(trans('exceptions.backend.device.create_error'));
    }

    /**
     * @param $devices
     * @throws GeneralException
     * @return mixed
     */
    public function createMultipleDevices($devices)
    {
        try{
            DB::beginTransaction();

            foreach ($devices as $input)
            {
                if ($input['serial_id'] == null)
                {
                    continue;
                }
                $device = $this->createDeviceStub($input);

                $device->save();
            }

            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            throw new GeneralException(trans('exceptions.backend.device.create_error'));
        }
    }

    /**
     * @param Device $device
     * @param $input
     * @throws GeneralException
     */
    public function update(Device $device, $input)
    {
        Log::info("restaurant update param:".json_encode($input));

        try
        {
            DB::beginTransaction();
            $device->update($input);

            DB::commit();
            return;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
        }

        throw new GeneralException(trans('exceptions.backend.device.update_error'));
    }

    /**
     * @param Device $device
     * @param $enabled
     * @return bool
     * @throws GeneralException
     */
    public function mark(Device $device, $enabled)
    {
        $device->enabled = $enabled;

        if ($device->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.device.mark_error'));
    }

    /**
     * @param $input
     * @return Device
     */
    private function createDeviceStub($input)
    {
        $device = new Device();
        $device->serial_id = $input['serial_id'];

        return $device;
    }
}