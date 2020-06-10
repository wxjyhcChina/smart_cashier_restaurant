@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.shop.management'))

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.shop.management') }}
        <small>{{ trans('labels.backend.shop.active') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.shop.active') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.shop.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="shop-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('labels.backend.shop.table.id') }}</th>
                            <th>{{ trans('labels.backend.shop.table.name') }}</th>
                            <th>{{ trans('labels.backend.shop.table.default') }}</th>
                            <th>{{ trans('labels.backend.shop.table.created_at') }}</th>
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
            $('#shop-table').DataTable({
                dom: 'lfrtip',
                pagingType: "page_select_with_ellipses",
                processing: false,
                serverSide: true,
                autoWidth: false,
                stateSave: true,
                //"targets": 0,  //序号列不能排序也不能搜索
                ajax: {
                    url: '{{ route("admin.shop.get") }}',
                    type: 'get',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'id',name:"id"},
                    {data: 'name', name: 'name'},
                    {data: 'default', name: 'default', render:function (data, type, row, meta){
                            if (data === '1')
                            {
                                return '<span class="label label-primary">' + '{{trans('labels.backend.shop.table.default')}}' + '</span>';
                            }

                            return '';
                    }},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'actions', name: 'actions', orderable: false, 'searchable':false}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });
        });
    </script>
@stop