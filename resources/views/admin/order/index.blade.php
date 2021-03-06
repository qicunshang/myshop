@extends('admin.layouts.admin')

@section('admin-css')
    <link href="{{ asset('asset_admin/assets/plugins/treeTable/vsStyle/jquery.treeTable.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset_admin/assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset_admin/assets/plugins/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <style>
        .expressInfo{
            width: 400px;
            position: absolute;
            background-color: #ffffff;
            border-radius: 5px;
            padding: 20px;
            float: left;
            display: none;
        }
        .showExpressInfo:hover +.expressInfo{
            display: block;
        }
    </style>
@endsection

@section('admin-content')
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="javascript:;">Home</a></li>
            <li><a href="javascript:;">Tables</a></li>
            <li class="active">Basic Tables</li>
        </ol>
        <!-- end breadcrumb -->
        <!-- begin page-header -->
        <h1 class="page-header">菜单列表 <small>header small text goes here...</small></h1>
        <!-- end page-header -->
        <!-- begin row -->
        <div class="row">
            <!-- begin col-6 -->
            <div class="col-md-12">
                <!-- begin panel -->
                <div class="panel panel-inverse" data-sortable-id="table-basic-5">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        </div>
                        <h4 class="panel-title">列表</h4>
                    </div>
                    <div class="panel-body">
                        {{--@permission('menus.add')--}}
                        {{--@if(auth('admin')->user()->can('notice.add'))
                        <a href="{{ url('admin/goods/create') }}">
                            <button type="button" class="btn btn-primary m-r-5 m-b-5"><i class="fa fa-plus-square-o"></i> 新增</button>
                        </a>
                        @endif--}}
                        {{--@endpermission--}}
                        <table class="table table-bordered table-hover" id="treeTable">
                            <thead>
                            <tr>
                                <th style="width: 16%;">订单编号</th>
                                <th style="width: 12%;">商品名称</th>
                                <th style="width: 12%;">单价</th>
                                <th style="width: 12%;">买家</th>
                                <th style="width: 12%;">总价</th>
                                <th style="width: 12%;">订单状态</th>
                                <th style="width: 12%;">创建时间</th>
                                <th style="width: 12%;">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $item)
                            <tr id="{{ $item->id }}">
                                <td>{{ $item->orderNo}}</td>
                                <td>
                                    <a href="/admin/goods/{{ $item->gId }}" target="_blank">{{ $item->gName}}</a>*{{$item->number}}
                                </td>
                                <td>{{ $item->price }}</td>
                                <td>
                                    {{ $item->contactName }} {{ $item->phone }}<br>
                                    {{ $item->address }}
                                </td>
                                <td>{{ $item->amount }}</td>
                                <td>
                                    @if($item->status == -1)
                                        已删除
                                    @elseif($item->status == 0)
                                        已取消
                                    @elseif($item->status == 1)
                                        未付款
                                    @elseif($item->status == 2)
                                        待发货
                                    @elseif($item->status == 3)
                                        待确认
                                    @elseif($item->status == 4)
                                        已完成
                                    @endif

                                    @if(in_array($item->status, [3, 4]))
                                        <br>
                                        <a class="showExpressInfo" href="JavaScript:;">查看物流</a>
                                        <div class="expressInfo">加载中...</div>
                                    @endif
                                </td>
                                <td>{{ $item->createDate }}</td>
                                <td>
                                    <a href='/admin/order/{{ $item->orderNo }}'>
                                        <button type='button' class='btn btn-success btn-xs'>
                                            <i class='fa fa-pencil'> 编辑</i>
                                        </button>
                                    </a>
                                    <a href='javascript:;' data-id='1' class='btn btn-danger btn-xs destroy'>
                                        <i class='fa fa-trash'> 删除</i>
                                        <form action='/admin/order/del/{{ $item->orderNo }}' method='get'  name='delete_item_1'  style='display:none'>{{ csrf_field() }}
                                        </form>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            <tr>{!! $list->render() !!}</tr>
                            </tbody>
                        </table>
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
    <script src="{{ asset('asset_admin/assets/plugins/gritter/js/jquery.gritter.js') }}"></script>
    <script src="{{ asset('asset_admin/assets/plugins/bootstrap-sweetalert-master/dist/sweetalert.js') }}"></script>
    <script src="{{ asset('asset_admin/assets/plugins/treeTable/jquery.treeTable.js') }}"></script>
    <script>
        $(function(){
            $('.showExpressInfo').hover(function(){
                if($('.expressInfo').html() == '加载中...'){
                    var order_id = $(this).parent().parent().attr("id");
                    $.get('/admin/express/info?order_id=' + order_id,function(data){
                        $('.expressInfo').html(data);
                    });
                }
            });


            var option = {
                theme:'vsStyle',
                expandLevel : 2,
                beforeExpand : function($treeTable, id) {
                    if ($('.' + id, $treeTable).length) { return; }
                    $treeTable.addChilds(html);
                },
                onSelect : function($treeTable, id) {
                    window.console && console.log('onSelect:' + id);
                }
            };
            $('#treeTable').treeTable(option);

            @if (session()->has('flash_notification.message'))
                //通知信息
                $.gritter.add({
                    title: '操作消息！',
                    text: '{!! session('flash_notification.message') !!}'
                });
            @endif

            //删除
            $(document).on('click','.destroy',function(){
                var _delete_id = $(this).attr('data-id');
                swal({
                        title: "确定删除？",
                        text: "删除将不可逆，请谨慎操作！",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        cancelButtonText: "取消",
                        confirmButtonText: "确定",
                        closeOnConfirm: false
                    },
                    function () {
                        $('form[name=delete_item_'+_delete_id+']').submit();
                    }
                );
            });
        });
    </script>

@endsection