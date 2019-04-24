@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.customer.management') . ' | ' . trans('labels.backend.customer.change_all_balance'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.customer.management') }}
        <small>{{ trans('labels.backend.customer.change_multiple_balance') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => ['admin.customer.changeMultipleBalanceStore'], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) }}

    <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.customer.change_multiple_balance') }}</h3>

                <div class="box-tools pull-right">
                    @include('backend.customer.includes.partials.header-buttons')
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('source', trans('validation.attributes.backend.customer.source'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::select('source', array('SYSTEM_ADD' => trans('labels.backend.customer.system_add'), 'SYSTEM_MINUS' => trans('labels.backend.customer.system_minus')), '3', ['class' => 'form-control']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('type', trans('validation.attributes.backend.customer.type'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10" style="padding-top: 7px">
                        <input type="radio" name="type" value="all">&nbsp;全部&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="type" value="department">&nbsp;部门&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="type" value="person">&nbsp;选人&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


                        <div class="table-responsive" style="margin-top: 10px">
                            <table id="department-table" class="table table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="select_all" value="1" id="select-all">
                                    </th>

                                </tr>
                                </thead>
                            </table>
                        </div><!--table-responsive-->

                        <div class="table-responsive" style="margin-top: 10px">
                            <table id="person-table" class="table table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="select_all" value="1" id="select-all">
                                    </th>

                                </tr>
                                </thead>
                            </table>
                        </div><!--table-responsive-->

                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('balance', trans('validation.attributes.backend.customer.balance'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('balance', null, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.customer.balance')]) }}
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
                    {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) }}
                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    {{ Form::close() }}
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
                    url: '{{ route("admin.customer.get") }}',
                    type: 'get',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'telephone', name: 'telephone'},
                    {data: 'id_license', name: 'id_license'},
                    {data: 'birthday', name: 'birthday'},
                    {data: 'card_number', name: 'cards.number'},
                    {data: 'department_name', name: 'departments.name'},
                    {data: 'consume_category_name', name: 'consume_categories.name'},
                    {data: 'account_balance', name: 'accounts.balance'},
                    {data: 'account_subsidy_balance', name: 'accounts.subsidy_balance'},
                    {data: 'total_balance', name: 'total_balance', render:function (data, type, row, meta){
                            return parseFloat(row['account_balance'] + row['account_subsidy_balance']).toFixed(2);
                        }, orderable: false, 'searchable':false},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'actions', name: 'actions', orderable: false, 'searchable':false}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });
        });
    </script>
@stop
