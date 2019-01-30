@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.customer.management') . ' | ' . trans('labels.backend.customer.unbind_card'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.customer.management') }}
        <small>{{ trans('labels.backend.customer.unbind_card') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($customer, ['route' => ['admin.customer.doUnbindCard', $customer], 'class' => 'form-horizontal', 'id'=>'edit-customer-form','role' => 'form', 'method' => 'PATCH']) }}


    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.customer.unbind_card') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.customer.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {{ Form::label('id', trans('validation.attributes.backend.customer.id').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$customer->id}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('user_name', trans('validation.attributes.backend.customer.user_name').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$customer->user_name}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('telephone', trans('validation.attributes.backend.customer.telephone').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$customer->telephone}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('department', trans('validation.attributes.backend.customer.department').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$customer->department != null ? $customer->department->name : ''}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('consume_category', trans('validation.attributes.backend.customer.consume_category').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$customer->consume_category != null ? $customer->consume_category->name : ''}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('consume_category', trans('validation.attributes.backend.customer.balance').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$customer->balance}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

             <div class="form-group">
                {{ Form::label('card', trans('validation.attributes.backend.customer.card').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$customer->card->number}}</p>
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
                {{ Form::submit(trans('buttons.backend.customer.unbind'), ['class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}

@stop

