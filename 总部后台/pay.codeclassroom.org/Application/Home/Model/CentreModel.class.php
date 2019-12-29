<?php
namespace Home\Model;
use Think\Model;

class CentreModel extends Model{
    //通过中心ID，查询中心档案表，返回该中心档案数据
    public function dang_only($id){
        $model = M('wx_centre_dangan');
        $tt = $model->where('centre_id =' . $id)
            ->select();
        return $tt;
    }
    //通过中心ID，连表查询所有存在档案的中心档案及中心名称
    public function dang_x($id){
        $tt = M('wx_centre_dangan as a')->join('wx_centre AS b on a.centre_id = b.centre_id')
            ->where('a.centre_id =' . $id)
            ->field('a.*,b.centre')
            ->select();
        return $tt;
    }
    //查询所有已有档案的中心ID
    public function dang_id(){
        $model = M('wx_centre_dangan');
        $tt = $model->field('centre_id')->select();
        for ($a=0;$a<count($tt);$a++){
            $arr[] = $tt[$a]['centre_id'];
        }
        return $arr;
    }
    //根据中心ID，查询中心状态
    public function dang_stat($d_id){
        $model = M('wx_centre_dangan');
        $tt = $model->where("d_id=".$d_id)->getfield("status");
        return $tt;
    }
    //修改中心状态
    
}