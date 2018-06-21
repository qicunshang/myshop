<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\PermissionRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\PermissionRepositoryEloquent as PermissionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class ExpressController extends Controller
{
    public function getExpressInfo(Request $request)
    {
        $params = $request->all();

        //根据订单查物流单号
        $orderInfo = DB::table('order')
            ->where('id', $params['order_id'])
            ->orWhere('orderNo', $params['order_id'])
            ->first();
        $expressTraces = $this->htmlTraces($orderInfo->expressNameCode, $orderInfo->expressNo);
        return $expressTraces?:'暂无物流信息或物流信息已过期';
    }

    /**
     * 功能说明：
     * @param string $expressNameCode
     * @param string $expressNo
     * @return array
     * @date: 2018/6/19 13:05
     */
    private function expressInfo($expressNameCode, $expressNo){
        //第三方快递配置
        $ExpressUserId = '1353509';
        $ApiKey = '16ba6824-c098-40db-9638-071b6c30a5f2';

        $url = 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx';

        $express = [
            'ShipperCode' => $expressNameCode,
            'LogisticCode' => $expressNo,
        ];
        $params = [
            'RequestData'   => urlencode(json_encode($express)),
            'EBusinessID'   => $ExpressUserId,
            'RequestType'   => 1002,
            'DataSign'      => $this->getDataSign($express, $ApiKey),
            'DataType'      => '2', //2-json
        ];

        $res_json = curl_request($url, false, 'post', $params);
//        dd($res_json);
        return json_decode($res_json, true);
    }

    private function htmlTraces($expressNameCode, $expressNo){
        $expressInfo = $this->expressInfo($expressNameCode, $expressNo);
        $html = '';
        foreach($expressInfo['Traces'] as $item){
            $html .= $item['AcceptStation'] . $item['AcceptTime'] . '<br>';
        }
        return $html;
    }

    /**
     * 功能说明：
     * @param array $data
     * @return string $key
     * @date: 2018/6/19 13:07
     */
    private function getDataSign($data, $key){
        //(请求内容(未编码)+AppKey)进行MD5加密，然后Base64编码，最后 进行URL(utf-8)编码
        return urlencode(base64_encode(md5(json_encode($data).$key)));
    }

}
