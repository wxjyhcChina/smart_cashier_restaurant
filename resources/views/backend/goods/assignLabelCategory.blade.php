@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.goods.management') . ' | ' . trans('labels.backend.goods.assignLabelCategory'))

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.goods.management') }}
        <small>{{ trans('labels.backend.goods.assignLabelCategory') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($goods, ['route' => ['admin.goods.assignLabelCategoryStore', $goods], 'class' => 'form-horizontal', 'id'=>'assign_label_category_form', 'role' => 'form', 'method' => 'post']) }}

    {{ Form::hidden('goods_id', $goods->id, ['id' => 'goods_id']) }}
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.goods.assignLabelCategory') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.goods.includes.partials.header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="label-category-table" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>
                            <input type="checkbox" name="select_all" value="1" id="select-all">
                        </th>
                        <th>{{ trans('labels.backend.labelCategory.table.id') }}</th>
                        <th>{{ trans('labels.backend.labelCategory.table.name') }}</th>
                        <th>{{ trans('labels.backend.labelCategory.table.created_at') }}</th>
                    </tr>
                    </thead>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.goods.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                <button class="btn btn-success btn-xs">{{trans('labels.backend.goods.assignLabelCategory')}}</button>
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}
@stop

@section('after-scripts')
    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/page_select_with_ellipses.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables-extend.js") }}

    {{ Html::script("js/backend/plugin/datatables/dataTables_locale.js") }}

    <script>
        $(function() {
            var table = $('#label-category-table').DataTable({
                dom: 'lfrtip',
                pagingType: "page_select_with_ellipses",
                processing: false,
                autoWidth: false,
                ajax: {
                    url: '{{ route("admin.goods.getLabelCategories") }}',
                    type: 'get',
                    data: {goods_id: $('#goods_id').attr("value")},
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {
                        data: 'id',
                        'targets': 0,
                        'searchable':false,
                        'orderable':false,
                        'render': function (data, type, row, meta){
                            return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '" ' + (row['checked'] == true ? 'checked="checked"' : '') +'>';
                        }
                    },
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'created_at', name: 'created_at'},
                ],
                'select': {
                    'style': 'multi'
                },
                order: [[0, "asc"]],
                searchDelay: 500
            });

            // Handle click on "Select all" control
            $('#select-all').on('click', function(){
                // Check/uncheck all checkboxes in the table
                var rows = table.rows({ 'search': 'applied' }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            // Handle click on checkbox to set state of "Select all" control
            $('#label-category-table tbody').on('change', 'input[type="checkbox"]', function(){
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

            // Handle form submission event
            $('#assign_label_category_form').on('submit', function(e){
                var form = this;

                // Iterate over all checkboxes in the table
                table.$('input[type="checkbox"]').each(function() {
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
            });
        });
    </script>
@stop