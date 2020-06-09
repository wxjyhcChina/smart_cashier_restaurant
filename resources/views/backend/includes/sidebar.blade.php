<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ access()->user()->picture }}" class="img-circle" alt="User Image" />
            </div><!--pull-left-->
            <div class="pull-left info">
                <p>{{ access()->user()->full_name }}</p>
            </div><!--pull-left-->
        </div><!--user-panel-->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">{{ trans('menus.backend.sidebar.general') }}</li>

            <li class="{{ active_class(Active::checkUriPattern('admin/dashboard')) }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>{{ trans('menus.backend.sidebar.dashboard') }}</span>
                </a>
            </li>

            <li class="header">{{ trans('menus.backend.sidebar.system') }}</li>

            @role(1)
            <li class="{{ active_class(Active::checkUriPattern('admin/access/*')) }} treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>{{ trans('menus.backend.access.title') }}</span>

                    @if ($pending_approval > 0)
                        <span class="label label-danger pull-right">{{ $pending_approval }}</span>
                    @else
                        <i class="fa fa-angle-left pull-right"></i>
                    @endif
                </a>

                <ul class="treeview-menu {{ active_class(Active::checkUriPattern('admin/access/*'), 'menu-open') }}" style="display: none; {{ active_class(Active::checkUriPattern('admin/access/*'), 'display: block;') }}">
                    <li class="{{ active_class(Active::checkUriPattern('admin/access/user*')) }}">
                        <a href="{{ route('admin.access.user.index') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{ trans('labels.backend.access.users.management') }}</span>

                            @if ($pending_approval > 0)
                                <span class="label label-danger pull-right">{{ $pending_approval }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="{{ active_class(Active::checkUriPattern('admin/access/role*')) }}">
                        <a href="{{ route('admin.access.role.index') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{ trans('labels.backend.access.roles.management') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endauth

            @permission('manage-department')
            <li class="{{ active_class(Active::checkUriPattern('admin/department*')) }}">
                <a href="{{ route('admin.department.index') }}">
                    <i class="fa fa-users"></i>
                    <span>{{ trans('menus.backend.department.title') }}</span>
                </a>
            </li>
            @endauth

            @permission('manage-shop')
            <li class="{{ active_class(Active::checkUriPattern('admin/shop*')) }}">
                <a href="{{ route('admin.shop.index') }}">
                    <i class="fa fa-map-o"></i>
                    <span>{{ trans('menus.backend.shop.title') }}</span>
                </a>
            </li>
            @endauth

            @permission('manage-customer')
            <li class="{{ active_class(Active::checkUriPattern('admin/customer*')) }}">
                <a href="{{ route('admin.customer.index') }}">
                    <i class="fa fa-user"></i>
                    <span>{{ trans('menus.backend.customer.title') }}</span>
                </a>
            </li>
            @endauth

            @permission('manage-card')
            <li class="{{ active_class(Active::checkUriPattern('admin/card*')) }}">
                <a href="{{ route('admin.card.index') }}">
                    <i class="fa fa-credit-card"></i>
                    <span>{{ trans('menus.backend.card.title') }}</span>
                </a>
            </li>
            @endauth

            @permission('manage-goods')
            <li class="{{ active_class(Active::checkUriPattern('admin/goods*')) }}">
                <a href="{{ route('admin.goods.index') }}">
                    <i class="fa fa-cutlery"></i>
                    <span>{{ trans('menus.backend.goods.title') }}</span>
                </a>
            </li>
            @endauth

            @permission('manage-label-category')
            <li class="{{ active_class(Active::checkUriPattern('admin/labelCategory*')) }}">
                <a href="{{ route('admin.labelCategory.index') }}">
                    <i class="fa fa-tag"></i>
                    <span>{{ trans('menus.backend.labelCategory.title') }}</span>
                </a>
            </li>
            @endauth

            @permission('manage-device')
            <li class="{{ active_class(Active::checkUriPattern('admin/device*')) }}">
                <a href="{{ route('admin.device.index') }}">
                    <i class="fa fa-hdd-o"></i>
                    <span>{{ trans('menus.backend.device.title') }}</span>
                </a>
            </li>
            @endauth

            @permission('manage-dinning-time')
            <li class="{{ active_class(Active::checkUriPattern('admin/dinningTime*')) }}">
                <a href="{{ route('admin.dinningTime.index') }}">
                    <i class="fa fa-calendar"></i>
                    <span>{{ trans('menus.backend.dinningTime.title') }}</span>
                </a>
            </li>
            @endauth

            @permission('manage-consume-category')
            <li class="{{ active_class(Active::checkUriPattern('admin/consumeCategory*')) }}">
                <a href="{{ route('admin.consumeCategory.index') }}">
                    <i class="fa fa-bars"></i>
                    <span>{{ trans('menus.backend.consumeCategory.title') }}</span>
                </a>
            </li>
            @endauth

            @permission('manage-consume-rule')
            <li class="{{ active_class(Active::checkUriPattern('admin/consumeRule*')) }}">
                <a href="{{ route('admin.consumeRule.index') }}">
                    <i class="fa fa-book"></i>
                    <span>{{ trans('menus.backend.consumeRule.title') }}</span>
                </a>
            </li>
            @endauth

            @permission('manage-pay-method')
            <li class="{{ active_class(Active::checkUriPattern('admin/payMethod*')) }}">
                <a href="{{ route('admin.payMethod.index') }}">
                    <i class="fa fa-hdd-o"></i>
                    <span>{{ trans('menus.backend.payMethod.title') }}</span>
                </a>
            </li>
            @endauth

            @permission('manage-consume-order')
            <li class="{{ active_class(Active::checkUriPattern('admin/consumeOrder*')) }}">
                <a href="{{ route('admin.consumeOrder.index') }}">
                    <i class="fa fa-shopping-cart"></i>
                    <span>{{ trans('menus.backend.consumeOrder.title') }}</span>
                </a>
            </li>
            @endauth

            @permission('manage-recharge-order')
            <li class="{{ active_class(Active::checkUriPattern('admin/rechargeOrder*')) }}">
                <a href="{{ route('admin.rechargeOrder.index') }}">
                    <i class="fa fa-exchange"></i>
                    <span>{{ trans('menus.backend.rechargeOrder.title') }}</span>
                </a>
            </li>
            @endauth

            @permission('manage-statistics')
            <li class="{{ active_class(Active::checkUriPattern('admin/statistics/*')) }} treeview">
                <a href="#">
                    <i class="fa fa-bar-chart"></i>
                    <span>{{ trans('menus.backend.statistics.title') }}</span>

                    <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu {{ active_class(Active::checkUriPattern('admin/statistics/*'), 'menu-open') }}" style="display: none; {{ active_class(Active::checkUriPattern('admin/statistics/*'), 'display: block;') }}">
                    <li class="{{ active_class(Active::checkUriPattern('admin/statistics/dinningTimeStatistics')) }}">
                        <a href="{{ route('admin.statistics.dinningTimeStatistics') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{ trans('labels.backend.statistics.dinningTimeStatistics') }}</span>
                        </a>
                    </li>

                    <li class="{{ active_class(Active::checkUriPattern('admin/statistics/departmentStatistics')) }}">
                        <a href="{{ route('admin.statistics.departmentStatistics') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{ trans('labels.backend.statistics.departmentStatistics') }}</span>
                        </a>
                    </li>

                    <li class="{{ active_class(Active::checkUriPattern('admin/statistics/consumeCategoryStatistics')) }}">
                        <a href="{{ route('admin.statistics.consumeCategoryStatistics') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{ trans('labels.backend.statistics.consumeCategoryStatistics') }}</span>
                        </a>
                    </li>


                    <li class="{{ active_class(Active::checkUriPattern('admin/statistics/shopStatistics')) }}">
                        <a href="{{ route('admin.statistics.shopStatistics') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{ trans('labels.backend.statistics.shopStatistics') }}</span>
                        </a>
                    </li>

                    <li class="{{ active_class(Active::checkUriPattern('admin/statistics/goodsStatistics')) }}">
                        <a href="{{ route('admin.statistics.goodsStatistics') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{ trans('labels.backend.statistics.goodsStatistics') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endauth

            <!--进销存管理模块-->
            @permission('manage-stock')
            <li class="{{ active_class(Active::checkUriPattern('admin/stock/*')) }} treeview">
                <a href="#">
                    <i class="fa fa-list"></i>
                    <span>{{ trans('menus.backend.stock.title') }}</span>

                    <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu {{ active_class(Active::checkUriPattern('admin/stocks/*'), 'menu-open') }}" style="display: none; {{ active_class(Active::checkUriPattern('admin/statistics/*'), 'display: block;') }}">
                    <li class="{{ active_class(Active::checkUriPattern('admin/stocks/purchase')) }}">
                        <a href="{{ route('admin.stocks.purchase') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{ trans('menus.backend.stock.purchase') }}</span>
                        </a>
                    </li>

                    <li class="{{ active_class(Active::checkUriPattern('admin/stocks/dailyConsume')) }}">
                        <a href="{{ route('admin.stocks.dailyConsume') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{ trans('menus.backend.stock.consume') }}</span>
                        </a>
                    </li>

                    <li class="{{ active_class(Active::checkUriPattern('admin/stocks/index')) }}">
                        <a href="{{ route('admin.stocks.index') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{ trans('menus.backend.stock.stock') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endauth

            <li class="{{ active_class(Active::checkUriPattern('admin/log-viewer*')) }} treeview">
                <a href="#">
                    <i class="fa fa-list"></i>
                    <span>{{ trans('menus.backend.log-viewer.main') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu {{ active_class(Active::checkUriPattern('admin/log-viewer*'), 'menu-open') }}" style="display: none; {{ active_class(Active::checkUriPattern('admin/log-viewer*'), 'display: block;') }}">
                    <li class="{{ active_class(Active::checkUriPattern('admin/log-viewer')) }}">
                        <a href="{{ route('log-viewer::dashboard') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{ trans('menus.backend.log-viewer.dashboard') }}</span>
                        </a>
                    </li>

                    <li class="{{ active_class(Active::checkUriPattern('admin/log-viewer/logs')) }}">
                        <a href="{{ route('log-viewer::logs.list') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{ trans('menus.backend.log-viewer.logs') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul><!-- /.sidebar-menu -->
    </section><!-- /.sidebar -->
</aside>