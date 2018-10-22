@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.payMethod.management') . ' | ' . trans('labels.backend.payMethod.wechatPay'))

@section('after-styles')
    {{ Html::style("css/backend/plugin/datepicker/daterangepicker.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.payMethod.management') }}
        <small>{{ trans('labels.backend.payMethod.wechatPay') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($payMethod, ['route' => ['admin.payMethod.update', $payMethod], 'class' => 'form-horizontal', 'id'=>'edit-dinningTime-form','role' => 'form', 'method' => 'PATCH']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.payMethod.wechatPay') }}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {{ Form::label('app_id', trans('validation.attributes.backend.payMethod.wechat_pay.app_id').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('app_id', $payMethod->wechat_pay_detail != null ? $payMethod->wechat_pay_detail->app_id : null, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.payMethod.wechat_pay.app_id')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('mch_id', trans('validation.attributes.backend.payMethod.wechat_pay.mch_id').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('mch_id', $payMethod->wechat_pay_detail != null ? $payMethod->wechat_pay_detail->mch_id : null, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.payMethod.wechat_pay.mch_id')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('mch_api_key', trans('validation.attributes.backend.payMethod.wechat_pay.mch_api_key').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('mch_api_key', $payMethod->wechat_pay_detail != null ? $payMethod->wechat_pay_detail->mch_api_key : null, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.payMethod.wechat_pay.mch_api_key')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('ssl_cert', trans('validation.attributes.backend.payMethod.wechat_pay.ssl_cert').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::textarea('ssl_cert', $payMethod->wechat_pay_detail != null ? \Illuminate\Support\Facades\Storage::disk('cert')->get($payMethod->restaurant_id.'/'.$payMethod->wechat_pay_detail->ssl_cert_path) : null, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.payMethod.wechat_pay.ssl_cert')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('ssl_key', trans('validation.attributes.backend.payMethod.wechat_pay.ssl_key').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::textarea('ssl_key', $payMethod->wechat_pay_detail != null ? \Illuminate\Support\Facades\Storage::disk('cert')->get($payMethod->restaurant_id.'/'.$payMethod->wechat_pay_detail->ssl_key_path) : null, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.payMethod.wechat_pay.ssl_key')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.payMethod.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                {{ Form::submit(trans('buttons.general.crud.edit'), ['class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}

@stop