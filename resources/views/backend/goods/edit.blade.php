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
    {{ Form::model($goods, ['route' => ['admin.goods.update', $goods], 'class' => 'form-horizontal', 'id'=>'edit-goods-form','role' => 'form', 'method' => 'PATCH']) }}

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
                    <div id="crop-avatar">
                        <div class="avatar-view avatar-view-customer custom-avatar-view" title="{{trans('labels.backend.goods.uploadImage')}}">
                            <input class="aspectRatio1" name="aspectRatio1" type="hidden" value='1'>
                            <input class="aspectRatio2" name="aspectRatio2" type="hidden" value='1'>
                            <input class="image_type" name="image_type" type="hidden" value='1'>
                            {{ Form::hidden('image', null, ['id' => 'image', 'class' => 'qiniuUrl']) }}
                            <img src="{{empty($goods->image) ? '../../../img/add_1_1.png' : $goods->image}}" alt="Avatar">
                        </div>
                    </div>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.goods.name').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('name', $goods->name, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.goods.name')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('price', trans('validation.attributes.backend.goods.price').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('price', $goods->price, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.goods.price')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('shop_id', trans('validation.attributes.backend.goods.shop_id').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::select('shop_id', $shops, $goods->shop_id, ['class' => 'form-control', 'required']) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('dinning_time_id', trans('validation.attributes.backend.goods.dinning_time_id').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::select('dinning_time_id', $dinningTime, $goods->dinning_time_id, ['class' => 'form-control', 'required']) }}
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

    @include('backend.includes.upload', ['uploadRoute' => 'admin.goods.uploadImage'])
@stop

@section('after-scripts')
    {{ Html::script('js/backend/upload/upload.js') }}
    {{ Html::style('css/backend/upload/upload.css') }}
    {{ Html::script('js/backend/plugin/cropper/cropper.js') }}
    {{ Html::style('css/backend/plugin/cropper/cropper.css') }}

    <script>
        $(function() {

        });

    </script>
@stop