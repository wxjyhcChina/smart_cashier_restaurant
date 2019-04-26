@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.customer.management') . ' | ' . trans('labels.backend.customer.change_multiple_balance'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.customer.management') }}
        <small>{{ trans('labels.backend.customer.change_multiple_balance') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => ['admin.customer.changeMultipleBalanceStore'], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id'=>'balance_form']) }}

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
                        <input type="radio" name="type" value="all" checked>&nbsp;全部&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="type" value="department">&nbsp;部门&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="type" value="customer">&nbsp;选人&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


                        <div id="department_container" class="table-responsive" style="margin-top: 10px" hidden>
                            <table id="department-table" class="table table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="department_select_all" value="1" id="department-select-all">
                                    </th>
                                    <th>{{ trans('labels.backend.department.table.id') }}</th>
                                    <th>{{ trans('labels.backend.department.table.code') }}</th>
                                    <th>{{ trans('labels.backend.department.table.name') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div><!--table-responsive-->

                        <div id="customer_container" class="table-responsive" style="margin-top: 10px" hidden>
                            <table id="customer-table" class="table table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="customer_select_all" value="1" id="customer-select-all">
                                    </th>
                                    <th>{{ trans('labels.backend.customer.table.id') }}</th>
                                    <th>{{ trans('labels.backend.customer.table.user_name') }}</th>
                                    <th>{{ trans('labels.backend.customer.table.balance') }}</th>
                                    <th>{{ trans('labels.backend.customer.table.subsidy_balance') }}</th>
                                    <th>{{ trans('labels.backend.customer.table.total_balance') }}</th>

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
                    {{ Form::submit(trans('buttons.general.save'), ['class' => 'btn btn-success btn-xs']) }}
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
            var customerTable = $('#customer-table').DataTable({
                dom: 'lfrtip',
                pagingType: "page_select_with_ellipses",
                processing: false,
                autoWidth: false,
                ajax: {
                    url: '{{ route("admin.customer.get") }}',
                    type: 'get',
                },
                columns: [
                    {
                        data: 'id',
                        'targets': 0,
                        'searchable':false,
                        'orderable':false,
                        'render': function (data, type, row, meta){
                            return '<input type="checkbox" name="customer_id[]" value="' + $('<div/>').text(data).html() + '" ' + (row['checked'] == true ? 'checked="checked"' : '') +'>';
                        }
                    },
                    {data: 'id', name: 'id'},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'account_balance', name: 'accounts.balance'},
                    {data: 'account_subsidy_balance', name: 'accounts.subsidy_balance'},
                    {data: 'total_balance', name: 'total_balance', render:function (data, type, row, meta){
                            return (parseFloat(row['account_balance']) + parseFloat(row['account_subsidy_balance'])).toFixed(2);
                        }, orderable: false, 'searchable':false},
                ],
                'select': {
                    'style': 'multi'
                },
                order: [[0, "asc"]],
                searchDelay: 500
            });

            // Handle click on "Select all" control
            $('#customer-select-all').on('click', function(){
                // Check/uncheck all checkboxes in the table
                var rows = customerTable.rows({ 'search': 'applied' }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            // Handle click on checkbox to set state of "Select all" control
            $('#customer-table tbody').on('change', 'input[type="checkbox"]', function(){
                // If checkbox is not checked
                if(!this.checked){
                    var el = $('#select-all').get(0);
                    // If "Select all" control is checked and has 'indeterminate' property
                    if(el && el.checked && ('indeterminate' in el)){
                        // Set visual state of "Select all" control
                        // as 'indeterminate'
                        el.indeterminate = true;
                    }
                }
            });

            var departmentTable = $('#department-table').DataTable({
                dom: 'lfrtip',
                pagingType: "page_select_with_ellipses",
                processing: false,
                autoWidth: false,
                ajax: {
                    url: '{{ route("admin.department.get") }}',
                    type: 'get',
                },
                columns: [
                    {
                        data: 'id',
                        'targets': 0,
                        'searchable':false,
                        'orderable':false,
                        'render': function (data, type, row, meta){
                            return '<input type="checkbox" name="department_id[]" value="' + $('<div/>').text(data).html() + '" ' + (row['checked'] == true ? 'checked="checked"' : '') +'>';
                        }
                    },
                    {data: 'id', name: 'id'},
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                ],
                'select': {
                    'style': 'multi'
                },
                order: [[0, "asc"]],
                searchDelay: 500
            });

            // Handle click on "Select all" control
            $('#department-select-all').on('click', function(){
                // Check/uncheck all checkboxes in the table
                var rows = departmentTable.rows({ 'search': 'applied' }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            // Handle click on checkbox to set state of "Select all" control
            $('#department-table tbody').on('change', 'input[type="checkbox"]', function(){
                // If checkbox is not checked
                if(!this.checked){
                    var el = $('#select-all').get(0);
                    // If "Select all" control is checked and has 'indeterminate' property
                    if(el && el.checked && ('indeterminate' in el)){
                        // Set visual state of "Select all" control
                        // as 'indeterminate'
                        el.indeterminate = true;
                    }
                }
            });

            $('input[type=radio][name=type]').change(function() {
                if (this.value === 'all') {
                    $('#department_container').attr('hidden', true);
                    $('#department_container').attr('disabled', true);
                    $('#customer_container').attr('hidden', true);
                    $('#customer_container').attr('disabled', true);
                }
                else if (this.value === 'department') {
                    $('#customer_container').attr('hidden', true);
                    $('#customer_container').attr('disabled', true);
                    $('#department_container').removeAttr('hidden');
                    $('#department_container').removeAttr('disabled');
                }
                else if (this.value === 'customer') {
                    $('#department_container').attr('hidden', true);
                    $('#department_container').attr('disabled', true);
                    $('#customer_container').removeAttr('hidden');
                    $('#customer_container').removeAttr('disabled');
                }
            });

            $('#balance_form').on('submit', function(e){
                var form = this;

                var radioValue = $("input[name='type']:checked"). val();
                // Iterate over all checkboxes in the table
                if (radioValue === 'department')
                {
                    departmentTable.$('input[type="checkbox"]').each(function() {
                        // If checkbox doesn't exist in DOM
                        if (!$.contains(document, this)) {
                            // If checkbox is checked
                            if (this.checked) {
                                // Create a hidden element
                                $(form).append(
                                    $('<input>')
                                        .attr('type', 'hidden')
                                        .attr('name', this.name)
                                        .val(this.value)
                                );
                            }
                        }
                    });
                }
                else if (radioValue === 'customer')
                {
                    customerTable.$('input[type="checkbox"]').each(function() {
                        // If checkbox doesn't exist in DOM
                        if (!$.contains(document, this)) {
                            // If checkbox is checked
                            if (this.checked) {
                                // Create a hidden element
                                $(form).append(
                                    $('<input>')
                                        .attr('type', 'hidden')
                                        .attr('name', this.name)
                                        .val(this.value)
                                );
                            }
                        }
                    });
                }
            });
        });
    </script>
@stop
