<?php
namespace Home\Model;
use Think\Model;

class PingJiaModel extends Model{
    //查询评价数据$data=岗位 $x：1=培训师评价标签 默认为0=学员评价标签
    public function getGangWei($data,$x=0){
        $model = M('xueyuan_pingjia');
        $t = array("statusx"=>$x,"gangwei"=>"$data");
        $tt = $model->where($t)
                    ->select();
        return $tt;
    }
}