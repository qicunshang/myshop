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
                        <form class="form-horizontal form-bordered" data-parsley-validate="true" action="{{ url('admin/order/save') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $info->id or '' }}">
                            @if(empty($info->orderNo))
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="orderNo">订单编号 * :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" name="orderNo" placeholder="订单编号" data-parsley-required="true" data-parsley-required-message="请输入订单编号" value="{{ $info->orderNo or old('orderNo') }}" disabled="disabled" />
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="gName">商品名称 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="gName" placeholder="商品名称" data-parsley-required="true" data-parsley-required-message="请输入商品名称" value="{{ $info->gName or old('gName') }}"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="price">商品价格 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="price" placeholder="单价" data-parsley-required="true" data-parsley-required-message="请输入单价" value="{{ $info->price or old('price') }}" disabled="disabled"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="number">数量 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="number" placeholder="数量" data-parsley-required="true" data-parsley-required-message="请输入购买数量" value="{{ $info->number or old('number') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="amount">订单价格 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="amount" placeholder="订单价格" data-parsley-required="true" data-parsley-required-message="请输入订单价格" value="{{ $info->amount or old('amount') }}"/>
                                </div>
                            </div>

                            @if(in_array($info->status, [2,3]))
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="expressNameCode">快递公司 * :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control selectpicker"
                                                data-live-search="true"
                                                data-style="btn-white"
                                                name="expressNameCode">
                                            <option value="">-- 请选择 --</option>
                                            @foreach($express as $item)
                                                <option value="{{ $item->expressNameCode }}" @if($info->expressNameCode == $item->expressNameCode) selected="selected" @endif>{{ $item->expressName }}</option>
                                            @endforeach
                                        </select>
                                        <p id="parent_id_error"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="expressNo">快递单号 * :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" name="expressNo" placeholder="快递单号" value="{{ $info->expressNo or old('expressNo') }}"/>
                                    </div>
                                </div>
                            @endif
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