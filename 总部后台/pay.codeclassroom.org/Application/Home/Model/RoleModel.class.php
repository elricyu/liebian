<?php
namespace  Model;
use Think\Model;
class RoleModel extends  Model{
	//权限分配
	//$auth是一位数组信息，代表分配的权限信息
	function saveAuth($auth,$role_id){
		//把权限ID信息由数组变为中间由逗号分割的字符串信息
		$auth_ids=implode(',', $auth);
		//根据ids权限id信息产寻具体操作方法信息
		$info=D('auth')->select($auth_ids);
		//show_bug($info);拼装控制器和操作方法
		$auth_ac="";
		foreach($info as $k=>$v){
			if(!empty($v['auth_c']) && !empty($v['auth_a'])){
				$auth_ac.=$v['auth_c']."-".$v['auth_a'].",";
			}	
		}
		$auth_ac=rtrim($auth_ac,',');//删除最右边的逗号
		$data=array(
				'role_id'=>$role_id,
				'role_auth_ids'=>$auth_ids,
				'role_auth_ac'=>$auth_ac,
		);
		return $this->save($data);
		
	}
}