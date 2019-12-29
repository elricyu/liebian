<?php
namespace Home\Model;
use Think\Model;

class BookModel extends Model{
    //查询住现有最大序号，并自增1
    public function xuHao(){
        $model = M('xueyuan_book');
        $a = $model->max('xuhao');          //查出现在最大的序号
        $b = $a+1;							//自增
        return $b;
    }
    
}