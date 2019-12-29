<?php
/**
 * Created by PhpStorm.
 * User: HANCY
 * Date: 2019/1/9
 * Time: 14:52
 */

namespace app\index\controller;


use think\Controller;
use think\Db;

class Wxpush extends Controller
{
//appid:xxxxxxxxxxxxxxxxxx
//secret:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    //签到推送
    public function qd_push($user_id,$pk_id,$teacher_id,$xiaohao)
    {
        $template_id='KQZT2NIY_9EXOEOY6pkeI13wxDJWHGbdkZxjTJ35OE8';
        $open = Db::table('wx_guanxi')
            ->where("(user_id='$user_id'or user_id like '$user_id,%' or user_id like '%,$user_id'  or user_id like '%,$user_id,%') and status = 1 and vip_openid != '' and push_status = 2")
            ->select();
        //课程
        $ke = Db::table('crm_kecheng')
			->alias('a')
            ->join("crm_ke b"," a.kc_id=b.kc_id")
            ->where("a.pk_id='$pk_id'")
            ->find();
        //老师
        $teacher_name = Db::table('xueyuan_baoming')->where("user_id",$teacher_id)->value('username');
	
        //剩余课时
        $y_keshi =  Db::table('wx_user')
			->alias('a')
            ->join("crm_kjilu b"," a.kahao = b.card_num and b.item_id = '{$ke['item_id']}' and b.status = 1")
            ->where("a.user_id='$user_id'")
            ->value('b.y_keshi');

        foreach ($open as $key => $value) {
            if(!empty($value['vip_openid']) && $value['push_status'] == 2){	//存在会员家openid并且开启了通知
                $tmp_keshi = $y_keshi - $xiaohao;   //剩余课时减去消耗课时
                $da['touser']=$value['vip_openid'];
                $da['template_id']=$template_id;
                $da['data']['first']=array("value"=>"签到成功通知");
                $da['url']='';
                $da['data']['keyword1']=array("value"=>"$ke[kc_name]","color"=>"#173177");
                $da['data']['keyword2']=array("value"=>"$teacher_name","color"=>"#173177");
                $da['data']['keyword3']=array("value"=>"","color"=>"#173177");
                $da['data']['keyword4']=array("value"=>"$tmp_keshi","color"=>"#173177");
                $da['data']['keyword5']=array("value"=>"前台签到","color"=>"#173177");
                $da['data']['remark']=array("value"=>'您好，您已签到成功',"color"=>"#173177");
                $res = wxts2($da);
                //拼装日志
                $tmp['centre_id'] = $ke['centre_id'];
                $tmp['user_id'] = $user_id;
                $tmp['pk_hd_id'] = $pk_id;
                $tmp['type'] = 1;
                $tmp['openid'] = $value['vip_openid'];
                $tmp['push_date'] = date('Y-m-d');
				$tmp['status'] = 2; //推送成功
                $tmp['errmsg'] = json_encode($res);
                // if($res['errcode'] != '0'){ //推送失败
                    // $tmp['status'] = 3;
                    // $tmp['errmsg'] = json_encode($res);
                // }else{
                    // $tmp['status'] = 2; //推送成功
                    // $tmp['errmsg'] = json_encode($res);
                // }
                Db::table('crm_wx_push_list')->insert($tmp);
            }
        }
    }

    //选课 选择固定班
    public function xk_push($user_id,$pk_id)
    {
        $template_id='K1R-zutH1Nj9AZ20j3fqwi7Ha9r90qe2kvZDi2rQu9E';
        $da = [];
        //$a['id'] = 149898;
        $open = Db::table('wx_guanxi')
            ->where("(user_id='$user_id'or user_id like '$user_id,%' or user_id like '%,$user_id'  or user_id like '%,$user_id,%') and status = 1 and vip_openid != '' and push_status = 2")
            ->select();
        $baobao_name = Db::table('wx_user')->where('user_id',$user_id)->value('baobao_name');
        //排课信息
        $pk = Db::table('crm_kecheng')->where('pk_id',$pk_id)->find();
        //课程
        $ke = Db::table('crm_ke')->where("kc_id='$pk[kc_id]'")->find();
        //教室
        $flexo = Db::table('flexo')->where("id='$pk[xuhao]'")->find();
        //老师
        $teacher = Db::table('xueyuan_baoming')->where("user_id",$pk['user_id'])->find();
        $week = ['','星期一','星期二','星期三','星期四','星期五','星期六','星期日'];
        //时间$pk['start_time'].
        $time = $week[$pk['week']].$pk['s_time'].'-'.$pk['x_time'];
        foreach ($open as $key => $value) {
            if(!empty($value['vip_openid']) && $value['push_status'] == 2){
                $da['touser']=$value['vip_openid'];
                $da['template_id']=$template_id;
                $da['data']['first']=array("value"=>"已为你选定以下课程");
                $da['url']='';
                $da['data']['keyword1']=array("value"=>"$ke[kc_name]","color"=>"#173177");
                $da['data']['keyword2']=array("value"=>"$baobao_name","color"=>"#173177");
                $da['data']['keyword3']=array("value"=>"$time","color"=>"#173177");
                $da['data']['keyword4']=array("value"=>"$flexo[content]","color"=>"#173177");
                $da['data']['keyword5']=array("value"=>"$teacher[username]","color"=>"#173177");
                $da['data']['remark']=array("value"=>'点击查看详情',"color"=>"#173177");
                $res = wxts2($da);
                //拼装日志
                $tmp['centre_id'] = $ke['centre_id'];
                $tmp['user_id'] = $user_id;
                $tmp['pk_hd_id'] = $pk_id;
                $tmp['type'] = 2;
                $tmp['openid'] = $value['vip_openid'];
                $tmp['push_date'] = date('Y-m-d');
				$tmp['status'] = 2; //推送成功
                $tmp['errmsg'] = json_encode($res);
          
                // if($res['errcode'] != '0'){ //推送失败
                    // $tmp['status'] = 3;
                    // $tmp['errmsg'] = json_encode($res);
                // }else{
                    // $tmp['status'] = 2; //推送成功
                    // $tmp['errmsg'] = json_encode($res);
                // }
                Db::table('crm_wx_push_list')->insert($tmp);
            }
        }
    }

    //预约活动课成功
    public function hd_push($user_id,$activity)
    {
        $da = [];
        //$data['user_id'] = 149898;
        $template_id='Q4yta2jnEOfqolBMvT2DFc8CKHqX6fii-nDdH9V3hYA';
        //中心
        //$centre_id = $this->token_data['centre_id'];
        $open = Db::table('wx_guanxi')
            ->where("(user_id='$user_id'or user_id like '$user_id,%' or user_id like '%,$user_id'  or user_id like '%,$user_id,%') and status = 1 and vip_openid != '' and push_status = 2")
            //->where('centre_id',$centre_id)
            ->select();
        foreach ($open as $key => $value) {
            if(!empty($value['vip_openid']) && $value['push_status'] == 2){
                $da['touser']=$value['vip_openid'];
                $da['template_id']=$template_id;
                $da['data']['first']=array("value"=>"您好,$value[jz_name],您的宝宝报名参加的$activity[title]活动即将开始,特此通知");
                $da['url']='';
                $da['data']['keyword1']=array("value"=>"$activity[title]","color"=>"#173177");
                $da['data']['keyword2']=array("value"=>"$activity[start_time]","color"=>"#173177");
                $da['data']['remark']=array("value"=>'请您准时到达，不见不散',"color"=>"#173177");
                $res = wxts2($da);
                //拼装日志
                $tmp['centre_id'] = $activity['centre_id'];
                $tmp['user_id'] = $user_id;
                $tmp['pk_hd_id'] = $activity['hd_id'];
                $tmp['type'] = 3;
                $tmp['openid'] = $value['vip_openid'];
                $tmp['push_date'] = date('Y-m-d');
                // if($res['errcode'] != '0'){ //推送失败
                    // $tmp['status'] = 3;
                    // $tmp['errmsg'] = json_encode($res);
                // }else{
                    // $tmp['status'] = 2; //推送成功
                    // $tmp['errmsg'] = json_encode($res);
                // }
				$tmp['status'] = 2; //推送成功
                $tmp['errmsg'] = json_encode($res);
                Db::table('crm_wx_push_list')->insert($tmp);
            }
        }
    }
	//测试发送
    public function wx_test()
    {
        $da['touser']="oxmpS0zkur0NfwT3SJaqhX97WmiI";
        $qd_tid = "KQZT2NIY_9EXOEOY6pkeI13wxDJWHGbdkZxjTJ35OE8";
//        $xk_tid = "K1R-zutH1Nj9AZ20j3fqwi7Ha9r90qe2kvZDi2rQu9E";
//        $hd_tid = "Q4yta2jnEOfqolBMvT2DFc8CKHqX6fii-nDdH9V3hYA";
        $da['template_id']=$qd_tid;
        $da['data']['first']=array("value"=>"签到成功通知");
        $da['url']='';
        $da['data']['keyword1']=array("value"=>"测试课程","color"=>"#173177");
        $da['data']['keyword2']=array("value"=>"测试老师","color"=>"#173177");
        $da['data']['keyword3']=array("value"=>"1","color"=>"#173177");
        $da['data']['keyword4']=array("value"=>"108","color"=>"#173177");
        $da['data']['keyword5']=array("value"=>"人工签到","color"=>"#173177");
        $da['data']['remark']=array("value"=>'您好，您已签到成功',"color"=>"#173177");
        $res= wxts2($da);
        echo "<pre>";
        var_dump($res);
        dd($res);
    }
}