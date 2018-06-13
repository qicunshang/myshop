<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    //

    public function addressList(Request $request)
    {
        $params = $request->all();
        $list = DB::table('address')->where('user_id', $params['user_id'])->orderBy('status', 'DESC')->get();
        return apiReturn($list);
    }

    public function detail(Request $request)
    {
        $params = $request->all();
        if(!isset($params['id'])){
            return apiReturn([], '100400', '缺少参数');
        }
        $info = DB::table('address')->where([['id', $params['id']], ['user_id', $params['user_id']]])->first();
        return apiReturn($info);
    }

    public function create(Request $request){
        $params = $request->all();
        $validate = Validator::make($params, [
            'contactName' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);
        if($validate->fails()){
            return apiReturn([],'100100','参数不合法或缺少参数');
        }

        $data = [
            'user_id'=> $params['user_id'],
            'contactName'=> $params['contactName'],
            'phone'=> $params['phone'],
            'province'=> $params['province'],
            'city'=> $params['city'],
            'county'=> $params['county'],
            'address'=> $params['address'],
        ];
        if(isset($params['status'])){
            $data['status'] = $params['status'];

        }else{
            $data['status'] = 0;
        }
        if($data['status'] == 1){
            $this->setDefaultAddress($params['user_id'], $params['id']);
        }

        if(DB::table('address')->insert($data)){
            return apiReturn([]);
        }else{
            return apiReturn([], '-1', '创建失败');
        }
    }

    public function update(Request $request)
    {
        $params = $request->all();
        $validate = Validator::make($params, [
            'id' => 'required',
        ]);
        if($validate->fails()){
            return apiReturn([],'100100','参数不合法或缺少参数');
        }

        if(isset($params['contactName'])) $data['contactName'] = $params['contactName'];
        if(isset($params['phone'])) $data['phone'] = $params['phone'];
        if(isset($params['province'])) $data['province'] = $params['province'];
        if(isset($params['city'])) $data['city'] = $params['city'];
        if(isset($params['county'])) $data['county'] = $params['county'];
        if(isset($params['address'])) $data['address'] = $params['address'];
        if(isset($params['status']) && $params['status'] == 1) {
            $this->setDefaultAddress($params['user_id'], $params['id']);
            $data['status'] = $params['status'];
        }

        if((DB::table('address')->where([['id', $params['id']], ['user_id', $params['user_id']]])->update($data))!==false){
            return apiReturn();
        }else{
            return apiReturn([], '-1', '保存失败');
        }
    }

    public function del(Request $request)
    {
        $params = $request->all();
        $validate = Validator::make($params, [
            'id' => 'required',
        ]);
        if($validate->fails()){
            return apiReturn([],'100100','参数不合法或缺少参数');
        }

        if(DB::table('address')->where([['id', $params['id']], ['user_id', $params['user_id']]])->delete()){
            return apiReturn();
        }else{
            return apiReturn([], '-1', '删除失败');
        }
    }

    public function setDefaultAddress($user_id, $id)
    {
        DB::table('address')->where('user_id', $user_id)->update(['status'=>0]);
        DB::table('address')->where('id', $id)->update(['status'=>1]);
    }
}
