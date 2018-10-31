<?php

namespace App\Http\Controllers\Backend\ConsumeOrder;

use App\Http\Requests\Backend\ConsumeOrder\ManageConsumeOrderRequest;
use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Repositories\Backend\ConsumeOrder\ConsumeOrderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConsumeOrderController extends Controller
{
    /**
     * @var ConsumeOrderRepository
     */
    private $consumeOrderRepo;

    /**
     * ConsumeOrderTableController constructor.
     * @param $consumeOrderRepo
     */
    public function __construct(ConsumeOrderRepository $consumeOrderRepo)
    {
        $this->consumeOrderRepo = $consumeOrderRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ManageConsumeOrderRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageConsumeOrderRequest $request)
    {
        //
        return view('backend.consumeOrder.index');
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
     * @param  ConsumeOrder $consumeOrder
     * @param  ManageConsumeOrderRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ConsumeOrder $consumeOrder, ManageConsumeOrderRequest $request)
    {
        //
        return view('backend.consumeOrder.info')->withConsumeOrder($consumeOrder);
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
