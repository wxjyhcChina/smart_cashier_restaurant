@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.users.management') . ' | ' . trans('labels.backend.access.users.edit'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.access.users.management') }}
        <small>{{ trans('labels.backend.access.users.edit') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($user, ['route' => ['admin.access.user.update', $user], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.access.users.edit') }}</h3>

                <div class="box-tools pull-right">
                    @include('backend.access.includes.partials.user-header-buttons')
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('username', trans('validation.attributes.backend.access.users.username'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        {{ Form::text('username', $username, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'placeholder' => trans('validation.attributes.backend.access.users.username')]) }}
                    </div><!--col-lg-10-->
                    <div class="col-lg-6">
                        <p style="padding-top: 7px">@ {{$restaurantCode}}</p>
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('first_name', trans('validation.attributes.backend.access.users.first_name'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('first_name', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.backend.access.users.first_name')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('last_name', trans('validation.attributes.backend.access.users.last_name'),
                    ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('last_name', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'placeholder' => trans('validation.attributes.backend.access.users.last_name')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('shop_id', trans('validation.attributes.backend.goods.shop_id').":", ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::select('shop_id', $shops, null, ['class' => 'form-control', 'required']) }}
                    </div><!--col-lg-10-->
                </div>

                @if (!$firstUser)

                    <div class="form-group">
                        {{ Form::label('associated_roles', trans('validation.attributes.backend.access.users.associated_roles'), ['class' => 'col-lg-2 control-label']) }}

                        <div class="col-lg-3">
                            @if (count($roles) > 0)
                                @foreach($roles as $role)
                                    <input type="checkbox" value="{{$role->id}}" name="assignees_roles[{{ $role->id }}]" {{ is_array(old('assignees_roles')) ? (in_array($role->id, old('assignees_roles')) ? 'checked' : '') : (in_array($role->id, $userRoles) ? 'checked' : '') }} id="role-{{$role->id}}" /> <label for="role-{{$role->id}}">{{ $role->name }}</label>
                                    <a href="#" data-role="role_{{$role->id}}" class="show-permissions small">
                                        (
                                        <span class="show-text">{{ trans('labels.general.show') }}</span>
                                        <span class="hide-text hidden">{{ trans('labels.general.hide') }}</span>
                                        {{ trans('labels.backend.access.users.permissions') }}
                                        )
                                    </a>
                                    <br/>
                                    <div class="permission-list hidden" data-role="role_{{$role->id}}">
                                        @if ($role->all)
                                            {{ trans('labels.backend.access.users.all_permissions') }}<br/><br/>
                                        @else
                                            @if (count($role->permissions) > 0)
                                                <blockquote class="small">{{--
                                            --}}@foreach ($role->permissions as $perm){{--
                                            --}}{{$perm->display_name}}<br/>
                                                    @endforeach
                                                </blockquote>
                                            @else
                                                {{ trans('labels.backend.access.users.no_permissions') }}<br/><br/>
                                            @endif
                                        @endif
                                    </div><!--permission list-->
                                @endforeach
                            @else
                                {{ trans('labels.backend.access.users.no_roles') }}
                            @endif
                        </div><!--col-lg-3-->
                    </div><!--form control-->
                @endif
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    {{ link_to_route('admin.access.user.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                </div><!--pull-left-->

                <div class="pull-right">
                    {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-xs']) }}
                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

        @if ($user->id == 1)
            {{ Form::hidden('status', 1) }}
            {{ Form::hidden('assignees_roles[0]', 1) }}
        @endif

    {{ Form::close() }}
@endsection

@section('after-scripts')
    {{ Html::script('js/backend/access/users/script.js') }}
@endsection
