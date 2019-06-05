@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.shop.management') . ' | ' . trans('labels.backend.shop.create'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.shop.management') }}
        <small>{{ trans('labels.backend.shop.create') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.shop.store', 'class' => 'form-horizontal', 'role' => 'form', 'id'=>'store-shop-form', 'method' => 'post']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.shop.create') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.shop.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.shop.name').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.shop.name')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('default', trans('validation.attributes.backend.shop.default').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10" style="margin-top: 8px">
                    {{ Form::checkbox('default', null, false) }}
                </div><!--col-lg-1-->
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