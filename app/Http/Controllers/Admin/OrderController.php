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

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = DB::table('order')
            ->select('order.*', 'goods.gName', 'goods.price')
            ->join('goods', 'order.gId', '=', 'goods.id')
            ->orderBy('createDate', 'DESC')
            ->get();
        foreach($list as $key=>$item){
            if(!empty($item->expressNo)){
                $expressInfo = $this->htmlTraces($item->expressNameCode, $item->expressNo);
                $list[$key]->expressInfo = $expressInfo;
            }
        }
//        dd($list);
        return view('admin.order.index', ['list' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $info = (object)[
            'cId'       => 0,
            'hot'       => 0,
            'package'   => 1,
            'gStatus'   => 0,
        ];
        $category = DB::table('category')
            ->orderBy('cNo', 'asc')
            ->where('cNo', '<>', '0')
            ->get();
        return view('admin.order.create', ['info' => $info, 'category' =>$category]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = DB::table('order')
            ->where('id',$id)
            ->orWhere('orderNo',$id)
            ->first();
        $express = DB::table('express')->get();
//        dd($info);
        return view('admin.order.create', ['info' => $info, 'express' => $express]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(Request $request)
    {
        $params = $request->all();

        $saveData = [
            'number'         => $params['number'],
            'amount'         => $params['amount'],
        ];
        //是否发货
        if(!empty($params['expressNo'])){
            $saveData['expressNameCode'] = $params['expressNameCode'];
            $saveData['expressNo'] = $params['expressNo'];
            $saveData['status'] = 3;
        }

        DB::table('order')
            ->where('id',$params['id'])
            ->update($saveData);
        return redirect('admin/order/list');
    }

    public function del($id)
    {
        $info = DB::table('order')
            ->where('id',$id)
            ->orWhere('orderNo',$id)
            ->first();
        if($info->status != -1){
            $res = DB::table('order')
                ->where('id', $id)
                ->orWhere('orderNo', $id)
                ->update(['status'=> -1]);
        }else{
            $res = DB::table('order')
                ->where('id', $id)
                ->orWhere('orderNo', $id)
                ->delete();
        }
        if ($res){
            flash('删除成功','success');
        }else{
            flash('删除失败','error');
        }
        return redirect('admin/order/list');
    }

    public function ajaxIndex(Request $request)
    {
        $result = $this->permission->ajaxIndex($request);
        return response()->json($result,JSON_UNESCAPED_UNICODE);
    }

    /**
     * 功能说明：
     * @param string $expressNameCode
     * @param string $expressNo
     * @return array
     * @date: 2018/6/19 13:05
     */
    private function expressInfo($expressNameCode, $expressNo){
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
