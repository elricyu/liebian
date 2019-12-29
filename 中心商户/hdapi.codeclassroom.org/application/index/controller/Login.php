<?php
/**
 * Created by PhpStorm.
 * User: HANCY
 * Date: 2018/6/11
 * Time: 14:16
 */

namespace app\index\controller;


use think\Config;
use think\Controller;
use think\Db;
use think\Loader;

class Login extends Controller
{
    //登录
    public function index(){
        header("access-control-allow-origin:*");
        ob_clean();
        //验证是否非法访问
        if(!request()->isPost()){
//            return json(['status'=>10002,'msg'=>'非法访问,请重新登录']);
        }
        $post_data = input('post.');
        //获取登录状态
        $zz=isset($post_data['zz']) ? $post_data['zz'] : '';
        if(empty($zz)){
            $zz=0;
        }
        $data = [
            'zz'=>$zz,
            'cui'=>0
        ];
        $post_data['phone'] = 13333333333;
        $post_data['pad'] = 'gym123456';
        $pwd = md5(md5($post_data['pad']));           //对收到的用户密码进行双层md5加密
        // echo 111;die;
        $user_info=db('xueyuan_baoming')->where(['phone'=>$post_data['phone'],'status'=>1])->find();    //查询用户手机号是否存在

        //判断用户是否存在
        if(!$user_info){
            return json(['status'=>3,'msg'=>'手机号码错误']);         //手机号不存在，返回1，页面提示手机号错误
        }

        //判断密码是否正确
        if ($user_info['pad'] != $pwd ) {
            return json(['status'=>4,'msg'=>'密码错误']);      //密码不正确，返回2，页面提示密码错误
        }

        //生成过期时间
        $expire_time = time()+3600; //1小时未操作后过期
        //生成前端存储token
        $token_data = [
            'name'=>$user_info['username'],
            'user_id'=>$user_info['user_id'],
            'centre_id'=>$user_info['centre_id'],
            'gangwei'=>'中心总监',
            'role'=>2,
            'phone'=>$user_info['phone'],
            'indicate'=>1,
        ];
        $token = set_token($token_data);  //生成token

        //生成登录日志信息
        $log_data = [
            'username'=>$user_info['username'],
            'phone'=>$user_info['phone'],
            'token'=>$token, //登录验证token连接当前登录时间戳
            'centre_id'=>$user_info['centre_id'],
            'user_id'=>$user_info['user_id'],
            'role'=>2,
            'gangwei'=>'中心总监',
            'ip'=>request()->ip(),
            'expire_time'=>$expire_time
        ];
        Db::table('saas_login')->insert($log_data);
        //返回状态 催缴状态信息
        return json(['status'=>1,'msg'=>'登录成功','data'=>$data,'token'=>$token,'gangwei'=>'中心总监']); //登录成功 返回6  页面跳转首页
    }
    //密码重置
    public function chong(){
        header("access-control-allow-origin:*");
        ob_clean();
        //验证是否非法访问
        if(!request()->isPost()){
            return json(['status'=>10002,'msg'=>'非法访问,请重新登录']);
        }
        $post_data = input('post.');
        if(empty($post_data['token'])){  //如果未定义token
            return json(['status'=>10003,'msg'=>'登录状态验证失败,请重新登录']);
        }
        if($post_data['pad'] != $post_data['pad1']){
            return json(['status'=>2,'msg'=>'两次输入密码不一致,请检查重新输入']);     //两次密码不一致
        }
        if(!$post_data['pad']){
            return json(['status'=>3,'msg'=>'密码不能为空']);      //密码不能为空
        }
        $info['pad'] = md5(md5($post_data['pad']));           //对收到的用户密码进行加密
        if($info['pad'] == "a9e9a81003adc4f8ce4d15a027347ff5"){
            return json(['status'=>4,'msg'=>'密码不能与初始密码相同']);      //密码不能和初始密码相同
        }
        $token_data = get_token($post_data['token']);   //解析用户token
        $user_id = $token_data['user_id'];  //获取用户ID
        $res = Db::table('xueyuan_baoming')->where(['user_id'=>$user_id])->update($info); //更新用户密码
        if(false !== $res){
            return json(['status'=>1,'msg'=>'密码修改成功']); //密码修改成功 跳转首页
        }
    }
    //引导页关闭  修改状态 完成引导
    public function mi(){
        header("access-control-allow-origin:*");
        ob_clean();
        //验证是否非法访问
        if(!request()->isPost()){
            return json(['status'=>10002,'msg'=>'非法访问,请重新登录']);
        }
        $token = input('post.token');
        if(empty($token)){  //如果未定义token
            return json(['status'=>10003,'msg'=>'登录状态验证失败,请重新登录']);
        }
        $data['status']=2;
        Db::connect('db_saas_log')->table('saas_login')->where(['token'=>$token])->update($data);    //更新状态完成引导
        //记录日志
        $content = '引导内容阅读完毕';
        $token_data = get_token($token);
        saas_log("saas_update",$token_data,$content);   //执行日志记录函数
        return json(['status'=>1,'msg'=>'引导页阅读完成']); //返回成功提示
    }

    //测试日志记录
    public function test()
    {
        $token = TEST_TOKEN;
        $token_data = get_token($token);   //解析用户token
        saas_log('saas_browse',$token_data,'发萨的防守打法士大夫');
    }
    //获取当前版本号
    public function get_version()
    {
        header("access-control-allow-origin:*");
        ob_clean();
        $version = Config::get('system_version');
        $version = !empty($version) ? $version : '';

        return json(['status'=>1,'msg'=>'版本号获取成功','version'=>$version]);
    }

    public function set_qrcode()
    {
        header("access-control-allow-origin:*");
        //开启缓冲区
        ob_start();
        Loader::import('phpqrcode.phpqrcode');
        // 生成二维码图片
        $object = new \QRcode();
        $time = time();
        $rand = mt_rand(1,100000);
        $time = $time.'A'.$rand;
        $url="https://wx.gymbaby.org/crm_login/index1.php?login_time=".$time;//网址或者是文本内容
        $errorCorrectionLevel = 'L';  //容错级别
        $matrixPointSize = 9;      //生成图片大小
        //生成二维码图片
        $object->png($url,false,$errorCorrectionLevel, $matrixPointSize, 2);
        $imageString = base64_encode(ob_get_contents());
        //关闭缓冲区
        ob_end_clean();
        $imgStrimg = "data:image/png;base64,".$imageString;
        //返回前端二维码数据
        $data=['status'=>1,'img'=>$imgStrimg,'login_time'=>$time];
        return json($data);
    }

    //扫码登录
    public function smdl(){
		header("access-control-allow-origin:*");
        ob_clean();
        $login_time=input('post.time');
        if(empty($login_time)){
            return json(['status'=>2,'msg'=>'未检测到相关验证参数']);
        }

        $m=Db::table('xueyuan_user')
            ->join('xueyuan_baoming','xueyuan_user.phone=xueyuan_baoming.phone','left')
            ->where("xueyuan_user.login_time='$login_time'")
            ->field('xueyuan_baoming.user_id,xueyuan_baoming.username,xueyuan_baoming.centre_id,xueyuan_baoming,xueyuan_baoming.phone')
            ->find();
			// dd(Db::getlastsql());
        if(!empty($m)){
            //生成过期时间
            $expire_time = time()+3600; //1小时未操作后过期
            //生成前端存储token
            $token_data = [
                'name'=>$m['username'],
                'user_id'=>$m['user_id'],
                'centre_id'=>$m['centre_id'],
                'role'=>2,
                'gangwei'=>'中心总监',
                'phone'=>$m['phone'],
                'indicate'=>1
            ];
	
            $token = set_token($token_data);  //生成token


            //返回状态 催缴状态信息
            return json(['status'=>1,'msg'=>'登录成功','token'=>$token,'gangwei'=>'中心总监']); //登录成功 返回6  页面跳转首页
		
        }else{
            $data['status']=2;
            $data['msg']='登录失败';
            return json($data);
        }
    }
    //判断是否重复登录
    public function check_repeat()
    {
        
    }
    // 用户通讯获取用户id
    public function get_id()
    {
        header("access-control-allow-origin:*");
        $token = input('post.token');
        if(empty($token)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $token_data = get_token($token);
        // dump($token_data);die;
        return json(['status'=>1,'msg'=>'请求成功','user_id'=>$token_data['user_id']]);
    }
    //批量刷新当前中心会员会员卡号 有的跳过 没有的分配
    public function make_data()
    {
        header("access-control-allow-origin:*");
        ob_clean();
        $stime =  microtime(true); //开始时间
        ddd('开始时间:'.$stime);
        $pageone  = 2;
        $c_id = input('get.c');  //第几次执行
        if(empty($c_id)){
            echo "输入参数C";
            die;
        }
        // $pyl = ($page-1)*$pageone;
        // $centres = Db::table('wx_user')->where(['status'=>1,'vip'=>1])->group('belong')->column('belong');
		$centres[] = $c_id;
       // sort($centres);//所有中心ID进行排序  从低到高
        $tmp = [];  //记录刷新完成的jl_id
        foreach($centres as $k1 => $v1){
            ddd('已执行:'.$v1);
            $centre_id = $v1;
            //查询当前中心全部会员
            $users = Db::table('wx_user')->where(['vip'=>1,'status'=>1,'belong'=>$centre_id])->select();
            //循环会员 判断会员卡号
            foreach($users as $k => $v){
                //判断是否存在jl_id  不存在直接跳过  是否已经执行过此jl_id  存在跳过
                if(empty($v['jl_id']) || in_array($v['jl_id'],$tmp)){
                    continue;
                }
                //不存在卡号
                $last_card = '';
                $kahao = trim($v['kahao'],' ');
                if(empty($kahao)){
                    //查询当前中心最后一个使用的会员卡号
                    $last_card = Db::table('crm_numbers')->where(['centre_id'=>$centre_id,'type'=>2,'num'=>['neq','']])->order('id desc')->value('num');
                    if(empty($last_card)){
                        $last_card = $centre_id.'00000001';
                    }else{
                        $last_card = $last_card+1;
                    }
                    //卡号已使用  更新卡号表
                    Db::table('crm_numbers')->insert(['num'=>$last_card,'type'=>2,'centre_id'=>$centre_id]);
                    //更新当前会员的会员卡号
                    Db::table('wx_user')->where('user_id',$v['user_id'])->setField('kahao',$last_card);
                    //更新合约记录表会员卡号 关联用户ID
                    Db::table('crm_kjilu')->where('jl_id',$v['jl_id'])->setField('card_num',$last_card);
                    //判断当前合约的user_id 是否存在该用户 存在id话跳过 不存在插入
                    $this -> check_jl_userid($v['jl_id'],$v['user_id']);
                }else{  //存在卡号
                   // continue;
                    $last_card = $v['kahao'];   //记录当前会员的卡号
                }
                //查询是否存在公用合约  同样刷新卡号记录
                $pub_con_users = Db::table('wx_user')->where('jl_id',$v['jl_id'])->select();
                //循环所有共用合约用户  更新会员卡号
                foreach($pub_con_users as $k2 => $v2){
                    Db::table('wx_user')->where('user_id',$v['user_id'])->setField('kahao',$last_card);
                    //判断当前合约的user_id 是否存在该用户 存在id话跳过 不存在插入
                    $this -> check_jl_userid($v['jl_id'],$v2['user_id']);
                }
                //查询过期合约 更新卡号
                $old_cons = Db::table('crm_kjilu')
                    ->where("user_id='{$v['user_id']}' or user_id like '{$v['user_id']},%' or user_id like '%,{$v['user_id']}' or user_id like '%,{$v['user_id']},%'")
                    ->column('jl_id');
                if(!empty($old_cons)){  //如果存在信息 更新卡号
                    Db::table('crm_kjilu')->where('jl_id','in',$old_cons)->setField('card_num',$last_card);
                }
                //插入已执行的JL_ID数组
                $tmp[] = $v['jl_id'];
                //插入日志 已知道的用户信息
                $log = [
                    'user_id'=>$v['user_id'],
                    'jl_id'=>$v['jl_id'],
                    'centre_id'=>$centre_id,
                    'kahao'=>$last_card,
                    'old_cons'=>json_encode($old_cons)
                ];
                Db::table("tmp_vip_log")
                    ->insert($log);
                //清空卡号字段
                $last_card = '';
            }
        }
        $etime=microtime(true);//获取程序执行结束的时间
        $total=$etime-$stime;  //计算差值
        ddd('结束时间:'.$etime);
        ddd("c_id".$c_id."执行结束,执行时间".$total);
    }

    //判断当前合约是否存在此关联用户ID
    public function check_jl_userid($jl_id,$user_id)
    {
        $now_con_user_id = Db::table('crm_kjilu')->where('jl_id',$jl_id)->value('user_id');
        $now_arr_user_id = explode(',',$now_con_user_id);
        if(!in_array($user_id,$now_arr_user_id)){
            $now_arr_user_id[] = $user_id;
            //更新记录表
            $str_user_id = implode(',',$now_arr_user_id);
            $str_user_id = trim($str_user_id,',');
            Db::table('crm_kjilu')->where('jl_id',$jl_id)->setField('user_id',$str_user_id);
        }
    }

}