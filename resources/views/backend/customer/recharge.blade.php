@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.customer.management') . ' | ' . trans('labels.backend.customer.recharge'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.customer.management') }}
        <small>{{ trans('labels.backend.customer.recharge') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($customer, ['route' => ['admin.customer.rechargeAndPay', $customer], 'class' => 'form-horizontal', 'id'=>'edit-customer-form','role' => 'form', 'method' => 'PATCH']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.customer.recharge') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.customer.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">

            <div class="form-group">
                {{ Form::label('id', trans('validation.attributes.backend.customer.id').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$customer->id}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('user_name', trans('validation.attributes.backend.customer.user_name').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$customer->user_name}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('card', trans('validation.attributes.backend.customer.card').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$customer->card->number}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('telephone', trans('validation.attributes.backend.customer.telephone').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$customer->telephone}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('department', trans('validation.attributes.backend.customer.department').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$customer->department != null ? $customer->department->name : ''}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('consume_category', trans('validation.attributes.backend.customer.consume_category').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$customer->consume_category != null ? $customer->consume_category->name : ''}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('consume_category', trans('validation.attributes.backend.customer.balance').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$customer->balance}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('money', trans('validation.attributes.backend.customer.money').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('money', null, ['class' => 'form-control', 'id' => 'money', 'placeholder' => trans('validation.attributes.backend.customer.money')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('pay_method', trans('validation.attributes.backend.customer.pay_method').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10" style="padding-top: 7px">
                    @foreach($payMethods as $key => $value)
                        <input type="radio" name="pay_method" value="{{$key}}">&nbsp;{{$value}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    @endforeach
                </div><!--col-lg-10-->

            </div><!--form control-->

        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.customer.index', trans('buttons.general.back'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                <button type="button" id="submit_button" class="btn btn-success btn-xs">{{trans('buttons.general.recharge')}}</button>
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}
@stop

@section('after-scripts')
    {{ Html::script("js/backend/jquery.scannerdetection.js") }}

    <script>
        var isPaying = false;

        $('#submit_button').on('click', function (e) {
            var pay_method = $("input[name='pay_method']:checked").val();
            var money = $("input[name='money']").val();

            if (!money)
            {
                swal({
                    title: "请输入金额",
                    type: "error",
                }, function(){

                });

                return;
            }

            if (pay_method === undefined)
            {
                swal({
                    title: "请选择支付方式",
                    type: "error",
                }, function(){

                });

                return;
            }
            else if (pay_method === "ALIPAY" || pay_method === "WECHAT_PAY")
            {
                isPaying = true;
                swal({
                    title: "请扫描支付码",
                    type: "info",
                    showCancelButton: true,
                    showConfirmButton: false,
                    cancelButtonText: "取消",
                }, function(inputValue){

                });
            }
            else
            {
                //submit recharge order
                submitOrder();
            }
        });

        $(document).scannerDetection({
            timeBeforeScanTest: 50, // wait for the next character for upto 200ms
            avgTimeByChar: 100, // it's not a barcode if a character takes longer than 100ms
            onComplete: function(barcode, qty){
                // $('#pTest').text(barcode);
                if (isPaying)
                {
                    swal.close();
                    isPaying = false;

                    //submit recharge order
                    submitOrder(barcode);
                }
                else
                {
                    //do noting
                }
            } // main callback function
        });

        function submitOrder(barcode = null) {
            var pay_method = $("input[name='pay_method']:checked").val();
            var money = $("input[name='money']").val();

            $.ajax({
                type: "POST",
                url: '{{ route("admin.customer.rechargeAndPay", $customer) }}',
                data: {
                    pay_method: pay_method,
                    money: money,
                    barcode: barcode,
                },
                dataType: "json",
                success: function (jsonResult) {
                    console.log(jsonResult.error_code);

                    if (jsonResult.error_code === undefined)
                    {
                        swal({
                            title: "充值成功",
                            type: "success",
                            timer: 2000,
                            showConfirmButton: false,
                        }, function () {
                            window.location.reload();
                        });
                    }
                    else
                    {
                        swal({
                            title: jsonResult.error_message,
                            type: "error",
                        }, function(){

                        });
                    }
                },
                fail: function () {
                }
            });
        }

    </script>
@stop