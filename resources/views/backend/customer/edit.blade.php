@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.customer.edit') . ' | ' . trans('labels.backend.customer.edit'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.customer.management') }}
        <small>{{ trans('labels.backend.customer.edit') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($customer, ['route' => ['admin.customer.update', $customer], 'class' => 'form-horizontal', 'id'=>'edit-customer-form','role' => 'form', 'method' => 'PATCH']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.customer.edit') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.customer.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {{ Form::label('user_name', trans('validation.attributes.backend.customer.user_name').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('user_name', $customer->user_name, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.customer.user_name')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('id_license', trans('validation.attributes.backend.customer.id_license').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('id_license', $customer->id_license, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.customer.id_license')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('birthday', trans('validation.attributes.backend.customer.birthday').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('birthday', $customer->birthday, ['class' => 'form-control', 'id' => 'birthday', 'placeholder' => trans('validation.attributes.backend.customer.birthday')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('telephone', trans('validation.attributes.backend.customer.telephone').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('telephone', $customer->telephone, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.customer.telephone')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('consume_category_id', trans('validation.attributes.backend.customer.consume_category').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::select('consume_category_id', $consumeCategories, $customer->consume_category_id, ['class' => 'form-control']) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('department_id', trans('validation.attributes.backend.customer.department').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::select('department_id', $departments, $customer->department_id, ['class' => 'form-control']) }}
                </div><!--col-lg-10-->
            </div><!--form control-->
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.customer.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                {{ Form::submit(trans('buttons.general.crud.edit'), ['class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}

@stop

@section('after-scripts')
    {{ Html::script("js/backend/plugin/moment/min/moment.min.js") }}
    {{ Html::script("js/backend/plugin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js") }}
    {{ Html::style("js/backend/plugin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css") }}

    <script>
        $(function() {
            $('#birthday').datetimepicker({format: 'YYYY-MM-DD'});
        });

    </script>


@stop