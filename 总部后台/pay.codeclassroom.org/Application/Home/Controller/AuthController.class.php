<?php
namespace Home\Controller;
use Home\Controller\CommonController;
class AuthController extends CommonController{
	function  showlist(){
		$info=$this->getInfo();
		$this->assign('info',$info);
		$this->sidebar();
		$this->authid = $this->dao();			//保证左侧导航条是展开状态，默认选中
		$this->display();
	}
	function add(){
		if(!empty($_POST)){
			// 添加权限操作
		// $auth里面有4个信息，还缺少2个关键信息：auth_path和auth_level
		// ①先把这4条信息放入数据库，auth_path需要新纪录的ID值所以先插入后修改
		// ②update 把path 和level更新进去
		//实例化authmode
		$authMode=D('sw_auth');
		$new_id = $authMode->add ( $_POST ); // 返回新记录主键ID值
		                           // path的值为两种情况
		                           // ①当前权限是顶级权限，path=$new_id
		                           // ②当前权限是非顶级权限 path=父级全路径+$new_id
		if ($_POST ['auth_pid'] == 0) {
			$auth_path = $new_id;
		} else {
			// 查询指定父级的全路径，条件$auth['auth_pid']
			$pinfo = $authMode->find ( $_POST ['auth_pid'] ); // 查询出父级的记录信息
			$p_path = $pinfo ['auth_path']; // 父级全路径
			$auth_path = $p_path . "-" . $new_id;
		}
		//auth_level数目：全路径里边中横线的个数
		//把全路径变为数组，计算数组个数和-1，就是leve的值
		$auth_level=count(explode('-', $auth_path))-1;
		$dt=array(
				'auth_id'=>$new_id,
				'auth_path'=>$auth_path,
				'auth_level'=>$auth_level,
		);
		   $z= $authMode->save($dt);
			if ($z) {
				jilu();         //添加操作日志
				$this->success('添加权限成功',U('showlist'));
			}else{
				$this->error('添加权限失败',U('showlist'));
			}
		}else{
		//获得父级权限信息
		$info=$this->getInfo(true);
		//从$fino里面获得信息。例如array(1=>'商品管理'，2=>'商品添加')
		$this->assign('authinfo',$info);
		$this->sidebar();
		$this->authid = $this->dao();			//保证左侧导航条是展开状态，默认选中
		$this->display();
		}
	}
	//删除权限
	function del(){
		//接受参数获得权限的ID
		$auth_id=$_POST['auth_id'];
		$Model=D('sw_auth');
		$re=$Model->delete($auth_id);//根据ID删除权限信息
		jilu();         //添加操作日志
		if($re){
			echo 1;
		}
	}
	
	
	function  getInfo($flag=FALSE){
		//如果$flag为false,查询全部的权限信息
		//如果$flag为true,查询level=0|1的信息
		if ($flag==true) {
			$info=D('sw_auth')->where('auth_level<2')->order('auth_path asc')->select();
			}else{
			$info=D('sw_auth')->order('auth_path asc')->select();
			}
		 
		//$info[x][auth_name]="->"auth_name
		//str_repeat('->', $v['auth_level'])第二参数显示几，就显示几个箭头
		foreach ($info as $k=>$v){
			$info[$k]['auth_name']=str_repeat('-->', $v['auth_level']).$info[$k]['auth_name'];
		}
		return $info;
	}
}