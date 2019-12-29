<?php
namespace Home\Model;
use Think\Model;

class BaomingModel extends Model{
    //通过角色过滤显示列表 $role=需要查询的角色
    public function get_role($role){
        $sql = "SELECT xueyuan_baoming.*,wx_centre.centre FROM xueyuan_baoming LEFT JOIN wx_centre ON xueyuan_baoming.centre_id = wx_centre.centre_id where xueyuan_baoming.role like '%{$role}%'";
        $data = $this->query ( $sql );
        return $data;
    }
    //查询某个角色的列表 $role=需要查询的角色
    public function get_role_s($role){
        $sql = "SELECT xueyuan_baoming.*,wx_centre.centre FROM xueyuan_baoming LEFT JOIN wx_centre ON xueyuan_baoming.centre_id = wx_centre.centre_id WHERE xueyuan_baoming.role in({$role})";
        $data = $this->query ( $sql );
        return $data;
    }
    //查询一个数据 $id=查询条件 $t=查询字段 $b=表名
    public function getone($id,$t,$b="xueyuan_baoming"){
        $tt = M($b)->where($id)->getfield($t);
        return $tt;
    }
    //通过报名表的用户ID，查询该用户信息 $id=user_id 返回一维数组
    public function get_id($id){
        $sql = "select xueyuan_baoming.*,wx_centre.centre from xueyuan_baoming  LEFT JOIN wx_centre on xueyuan_baoming.centre_id=wx_centre.centre_id WHERE xueyuan_baoming.user_id={$id}";
        $data = $this->query ( $sql );
        return $data[0];
    }
}