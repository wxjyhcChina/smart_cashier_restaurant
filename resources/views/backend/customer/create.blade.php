@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.customer.management') . ' | ' . trans('labels.backend.customer.create'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.customer.management') }}
        <small>{{ trans('labels.backend.customer.create') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.customer.store', 'class' => 'form-horizontal', 'role' => 'form', 'id'=>'store-customer-form', 'method' => 'post']) }}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.customer.create') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.customer.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {{ Form::label('user_name', trans('validation.attributes.backend.customer.user_name').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('user_name', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.customer.user_name')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('id_license', trans('validation.attributes.backend.customer.id_license').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('id_license', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.customer.id_license')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('birthday', trans('validation.attributes.backend.customer.birthday').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('birthday', null, ['class' => 'form-control', 'id' => 'birthday', 'placeholder' => trans('validation.attributes.backend.customer.birthday')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('telephone', trans('validation.attributes.backend.customer.telephone').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('telephone', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.customer.telephone')]) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('consume_category_id', trans('validation.attributes.backend.customer.consume_category').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::select('consume_category_id', $consumeCategories, null, ['class' => 'form-control']) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('department_id', trans('validation.attributes.backend.customer.department').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::select('department_id', $departments, null, ['class' => 'form-control']) }}
                </div><!--col-lg-10-->
            </div><!--form control-->

            <div class="form-group">
                {{ Form::label('card_id', trans('validation.attributes.backend.customer.card').":", ['class' => 'col-lg-2 control-label']) }}

                <div class="col-lg-10">
                    {{ Form::text('card', null, ['class' => 'form-control', 'id'=>'card', 'required', 'placeholder' => trans('validation.attributes.backend.customer.card')]) }}
                    {{ Form::hidden('card_id', null, ['class' => 'form-control', 'required', 'placeholder' => trans('validation.attributes.backend.customer.card')]) }}
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

    <div class="modal fade" id="cardModel" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                    <h4 class="modal-title" id="modal-label">{{trans('labels.backend.customer.selectCard')}}</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="card-table" class="table table-condensed table-hover">
                            <thead>
                            <tr>
                                <th></th>
                                <th>{{ trans('labels.backend.card.table.number') }}</th>
                                <th>{{ trans('labels.backend.card.table.created_at') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div><!--table-responsive-->
                </div>

                <div class="modal-footer">
                    <div class="pull-right">
                        <button class="btn btn-danger" type="button" id="model_cancel">取消</button>
                        <button class="btn btn-primary" type="button" id="model_confirm">确定</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{ Form::close() }}

@stop


@section('after-scripts')
    {{ Html::script("js/backend/plugin/moment/min/moment.min.js") }}
    {{ Html::script("js/backend/plugin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js") }}
    {{ Html::style("js/backend/plugin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css") }}

    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables-extend.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables_locale.js") }}
    {{ Html::script("js/backend/plugin/datatables/page_select_with_ellipses.js") }}
    {{ Html::script("js/backend/jquery.scannerdetection.js") }}

    <script>
        $(function() {

            $('#birthday').datetimepicker({format: 'YYYY-MM-DD'});

            var myTable = $('#card-table').DataTable({
                dom: 'lfrtip',
                pagingType: "page_select_with_ellipses",
                processing: false,
                serverSide: true,
                autoWidth: false,
                stateSave: true,
                ajax: {
                    url: '{{ route("admin.customer.availableCard") }}',
                    type: 'get',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {
                        data: 'id', name: 'id', render: function (data, type, row, meta) {
                            return '<input type="radio" name="id" id="' + row['internal_number'] + '"' + ' value="' + row['number'] + '"' + (row['id'] == $('#card').val() ? 'checked="checked"' : '') + '>';
                        }
                    },
                    {data: 'number', name: 'number'},
                    {data: 'created_at', name: 'created_at'},
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });

            $('#card').on('click', function () {
                $("#cardModel").modal('show');
                myTable.columns.adjust();
            });

            $('#model_cancel').on('click', function () {
                $("#cardModel").modal('hide');
            });

            $('#model_confirm').on('click', function () {
                var count = 0;
                $("input[type=radio]:checked").each(function (a, b) {
                    var id = $(b).attr("id");
                    var number = $(b).attr("value");
                    $('#card').val(number);
                    $('#card_id').val(id);
                    count++;
                });

                if (count === 0) {
                    alert('请选择一张卡');
                }
                else {
                    $("#cardModel").modal('hide');
                }
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
                                if (jsonResult.status === 'ACTIVATED')
                                {
                                    swal({
                                        title: "卡片状态有误",
                                        type: "error",
                                    }, function(){

                                    });
                                }
                                else
                                {
                                    $('#card').val(jsonResult.number);
                                    $('#card_id').val(jsonResult.id);

                                    $("#cardModel").modal('hide');
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