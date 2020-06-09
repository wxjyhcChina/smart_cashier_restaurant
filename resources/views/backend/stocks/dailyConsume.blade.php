@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.stocks.management'))

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
    {{ Html::style("css/backend/plugin/datepicker/daterangepicker.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.stocks.management') }}
        <small>{{ trans('labels.backend.stocks.dailyConsume') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.stocks.dailyConsume') }}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            {{ Form::open(['route' => 'admin.statistics.consumeCategoryStatisticsExport', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) }}

            <div class="row">
                {{ Form::label('search_time', trans('labels.backend.stocks.searchTime'), ['class' => 'control-label', 'style'=>'float: left;padding-left: 35px;padding-right: 15px;']) }}

                <div class="col-lg-4">
                    {{ Form::text('search_time', null, ['class' => 'form-control', 'id'=>'search_time', 'placeholder' => trans('labels.backend.stocks.searchTime')]) }}
                </div><!--col-lg-10-->

                <button type="button" class="btn btn-primary" id="search_btn">{{trans('labels.backend.stocks.search')}}</button>
                <!--
                <button class="btn btn-primary" id="export_btn">{{trans('labels.backend.stocks.export')}}</button>-->
            </div>
            </form>

            <div class="row" style="margin-top: 20px">

            </div>
        </div>
    </div>

    <div class="box box-success">

        <div class="box-body">
            <div class="table-responsive">
                <table id="consume-order-table" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.stocks.table.id') }}</th>
                        <th>{{ trans('labels.backend.stocks.table.material_name') }}</th>
                        <th>{{ trans('labels.backend.stocks.table.count') }}</th>
                        <th>{{ trans('labels.backend.stocks.table.status') }}</th>
                        <th>{{ trans('labels.backend.consumeOrder.table.created_at') }}</th>
                    </tr>
                    </thead>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts')
    {{ Html::style('css/backend/ionicons/css/ionicons.min.css') }}
    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables-extend.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables_locale.js") }}
    {{ Html::script("js/backend/plugin/datatables/page_select_with_ellipses.js") }}

    {{ Html::script("js/backend/plugin/moment/moment.js") }}
    {{ Html::script("js/backend/plugin/moment/locale/zh-cn.js") }}
    {{ Html::script("js/backend/plugin/daterangepicker/daterangepicker.js") }}

    <script>
        $(function() {
            var startDate = '{{$startTime}}';
            var endDate = '{{$endTime}}';

            var table = $('#consume-order-table').DataTable({
                dom: 'lfrtip',
                pagingType: "page_select_with_ellipses",
                processing: false,
                serverSide: true,
                autoWidth: false,
                stateSave: true,
                ajax: {
                    url: '{{ route("admin.stocks.getDailyConsumeStatistics") }}',
                    data: function ( d ) {
                        d.start_time = startDate;
                        d.end_time = endDate;
                    },
                    type: 'get',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    // {data: 'order_id', name: 'order_id'},
                    {data: 'name', name: 'materials.name'},
                    {data: 'number', name: 'number'},
                    {data: 'show_status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });

            $('#search_time').daterangepicker(
                {
                    "timePicker": true,
                    "timePicker24Hour": true,
                    "timePickerSeconds": true,
                    "startDate": startDate,
                    "endDate": endDate,
                    "locale": {
                        format: 'YYYY-MM-DD HH:mm:ss'
                    },
                },

                function(start, end) {
                    console.log("Callback has been called!");
                    startDate = start.format('YYYY-MM-DD HH:mm:ss');
                    endDate = end.format('YYYY-MM-DD HH:mm:ss');
                }
            );

            getSearchResult();

            $('#search_btn').click(function(e){
                table.ajax.reload();
                getSearchResult();
            });

            function checkDate(startDate,endDate){
                let start=Date.parse(new Date(startDate));
                let end=Date.parse(new Date(endDate));
                //console.log(start);console.log(end);
                if((end-start)<14*24*60*60*1000){
                    //console.log(true);
                    return true;
                }else{
                    //console.log(false);
                    return false;
                }
            }

            function getSearchResult() {
                //新增,查询时间不得超过两周(14天)
                if(!checkDate(startDate,endDate)){
                    alert("查询时间跨度不得超过两周");
                    return false;
                }
            }
        });
    </script>
@stop