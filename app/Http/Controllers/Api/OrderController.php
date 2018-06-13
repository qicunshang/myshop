<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Goods;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * 订单状态
     * -1 已删除
     * 0 已取消
     * 1 下单未支付（默认）
     * 2 已支付
     * 3 已发货
     * 4 已确认收货
     *
     * */


    /**
     * 功能说明：创建订单，需要接受的参数商品id、购买数量、用户id、收货地址id
     * @return string json
     * @date: 2018/4/25 15:44
     */
    public function create(Request $request)
    {
        $params = $request->all();
        $validate = Validator::make($params, [
            'gId' => 'required',
            'addressId' => 'required',
            'number' => 'required',
        ]);
        if($validate->fails()){
            return apiReturn([],'100100','参数不合法或缺少参数');
        }

        //商品
        $m_goods = new Goods();
        $goodsInfo = $m_goods->GoodsInfo($params['gId']);
        $goodsInfo->gImg = $goodsInfo->gImg[0];
        //判断库存
        if($goodsInfo->stock - $params['number'] < 0){
            return apiReturn([], '100504', '库存不足');
        }

        //地址
        $addressInfo = DB::table('address')
            ->where('id', $params['addressId'])
            ->first();

        //用户
        $userInfo = DB::table('users')->where('id', $params['user_id'])->first();

        //订单数据
        //判断用户身份，对应购买价格
        $data = [
            'user_id' => $params['user_id'],
            'gId' => $params['gId'],
            'gName' => $goodsInfo->gName,
            'number' => $params['number'],
            'freightAmount' => $goodsInfo->freightAmount,
            'status' => 1,
            'contactName' => $addressInfo->contactName,
            'phone' => $addressInfo->phone,
            'address' => $addressInfo->address,
            'createDate' => date('Y-m-d H:i:s', time()),
        ];
        //根据用户类型定价
        if($userInfo->level == 0){
            $data['price'] = $goodsInfo->price;
        }else{
            $data['price'] = $goodsInfo->tradePrice;
        }
        $data['amount'] = $params['number'] * $data['price'] + $goodsInfo->freightAmount;

        $data['orderNo'] = $this->makeNo($params['user_id']);
        if(isset($params['comment'])) $data['comment'] = $params['comment'];

        if(DB::table('order')->insert($data)){
            return apiReturn();
        }else{
            return apiReturn([], '-1', '保存失败');
        }
    }

    //TODO 权限验证，非管理员无法查看别人的订单
    public function orderList(Request $request)
    {
        $params = $request->all();
        $list = DB::table('order')
            ->where('user_id', $params['user_id'])
            ->where(function($query) use($params){
                if(isset($params['status'])){
                    $query->where('status', $params['status']);
                }
            })
            ->orderBy('id', 'asc')
            ->get();
        return apiReturn($list);
    }

    public function detail(Request $request)
    {
        $params = $request->all();
        if(!isset($params['id'])){
            return apiReturn([], '100400', '缺少参数');
        }
        $info = DB::table('order')->where([['id', $params['id']], ['user_id', $params['user_id']]])->first();
        return apiReturn($info);
    }

    public function cancel(Request $request)
    {
        $params = $request->all();
        $validate = Validator::make($params, [
            'id' => 'required',
        ]);
        if($validate->fails()){
            return apiReturn([],'100100','参数不合法或缺少参数');
        }

        if(DB::table('order')->where([['id', $params['id']], ['user_id', $params['user_id']]])->update(['status'=>0])){
            return apiReturn();
        }else{
            return apiReturn([], '-1', '删除失败');
        }
    }


    //生成订单编号
    private function makeNo($user_id)
    {
        return mt_rand(10,99)
        . sprintf('%010d',time() - 946656000)
        . sprintf('%03d', (float) microtime() * 1000)
        . sprintf('%03d', (int) $user_id % 1000);
    }
}
