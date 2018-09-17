@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.card.management') . ' | ' . trans('labels.backend.card.edit'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.card.management') }}
        <small>{{ trans('labels.backend.card.edit') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($card, ['route' => ['admin.card.update', $card], 'class' => 'form-horizontal', 'id'=>'edit-card-form','role' => 'form', 'method' => 'PATCH']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.card.edit') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.card.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {{ Form::label('number', trans('validation.attributes.backend.card.number').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('number', $card->number, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.card.number')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('internal_number', trans('validation.attributes.backend.card.internal_number').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('internal_number', $card->internal_number, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.card.internal_number')]) }}
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
                {{ Form::submit(trans('buttons.general.crud.edit'), ['class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}

@stop