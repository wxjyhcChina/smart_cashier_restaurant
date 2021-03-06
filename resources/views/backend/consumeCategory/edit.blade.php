@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.consumeCategory.management') . ' | ' . trans('labels.backend.consumeCategory.edit'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.consumeCategory.management') }}
        <small>{{ trans('labels.backend.consumeCategory.edit') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($consumeCategory, ['route' => ['admin.consumeCategory.update', $consumeCategory], 'class' => 'form-horizontal', 'id'=>'edit-$consume-category-form','role' => 'form', 'method' => 'PATCH']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.consumeCategory.edit') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.consumeCategory.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.consumeCategory.name').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('name', $consumeCategory->name, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.consumeCategory.name')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.consumeCategory.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                {{ Form::submit(trans('buttons.general.crud.edit'), ['class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}

@stop