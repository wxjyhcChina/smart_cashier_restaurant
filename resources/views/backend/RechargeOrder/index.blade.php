@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.rechargeOrder.management'))

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.rechargeOrder.management') }}
        <small>{{ trans('labels.backend.rechargeOrder.active') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.rechargeOrder.active') }}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="department-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('labels.backend.rechargeOrder.table.id') }}</th>
                            <th>{{ trans('labels.backend.rechargeOrder.table.order_id') }}</th>
                            <th>{{ trans('labels.backend.rechargeOrder.table.customer_id') }}</th>
                            <th>{{ trans('labels.backend.rechargeOrder.table.card_id') }}</th>
                            <th>{{ trans('labels.backend.rechargeOrder.table.pay_method') }}</th>
                            <th>{{ trans('labels.backend.rechargeOrder.table.price') }}</th>
                            <th>{{ trans('labels.backend.rechargeOrder.table.created_at') }}</th>
                            <th>{{ trans('labels.backend.rechargeOrder.table.restaurant_user_id') }}</th>
                            <th>{{ trans('labels.backend.rechargeOrder.table.status') }}</th>
                        </tr>
                    </thead>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables-extend.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables_locale.js") }}
    {{ Html::script("js/backend/plugin/datatables/page_select_with_ellipses.js") }}

    <script>
        $(function() {
            $('#department-table').DataTable({
                dom: 'lfrtip',
                pagingType: "page_select_with_ellipses",
                processing: false,
                serverSide: true,
                autoWidth: false,
                stateSave: true,
                ajax: {
                    url: '{{ route("admin.rechargeOrder.get") }}',
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
                    {data: 'pay_method', name: 'pay_method'},
                    {data: 'money', name: 'money'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'restaurant_user_name', name: 'restaurant_users.username'},
                    {data: 'show_status', name: 'status'},
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });
        });
    </script>
@stop