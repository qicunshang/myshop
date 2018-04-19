<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
    //
    public function goodsList(Request $request)
    {
        $params = $request->all();
        $skip = isset($params['page'])?($params['page']-1) * ($params['limit']):0;
        $take = isset($params['limit'])?$params['limit']:10;
        $list = DB::table('goods')
            ->where(function($query) use($params){
                if(isset($params['cId'])){
                    $query->where('cId', $params['cId']);
                }
            })
            ->skip($skip)
            ->take($take)
            ->get();
        return apiReturn($list);
    }
}
