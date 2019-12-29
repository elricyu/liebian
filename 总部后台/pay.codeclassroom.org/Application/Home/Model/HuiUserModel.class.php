<?php
namespace Home\Model;
use Think\Model;

class HuiUserModel extends Model{
    //查询管理费记录表
    //array(
    //  field => 需要查询的字段 （可选，不写查询全部）
    //  biao1 => 需要查询的表   (必选)
    //  biao2 => 需要连的表    （可选）
    //  biao3 => 需要连的表    （可选）
    //  where => 查询条件多个添加用数组 array(
    //                                       [0] => "user_id = 3"
    //                                       [1] => "t_time >= 3"
    //                                       )  （可选）
    //  order => "id asc/desc"
    //)
    public function get_all($b){
        $sql = "SELECT ";
        if($b['field']){
            $sql.= $b['field'];
        }else{
            $sql.= " * ";
        }
        $sql.= " FROM ".$b['biao1'];
        for ($t=2;$t<10;$t++){
            if($b['biao'.$t]){
                $sql.= " LEFT JOIN ".$b['biao'.$t];
            }
        }
        $sql.= " where 2 < 3 ";
        if($b['where']){
            if(is_array ( $b['where'] )){
                foreach ($b['where'] as $k => $v){
                    $sql.= " and ".$v;
                }
            }else{
                $sql.= " and ".$b['where'];
            }
        }
        if($b['time_s']){
            $sql.= " and a.create_time>='{$b['time_s']}' ";
        }
        if($b['time_e']){
            $sql.= " and a.create_time<='{$b['time_e']}' ";
        }
        if($b['centre']){
            $sql.= " and b.centre='{$b['centre']}' ";
        }
        if($b['name']){
            $sql.= " and c.baobao_name='{$b['name']}' ";
        }
        if($b['order']){
            $sql.= " ORDER BY ".$b['order'];
        }
        $a = $this->query($sql);
        return $a;
    }
}