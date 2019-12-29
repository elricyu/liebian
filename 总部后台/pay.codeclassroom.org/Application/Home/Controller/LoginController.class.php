<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
    	$this->display();
    }
    public function login(){
        $name=I('post.name');
        $pwd=I('post.pwd');
        $m=M('sw_manager')->where("mg_name='$name' and status=1")->find();
    	if(!$m){
    		$this->redirect('Login/index',array('message'=>'用户名密码不正确'));
    	}else{
    		if ($m['mg_pwd']!=$pwd) {
    			$this->redirect('Login/index',array('message'=>'用户名密码不正确'));
    		}else{
    			session('a_name',$name);
    			session('mg_id',$m['mg_id']);
				session('centre_id',$m['centre_id']);
				session('username',$m['mg_u_name']);
                cookie('mg_id',$m['mg_id']);
				$this ->do_centre_state();
    			$this->redirect('Index/index');
    		}
    	}
    }
    
	//模糊查询中心信息
	public function serch(){
		//接收参数
		$serchString=$_POST['serch'];
		//实例化mode
		$mode=M('wx_centre');
		//拼接sql语句
		$sql="SELECT * FROM wx_centre where sequence like '%{$serchString}%' or centre like '%{$serchString}%' or site like '%{$serchString}%'";
		$re=$mode->query($sql);
		if(re){
			echo json_encode($re);
		}
	}
	//导出Excel $aname=文件名 $expCellName=字段对应 $expTableData=数据
	public function exportExcel($expTitle,$expCellName,$expTableData,$aname){
		$xlsTitle = iconv('utf-8', 'gb2312', $expTitle); //文件名称
		$fileName = $aname;//or $xlsTitle 文件名称可根据自己情况设定
		$cellNum = count($expCellName);
		$dataNum = count($expTableData);
		vendor("PHPExcel.PHPExcel");
		$objPHPExcel = new \PHPExcel();
		$cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

		$objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
		 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'        导出时间:'.date('Y-m-d H:i:s'));
		for($i=0;$i<$cellNum;$i++){
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
		}
		// Miscellaneous glyphs, UTF-8
		for($i=0;$i<$dataNum;$i++){
			for($j=0;$j<$cellNum;$j++){
				$objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
			}
		}
		ob_end_clean();
		header('pragma:public');
		// header("Content-type:application/vnd.ms-excel");
		// header("Content-Disposition:attachment;filename={$xlsTitle}.xls");
		header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
		header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$tt = $objWriter->save('php://output');
		return $tt;
	}
	
	//批量脚本刷新枣庄中心外教课耗课数量
    public function do_zzhk()
    {
        $pkids = [141421,136936,136937,141420]; //所有待修正的排课ID
        //循环处理
        foreach($pkids as $k => $v){
            M('crm_kecheng')->where(['pk_id'=>$v])->setField('xiaoke',1);   //更改排课记录耗课数量
            //查询当前排课ID的已结课上课记录
            $skjls = M('crm_user_skjl')->where(['pk_id'=>$v,'status'=>2])->select();
            //循环上课记录 修改耗课数量 修改当前上课会员合约剩余课时
            foreach($skjls as $k1 => $v1){
                //更改当前上课记录耗课数量
                M('crm_user_skjl')->where(['sk_id'=>$v1['sk_id']])->setField('xiaohao',1);
                //给当前上课会员扣课
                $jl_id = M('wx_user')->where(['user_id'=>$v1['user_id']])->getField('jl_id');
                M('crm_kjilu')->where(['jl_id'=>$jl_id])->setDec('y_keshi');
            }
        }
        echo 111111111111;
    }

    //登录执行判断所有中心合约状态
    public function do_centre_state(){
        //实例化数据库
        M()->db(1,'DB_LOG');
        //判断当前脚本上次执行时间 超过4小时在执行
        $last_day = M()->db(1)->table('centre_state_log')->where(['centre'=>'脚本执行记录时间'])->order('id desc')->getField('contract_date');
		
        $last_day OR $last_day = "2010-01-01";  //首次执行定义默认执行时间
	
        $exp = 60*60*4; //4小时的秒数
        $last_time = strtotime($last_day);    //上次执行脚本的日期时间戳
        $diff = time() - $last_time;    //当前时间戳减去上次执行时间戳
        if($diff < $exp){   //执行时间不超过4小时 终止执行
            return false;
        }
        //获取当前所有中心信息数据  新合约状态小于  不读取违约后的状态数据 新合约状态 0未设置 1合约未开店(合约前) 2合约中  3催收期 4延期 5违约
        $centres = M()->db(0)->table('wx_centre')->field('centre_id,centre,contract_date,contract_state')->where("contract_state < 5 and contract_state != 0")->select();
        //获取当前时间
        $now_date = date('Y-m-d');
        //遍历中心信息
        foreach ($centres as $k => $v) {
            //判断是否填写了新合约状态 新合约到期时间
            if (empty($v['contract_state']) || empty($v['contract_date'])) {  //未填写新合约状态 合约到期时间  跳过不执行
                continue;
            } else {
                //根据当前合约状态进行不同内容匹配
                switch ($v['contract_state']) {
                    case 1: //合约未开店 不执行操作
                        break;
                    case 2: //合约中
                        //判断合约过期时间是否差一月到期
                        $prev_month_date = date('Y-m-d',strtotime($v['contract_date']." -1 month"));
                        if ($prev_month_date <= $now_date) {
                            //修改合约状态为催收期
                            M()->db(0)->table('wx_centre') -> where(['centre_id'=>$v['centre_id']])->setField('contract_state',3);  //设置催收期
                            $log_data = [
                                'centre_id'=>$v['centre_id'],
                                'centre'=>$v['centre'],
                                'before_state'=>2,
                                'after_state'=>3,
                                'contract_date'=>$v['contract_date']
                            ];
                            M()->db(1)->table('centre_state_log') -> add($log_data);  //添加日志
                        }

                        break;
                    case 3: //催收期
                        //判断合约过期时间是否已经超过当前时间
                        if($v['contract_date'] <= $now_date){
                            M()->db(0)->table('wx_centre') -> where(['centre_id'=>$v['centre_id']])->setField('contract_state',5);  //设置违约期
                            $log_data = [
                                'centre_id'=>$v['centre_id'],
                                'centre'=>$v['centre'],
                                'before_state'=>3,
                                'after_state'=>5,
                                'contract_date'=>$v['contract_date']
                            ];
                            M()->db(1)->table('centre_state_log') -> add($log_data);  //添加日志
                        }

                        break;
                    case 4: //延期
                        //判断合约过期时间是否已经超过当前时间
                        if($v['contract_date'] <= $now_date){
                            M()->db(0)->table('wx_centre') -> where(['centre_id'=>$v['centre_id']])->setField('contract_state',5);  //设置违约期
                            $log_data = [
                                'centre_id'=>$v['centre_id'],
                                'centre'=>$v['centre'],
                                'before_state'=>4,
                                'after_state'=>5,
                                'contract_date'=>$v['contract_date']
                            ];
                            M()->db(1)->table('centre_state_log') -> add($log_data);  //添加日志
                        }
                        break;
                    default:
                        continue;
                }
            }
        }
        M()->db(1)->table('centre_state_log')->add(['centre'=>'脚本执行记录时间','contract_date'=>date('Y-m-d H:i:s')]);
    }

    // 返回当前用户的id
    public function get_id()
    {
        header("access-control-allow-origin:*");
        $mg_id = session('mg_id');
        // dump($mg_id);die;
        $this->ajaxReturn(['status'=>1,'msg'=>'请求成功','user_id'=>$mg_id]);
    }
}