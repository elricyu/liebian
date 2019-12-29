<?php
namespace Home\Model;
use Think\Model;

class BaoBiaoModel extends Model{
    //查询管理费记录表
    public function get_all($b){
        $sql = "SELECT a.*,b.centre FROM wx_centre_glf as a LEFT JOIN wx_centre as b on a.centre_id=b.centre_id where s_status=1 ";
        if($b['time_s']){
            $sql.= " and a.jiao_time>='{$b['time_s']}'";
        }
        if($b['time_e']){
            $sql.= " and a.jiao_time<='{$b['time_e']}'";
        }
        if($b['centre']){
            $sql.= " and b.centre='{$b['centre']}'";
        }
        if($b['f_name']){
            $sql.= " and a.f_name='{$b['f_name']}'";
        }
        $sql.=" ORDER BY jiao_time;";
        $a = $this->query($sql);
        $he = 0;
        foreach ($a as $v){
            $he=$v['shi_fei']+$he;
        }
        $t['t'] = $a;
        $t['he'] = $he;
        return $t;
    }
}