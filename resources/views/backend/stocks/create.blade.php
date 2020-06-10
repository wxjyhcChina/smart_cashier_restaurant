@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.stocks.management') . ' | ' . trans('labels.backend.stocks.create'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.stocks.management') }}
        <small>{{ trans('labels.backend.stocks.create') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.stocks.store', 'class' => 'form-horizontal', 'role' => 'form', 'id'=>'store-shop-form', 'method' => 'post']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.stocks.create') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.shop.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.stocks.name').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.stocks.name')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                <!--col-lg-1-->
                {{ Form::label('main_supplier', trans('labels.backend.stocks.table.main_supplier').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('main_supplier', null, ['class' => 'form-control', 'required', 'placeholder' => trans('labels.backend.stocks.table.main_supplier')]) }}
                </div>
            </div><!--form control-->

        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.shop.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}

@stop