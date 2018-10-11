<div class="pull-right mb-10 hidden-sm hidden-xs" id="max_header">
    {{ link_to_route('admin.customer.index', trans('menus.backend.customer.all'), [], ['class' => 'btn btn-primary btn-xs']) }}
    {{ link_to_route('admin.customer.create', trans('menus.backend.customer.create'), [], ['class' => 'btn btn-success btn-xs']) }}
</div><!--pull right-->

<div class="pull-right mb-10 hidden-lg hidden-md" id="min_header">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ trans('menus.backend.customer.title') }} <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('admin.customer.index', trans('menus.backend.customer.all')) }}</li>
            <li>{{ link_to_route('admin.customer.create', trans('menus.backend.customer.create')) }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>