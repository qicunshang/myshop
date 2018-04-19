<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
    //列表
    public function goodsList(Request $request)
    {
        $params = $request->all();
        //排序规则
        $orderBy = isset($params['orderBy'])?$params['orderBy']:'Id';
        //取出记录数
        $take = isset($params['limit'])?$params['limit']:10;
        $skip = isset($params['pageNum'])?($params['pageNum']-1) * $take:0;
        $list = DB::table('goods')
            ->where(function($query) use($params){
                if(isset($params['cId'])){
                    $query->where('cId', $params['cId']);
                }
            })
            ->where(function($query) use($params){
                if(isset($params['gName'])){
                    $query->where('gName', 'like', "%{$params['gName']}%");
                }
            })
            ->orderBy($orderBy, 'DESC')
            ->skip($skip)
            ->take($take)
            ->get();
        return apiReturn($list);
    }

    public function detail(Request $request)
    {
        $id = $request->get('id');
        $info = DB::table('goods')->where('id', $id)->get();
        return apiReturn($info);
    }
}
