@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.payMethod.management') . ' | ' . trans('labels.backend.payMethod.alipay'))

@section('after-styles')
    {{ Html::style("css/backend/plugin/datepicker/daterangepicker.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.payMethod.management') }}
        <small>{{ trans('labels.backend.payMethod.alipay') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($payMethod, ['route' => ['admin.payMethod.update', $payMethod], 'class' => 'form-horizontal', 'id'=>'edit-dinningTime-form','role' => 'form', 'method' => 'PATCH']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.payMethod.alipay') }}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {{ Form::label('app_id', trans('validation.attributes.backend.payMethod.alipay.app_id').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('app_id', $payMethod->alipay_detail != null ? $payMethod->alipay_detail->app_id : null, ['class' => 'form-control', 'required',  'placeholder' => trans('validation.attributes.backend.payMethod.alipay.app_id')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('pid', trans('validation.attributes.backend.payMethod.alipay.pid').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('pid', $payMethod->alipay_detail != null ? $payMethod->alipay_detail->pid : null, ['class' => 'form-control', 'required',  'placeholder' => trans('validation.attributes.backend.payMethod.alipay.pid')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('pub_key', trans('validation.attributes.backend.payMethod.alipay.alipay_public_key').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::textarea('pub_key', $payMethod->alipay_detail != null ? \Illuminate\Support\Facades\Storage::disk('cert')->get($payMethod->restaurant_id.'/'.$payMethod->alipay_detail->pub_key_path) : null, ['class' => 'form-control', 'required',  'placeholder' => trans('validation.attributes.backend.payMethod.alipay.alipay_public_key')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('mch_private_key', trans('validation.attributes.backend.payMethod.alipay.mch_private_key').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::textarea('mch_private_key', $payMethod->alipay_detail != null ? \Illuminate\Support\Facades\Storage::disk('cert')->get($payMethod->restaurant_id.'/'.$payMethod->alipay_detail->mch_private_key_path) : null, ['class' => 'form-control', 'required',  'placeholder' => trans('validation.attributes.backend.payMethod.alipay.mch_private_key')]) }}
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