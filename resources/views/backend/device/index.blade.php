@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.device.management'))

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.device.management') }}
        <small>{{ trans('labels.backend.device.active') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.device.active') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.device.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="device-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('labels.backend.device.table.id') }}</th>
                            <th>{{ trans('labels.backend.device.table.serial_id') }}</th>
                            <th>{{ trans('labels.backend.device.table.created_at') }}</th>
                            <th>{{ trans('labels.general.actions') }}</th>
                        </tr>
                    </thead>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="modal fade" id="importModel" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            {{ Form::open(['route' => 'admin.device.import', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'enctype' => "multipart/form-data"]) }}
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="exampleModalLabel">{{trans('labels.backend.device.import')}}</h4>
                </div>
                <div class="modal-body">

                    <label class="control-label">{{trans('labels.backend.device.select')}}</label>
                    <input name='xls' id="file_load" type="file" class="file">
                </div>
                <div class="modal-footer">
                    <button type='button' class="btn btn-secondary" data-dismiss="modal">{{trans('buttons.general.cancel')}}</button>
                    {{ Form::submit(trans('buttons.general.import'), ['class' => 'btn btn-primary']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop

@section('after-scripts')
    {{ Html::style("js/backend/plugin/bootstrap-fileinput/css/fileinput.min.css") }}
    {{ Html::script("js/backend/plugin/bootstrap-fileinput/js/fileinput.min.js") }}

    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables-extend.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables_locale.js") }}
    {{ Html::script("js/backend/plugin/datatables/page_select_with_ellipses.js") }}

    <script>
        $("#file_load").fileinput({
            'showPreview' : false,
            'showUpload' : false,
            'allowedFileExtensions' : ['xls', 'xlsx']
        });

        $(document).on('hidden.bs.modal', function (e) {
            $(e.target).find('form').trigger('reset');
        });

        function addImportButtons() {
            $("#max_header").append("<button type='button' class='btn btn-warning btn-xs' data-toggle='modal' data-target='#importModel'>{{trans('menus.backend.device.import')}}</button>");
            $("#min_header ul").append("<li><a data-toggle='modal' data-target='#importModel'>{{trans('menus.backend.device.import')}}</a></li>");
        }

        addImportButtons();

        $(function() {
            $('#device-table').DataTable({
                dom: 'lfrtip',
                pagingType: "page_select_with_ellipses",
                processing: false,
                serverSide: true,
                autoWidth: false,
                stateSave: true,
                ajax: {
                    url: '{{ route("admin.device.get") }}',
                    type: 'get',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'serial_id', name: 'serial_id'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'actions', name: 'actions', orderable: false, 'searchable':false}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });
        });
    </script>
@stop