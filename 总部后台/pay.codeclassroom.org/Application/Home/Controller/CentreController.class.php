<?php
namespace Home\Controller;

use Home\Controller\CommonController;

class CentreController extends CommonController
{
	//中心列表
    public function index()
    {
        $b = I('get.');                    //获取查询条件
        $this->assign('ar', $b);                //查询条件回传
        // 判断当前登录人id
        $id = session('mg_id');
        $uuu = M('sw_manager')->getByMgId($id);
        $where = '1<2';
        if(!empty($b['set'])){
            $where .= " and centre like '%{$b['set']}%'";
        }
        $m = M('wx_centre')->field("centre_id,centre,site,c_phone,l_name,l_phone,sid");
        $p = getpage($m, $where,20);
        $t = $m->where($where)
            ->order("centre_id")
            ->select();
        // dump($t);die;
        $this->page = bootstrap_page_style($p->show());        //把分页页码样式转换后 传入页面
        $this -> assign('nowpage',$now_page);   //分配当前页码
        $this->sheng_ids = M('region')->where("REGION_ID in({$uuu['sheng_ids']})")->select();
        $this->assign('data', $t);
        $this->sidebar();                        //侧边公共栏
        $this->authid = $this->dao();            //保证左侧导航条是展开状态，默认选中
        $this->display();
    }


    //添加中心
    public function add()
    {
        if ($a = I('post.')) {
            $Centre = M('wx_centre');
            $di = M('region');
            $b = $Centre->max('sequence');        //查出现有中心最大序号
            $a['sequence'] = $b + 1;                //自增并插入数组

            //判断是否填写了省市县信息，没有返回提示必须填写 填写了进行数据拼接储存 地址拼接时省市县中间隔空格
            if ($a['s_province'] == "") {
                $this->guo = $di->where("PARENT_ID=1")->select();        //所有省份信息
                $this->assign('ar', 1);             //回传提示地址未选择
                $this->assign('data', $a);
                $this->sidebar();
                $this->authid = $this->dao();       //保证左侧导航条是展开状态，默认选中
                $this->display();
                die;
            } else {
                $sheng = $di->field('REGION_NAME')->find($a['s_province']);
                $shi = $di->field('REGION_NAME')->find($a['s_city']);
                $qu = $di->field('REGION_NAME')->find($a['s_county']);
                $a['site'] = $sheng['REGION_NAME'] . " " . $shi['REGION_NAME'] . " " . $qu['REGION_NAME'] . " " . $a['site']; //拼接地址
            }
            //根据省份，分配总部客服
            $a['sid'] = $_POST['s_province'];
            $a['shi_id'] = $a['s_city'];
            $a['qu_id'] = $a['s_county'];
            $a['jyzt'] = 10;                    //默认新添加中心经营状态为合约未开店
            $a['contract_state'] = 2;                    //默认新添加中心经营状态为合约未开店
            // 验证手机号
            $is_phone = $this->match_phone($a['l_phone']);
            // $is_phone = false;
            if($is_phone  !== true){
                $this->error('手机号验证失败');
            }
            // dump($a);die;
            $re = $Centre->add($a);                //执行添加
            if ($re) {
                $this->saas_add($a['l_phone'],$a['l_name'],$re);
                $this->success('添加成功', 'index');
            } else {
                $this->error('添加中心失败', 'index');
            }
        } else {
            $this->guo = M("region")->where("PARENT_ID=1")->select();        //所有省份信息
            $this->sidebar();
            $this->authid = $this->dao();        //保证左侧导航条是展开状态，默认选中
            $this->display();
        }
    }

    // 添加管理系统账号
    public function saas_add($phone,$name,$centre_id)
    {
        $res = D('xueyuan_baoming')->add(['username'=>$name,'phone'=>$phone,'centre_id'=>$centre_id]);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    //修改中心
    public function upd()
    {
        $Centre = M('wx_centre');                    //实例化中心mode
        $di = M('region');
        $post = I('post.');
        // k($post);die;
        if ($a = I('post.')) {
            //判断是否修改了地址信息，如有拼接地址。
            if ($a['s_county']) {
                $sheng = $di->field('REGION_NAME')->find($a['s_province']);
                $shi = $di->field('REGION_NAME')->find($a['s_city']);
                $qu = $di->field('REGION_NAME')->find($a['s_county']);
                //拼接地址
                $a['site'] = $sheng['REGION_NAME'] . " " . $shi['REGION_NAME'] . " " . $qu['REGION_NAME'] . " " . $a['site'];    
            }
            if (!empty($a['s_province'])){
                $a['sid'] =$a['s_province'];
            }
            if(!empty($a['s_city'])){
                $a['shi_id'] = $a['s_city'];
            }
            if(!empty($a['s_county'])){
                $a['qu_id'] = $a['s_county'];
            }
            // dump($a);die;
            $re = $Centre->save($a);
            if (false !== $re) {
                $this->saas_upd($a['centre_id'],$a['l_name'],$a['l_phone'],$a['old_name'],$a['old_phone']);
                $this->success('修改成功', 'index');
            } else {
                $this->error('修改失败', 'index');
            }
        } else {
            $a = I('get.');
            $this->assign('data', $a);
            $ar = M('wx_centre')->where(['centre_id'=>$a['c_id']])->find();
            $this->assign('ar',$ar);
            // dump($ar);die;
            $b['site'] = array_pop($tmp_site);
            $shi = M('region')->where(['PARENT_ID'=>$ar['sid']])->select();
            $qu = M('region')->where(['PARENT_ID'=>$ar['shi_id']])->select();
            $this->assign('qu',$qu);
            $this->assign('shi',$shi);
            $this->guo = M("region")->where("PARENT_ID=1")->select();        //所有省份信息
            $this->zhu = 1;                    //读取当前登录人的ID 并传入页面
            $this->sidebar();
            $this->authid = $this->dao();        //保证左侧导航条是展开状态，默认选中
            $this->display();                    //展示模板
        }
    }

    public function saas_upd($centre_id,$name,$phone,$old_name,$old_phone)
    {
        $res = D('xueyuan_baoming')
            ->where(['centre_id'=>$centre_id,'username'=>$old_name,'phone'=>$old_phone])
            ->save(['username'=>$name,'phone'=>$phone]);
            // dump($res);die;
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public function resetting()
    {
        $centre_id = I('post.centre_id');
        $phone = I('post.phone');
        $res = D('xueyuan_baoming')->where(['centre_id'=>$centre_id,'phone'=>$phone])->setField('pad','a9e9a81003adc4f8ce4d15a027347ff5');
        if($res !== false){
            $this->ajaxReturn(['status'=>1,'msg'=>'密码重置成功'],'JSON');
        }else{
            $this->ajaxReturn(['status'=>2,'msg'=>'密码重置失败'],'JSON');
        }
    }

    //地区联动
    public function sel_coun()
    {
        $coun = $_POST['coun'];
        $region_info = M('region');
        $region_arr = $region_info->where("PARENT_id=" . $coun)->select();
        echo json_encode($region_arr);
    }


    /*
     * 手机号验证函数
     * */
    public function match_phone($phonenumber)
    {
        if (empty($phonenumber)){
            return false;
        }else{
            if(preg_match("/^1[3456789]{1}\d{9}$/",$phonenumber)){
                return true;
            }else{
                return false;
            }
        }
    }
	// 活动统计
    public function hd_count()
    {
        $b = I('get.');                    //获取查询条件
        $this->assign('ar', $b);                //查询条件回传
        // 判断当前登录人id
        $id = session('mg_id');
        $uuu = M('sw_manager')->getByMgId($id);
        $where = '1<2';
        if(!empty($b['set'])){
            $where .= " and centre like '%{$b['set']}%'";
        }
        $now_page = I('get.p');
        $m = M('wx_centre')->field("centre_id,centre,site");
        $p = getpage($m, $where,20);
        $this->page = bootstrap_page_style($p->show());        //把分页页码样式转换后 传入页面
        $this -> assign('nowpage',$now_page);   //分配当前页码
        $t = $m->where($where)
            ->order("centre_id")
            ->select();
            // dump($t);die;
        // 循环获取当前中心活动统计
        foreach($t as $k => $v){
            $hd_info = M('crm_huodong')
                ->alias('a')
                ->join('left join crm_template as b on a.template_id=b.id')
                ->where(['b.baocun'=>2,'a.fb_type'=>['neq',1],'a.status'=>1])
                ->field('hd_id,jc_name,hd_type,hd_start_time,hd_end_time')
                ->where(['b.centre_id'=>$v['centre_id']])
                ->order("a.hd_start_time desc")
                ->select();
            $t[$k]['hd_count'] = count($hd_info); // 举办活动总数
            $t[$k]['browse'] = 0;
            $t[$k]['zrs'] = 0;
            $t[$k]['fxs'] = 0;
            $t[$k]['zsr'] = 0;
            // 获取总浏览量，参与人数，分享人数，总收入
            foreach($hd_info as $k1 => $v1){
                // $info[$k]['hd_type'] = $hd_type[$v1['hd_type']] ;
                $info[$k]['browse'] = M('hd_access_record')->where(['hd_id'=>"{$v1['hd_id']}",'centre_id'=>$v['centre_id'],'cz_name'=>'进入活动'])->count();//浏览量
                $info[$k]['zrs'] = M('hd_user')->where(['hd_id'=>"{$v1['hd_id']}",'centre_id'=>$v['centre_id']])->count();//活动总人数
                $info[$k]['fxs'] = M('hd_user')->where(['hd_id'=>"{$v1['hd_id']}",'centre_id'=>$v['centre_id']])->sum('share_num');  //分享数
                $info[$k]['zsr'] = M('crm_hd_order')->where(['hd_id'=>"{$v1['hd_id']}",'centre_id'=>$v['centre_id'],'status'=>['neq',0]])->sum('price');
                $t[$k]['browse'] +=  $info[$k]['browse'];
                $t[$k]['zrs']    +=  $info[$k]['zrs'];
                $t[$k]['fxs']    +=  $info[$k]['fxs'];
                $t[$k]['zsr']    +=  $info[$k]['zsr'];
            }
            // dump($browse);die;
        }
        // dump($t);die;
        $this->assign('data',$t);
        $this->sidebar();                        //侧边公共栏
        $this->authid = $this->dao();            //保证左侧导航条是展开状态，默认选中
        $this->display();
    }

    public function hd_count_detail()
    {
        $b = I('get.');                    //获取查询条件
        $this->assign('ar', $b);                //查询条件回传
        $centre_id = $b['centre_id'];
        $where = [
            'b.centre_id' => $centre_id,
            'b.baocun'    => 2,
            'a.fb_type'   => ['neq',1],
            'a.status'    => 1
        ];
        $now_page = I('get.p');
        $m = M('crm_huodong')
            ->alias('a')
            ->join('left join crm_template as b on a.template_id=b.id')
            ->where($where)
            ->field('hd_id,jc_name,hd_type,hd_start_time,hd_end_time')
            ->order("a.hd_start_time desc");
            // ->where($where);
        $p = getpage($m, $where,20);
        $this->page = bootstrap_page_style($p->show());        //把分页页码样式转换后 传入页面
        $this -> assign('nowpage',$now_page);   //分配当前页码
        //获取活动信息
        $info = $m
            ->order("a.hd_start_time desc")
            ->select();
        $hd_type = ['1'=>'砍价活动','2'=>'拼团活动','3'=>'促单(线上)','4'=>'促单(线上+线下)'];
        $browse = 0;   //总浏览量
        $zrs    = 0;   //参与总人数
        $fxs    = 0;   //分享总人数
        $zsr    = 0;    //总收入
        //处理数据
        foreach($info as $k=>$v){
            $info[$k]['hd_type'] = $hd_type[$v['hd_type']] ;
            $info[$k]['browse'] = M('hd_access_record')->where(['hd_id'=>"{$v['hd_id']}",'centre_id'=>$v['centre_id'],'cz_name'=>'进入活动'])->count();//浏览量
            $info[$k]['zrs'] = M('hd_user')->where(['hd_id'=>"{$v['hd_id']}",'centre_id'=>$v['centre_id']])->count();//活动总人数
            $info[$k]['fxs'] = M('hd_user')->where(['hd_id'=>"{$v['hd_id']}",'centre_id'=>$v['centre_id']])->sum('share_num');  //分享数
            $info[$k]['zsr'] = M('crm_hd_order')->where(['hd_id'=>"{$v['hd_id']}",'centre_id'=>$v['centre_id'],'status'=>['neq',0]])->sum('price');
        }
        $this->assign('data',$info);
        $this->sidebar();                        //侧边公共栏
        $this->authid = $this->dao();            //保证左侧导航条是展开状态，默认选中
        $this->display();
    }

}