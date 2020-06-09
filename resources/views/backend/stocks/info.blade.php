@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.stocks.management') . ' | ' . trans('labels.backend.stocks.edit'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.stocks.management') }}
        <small>{{ trans('labels.backend.stocks.edit') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($stock, ['route' => ['admin.stocks.update', $stock], 'class' => 'form-horizontal', 'id'=>'edit-stock-form','role' => 'form', 'method' => 'PATCH']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.stocks.edit') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.stocks.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.stocks.name').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-4">
                    {{ Form::text('name', $materials[0]->name, ['class' => 'form-control', 'disabled', 'placeholder' => trans('validation.attributes.backend.stocks.name')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('default', trans('validation.attributes.backend.stocks.count').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-4" style="margin-top: 8px">
                    {{ Form::number('count', $stock->count, ['class' => 'form-control', 'min' => 0, 'required', 'placeholder' => trans('validation.attributes.backend.stocks.count')]) }}
                </div><!--col-lg-1-->
            </div><!--form control-->
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.stocks.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                {{ Form::submit(trans('buttons.general.crud.edit'), ['class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}

@stop