<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\DinningTime\StoreDinningTimeRequest;
use App\Http\Requests\Api\DinningTime\UpdateDinningTimeRequest;
use App\Modules\Models\DinningTime\DinningTime;
use App\Repositories\Api\DinningTime\DinningTimeRepository;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::User();

        //$dinningTimes = $this->dinningTimeRepo->getByRestaurant($user->restaurant_id);
        $dinningTimes = $this->dinningTimeRepo->getByShopQuery($user->shop_id);

        return $this->responseSuccess($dinningTimes);
    }

    /**
     * @param StoreDinningTimeRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function store(StoreDinningTimeRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;
        $input['shop_id'] = Auth::User()->shop_id;

        $dinningTime = $this->dinningTimeRepo->create($input);

        return $this->responseSuccessWithObject($dinningTime);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * @param DinningTime $dinningTime
     * @param UpdateDinningTimeRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function update(DinningTime $dinningTime, UpdateDinningTimeRequest $request)
    {
        //
        $dinningTime = $this->dinningTimeRepo->update($dinningTime, $request->all());

        return $this->responseSuccessWithObject($dinningTime);
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
