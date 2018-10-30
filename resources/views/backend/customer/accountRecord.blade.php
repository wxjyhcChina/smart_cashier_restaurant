@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.customer.management'))

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.customer.management') }}
        <small>{{ $customer->user_name.trans('labels.backend.customer.accountRecord.record') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $customer->user_name.trans('labels.backend.customer.accountRecord.record') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.customer.includes.partials.score-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="order-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('labels.backend.customer.accountRecord.table.id') }}</th>
                            <th>{{ trans('labels.backend.customer.accountRecord.table.money') }}</th>
                            <th>{{ trans('labels.backend.customer.accountRecord.table.pay_method_id') }}</th>
                            <th>{{ trans('labels.backend.customer.accountRecord.table.consume_order') }}</th>
                            <th>{{ trans('labels.backend.customer.accountRecord.table.recharge_order') }}</th>
                            <th>{{ trans('labels.backend.customer.accountRecord.table.created_at') }}</th>
                            <th>{{ trans('labels.backend.customer.accountRecord.table.type') }}</th>
                        </tr>
                    </thead>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/page_select_with_ellipses.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables-extend.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables_locale.js") }}

    <script>
        $(function() {

            $('#order-table').DataTable({
                dom: 'lfrtip',
                pagingType: "page_select_with_ellipses",
                processing: false,
                serverSide: true,
                autoWidth: false,
                stateSave: true,
                ajax: {
                    url: '{{ route("admin.customer.getAccountRecords", $customer->id) }}',
                    type: 'get',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'money', name: 'money'},
                    {data: 'pay_method', name: 'pay_method'},
                    {data: 'consume_order.order_id', name: 'consume_order.order_id',  defaultContent: ''},
                    {data: 'recharge_order.order_id', name: 'recharge_order.order_id',  defaultContent: ''},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'show_name', name: 'type'}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });
        });
    </script>
@stop