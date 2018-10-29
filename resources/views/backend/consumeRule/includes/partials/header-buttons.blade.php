<div class="pull-right mb-10 hidden-sm hidden-xs" id="max_header">
    {{ link_to_route('admin.consumeRule.index', trans('menus.backend.consumeRule.all'), [], ['class' => 'btn btn-primary btn-xs']) }}
    {{ link_to_route('admin.consumeRule.create', trans('menus.backend.consumeRule.create'), [], ['class' => 'btn btn-success btn-xs']) }}
</div><!--pull right-->

<div class="pull-right mb-10 hidden-lg hidden-md" id="min_header">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ trans('menus.backend.consumeRule.title') }} <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('admin.consumeRule.index', trans('menus.backend.consumeRule.all')) }}</li>
            <li>{{ link_to_route('admin.consumeRule.create', trans('menus.backend.consumeRule.create')) }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>
