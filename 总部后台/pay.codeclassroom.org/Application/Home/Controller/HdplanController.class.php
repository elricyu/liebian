<?php
/**
 * @Date    2019-04-04 14:30
 * @Version ${id}
 * @Description 活动方案
 */
namespace Home\Controller;
use Think\Controller;

class HdplanController extends CommonController
{
	// 活动方案列表页
	public function index()
	{
		$a = I('get.');
		$this->assign('ar', $a);
		$where = 'a.baocun = 2 and a.centre_id = 0  and a.status = 1';
		if($a['time_s']){
			$where .= " and a.create_time >= '{$a['time_s']}'";
		}
		if($a['time_e']){
			$where .= " and a.create_time <= '{$a['time_e']}'";
		}
		if($a['set']){
			$where .= " and b.jc_name like '%{$a['set']}%'";
		}

		$m = M('crm_template as a')->join('crm_huodong as b on a.id=b.template_id');
		$p = getpage($m, $where, 10);        //使用公用函数分页
		$this->page = bootstrap_page_style($p->show());        //把分页页码样式转换后 传入页面
		$data = $m->where($where)->order('a.id desc')->select();
		$this->assign('data',$data);
		$this->sidebar();                        //侧边公共栏
        $this->authid = $this->dao();            //保证左侧导航条是展开状态，默认选中
        $this->display();
	}

	// 活动方案详情页
	public function details()
	{
		$id = I('get.id');
		if(empty($id)){
			$this->error('参数错误');
		}
		$data = D('crm_template as a')
			->join('crm_huodong as b on a.id=b.template_id ')
			->where(['a.status'=>1,'a.id'=>$id])
			->find();
		$type = ['1'=>'主题活动','2'=>'节日活动'];
		$hd_type = ['1'=>'砍价活动','2'=>'拼团活动','3'=>'促单(线上)','4'=>'促单(线上+线下)'];
		$data['type'] = $type[$data['type']];
		$data['hd_type']  = $hd_type[$data['hd_type']];
		$this->assign('data',$data);
		$this->sidebar();                        //侧边公共栏
        $this->authid = $this->dao();            //保证左侧导航条是展开状态，默认选中
        $this->display();
	}

	// 添加活动方案
	public function add()
	{
		if(IS_POST){
			$data = I('post.');
			$tupian = $this->upload();
			$huodong['img'] = $tupian['thumb_img'];
			$image = [];
			foreach($tupian as $k=>$v){
				if(is_numeric($k)){
					$image[$k+1] = $v;
				}
			}
			$img = json_encode($image);
			$template['img'] = $img;
			$template['create_user'] = session('mg_id');
			$huodong['jc_name'] = $data['jc_name'];    //活动名称
			$huodong['hd_type'] = $data['hd_type'];     //活动类型
			$huodong['jc_describe'] = $data['jc_describe'];    //活动描述
			$huodong['title']       = $data['title'];      //分享名称
			$huodong['fx_describe'] = $data['fx_describe'];  //分享描述
			// dump($data);die;
			if(empty($huodong['img'])){
				$this->error('分享图不能为空');
			}
			if(empty($template['img'])){
				$this->error('活动图片不能为空');
			}
			if(empty($huodong['jc_name'])){
				$this->error('活动名称不能为空');
			}
			if(empty($huodong['jc_describe'])){
				$this->error('活动描述不能为空');
			}
			if(empty($huodong['title'])){
				$this->error('分享名称不能为空');
			}
			if(empty($data['type'])){
				$this->error('活动标签不能为空');
			}
			if(empty($huodong['fx_describe'])){
				$this->error('分享描述不能为空');
			}
			$template['centre_id'] = 0;
			$template['type']       = $data['type'];    //活动标签
			$template['baocun']     = 2;
			$template['create_time'] = date("Y-m-d H:i:s");
			$template_id = D('crm_template')->add($template);
			$huodong['template_id'] = $template_id;     //模板id
			$huodong['create_time'] = date("Y-m-d H:i:s");
			$res = D('crm_huodong')->add($huodong);
			if($res){
				$this->success('添加成功',U('Hdplan/index'));
			}else{
				$this->error('添加失败');
			}
		}else{
			$this->sidebar();                        //侧边公共栏
	        $this->authid = $this->dao();            //保证左侧导航条是展开状态，默认选中
	        $this->display();
		}
	}

	// 删除活动方案
	public function del()
	{
		$id = I('post.id');
		D('crm_template')->where(['id'=>$id,'centre_id'=>0])->setField('status',0);
		$res = D('crm_huodong')->where(['template_id'=>$id])->setField('status',0);
		if($res){
			$this->ajaxReturn(['status'=>1,'msg'=>'删除成功']);
		}else{
			$this->ajaxReturn(['status'=>2,'msg'=>'删除失败']);
		}
	}


	// 图片上传
	public function upload()
	{
		$upload = new \Think\Upload();// 实例化上传类    
		$upload->maxSize   =     204800000 ;// 设置附件上传大小    
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','zip','bmp');// 设置附件上传类型
		$upload->savePath  =      '/Public/plan/'; // 设置附件上传目录    // 上传单个文件     
		$info   =   $upload->upload();
		if(!$info) {
			// 上传错误提示错误信息        
			return false;    
		}else{// 上传成功 获取上传文件信息
			foreach($info as $k=>$v){        
				$url[$k] = 'http://' . $_SERVER['SERVER_NAME'] . '/' . 'Uploads/' . $v['savepath'].$v['savename'];
			}
			return $url;
		}
	}

}