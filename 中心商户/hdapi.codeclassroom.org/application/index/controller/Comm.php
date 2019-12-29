<?php
/**
 * Created by PhpStorm.
 * User: HANCY
 * Date: 2018/6/13
 * Time: 10:59
 */

namespace app\index\controller;


use think\Controller;
use think\Db;

class Comm extends Controller
{
    protected $token_data = [];
    protected $token = "";

    public function _initialize()
    {
        header("access-control-allow-origin:*");
        ob_clean();
        $token = input('post.token');
        $this->token = $token;
        if(empty($this->token)){
            exit(json_encode(['status'=>2,'msg'=>'参数错误']));
        }else{
            $this->token_data = get_token($token);
        }
    }

    //验证用户是否已经登录  每次访问其他接口首先访问该接口
    public function check_login()
    {
        $token_data = $this -> token_data;    //解析token
        $token = $this -> token;
        $user_id=isset($token_data["user_id"]) ? $token_data["user_id"] : '';    //获取用户ID
        $centre_id=isset($token_data["centre_id"]) ? $token_data["centre_id"] : '';    //获取用户中心ID

        if($user_id && $centre_id ){    //如果存在用户ID  中心ID
            $arr=Db::table('saas_login')->where(['user_id'=>$user_id,'centre_id'=>$centre_id])->order('id desc')->field('id,token,status,expire_time')->find();  //获取最后一条登录信息
            
            if(empty($arr)){    //如果不存在登录信息
                return json(['status'=>10003,'msg'=>'登录状态验证失败,请重新登录']);
            }
            if($arr['status']==1){  //如果未阅读引导页
               // exit(json_encode(['status'=>10005,'msg'=>'请先阅读引导内容'])); //返回 未阅读引导页  跳转引导页
            }
            
            if($arr['token'] != $token){    //如果最后一次登录token与当前token不一致
                return json(['status'=>10006,'msg'=>'您的账号在另一地点登录。已被迫下线']);
            }
            //判断是否长时间未操作
            $nowtime = time();
            if($nowtime > ($arr['expire_time'] + 3600)){ //已过期
                // dump($arr['expire_time']);die;
                return json(['status'=>10004,'msg'=>'长时间未操作,请重新登录']);
            }else{  //未过期  更新过期时间
                $expire_time = $nowtime+3600;
                Db::table('saas_login')->where('id',$arr['id'])->setField('expire_time',$expire_time);
            }
            //验证成功执行默认操作
            return json(['status'=>10001,'msg'=>'验证成功']);
        }else{
            return json(['status'=>10003,'msg'=>'登录状态验证失败,请重新登录']);
        }
    }

}