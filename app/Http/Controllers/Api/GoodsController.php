<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\Goods;
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
        $orderBy = isset($params['orderBy'])?$params['orderBy']:'id';
        $orderBy = explode('.', $orderBy);
        $orderBy[1] = isset($orderBy[1])?$orderBy[1]:'DESC';
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
            ->where(function($query) use($params){
                if(isset($params['hot'])){
                    $query->where('hot', $params['hot']);
                }
            })
            ->orderBy($orderBy[0], $orderBy[1])
            ->orderBy('id', 'DESC')
            ->skip($skip)
            ->take($take)
            ->get();
        foreach($list as $key=>$value){
            $list[$key]->gImg  = $this->parseImg(DB::table('goods_img')->where('gid', $value->id)->get());
        }
        return apiReturn($list);
    }

    public function detail(Request $request)
    {
        $params = $request->all();
        if(!isset($params['id'])){
            return apiReturn([], '100400', '缺少参数');
        }
        $m_goods = new Goods();
        $info = $m_goods->GoodsInfo($params['id']);
        return apiReturn($info);
    }

    /**
     * 处理图片字符串，前面加上网址
     * */
    private function parseImg($images){
        $data = [];
        foreach($images as $key=>$value){
            $data[] = 'https://' . $_SERVER["HTTP_HOST"] . $value->imgUrl;
        }
        return $data;
    }
}
