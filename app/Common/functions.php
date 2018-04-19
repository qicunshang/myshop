<?php
/**
 * Created by PhpStorm.
 * User: 刘帅
 * Date: 2018/4/19
 * Time: 11:21
 *
 * Talk is cheap, Show me the code.
 */

function curl_request($url,$https=true,$method='get',$data=null){
    //1.初始化url
    $ch = curl_init($url);
    //2.设置相关的参数
    //字符串不直接输出,进行一个变量的存储
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //判断是否为https请求
    if($https === true){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    //判断是否为post请求
    if($method == 'post'){
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    //3.发送请求
    $str = curl_exec($ch);
    //4.关闭连接
    curl_close($ch);
    //返回请求到的结果
    return $str;
}

//跳转
function success($url, $msg = '操作成功！', $waitSecond=2){
    return view('jump', ['info' => ['status'=>'success','msg'=>$msg,'url'=>$url], 'waitSecond'=>$waitSecond]);
}

function error($url, $msg = '操作失败！', $waitSecond=2){
    return view('jump', ['info' => ['status'=>'error','msg'=>$msg,'url'=>$url], 'waitSecond'=>$waitSecond]);
}

/**
 * 返回接口数据
 * @param $array array 需要返回的数据
 * @param $code string  错误码
 * @param $info string 错误信息
 * @return string json字符串
 * */
function apiReturn($array = array(), $code = '0', $info ='success'){
    $arr['data'] = $array;
    if(!$arr['data']){
        $arr['data'] = array();
    }
    $arr['status'] = array(
        'code' => $code,
        'info' => $info,
    );
    ob_clean();

    $content = json_encode($arr);
    $status = 200;
    $value = 'application/json';
    $response = new \Illuminate\Http\Response($content,$status);
    return $response->header('Content-Type', $value);
}


/**
 * 功能说明：
 * @param String $httpurl
 * @return boolean
 * @date: 2018/4/4 10:08
 *
 * */
function parseHost($httpurl)
{

    $httpurl = strtolower( trim($httpurl) );
    if(empty($httpurl)) return ;
    $regx1 = '/https?:\/\/(([^\/\?#&]+\.)?([^\/\?#&\.]+\.)(com\.cn|org\.cn|net\.cn|com\.jp|co\.jp|com\.kr|com\.tw)(\:[0-9]+)?)\/?/i';
    $regx2 = '/https?:\/\/(([^\/\?#&]+\.)?([^\/\?#&\.]+\.)(cn|com|org|info|us|fr|de|tv|net|cc|biz|hk|jp|kr|name|me|tw|la)(\:[0-9]+)?)\/?/i';
    $host = $tophost = '';
    if(preg_match($regx1,$httpurl,$matches))
    {
        $host = $matches[1];
    } elseif(preg_match($regx2, $httpurl, $matches)) {
        $host = $matches[1];
    }
    if($matches)
    {
        $tophost = $matches[3].$matches[4];
        $domainLevel = $matches[2] == 'www.' ? 1:(substr_count($matches[2],'.')+1);
    } else {
        $tophost = '';
        $domainLevel = 0;
    }
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    return array($domainLevel,$tophost,$http_type.$host);
}

//jsssdk_sign
function sha1Sign($params){
    ksort($params, SORT_STRING);
    $string = formatQueryParaMap($params, false);
    $sign = sha1($string);
    return $sign;
}

function formatQueryParaMap($paraMap, $urlEncode = false)
{
    $buff = "";
    ksort($paraMap);
    foreach ($paraMap as $k => $v) {
        if (null != $v && "null" != $v) {
            if ($urlEncode) {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
    }
    $reqPar = '';
    if (strlen($buff) > 0) {
        $reqPar = substr($buff, 0, strlen($buff) - 1);
    }
    return $reqPar;
}