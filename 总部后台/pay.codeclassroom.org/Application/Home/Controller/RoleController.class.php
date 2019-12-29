<?php
namespace Home\Controller;

use Home\Controller\CommonController;
//角色控制器
class RoleController extends CommonController{
//显示角色列表
	function index(){
		$info=D()->table('sw_role')->select();
		$this->assign('info',$info);
		$this->sidebar();
		$this->authid = $this->dao();           //保证左侧导航条是展开状态，默认选中
		$this->display();
	}
	//权限分配
	function distribute($role_id){
		if(!empty($_POST)){
			
			$role=M('sw_role');//实例化一个role模型
			//把权限ID信息由数组变为中间由逗号分割的字符串信息
			$auth_ids=implode(',', $_POST['authname']);
			//根据ids权限id信息产寻具体操作方法信息
			$info=D('sw_auth')->select($auth_ids);
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
// 			var_dump($data);exit();
			$z=$role->save($data);		
			if($z){
				jilu();         //添加操作日志
				$this->redirect('Role/index');
			}else{
			
				$this->redirect('Role/distribute',array(role_id=>$role_id));
			}
		}else{
		//通过$role_id获得角色名称
		$rinfo=D('sw_role')->getByRole_id($role_id);
		$this->assign('role_name',$rinfo['role_name']);
		
		//查询全部的权限信息，放如模板进行权限分配
		$pauth_info=D('sw_auth')->where('auth_level=0')->select();//父级权限
		$sauth_info=D('sw_auth')->where('auth_level=1')->select();//次级权限
		$tauth_info=D('sw_auth')->where('auth_level=2')->select();//次次级权限
		
		
		//把当前角色对应的权限给查询出来
		$authinfo=D('sw_role')->getByRole_id($role_id);
		$auth_ids_arr=explode(',',$authinfo['role_auth_ids']);//数组auth_id信息
		
		  
		$this->assign('auth_ids_arr',$auth_ids_arr);
		$this->assign('pauth_info',$pauth_info);
		$this->assign('sauth_info',$sauth_info);
		$this->assign('tauth_info',$tauth_info);
		//显示侧边栏
		
		$this->sidebar();
		$this->authid = $this->dao();           //保证左侧导航条是展开状态，默认选中
		$this->display();
		}
	}
	function add(){
		if(!empty($_POST)){
			//实例化角色表
			$mode=D('sw_role');
			$mode->create();
			$re=$mode->add();
			if($re){
				jilu();         //添加操作日志
				$this->redirect('index');
			}else{
				$this->redirect('index');
			}
		}else{
			$this->sidebar();
			$this->authid = $this->dao();           //保证左侧导航条是展开状态，默认选中
			$this->display();
		}
		
	}
	function upd(){
		if(!empty($_POST)){
			$Mode=D('sw_role');
			$Mode->create();
			$re=$Mode->save();
			if($re){
				jilu();         //添加操作日志
				$this->redirect('role/index');
			}else{
				$this->redirect('role/index');
			}
			
		}else{
			//获当前角色权限
			$this->data=D('sw_role')->getByRole_id($_GET['role_id']);
			$this->sidebar();
			$this->authid = $this->dao();           //保证左侧导航条是展开状态，默认选中
			$this->display();
		}
	}
	function del(){
		$Role = M ( 'sw_role' ); // 实例化角色表
		$id = I ( 'post.id' ); // 获得ID
		//删除角色前删除对应的管理员(非必要)
		
		$rel = $Role->delete ($id); // 执行删除操作
		if ($rel) {
			jilu();         //添加操作日志
			echo 1;
		}
	}
}
