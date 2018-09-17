<?php

namespace App\Http\Controllers\Backend\Device;

use App\Exceptions\GeneralException;
use App\Http\Requests\Backend\Device\ManageDeviceRequest;
use App\Http\Requests\Backend\Device\StoreDeviceRequest;
use App\Modules\Models\Device\Device;
use App\Repositories\Backend\Device\DeviceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeviceController extends Controller
{
    /**
     * @var DeviceRepository
     */
    private $deviceRepo;

    /**
     * CardController constructor.
     * @param $deviceRepo
     */
    public function __construct(DeviceRepository $deviceRepo)
    {
        $this->deviceRepo = $deviceRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ManageDeviceRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageDeviceRequest $request)
    {
        //
        return view('backend.device.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ManageDeviceRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(ManageDeviceRequest $request)
    {
        //
        return view('backend.device.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreDeviceRequest  $request
     * @return \Illuminate\Http\Response
     * @throws GeneralException
     */
    public function store(StoreDeviceRequest $request)
    {
        //
        $this->deviceRepo->create($request->all());

        return redirect()->route('admin.device.index')->withFlashSuccess(trans('alerts.backend.device.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Device $device
     * @param ManageDeviceRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Device $device, ManageDeviceRequest $request)
    {
        //
        return view('backend.device.edit')->withDevice($device);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Device $device
     * @param  ManageDeviceRequest $request
     * @return \Illuminate\Http\Response
     * @throws GeneralException
     */
    public function update(Device $device, ManageDeviceRequest $request)
    {
        //
        $this->deviceRepo->update($device, $request->all());

        return redirect()->route('admin.device.index')->withFlashSuccess(trans('alerts.backend.device.updated'));
    }

    /**
     * @param Device $device
     * @param $status
     * @param ManageDeviceRequest $request
     * @return mixed
     * @throws GeneralException
     */
    public function mark(Device $device, $status, ManageDeviceRequest $request)
    {
        $this->deviceRepo->mark($device, $status);

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.device.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
