<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $params = $request->all();
        $validate = Validator::make($params, [
            'code' => 'required',
            'encryptedData' => 'required',
            'iv' => 'required',
        ]);
        if($validate->fails()){
            return apiReturn([],'100100','参数不合法或缺少参数');
        }

        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . getenv('WX_APPID') . '&secret=' . getenv('WX_APPSECRET') . '&js_code=' . $params['code'] . '&grant_type=authorization_code';
        $json = curl_request($url);
        $json = json_decode($json);

        if(!isset($json->errcode) && isset($json->session_key)) {
            $userifo = new \WXBizDataCrypt(getenv('WX_APPID'), $json->session_key);
            $errCode = $userifo->decryptData($params['encryptedData'], $params['iv'], $data);
            if ($errCode == 0) {
                $wxData = json_decode($data);
                $userInfo = DB::table('users')->where('openid', $wxData->openId)->first();

                //TODO 数据库中有此用户,更新token
                $token = uniqid();
                if($userInfo){
                    DB::table('users')
                        ->where('id', $userInfo->id)
                        ->update([
                            'remember_token' => $token,
                            'token_at' => date('Y-m-d H:i:s', time()),
                            'updated_at' => date('Y-m-d H:i:s', time()),
                        ]);
                    $user_id = $userInfo->id;
                }else{
                    $data = array(
                        'openid'        => $wxData->openId,
                        'name'          => $wxData->nickName,
                        'level'         => 0,
                        'remember_token'=> $token,
                        'token_at'      => date('Y-m-d H:i:s', time()),
                        'created_at'    => date('Y-m-d H:i:s', time()),
                        'updated_at'    => date('Y-m-d H:i:s', time()),
                    );
                    $user_id = DB::table('users')->insertGetId($data);
                }
                return apiReturn(['user_id'=>$user_id,'token'=>$token]);
            }else{
                return apiReturn([],'100102','微信验证失败');
            }
        }else{
            return apiReturn([],'100101','参数不正确');
        }
    }
}
