@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.statistics.title'))

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
    {{ Html::style("css/backend/plugin/datepicker/daterangepicker.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.statistics.title') }}
        <small>{{ trans('labels.backend.statistics.shopStatistics') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.statistics.shopStatistics') }}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            {{ Form::open(['route' => 'admin.statistics.shopStatisticsExport', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) }}

                <div class="row">
                    {{ Form::label('search_time', trans('labels.backend.consumeOrder.searchTime'), ['class' => 'control-label', 'style'=>'float: left;padding-left: 35px;padding-right: 15px;']) }}

                    <div class="col-lg-4">
                        {{ Form::text('search_time', null, ['class' => 'form-control', 'id'=>'search_time', 'placeholder' => trans('labels.backend.consumeOrder.searchTime')]) }}
                    </div><!--col-lg-10-->

                    <button type="button" class="btn btn-primary" id="search_btn">{{trans('labels.backend.statistics.search')}}</button>
                    <button class="btn btn-primary" id="export_btn">{{trans('labels.backend.statistics.export')}}</button>
                </div>
            </form>

            <div class="row" style="margin-top: 20px">

            </div>
        </div>
    </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="table-responsive">
                <table id="statistics-table" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.statistics.table.id') }}</th>
                        <th>{{ trans('labels.backend.statistics.table.shop') }}</th>
                        <th>{{ trans('labels.backend.statistics.table.cash') }}</th>
                        <th>{{ trans('labels.backend.statistics.table.card') }}</th>
                        <th>{{ trans('labels.backend.statistics.table.alipay') }}</th>
                        <th>{{ trans('labels.backend.statistics.table.wechat') }}</th>
                        <th>{{ trans('labels.backend.statistics.table.total') }}</th>
                    </tr>
                    </thead>

                    <tbody id="statistics_container">

                    </tbody>
                </table>
            </div><!--table-responsive-->
        </div>
    </div>

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
                getSearchResult();
            });

            function checkDate(startDate,endDate){
                let start=Date.parse(new Date(startDate));
                let end=Date.parse(new Date(endDate));
                if((end-start)<14*24*60*60*1000){
                    return true;
                }else{
                    return false;
                }
            }

            function getSearchResult() {
                //新增,查询时间不得超过两周(14天)
                if(!checkDate(startDate,endDate)){
                    alert("查询时间跨度不得超过两周");
                    return false;
                }

                $.ajax({
                    type: "GET",
                    url: '{{ route("admin.statistics.getShopStatistics") }}',
                    data: {
                        start_time: startDate,
                        end_time: endDate,
                    },
                    dataType: "json",
                    success: function (items) {
                        console.log(items);
                        $('#statistics_container').empty();
                        for(let i=0;i<items.length;i++){
                            let e=items[i];
                            $('#statistics_container').append('<tr>' +
                                '<td>' + (i+1) + '</td>' +
                                '<td>' + e.name + '</td>' +
                                '<td>' + e.cash + '</td>' +
                                '<td>' + e.cash_count  + '</td>' +
                                '<td>' + e.card + '</td>' +
                                '<td>' + e.card_count  + '</td>' +
                                '<td>' + e.alipay + '</td>' +
                                '<td>' + e.alipay_count  + '</td>' +
                                '<td>' + e.wechat + '</td>' +
                                '<td>' + e.wechat_count  + '</td>' +
                                '<td>' + e.total + '</td>' +
                                '<td>' + e.total_count + '</td>' +
                                '</tr>')
                        }
                        /*items.forEach(function (e) {
                            $('#statistics_container').append('<tr>' +
                                '<td>' + e.id + '</td>' +
                                '<td>' + e.name + '</td>' +
                                '<td>' + e.cash + '</td>' +
                                '<td>' + e.card + '</td>' +
                                '<td>' + e.alipay + '</td>' +
                                '<td>' + e.wechat + '</td>' +
                                '<td>' + e.total + '</td>' +
                                '</tr>')
                        })*/
                    },
                    fail: function () {
                    }
                });
            }
        });
    </script>
@stop