@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.goodCategory.management') . ' | ' . trans('labels.backend.goodCategory.create'))

@section('after-styles')
    {{ Html::style("css/backend/plugin/datepicker/daterangepicker.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.goodCategory.management') }}
        <small>{{ trans('labels.backend.goodCategory.create') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.goodCategory.store', 'class' => 'form-horizontal', 'role' => 'form', 'id'=>'store-goods-form', 'method' => 'post']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.goodCategory.create') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.goodCategory.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.goodCategory.name').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.goodCategory.name')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->


            <div class="form-group">
                {{ Form::label('shop_id', trans('validation.attributes.backend.goods.shop_id').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::select('shop_id', $shops, null, ['class' => 'form-control', 'required']) }}
                </div><!--col-lg-10-->
            </div><!--form control-->
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.goodCategory.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) }}
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