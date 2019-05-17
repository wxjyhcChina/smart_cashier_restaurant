@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.customer.management'))

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.customer.management') }}
        <small>{{ trans('labels.backend.customer.active') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.customer.active') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.customer.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <button type="button" class="btn btn-primary" id="read_card_btn">{{trans('labels.backend.customer.read_card')}}</button>

            <div class="table-responsive" style="margin-top: 10px">
                <table id="department-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('labels.backend.customer.table.id') }}</th>
                            <th>{{ trans('labels.backend.customer.table.user_name') }}</th>
                            <th>{{ trans('labels.backend.customer.table.telephone') }}</th>
                            <th>{{ trans('labels.backend.customer.table.id_license') }}</th>
                            <th>{{ trans('labels.backend.customer.table.birthday') }}</th>
                            <th>{{ trans('labels.backend.customer.table.card') }}</th>
                            <th>{{ trans('labels.backend.customer.table.department') }}</th>
                            <th>{{ trans('labels.backend.customer.table.consume_category') }}</th>
                            <th>{{ trans('labels.backend.customer.table.balance') }}</th>
                            <th>{{ trans('labels.backend.customer.table.subsidy_balance') }}</th>
                            <th>{{ trans('labels.backend.customer.table.total_balance') }}</th>
                            <th>{{ trans('labels.backend.customer.table.created_at') }}</th>
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
    {{ Html::script("js/backend/jquery.scannerdetection.js") }}

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
                        return (parseFloat(row['account_balance']) + parseFloat(row['account_subsidy_balance'])).toFixed(2);
                    }, orderable: false, 'searchable':false},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'actions', name: 'actions', orderable: false, 'searchable':false}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });

            $('#read_card_btn').on('click', function (e) {
                swal({
                    title: "请刷卡",
                    type: "info",
                    showCancelButton: true,
                    showConfirmButton: false,
                    cancelButtonText: "取消",
                }, function(inputValue){

                });
            });

            $(document).scannerDetection({
                timeBeforeScanTest: 50, // wait for the next character for upto 200ms
                avgTimeByChar: 100, // it's not a barcode if a character takes longer than 100ms
                onComplete: function(code, qty){
                    // $('#pTest').text(barcode);
                    code = code.toUpperCase();

                    $.ajax({
                        type: "GET",
                        url: '{{ route("admin.card.getByInternalNumber") }}',
                        data: {
                            internal_number: code,
                        },
                        dataType: "json",
                        success: function (jsonResult) {
                            console.log(jsonResult.error_code);

                            if (jsonResult.error_code === undefined)
                            {
                                if (jsonResult.status === 'UNACTIVATED')
                                {
                                    swal({
                                        title: "卡片状态有误",
                                        type: "error",
                                    }, function(){

                                    });
                                }
                                else
                                {
                                    var url = '{{ route("admin.customer.recharge", ":customer_id") }}';
                                    url = url.replace(":customer_id", jsonResult.customer_id);

                                    window.location.href=url;
                                }
                            }
                            else
                            {
                                swal({
                                    title: jsonResult.error_message,
                                    type: "error",
                                }, function(){

                                });
                            }
                        },
                        fail: function () {
                        }
                    });
                } // main callback function
            });

        });
    </script>
@stop