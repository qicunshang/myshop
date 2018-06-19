@extends('admin.layouts.admin')

@section('admin-css')
    <link href="{{ asset('asset_admin/assets/plugins/parsley/src/parsley.css') }}" rel="stylesheet" />
    <link href="{{ asset('asset_admin/assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection

@section('admin-content')
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="javascript:;">Home</a></li>
            <li><a href="javascript:;">Form Stuff</a></li>
            <li class="active">Form Validation</li>
        </ol>
        <!-- end breadcrumb -->
        <!-- begin page-header -->
        <h1 class="page-header">编辑 <small>header small text goes here...</small></h1>
        <!-- end page-header -->

        <!-- begin row -->
        <div class="row">
            <!-- begin col-6 -->
            <div class="col-md-12">
                <!-- begin panel -->
                <div class="panel panel-inverse" data-sortable-id="form-validation-1">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                        </div>
                        <h4 class="panel-title">Basic Form Validation</h4>
                    </div>
                    @if(count($errors)>0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="panel-body panel-form">
                        <form class="form-horizontal form-bordered" data-parsley-validate="true" action="{{ url('admin/category/save') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $info->id or '' }}">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="cNo">逻辑编号 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="cNo" placeholder="逻辑编号" data-parsley-required="true" data-parsley-required-message="请输入逻辑编号" value="{{ $info->cNo or old('cNo') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="cName">分类名 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="cName" placeholder="分类名" data-parsley-required="true" data-parsley-required-message="请输入分类名" value="{{ $info->cName or old('cName') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="iconUrl">图标路径 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="iconUrl" placeholder="图标路径" data-parsley-required="true" data-parsley-required-message="请输入图标路径" value="{{ $info->iconUrl or old('iconUrl') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="cStatus">分类状态 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <select class="form-control selectpicker"
                                            data-live-search="true"
                                            data-style="btn-white"
                                            data-parsley-required="true"
                                            data-parsley-errors-container="#parent_id_error"
                                            data-parsley-required-message="请选择分类状态"
                                            name="cStatus">
                                        <option value="">-- 请选择 --</option>
                                        <option value="1" @if($info->cStatus == 1) selected="selected" @endif>显示</option>
                                        <option value="0" @if($info->cStatus == 0) selected="selected" @endif>不显示</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4"></label>
                                <div class="col-md-6 col-sm-6">
                                    <button type="submit" class="btn btn-primary">提交</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end panel -->
            </div>
            <!-- end col-6 -->
        </div>
        <!-- end row -->
    </div>
@endsection

@section('admin-js')
    <script src="{{ asset('asset_admin/assets/plugins/parsley/dist/parsley.js') }}"></script>
@endsection