@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.device.management') . ' | ' . trans('labels.backend.device.edit'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.device.management') }}
        <small>{{ trans('labels.backend.device.edit') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($device, ['route' => ['admin.device.update', $device], 'class' => 'form-horizontal', 'id'=>'edit-device-form','role' => 'form', 'method' => 'PATCH']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.device.edit') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.device.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {{ Form::label('serial_id', trans('validation.attributes.backend.device.serial_id').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('serial_id', $device->serial_id, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.device.serial_id')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.device.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                {{ Form::submit(trans('buttons.general.crud.edit'), ['class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}

@stop