@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.customer.management') . ' | ' . trans('labels.backend.customer.recharge'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.customer.management') }}
        <small>{{ trans('labels.backend.customer.recharge') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($customer, ['route' => ['admin.customer.getRechargeQrUrl', $customer], 'class' => 'form-horizontal', 'id'=>'edit-customer-form','role' => 'form', 'method' => 'PATCH']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.entityCard.recharge') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.entityCard.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">

            <div class="form-group">
                {{ Form::label('id', trans('validation.attributes.backend.entityCard.id').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$entityCard->id}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('internal_id', trans('validation.attributes.backend.entityCard.userName').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$entityCard->user_name}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('amount', trans('validation.attributes.backend.entityCard.money').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10" style="padding-top: 7px">
                    @for($i = 0; $i < count($rechargeAmounts); $i++)
                        <input type="radio" name="amount" value="{{$rechargeAmounts[$i]->amount}}" {{isset($amount) ? ($amount == $rechargeAmounts[$i]->amount ? 'checked' : '') : ($i == 0 ? 'checked' : '') }}>&nbsp;{{$rechargeAmounts[$i]->amount}}元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    @endfor
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('pay_method', trans('validation.attributes.backend.entityCard.payMethod').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10" style="padding-top: 7px">
                    <input type="radio" name="pay_method" value="1" {{isset($payMethod) ? ($payMethod == 1 ? 'checked' : '') : 'checked' }}>&nbsp;{{trans('labels.backend.pay_method.alipay')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="pay_method" value="2" {{isset($payMethod) ? ($payMethod == 2 ? 'checked' : '') : '' }}>&nbsp;{{trans('labels.backend.pay_method.wechat')}}
                </div><!--col-lg-10-->

            </div><!--form control-->

            @if(isset($qrInfo))
            <div class="form-group">
                {{ Form::label('qrcode', trans('validation.attributes.backend.entityCard.qrcode').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10" id="qr_area">
                    {!! $qrInfo !!}
                </div>
            </div>
            @endif

        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.entityCard.index', trans('buttons.general.back'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                {{ Form::submit(trans('buttons.general.crud.generatePayQR'), ['class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}
@stop

@section('after-scripts')
    <script>
        $('#activate').change(function() {
            if($(this).is(":checked")) {
                $('#telephone').prop('required',true);
                $('#user_name').prop('required',true);
            }
            else
            {
                $('#telephone').prop('required',false);
                $('#user_name').prop('required',false);
            }
        });

    </script>


@stop