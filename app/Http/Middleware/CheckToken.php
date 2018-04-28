<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $params = $request->all();
        $validate = Validator::make($params, [
            'token' => 'required|string',
            'user_id' => 'required'
        ]);
        if(!isset($params['token']) || empty($params['token'])){
            return apiReturn([],'100010','token过期或不存在');
        }
        if($validate->fails()) {
            return apiReturn([],'100100','参数不合法或缺少参数');
        }
        //token不存在
        $userInfo = DB::table('users')->where([['id', $params['user_id']] ,['remember_token', $params['token']]])->first();
        if($userInfo && (strtotime($userInfo->token_at) + 30 * 24 * 3600 > time())){
            return $next($request);
        }else{
            return apiReturn([],'100010','token过期或不存在');
        }
    }
}
