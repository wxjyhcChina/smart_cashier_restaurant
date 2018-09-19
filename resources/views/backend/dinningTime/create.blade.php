@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.dinningTime.management') . ' | ' . trans('labels.backend.dinningTime.create'))

@section('after-styles')
    {{ Html::style("css/backend/plugin/datepicker/daterangepicker.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.dinningTime.management') }}
        <small>{{ trans('labels.backend.dinningTime.create') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.dinningTime.store', 'class' => 'form-horizontal', 'role' => 'form', 'id'=>'store-dinningTime-form', 'method' => 'post']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.dinningTime.create') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.dinningTime.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.dinningTime.name').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.dinningTime.name')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('time', trans('validation.attributes.backend.dinningTime.time').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('start_time', null, ['class' => 'form-control', 'required', 'style'=>'margin-bottom:3px', 'id' => 'start_time', 'placeholder' => trans('validation.attributes.backend.dinningTime.start_time')]) }}
                    {{ Form::text('end_time', null, ['class' => 'form-control', 'required', 'style'=>'margin-bottom:3px', 'id' => 'end_time', 'placeholder' => trans('validation.attributes.backend.dinningTime.end_time')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.dinningTime.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}

@stop

@section('after-scripts')
    {{ Html::script("js/backend/plugin/moment/min/moment.min.js") }}
    {{ Html::script("js/backend/plugin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js") }}
    {{ Html::style("js/backend/plugin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css") }}

    <script>
        $(function() {
            $('#start_time').datetimepicker({format: 'HH:mm'});
            $('#end_time').datetimepicker({format: 'HH:mm'});
        });

    </script>
@stop