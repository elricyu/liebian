<?php
namespace Home\Model;
use Think\Model;

class PkjlModel extends Model{
    public function getweek($jiaoshiname,$centre_id,$x,$start_time,$end_time){
    	if(empty($start_time)){
		$time=date("Ymd");
		$arr=M('crm_kecheng')
		->join("crm_ke on crm_kecheng.kc_id=crm_ke.kc_id")
		->join("xueyuan_baoming on crm_kecheng.user_id=xueyuan_baoming.user_id")
		->where("crm_kecheng.centre_id='$centre_id' and crm_kecheng.status=1 and crm_kecheng.week=$x")
		->field("crm_kecheng.xuhao,crm_kecheng.s_time,crm_kecheng.x_time,crm_ke.kc_name,crm_ke.yueling,xueyuan_baoming.username,crm_kecheng.start_time,crm_kecheng.end_time")
		->order('crm_kecheng.xuhao')
		->select();
		foreach ($arr as $key => $val) {
		    $b=str_replace("-", "", $val['start_time']);
		    $c=str_replace("-", "", $val['end_time']);
		    if($time>=$b and $c>$time){
		    	$ar[]=$val;
		    }
		}
		$hhh=[];
			foreach ($ar as $k => $val) {
                    $hhh[$val['s_time']."-".$val['x_time']][]=$val;
        }
	}else{
		$ar=M('crm_kecheng')
		->join("crm_ke on crm_kecheng.kc_id=crm_ke.kc_id")
		->join("xueyuan_baoming on crm_kecheng.user_id=xueyuan_baoming.user_id")
		->where("crm_kecheng.centre_id='$centre_id' and crm_kecheng.status=1 and crm_kecheng.week=$x and start_time='$start_time' and end_time='$end_time'")
		->field("crm_kecheng.xuhao,crm_kecheng.s_time,crm_kecheng.x_time,crm_ke.kc_name,crm_ke.yueling,xueyuan_baoming.username,crm_kecheng.start_time,crm_kecheng.end_time")
		->order('crm_kecheng.xuhao')
		->select();
		$hhh=[];
		foreach ($ar as $k => $val) {
                    $hhh[$val['s_time']."-".$val['x_time']][]=$val;
        }
	}
        return $hhh;
    }
}