@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.stocks.management'))

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.stocks.management') }}
        <small>{{ trans('labels.backend.stocks.purchase') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.stocks.purchase') }}</h3>
            <div class="box-tools pull-right">
                @include('backend.stocks.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="stocks-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('labels.backend.stocks.table.id') }}</th>
                            <th>{{ trans('labels.backend.stocks.table.material_name') }}</th>
                            <th>{{ trans('labels.backend.stocks.table.count') }}</th>
                            <th>{{ trans('labels.backend.stocks.table.kilogram') }}</th>
                            <th>{{ trans('labels.backend.stocks.table.main_supplier') }}</th>
                            @permission('manage-frmLoss')
                            <th>{{ trans('labels.general.actions') }}</th>
                            @endauth
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
            var table=$('#stocks-table').DataTable({
                dom: 'lfrtip',
                pagingType: "page_select_with_ellipses",
                processing: false,
                serverSide: true,
                autoWidth: false,
                stateSave: true,
                ajax: {
                    url: '{{ route("admin.stocks.getPurchase") }}',
                    type: 'get',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'id',name:"id"},
                    {data:'name',name:'materials.name'},
                    {data:'count',name:'count'},
                    {   'targets': 0,
                        'searchable':false,
                        'orderable':false,
                        render:function(data, type, row){
                            var str ="<span class='label label-success'>千克</span>";
                            return str;
                        } },
                    {data:'main_supplier',name:'materials.main_supplier'},
                    {data: 'actions', name: 'actions', orderable: false, 'searchable':false}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });

            table.on('order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each(
                    function (cell, i) {
                        cell.innerHTML = i+1;
                    }
                );
            }).draw();
        });
    </script>
@stop