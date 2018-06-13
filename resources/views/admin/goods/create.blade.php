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
                        <form class="form-horizontal form-bordered" data-parsley-validate="true" action="{{ url('admin/goods/save') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $info->id or '' }}">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="gName">商品名称 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="gName" placeholder="商品名称" data-parsley-required="true" data-parsley-required-message="请输入商品名称" value="{{ $info->gName or old('gName') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="gDesc">商品简介 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="gDesc" placeholder="商品简介" data-parsley-required="true" data-parsley-required-message="请输入商品简介" value="{{ $info->gDesc or old('gDesc') }}"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="cId">商品分类 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <select class="form-control selectpicker"
                                            data-live-search="true"
                                            data-style="btn-white"
                                            data-parsley-required="true"
                                            data-parsley-errors-container="#parent_id_error"
                                            data-parsley-required-message="请选择商品分类"
                                            name="cId">
                                        <option value="">-- 请选择 --</option>
                                        @foreach($category as $item)
                                            <option value="{{ $item->id }}" @if($info->cId == $item->id) selected="selected" @endif>{{ $item->cName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="price">商品价格 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="price" placeholder="售价" data-parsley-required="true" data-parsley-required-message="请输入价格" value="{{ $info->price or old('price') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="tradePrice">批发价格 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="tradePrice" placeholder="批发价格" data-parsley-required="true" data-parsley-required-message="请输入批发价格" value="{{ $info->tradePrice or old('tradePrice') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="region">发货地 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="region" placeholder="发货地址" data-parsley-required="true" data-parsley-required-message="请输入发货地" value="{{ $info->region or old('region') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="freightAmount">运费 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="freightAmount" placeholder="运费" data-parsley-required="true" data-parsley-required-message="请输入运费" value="{{ $info->freightAmount or old('freightAmount') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="stock">库存 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="stock" placeholder="库存" data-parsley-required="true" data-parsley-required-message="请输入库存" value="{{ $info->stock or old('stock') }}"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="hot">是否推荐 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <select class="form-control selectpicker"
                                            data-live-search="true"
                                            data-style="btn-white"
                                            data-parsley-required="true"
                                            data-parsley-errors-container="#parent_id_error"
                                            data-parsley-required-message="请选择是否推荐"
                                            name="hot">
                                        <option value="">-- 请选择 --</option>
                                        <option value="1" @if($info->hot == 1) selected="selected" @endif>推荐</option>
                                        <option value="0" @if($info->hot == 0) selected="selected" @endif>不推荐</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="package">是否包邮 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <select class="form-control selectpicker"
                                            data-live-search="true"
                                            data-style="btn-white"
                                            data-parsley-required="true"
                                            data-parsley-errors-container="#parent_id_error"
                                            data-parsley-required-message="请选择是否推荐"
                                            name="package">
                                        <option value="">-- 请选择 --</option>
                                        <option value="1" @if($info->package == 1) selected="selected" @endif>包邮</option>
                                        <option value="0" @if($info->package == 0) selected="selected" @endif>不包邮</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="gStatus">商品状态 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <select class="form-control selectpicker"
                                            data-live-search="true"
                                            data-style="btn-white"
                                            data-parsley-required="true"
                                            data-parsley-errors-container="#parent_id_error"
                                            data-parsley-required-message="请选择商品状态"
                                            name="gStatus">
                                        <option value="">-- 请选择 --</option>
                                        <option value="0" @if($info->gStatus == 0) selected="selected" @endif>上架</option>
                                        <option value="1" @if($info->gStatus == 1) selected="selected" @endif>下架</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="gMovie">图片/视频 :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="file" name="gMovie" />
                                    <span style="color: #ff0000;">*不修改请留空</span>
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