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
            <div class="table-responsive">
                <table id="consume-order-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('labels.backend.consumeOrder.table.id') }}</th>
{{--                            <th>{{ trans('labels.backend.consumeOrder.table.order_id') }}</th>--}}
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
                    // {data: 'order_id', name: 'order_id'},
                    {data: 'customer_name', name: 'customers.user_name'},
                    {data: 'card_number', name: 'cards.number'},
                    {data: 'discount_price', name: 'discount_price'},
                    {data: 'show_pay_method', name: 'pay_method'},
                    {data: 'dinning_time_name', name: 'dinning_time.name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'restaurant_user_name', name: 'restaurant_users.username'},
                    {data: 'show_status', name: 'status'},
                    {data: 'actions', name: 'actions', orderable: false, 'searchable':false},
                    {data: 'restaurant_last_name', name: 'restaurant_users.last_name', visible:false},
                    {data: 'restaurant_first_name', name: 'restaurant_users.first_name', visible:false}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });
        });
    </script>
@stop