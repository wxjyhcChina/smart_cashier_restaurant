<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Api\PayMethod\PayMethodRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PayMethodController extends Controller
{
    /**
     * @var PayMethodRepository
     */
    private $payMethodRepo;

    /**
     * PayMethodController constructor.
     * @param $payMethodRepo
     */
    public function __construct(PayMethodRepository $payMethodRepo)
    {
        $this->payMethodRepo = $payMethodRepo;
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

        //$payMethods = $this->payMethodRepo->getByRestaurant($user->restaurant_id);
        $payMethods = $this->payMethodRepo->getByShopQuery($user->shop_id);

        return $this->responseSuccess($payMethods);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
