<div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            {{ Form::open(['route' => $uploadRoute, 'class' => 'avatar-form', 'enctype'=>'multipart/form-data','role' => 'form', 'method' => 'post']) }}
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
                <h4 class="modal-title" id="avatar-modal-label">{{trans('labels.backend.goods.uploadImage')}}</h4>
            </div>
            <div class="modal-body">
                <div class="avatar-body">
                    <div class="avatar-upload">
                        <input class="avatar-src" name="avatar_src" type="hidden">
                        <input class="avatar-data" name="avatar_data" type="hidden">
                        <label for="avatarInput">图片上传</label>
                        <input class="avatar-input" id="avatarInput" name="avatar_file" type="file"></div>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="avatar-wrapper"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="avatar-preview preview-1-1"></div>
                        </div>
                    </div>
                    <div class="row avatar-btns">
                        <div class="col-md-9">
                            <div class="btn-group">
                                <button class="btn avatar-zoom" data-option="0.1" type="button" title="Rotate -90 degrees"><i class="fa fa-search-plus"></i> 放大</button>
                            </div>
                            <div class="btn-group">
                                <button class="btn avatar-zoom" data-option="-0.1" type="button" title="Rotate 90 degrees"><i class="fa fa-search-minus"></i> 缩小</button>
                            </div>

                            <div class="btn-group">
                                <button class="btn avatar-rotate" data-option="-90" type="button" title="Rotate -90 degrees"><i class="fa fa-undo"></i> 向左旋转</button>
                            </div>
                            <div class="btn-group">
                                <button class="btn avatar-rotate" data-option="90" type="button" title="Rotate 90 degrees"><i class="fa fa-repeat"></i> 向右旋转</button>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <button class="btn btn-primary btn-block avatar-save" type="submit"><i class="fa fa-save"></i> 保存修改</button>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>