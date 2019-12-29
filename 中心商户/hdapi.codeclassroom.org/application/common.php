<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function dd($info){
    echo "<pre>";
    print_r($info);
    die;
}

function ddd($info){
    echo "<pre>";
    print_r($info);
}
//生成前端存储token值
function set_token($data){
    //$data  = ['centre_id'=>'','user_id'=>'','name'=>'','role'=>'','gangwei'=>'','phone'=>''];     存储数组格式
    $tmp_token = base64_encode(json_encode($data));
    $token = $tmp_token.'|**|'.time();  //连接当前时间戳 用于判断是否重复登录
    return $token;
}
//解析前端发送的token
function get_token($token){
    $tmp = explode('|**|',$token);  //拆分token
    $tmp_token = $tmp[0];   //获取token加密串
    $data = json_decode(base64_decode($tmp_token),true);    //解密 返回数组
    return $data;
}

//获取月龄
function get_month_diff($start, $end = FALSE)
{
    if(strpos($start,'0000-') !== false){
        $start = '';
    }
    $res = isDateTime($start);
    if($res == false){
        $start = '';
    }
    if(empty($start) || !isset($start)){
        $start = time();
    }else{
        $start = strtotime($start);
    }
    $end OR $end = time();

    $startobj = new \DateTime();
    $startobj -> setTimestamp(@$start);
    $endobj   = new \DateTime();
    $endobj -> setTimestamp(@$end);
    $diff  = $startobj->diff($endobj);
    return $diff->format('%y') * 12 + $diff->format('%m');
}

//判断是否为时间格式
function isDateTime($dateTime){
    $ret = strtotime($dateTime);
    return $ret !== FALSE && $ret != -1;
}