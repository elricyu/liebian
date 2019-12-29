<?php
namespace Home\Controller;
use Home\Controller\CommonController;
class IndexController extends CommonController {
    public function index(){
    	//传值给侧边公共栏
      $this->sidebar(1);	
	  $this->display();
    }
    public function loginout(){
    	session('a_name',null);
    	session('mg_id',null);
        session('centre_id',null);
    	$this->redirect("Login/index");
    }
//    public function shan(){
//        $a = M('crm_kecheng')->where('centre_id=223')->delete();
//        k($a);die;
//    }
}