<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\DinningTime\StoreDinningTimeRequest;
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

        $dinningTimes = $this->dinningTimeRepo->getByRestaurant($user->restaurant_id);

        return $this->responseSuccess($dinningTimes);
    }

    /**
     * @param StoreDinningTimeRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function create(StoreDinningTimeRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;

        $dinningTime = $this->dinningTimeRepo->create($input);

        return $this->responseSuccessWithObject($dinningTime);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function update(DinningTime $dinningTime, Request $request)
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
