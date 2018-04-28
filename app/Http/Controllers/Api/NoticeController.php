<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    //
    public function noticeList(Request $request)
    {
        $params = $request->all();
        $list = DB::table('notice')
            ->where(function($query) use($params){
                if(isset($params['type'])){
                    $query->where('type', $params['type']);
                }
            })
            ->get();
        foreach($list as $key=>$value){
            $list[$key]->image = $value->image?'https://' . $_SERVER["HTTP_HOST"] . $value->image:$value->image;
        }
        return apiReturn($list);
    }

    public function detail(Request $request)
    {
        $id = $request->get('id');
        if(!$id){
            return apiReturn([], '100400', '缺少参数');
        }
        $info = DB::table('notice')->where('id', $id)->first();
        return apiReturn($info);
    }
}
