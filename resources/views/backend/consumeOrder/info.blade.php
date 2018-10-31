@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.consumeOrder.management') . ' | ' . trans('labels.backend.consumeOrder.info'))

@section('after-styles')
    {{ Html::style("css/backend/plugin/datepicker/daterangepicker.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.consumeOrder.management') }}
        <small>{{ trans('labels.backend.consumeOrder.info') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['class' => 'form-horizontal', 'role' => 'form', 'method' => 'get']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.consumeOrder.info') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.goods.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.consumeOrder.order_id').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$consumeOrder->order_id}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.consumeOrder.customer').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$consumeOrder->customer != null ? $consumeOrder->customer->user_name : ''}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.consumeOrder.card').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$consumeOrder->card != null ? $consumeOrder->card->number : ''}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.consumeOrder.consume_category').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$consumeOrder->consume_category ? $consumeOrder->consume_category->name : ''}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.consumeOrder.price').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$consumeOrder->price}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.consumeOrder.discount_price').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$consumeOrder->discount_price}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.consumeOrder.goods').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <table id="users-table" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>{{ trans('validation.attributes.backend.consumeOrder.goods_name') }}</th>
                            <th>{{ trans('validation.attributes.backend.consumeOrder.price') }}</th>

                        </thead>
                        <tbody>
                        @foreach ($consumeOrder->goods as $good)
                            <tr>
                                <td>{{$good->name}}</td>
                                <td>{{$good->price}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.consumeOrder.pay_method').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$consumeOrder->pay_method}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.consumeOrder.status').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{!! $consumeOrder->getShowStatusAttribute() !!}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('name', trans('validation.attributes.backend.consumeOrder.external_pay_no').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    <p style="padding-top: 7px">{{$consumeOrder->external_pay_no}}</p>
                </div><!--col-lg-10-->
            </div><!--form control-->

        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.consumeOrder.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}
@stop