@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.customer.management') . ' | ' . trans('labels.backend.customer.change_all_balance'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.customer.management') }}
        <small>{{ trans('labels.backend.customer.change_all_balance') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => ['admin.customer.changeAllBalanceStore'], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) }}

    <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.customer.change_all_balance') }}</h3>

                <div class="box-tools pull-right">
                    @include('backend.customer.includes.partials.header-buttons')
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('source', trans('validation.attributes.backend.customer.source'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::select('source', array('SYSTEM_ADD' => trans('labels.backend.customer.system_add'), 'SYSTEM_MINUS' => trans('labels.backend.customer.system_minus')), '3', ['class' => 'form-control']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('balance', trans('validation.attributes.backend.customer.balance'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('balance', null, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.customer.balance')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    {{ link_to_route('admin.customer.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                </div><!--pull-left-->

                <div class="pull-right">
                    {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) }}
                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    {{ Form::close() }}
@stop
