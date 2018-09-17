@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.card.management') . ' | ' . trans('labels.backend.card.create'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.card.management') }}
        <small>{{ trans('labels.backend.card.create') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.card.store', 'class' => 'form-horizontal', 'role' => 'form', 'id'=>'store-card-form', 'method' => 'post']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.card.create') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.card.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {{ Form::label('number', trans('validation.attributes.backend.card.number').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('number', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.card.number')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('internal_number', trans('validation.attributes.backend.card.internal_number').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('internal_number', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.card.internal_number')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.card.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}

@stop