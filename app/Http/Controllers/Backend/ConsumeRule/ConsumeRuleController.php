<?php

namespace App\Http\Controllers\Backend\ConsumeRule;

use App\Http\Requests\Api\ConsumeRule\StoreConsumeRuleRequest;
use App\Http\Requests\Backend\ConsumeRule\ManageConsumeRuleRequest;
use App\Modules\Models\ConsumeRule\ConsumeRule;
use App\Repositories\Backend\ConsumeRule\ConsumeRuleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConsumeRuleController extends Controller
{
    private $consumeRuleRepo;

    /**
     * ConsumeRuleController constructor.
     * @param $consumeRuleRepo
     */
    public function __construct(ConsumeRuleRepository $consumeRuleRepo)
    {
        $this->consumeRuleRepo = $consumeRuleRepo;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('backend.consumeRule.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('backend.consumeRule.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreConsumeRuleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreConsumeRuleRequest $request)
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
     * @param ConsumeRule $consumeRule
     * @param ManageConsumeRuleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit(ConsumeRule $consumeRule, ManageConsumeRuleRequest $request)
    {
        //
        return view('backend.consumeRule.create');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ConsumeRule $consumeRule
     * @param  ManageConsumeRuleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ConsumeRule $consumeRule, ManageConsumeRuleRequest $request)
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
