<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
	function __construct() {
		// 先执行父类构造方法，否则系统要报错
		// 因为原先的构造方法默认是被执行的
		parent::__construct ();
		// 自己写的不知道对错
		if ($_SESSION ["mg_id"] !=null) {
			// CONTROLLER_NAME ----Goods
			// ACTION_NAME ---showlist
			// 当前请求的操作
			$now_ac = CONTROLLER_NAME . "-" . ACTION_NAME;
			// 过滤控制器和方法，避免用户非法请求
			// 通过角色获得用户可以访问的控制器和方法信息
			$sql = "select role_auth_ac from sw_manager a join sw_role b on a.mg_role_id=b.role_id where a.mg_id=" . $_SESSION ['mg_id'];
			$auth_ac = D ()->query ( $sql );
			$auth_ac = $auth_ac [0] ['role_auth_ac'];
			// 判断$now_ac是否在$auth_ac里出现过
			// strpos返回false 是没有出现，返回数字表示有
			// 管理员不限制
			// 默认情况下下列方法没有限制
			// 'Index-index', 'Index-loginout', 'Common-noauth' manger/login College-checkphone=验证报名表手机号是否唯一
			//Centre-serch 中心模糊查询
			$allow_ac = array (	
					'Index-index',
					'Index-loginout',
					'Common-noauth',
					'Centre-serch',
					'College-checkphone'
			);
			if (! in_array ( $now_ac, $allow_ac ) && $_SESSION ['mg_id'] != 1 && strpos ( $auth_ac, $now_ac ) === false) {
				$this->redirect('Common/noauth');
			}
		}else{
			//未登陆就先跳转登陆页
			$this->redirect("Login/index");//Manager 方法继承CommonController后会产生死循环反复重定向登陆页面
			exit();
			//设想解决方式
		}
	}
	
	public function sidebar(){
		//根据session用户id 查询用户角色ID信息
		$sql="select * from sw_manager where mg_id=".$_SESSION['mg_id'];
		$minfo=D()->query($sql);
		$role_id=$minfo[0]['mg_role_id'];	
		//根据角色ID获得权限ids信息
		$sql="select * from sw_role where role_id=".$role_id;
		$rinfo=D()->query($sql);
		$auth_ids=$rinfo[0]['role_auth_ids'];		
		//根据$auth_ids产寻全部权限信息
		//①获得顶级权限
		$sql="select * from sw_auth where auth_level=0 ";
		//如果是admin管理员就不要进行权限控制
		if($_SESSION["mg_id"]!=1){
			$sql.=" and auth_id in ($auth_ids)";
		}
		$p_info=D()->query($sql);
		//②获得次顶级权限
		$sql="select * from sw_auth where auth_level=1 ";
		//如果是admin管理员就不要进行权限控制
		if($_SESSION["mg_id"]!=1){
			$sql.=" and auth_id in ($auth_ids)";
		}
		$s_info=D()->query($sql);
		$this->assign('pauth_info',$p_info);
		$this->assign('sauth_info',$s_info);
		//传值给侧边公共栏
	}
	//空方法访问后
	public function err(){
		//展示404页面
		$this->display();
	}
	public function _empty(){
		//空方法访问跳转error方法展示404页面
		$this->redirect('Common/err');
	}
	public function noauth(){
		$this->display();
	}
	//查询该用户是否有某个方法的权限
	public function quan($ca){
			$mg_id = session('mg_id');  		//获取用户ID
												// 通过用户ID查询用户角色，连表查询该角色的所有权限
			$sql = "select role_auth_ac from sw_manager a join sw_role b on a.mg_role_id=b.role_id where a.mg_id=" . $mg_id;
			$auth_ac = D()->query ( $sql );
			$auth_ac = $auth_ac [0] ['role_auth_ac'];
			// 判断$ca是否在$auth_ac里出现过  没有权限返回0
			if ($_SESSION ['mg_id'] != 1 && strpos ( $auth_ac, $ca ) === false) {
				return 0;
			}else{
				return 1;
			}
	}
	//锁定本页面导航条
	public function dao($a='',$c=''){
		$a OR $a =ACTION_NAME;
		$c OR $c = CONTROLLER_NAME;
		$ww = M('sw_auth')->where("auth_c='{$c}' and auth_a='{$a}'")->field('auth_path')->select();
		$t = explode("-",$ww['0']['auth_path']);
		return $t[1];
	}
}
