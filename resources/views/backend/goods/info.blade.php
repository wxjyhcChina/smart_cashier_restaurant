@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.goods.management') . ' | ' . trans('labels.backend.goods.edit'))

@section('after-styles')
    {{ Html::style("css/backend/plugin/datepicker/daterangepicker.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.goods.management') }}
        <small>{{ trans('labels.backend.goods.edit') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($goods, ['route' => ['admin.shop.edit', $goods], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'get']) }}

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
                    <img height="100px" width="100px" src="{{empty($goods->image) ? "../../../header.png" : $goods->image}}" alt="Avatar">
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.goods.name').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$goods->name}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('price', trans('validation.attributes.backend.goods.price').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$goods->price}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('shop_id', trans('validation.attributes.backend.goods.shop_id').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$goods->shop->name}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('dinning_time_id', trans('validation.attributes.backend.goods.dinning_time_id').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$goods->dinning_time->name}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.goods.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                {{ Form::submit(trans('buttons.general.crud.edit'), ['class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}
@stop