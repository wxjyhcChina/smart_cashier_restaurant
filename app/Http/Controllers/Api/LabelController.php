<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Api\Label\LabelRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LabelController extends Controller
{
    /**
     * @var LabelRepository
     */
    private $labelRepo;

    /**
     * LabelController constructor.
     * @param $labelRepo
     */
    public function __construct(LabelRepository $labelRepo)
    {
        $this->labelRepo = $labelRepo;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function index(Request $request)
    {
        //
        $conditions = $request->all();

        $label = $this->labelRepo->findOne($conditions);

        return $this->responseSuccessWithObject($label);
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
