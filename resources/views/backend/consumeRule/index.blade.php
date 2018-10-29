@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.consumeRule.management'))

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.consumeRule.management') }}
        <small>{{ trans('labels.backend.consumeRule.active') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.consumeRule.active') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.consumeRule.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="consume-category-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('labels.backend.consumeRule.table.id') }}</th>
                            <th>{{ trans('labels.backend.consumeRule.table.name') }}</th>
                            <th>{{ trans('labels.backend.consumeRule.table.weekday') }}</th>
                            <th>{{ trans('labels.backend.consumeRule.table.dinningTime') }}</th>
                            <th>{{ trans('labels.backend.consumeRule.table.consumeCategory') }}</th>
                            <th>{{ trans('labels.backend.consumeRule.table.discount') }}</th>
                            <th>{{ trans('labels.backend.consumeRule.table.created_at') }}</th>
                            <th>{{ trans('labels.general.actions') }}</th>
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
            $('#consume-category-table').DataTable({
                dom: 'lfrtip',
                pagingType: "page_select_with_ellipses",
                processing: false,
                serverSide: false,
                autoWidth: false,
                stateSave: true,
                ajax: {
                    url: '{{ route("admin.consumeRule.get") }}',
                    type: 'get',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'weekday', name: 'weekday', render:function (data, type, row, meta){
                        var arr = [];
                        data.forEach(function (e) {
                            e = parseInt(e);
                            if (e === 0)
                            {
                                arr.push('周日');
                            }
                            else if (e === 1)
                            {
                                arr.push('周一');
                            }
                            else if (e === 2)
                            {
                                arr.push('周二');
                            }
                            else if (e === 3)
                            {
                                arr.push('周三');
                            }
                            else if (e === 4)
                            {
                                arr.push('周四');
                            }
                            else if (e === 5)
                            {
                                arr.push('周五️');
                            }
                            else if (e === 6)
                            {
                                arr.push('周六');
                            }
                        });

                        return arr.join(', ');
                    }},
                    {data: 'dinning_time', name: 'dinning_time', render:function (data, type, row, meta){
                        var arr = [];
                        data.forEach(function (e) {
                            arr.push(e.name);
                        });

                        return arr.join(', ');
                    }},
                    {data: 'consume_categories', name: 'consume_categories', render:function (data, type, row, meta){
                        var arr = [];
                        data.forEach(function (e) {
                            arr.push(e.name);
                        });

                        return arr.join(', ');
                    }},
                    {data: 'discount', name: 'discount'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'actions', name: 'actions', orderable: false, 'searchable':false}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });
        });
    </script>
@stop