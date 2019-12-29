<?php
namespace Model;
use Think\Model;

class ManagerModel extends Model{
	//实现表单验证
	// 是否批处理验证 true一次性获得全部验证错误信息
	protected $patchValidate    =   true;
	//通过重新父类属性validate实现表单验证
	protected $_validate        =   array(
			array('mg_pwd','require','原密码必须填写'),
			array('mg_pwd','mg_ypwd','原始密码不正确',0,'confirm'),
			array('mg_npwd','require','新密码必须填写'),
			array('mg_rpwd','require','重复新密码必须填写'),
			array('mg_npwd','mg_rpwd','新密码与重复密码必须一致',0,'confirm'),	
	);
	//验证用户名密码
	function checkNamePwd($name,$pwd){
		//根据$name 查询是否有此记录
		//select * from sw_manager where mg_name=$name
		//select() find()
		//根据指定字段进行查询getByxxx();
		$info=$this->getByMg_name($name);
		//$info = null 说明用户错误
		//$info = 一位数组 用户名正确
		
		//判断$info不等于NULL就继续验证密码
		if($info!=null){
			//验证密码(查询出来的密码如输入的密码进行比较)
			if($info['mg_pwd']!=$pwd){
				return false;
			}else{
				return $info;
			}			
		}else{
			return false;
		}		
	}
	
}