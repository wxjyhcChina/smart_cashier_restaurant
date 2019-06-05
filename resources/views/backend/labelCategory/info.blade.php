@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.labelCategory.management') . ' | ' . trans('labels.backend.labelCategory.edit'))

@section('after-styles')
    {{ Html::style("css/backend/plugin/datepicker/daterangepicker.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.labelCategory.management') }}
        <small>{{ trans('labels.backend.labelCategory.edit') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($labelCategory, ['route' => ['admin.labelCategory.edit', $labelCategory], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'get']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.goods.edit') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.goods.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {{ Form::label('image', trans('validation.attributes.backend.goods.image'), ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <img height="100px" width="100px" src="{{empty($labelCategory->image) ? "../../../img/category.png" : $labelCategory->image}}" alt="Avatar">
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.labelCategory.name').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$labelCategory->name}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.labelCategory.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                {{ Form::submit(trans('buttons.general.crud.edit'), ['class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}
@stop