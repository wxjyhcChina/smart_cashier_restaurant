<?php

namespace App\Http\Controllers\Backend\PayMethod;

use App\Http\Requests\Backend\PayMethod\ManagePayMethodRequest;
use App\Modules\Enums\PayMethodType;
use App\Modules\Models\PayMethod\PayMethod;
use App\Repositories\Backend\PayMethod\PayMethodRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PayMethodController extends Controller
{
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
     * @param ManagePayMethodRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManagePayMethodRequest $request)
    {
        //
        return view('backend.payMethod.index');
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
     * @param  PayMethod $payMethod
     * @param ManagePayMethodRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit(PayMethod $payMethod, ManagePayMethodRequest $request)
    {
        //
        if ($payMethod->method == PayMethodType::ALIPAY)
        {
            return view('backend.payMethod.alipay')->withPayMethod($payMethod);
        }
        else if ($payMethod->method == PayMethodType::WECHAT_PAY)
        {
            return view('backend.payMethod.wechatPay')->withPayMethod($payMethod);
        }
        else
        {
            return redirect()->back()->withFlashDanger(trans('exceptions.backend.payMethod.update_error'));
        }
    }

    /**
     * @param PayMethod $payMethod
     * @param ManagePayMethodRequest $request
     * @return mixed
     */
    public function update(PayMethod $payMethod, ManagePayMethodRequest $request)
    {
        if ($payMethod->method == PayMethodType::ALIPAY)
        {
            $request->validate([
                'app_id' => 'required',
                'pid' => 'required',
                'pub_key' => 'required',
                'mch_private_key' => 'required',
            ]);
        }
        else if ($payMethod->method == PayMethodType::WECHAT_PAY)
        {
            $request->validate([
                'app_id' => 'required',
                'mch_id' => 'required',
                'mch_api_key' => 'required',
                'ssl_cert' => 'required',
                'ssl_key' => 'required',
            ]);
        }

        $this->payMethodRepo->update($payMethod, $request->all());

        return redirect()->route('admin.payMethod.index')->withFlashSuccess(trans('alerts.backend.payMethod.updated'));
    }

    /**
     * @param PayMethod $payMethod
     * @param $status
     * @param ManagePayMethodRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function mark(PayMethod $payMethod, $status, ManagePayMethodRequest $request)
    {
        $this->payMethodRepo->mark($payMethod, $status);

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.payMethod.updated'));
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
