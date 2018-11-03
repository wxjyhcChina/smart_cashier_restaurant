@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.consumeRule.management') . ' | ' . trans('labels.backend.consumeRule.edit'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.consumeRule.management') }}
        <small>{{ trans('labels.backend.consumeRule.edit') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($consumeRule, ['route' => ['admin.consumeRule.update', $consumeRule], 'class' => 'form-horizontal', 'id'=>'edit-$consume-rule-form','role' => 'form', 'method' => 'PATCH']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.consumeRule.edit') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.consumeRule.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.consumeRule.weekday').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10" style="padding-top: 7px">
                    @foreach($weekdays as $object)
                        {{ Form::checkbox('weekday[]', $object['id'], in_array($object['id'], $consumeRule->weekday)) }} {{$object['name']}}&nbsp&nbsp
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                {{ Form::label('dinning_time', trans('validation.attributes.backend.consumeRule.dinning_time').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10" style="padding-top: 7px">
                    @foreach($dinningTime as $object)
                        {{ Form::checkbox('dinning_time[]', $object->id, in_array($object->id, $consumeRule->dinning_time->pluck('id')->toArray())) }} {{$object->name}}&nbsp&nbsp
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                {{ Form::label('consume_categories', trans('validation.attributes.backend.consumeRule.consumeCategory').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10" style="padding-top: 7px">
                    @foreach($consumeCategories as $object)
                        {{ Form::checkbox('consume_categories[]', $object->id, in_array($object->id, $consumeRule->consume_categories->pluck('id')->toArray())) }} {{$object->name}}&nbsp&nbsp
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.consumeRule.name').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('name', $consumeRule->name, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.consumeRule.name')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('discount', trans('validation.attributes.backend.consumeRule.discount').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('discount', $consumeRule->discount, ['class' => 'form-control', 'min'=>0.1, 'max'=>9.9, "step" => 0.1, 'placeholder' => trans('validation.attributes.backend.consumeRule.discount')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.consumeRule.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                {{ Form::submit(trans('buttons.general.crud.edit'), ['class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}

@stop