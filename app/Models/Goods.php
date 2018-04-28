<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Goods extends Model
{
    //
    protected $table = 'goods';
    public $timestamps = false;

    public function GoodsInfo($gId)
    {
        $info = DB::table('goods')->where('id', $gId)->first();
        $info->gImg = $this->parseImg(DB::table('goods_img')->where('gid', $info->id)->get());
        return $info;
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
