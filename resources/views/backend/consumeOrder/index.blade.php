@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.consumeOrder.management'))

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
    {{ Html::style("css/backend/plugin/datepicker/daterangepicker.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.consumeOrder.management') }}
        <small>{{ trans('labels.backend.consumeOrder.active') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.consumeOrder.active') }}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <form class="form-horizontal">

                <div class="row">
                    {{ Form::label('search_time', trans('labels.backend.consumeOrder.searchTime'), ['class' => 'control-label', 'style'=>'float: left;padding-left: 35px;padding-right: 15px;']) }}

                    <div class="col-lg-4">
                        {{ Form::text('search_time', null, ['class' => 'form-control', 'id'=>'search_time', 'placeholder' => trans('labels.backend.consumeOrder.searchTime')]) }}
                    </div><!--col-lg-10-->

                    {{ Form::label('dinning_time', trans('labels.backend.consumeOrder.dinning_time'), ['class' => 'control-label', 'style'=>'float: left;padding-left: 35px;padding-right: 15px;']) }}

                    <div class="col-lg-4">
                        {{ Form::select('dinning_time_id', $dinningTime, null, ['id' => 'dinning_time_id', 'class' => 'form-control']) }}
                    </div><!--col-lg-10-->
                </div>

                <div class="row" style="margin-top: 20px">

                    {{ Form::label('pay_method', trans('labels.backend.consumeOrder.pay_method'), ['class' => 'control-label', 'style'=>'float: left;padding-left: 35px;padding-right: 15px;']) }}

                    <div class="col-lg-4">
                        {{ Form::select('pay_method', $payMethod, null, ['class' => 'form-control']) }}
                    </div><!--col-lg-10-->

                    {{ Form::label('restaurant_user', trans('labels.backend.consumeOrder.restaurant_user'), ['class' => 'control-label', 'style'=>'float: left;padding-left: 35px;padding-right: 15px;']) }}

                    <div class="col-lg-4">
                        {{ Form::select('restaurant_user_id', $restaurantUser, null, ['class' => 'form-control']) }}
                    </div><!--col-lg-10-->

                    <button type="button" class="btn btn-primary" id="search_btn">{{trans('labels.backend.consumeOrder.search')}}</button>

                </div>
            </form>

            <div class="row" style="margin-top: 20px">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3 id="order_count">0</h3>

                            <p>总订单数</p>
                            <p></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-social-yen"></i>
                        </div>
                        <div class="small-box-footer">&nbsp;</div>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3 id="money">0</h3>

                            <p>销售金额</p>
                            <p></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-social-yen"></i>
                        </div>
                        <div class="small-box-footer">&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table id="consume-order-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('labels.backend.consumeOrder.table.id') }}</th>
                            <th>{{ trans('labels.backend.consumeOrder.table.order_id') }}</th>
                            <th>{{ trans('labels.backend.consumeOrder.table.customer_id') }}</th>
                            <th>{{ trans('labels.backend.consumeOrder.table.card_id') }}</th>
                            <th>{{ trans('labels.backend.consumeOrder.table.price') }}</th>
                            <th>{{ trans('labels.backend.consumeOrder.table.pay_method') }}</th>
                            <th>{{ trans('labels.backend.consumeOrder.table.dinning_time') }}</th>
                            <th>{{ trans('labels.backend.consumeOrder.table.created_at') }}</th>
                            <th>{{ trans('labels.backend.consumeOrder.table.restaurant_user_id') }}</th>
                            <th>{{ trans('labels.backend.consumeOrder.table.status') }}</th>
                            <th>{{ trans('labels.general.actions') }}</th>
                        </tr>
                    </thead>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    {{ Html::style('css/backend/ionicons/css/ionicons.min.css') }}
    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables-extend.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables_locale.js") }}
    {{ Html::script("js/backend/plugin/datatables/page_select_with_ellipses.js") }}

    {{ Html::script("js/backend/plugin/moment/moment.js") }}
    {{ Html::script("js/backend/plugin/moment/locale/zh-cn.js") }}
    {{ Html::script("js/backend/plugin/daterangepicker/daterangepicker.js") }}

    <script>
        $(function() {
            var startDate = '{{$startTime}}';
            var endDate = '{{$endTime}}';

            var table = $('#consume-order-table').DataTable({
                dom: 'lfrtip',
                pagingType: "page_select_with_ellipses",
                processing: false,
                serverSide: true,
                autoWidth: false,
                stateSave: true,
                ajax: {
                    url: '{{ route("admin.consumeOrder.get") }}',
                    data: function ( d ) {
                        d.start_time = startDate;
                        d.end_time = endDate;
                        d.dinning_time_id = $('#dinning_time_id').val();
                        d.pay_method = $('#pay_method').val();
                        d.restaurant_user_id = $('#restaurant_user_id').val();
                    },
                    type: 'get',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'order_id', name: 'order_id'},
                    {data: 'customer_name', name: 'customers.user_name'},
                    {data: 'card_number', name: 'cards.number'},
                    {data: 'discount_price', name: 'discount_price'},
                    {data: 'pay_method', name: 'pay_method'},
                    {data: 'dinning_time_name', name: 'dinning_time.name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'restaurant_user_name', name: 'restaurant_users.username'},
                    {data: 'show_status', name: 'status'},
                    {data: 'actions', name: 'actions', orderable: false, 'searchable':false}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });


            $('#search_time').daterangepicker(
                {
                    "timePicker": true,
                    "timePicker24Hour": true,
                    "timePickerSeconds": true,
                    "startDate": startDate,
                    "endDate": endDate,
                    "locale": {
                        format: 'YYYY-MM-DD HH:mm:ss'
                    },
                },

                function(start, end) {
                    console.log("Callback has been called!");
                    startDate = start.format('YYYY-MM-DD HH:mm:ss');
                    endDate = end.format('YYYY-MM-DD HH:mm:ss');
                }
            );

            getSearchResult();

            $('#search_btn').click(function(e){
                table.ajax.reload();
                getSearchResult();
            });

            function getSearchResult() {
                $.ajax({
                    type: "GET",
                    url: '{{ route("admin.consumeOrder.searchOrder") }}',
                    data: {start_time: startDate,
                        end_time: endDate,
                        dinning_time_id: $('#dinning_time_id').val(),
                        pay_method: $('#pay_method').val(),
                        restaurant_user_id: $('#restaurant_user_id').val()
                    },
                    dataType: "json",
                    success: function (jsonResult) {
                        console.log(jsonResult);

                        $('#order_count').html(jsonResult.order_count);
                        $('#money').html(jsonResult.money);
                    },
                    fail: function () {
                    }
                });
            }
        });
    </script>
@stop