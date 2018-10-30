<div class="pull-right mb-10 hidden-sm hidden-xs">
    {{ link_to_route('admin.customer.index', trans('menus.backend.customer.all'), [], ['class' => 'btn btn-primary btn-xs']) }}
    {{ link_to_route('admin.customer.create', trans('menus.backend.customer.create'), [], ['class' => 'btn btn-success btn-xs']) }}
    {{ link_to_route('admin.customer.changeAllBalance', trans('menus.backend.customer.changeAllBalance'), [], ['class' => 'btn btn-danger btn-xs']) }}

    {{ link_to_route('admin.customer.changeBalance', trans('menus.backend.customer.changeBalance'), $customer->id, ['class' => 'btn btn-warning btn-xs']) }}

</div><!--pull right-->

<div class="pull-right mb-10 hidden-lg hidden-md">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ trans('menus.backend.customer.title') }} <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('admin.customer.index', trans('menus.backend.customer.all')) }}</li>
            <li>{{ link_to_route('admin.customer.create', trans('menus.backend.customer.create')) }}</li>
            <li>{{ link_to_route('admin.customer.changeAllBalance', trans('menus.backend.customer.changeAllBalance')) }}</li>
            <li class="divider"></li>
            {{ link_to_route('admin.customer.changeBalance', trans('menus.backend.customer.changeBalance'), $customer->id, ['class' => 'btn btn-warning btn-xs']) }}
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>