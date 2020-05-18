<?php

namespace App\Http\Controllers\Backend\DinningTime;

use App\Exceptions\GeneralException;
use App\Http\Requests\Backend\DinningTime\ManageDinningTimeRequest;
use App\Http\Requests\Backend\DinningTime\StoreDinningTimeRequest;
use App\Modules\Models\DinningTime\DinningTime;
use App\Repositories\Backend\DinningTime\DinningTimeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DinningTimeController extends Controller
{
    /**
     * @var DinningTimeRepository
     */
    private $dinningTimeRepo;

    /**
     * DinningTimeController constructor.
     * @param $dinningTimeRepo
     */
    public function __construct(DinningTimeRepository $dinningTimeRepo)
    {
        $this->dinningTimeRepo = $dinningTimeRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ManageDinningTimeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageDinningTimeRequest $request)
    {
        //
        return view('backend.dinningTime.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ManageDinningTimeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(ManageDinningTimeRequest $request)
    {
        //
        return view('backend.dinningTime.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreDinningTimeRequest  $request
     * @return \Illuminate\Http\Response
     * @throws GeneralException
     */
    public function store(StoreDinningTimeRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;
        $input['shop_id'] = Auth::User()->shop_id;

        $this->dinningTimeRepo->create($input);

        return redirect()->route('admin.dinningTime.index')->withFlashSuccess(trans('alerts.backend.dinningTime.created'));
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
     * @param  DinningTime $dinningTime
     * @param  ManageDinningTimeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit(DinningTime $dinningTime, ManageDinningTimeRequest $request)
    {
        //
        return view('backend.dinningTime.edit')->withDinningTime($dinningTime);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  DinningTime $dinningTime
     * @param  StoreDinningTimeRequest $request
     * @return \Illuminate\Http\Response
     * @throws GeneralException
     */
    public function update(DinningTime $dinningTime, StoreDinningTimeRequest $request)
    {
        //
        $this->dinningTimeRepo->update($dinningTime, $request->all());

        return redirect()->route('admin.dinningTime.index')->withFlashSuccess(trans('alerts.backend.dinningTime.updated'));
    }


    /**
     * @param DinningTime $dinningTime
     * @param $status
     * @param ManageDinningTimeRequest $request
     * @return mixed
     * @throws GeneralException
     */
    public function mark(DinningTime $dinningTime, $status, ManageDinningTimeRequest $request)
    {
        $this->dinningTimeRepo->mark($dinningTime, $status);

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.dinningTime.updated'));
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
