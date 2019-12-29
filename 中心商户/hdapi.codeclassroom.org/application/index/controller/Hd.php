<?php
/**
 * @Date    2019-03-28 09:23:19
 * @Version ${id}
 * @Description 微营销活动---移动端
 */

namespace app\index\controller;

use think\Controller;
use think\Loader;
use think\Db;
Loader::import('dx.Photo', EXTEND_PATH, '.class.php');

header("access-control-allow-origin:*");

class Hd extends Controller
{
	// 首页
    public function index()
    {
        $hd_id = input('post.hd_id');
        $centre_id = input('post.centre_id');
        if(empty($hd_id)){
            return json(['status'=>2,'msg'=>'hd_id参数验证失败']);
        }
        $hd_info = db('crm_huodong')
            ->where(['hd_id'=>$hd_id])
            ->field('hd_id,jc_name,linkhref,bm_start_time,bm_end_time,template_id,hd_type,hd_site,guize,red_type')
            ->find();
        $mb_info = db('crm_template')
            ->where(['id'=>$hd_info['template_id']])
            ->field('img,text1,left1,top1,underline1,ziti1,zihao1,jianju1,color1,yangshi1,text2,left2,top2,underline2,ziti2,zihao2,jianju2,color2,yangshi2,text3,left3,top3,underline3,ziti3,zihao3,jianju3,color3,yangshi3,type')
            ->find();
        foreach($mb_info as $k => $v){
            $mb_info[$k] = json_decode($v,true);
        }
        $arr = [];
        foreach($mb_info['img'] as $k => $v){
            $arr[$k]['img']        = $v;
            $arr[$k]['text1']      = isset($mb_info['text1'][$k]) ? $mb_info['text1'][$k] : '';
            $arr[$k]['text2']      = isset($mb_info['text2'][$k]) ? $mb_info['text2'][$k] : '';
            $arr[$k]['text3']      = isset($mb_info['text3'][$k]) ? $mb_info['text3'][$k] : '';
            $arr[$k]['left1']      = isset($mb_info['left1'][$k]) ? $mb_info['left1'][$k] : '';
            $arr[$k]['left2']      = isset($mb_info['left2'][$k]) ? $mb_info['left2'][$k] : '';
            $arr[$k]['left3']      = isset($mb_info['left3'][$k]) ? $mb_info['left3'][$k] : '';
            $arr[$k]['top1']       = isset($mb_info['top1'][$k]) ? $mb_info['top1'][$k] : '';
            $arr[$k]['top2']       = isset($mb_info['top2'][$k]) ? $mb_info['top2'][$k] : '';
            $arr[$k]['top3']       = isset($mb_info['top3'][$k]) ? $mb_info['top3'][$k] : '';
            $arr[$k]['underline1'] = isset($mb_info['underline1'][$k]) ? $mb_info['underline1'][$k] : '';
            $arr[$k]['underline2'] = isset($mb_info['underline2'][$k]) ? $mb_info['underline2'][$k] : '';
            $arr[$k]['underline3'] = isset($mb_info['underline3'][$k]) ? $mb_info['underline3'][$k] : '';
            $arr[$k]['ziti1']      = isset($mb_info['ziti1'][$k]) ? $mb_info['ziti1'][$k] : '';
            $arr[$k]['ziti2']      = isset($mb_info['ziti2'][$k]) ? $mb_info['ziti2'][$k] : '';
            $arr[$k]['ziti3']      = isset($mb_info['ziti3'][$k]) ? $mb_info['ziti3'][$k] : '';
            $arr[$k]['zihao1']     = isset($mb_info['zihao1'][$k]) ? $mb_info['zihao1'][$k] : '';
            $arr[$k]['zihao2']     = isset($mb_info['zihao2'][$k]) ? $mb_info['zihao2'][$k] : '';
            $arr[$k]['zihao3']     = isset($mb_info['zihao3'][$k]) ? $mb_info['zihao3'][$k] : '';
            $arr[$k]['jianju1']    = isset($mb_info['jianju1'][$k]) ? $mb_info['jianju1'][$k] : '';
            $arr[$k]['jianju2']    = isset($mb_info['jianju2'][$k]) ? $mb_info['jianju2'][$k] : '';
            $arr[$k]['jianju3']    = isset($mb_info['jianju3'][$k]) ? $mb_info['jianju3'][$k] : '';
            $arr[$k]['color1']     = isset($mb_info['color1'][$k]) ? $mb_info['color1'][$k] : '';
            $arr[$k]['color2']     = isset($mb_info['color2'][$k]) ? $mb_info['color2'][$k] : '';
            $arr[$k]['color3']     = isset($mb_info['color3'][$k]) ? $mb_info['color3'][$k] : '';
            $arr[$k]['yangshi1']   = isset($mb_info['yangshi1'][$k]) ? $mb_info['yangshi1'][$k] : '';
            $arr[$k]['yangshi2']   = isset($mb_info['yangshi2'][$k]) ? $mb_info['yangshi2'][$k] : '';
            $arr[$k]['yangshi3']   = isset($mb_info['yangshi3'][$k]) ? $mb_info['yangshi3'][$k] : '';
        }
        $data['hd_info'] = $hd_info;
        $data['mb_info'] = array_values($arr);
        if(empty($data)){
            return json(['status'=>2,'msg'=>'暂无数据']);
        }else{
            if($mb_info['type'] > 0){
                $centre = db('wx_centre')->where(['centre_id'=>$centre_id])->find();
                $foot['centre'] = $centre['centre'];
                $foot['site'] = $centre['site'];
                $foot['c_phone'] = $centre['c_phone'];
                if($hd_info['hd_type'] == 4){
                    $foot['hd_site'] = $hd_info['hd_site'];
                    return json(['status'=>3,'msg'=>'请求成功','data'=>$data,'foot'=>$foot]);
                }else{
                    return json(['status'=>4,'msg'=>'请求成功','data'=>$data,'foot'=>$foot]);
                }
            }
            return json(['status'=>1,'msg'=>'请求成功','data'=>$data]);
        }
    }

    //  判断是否是免费课
    public function is_free()
    {
    	$hd_id = input('post.hd_id'); // 活动id
    	if(empty($hd_id)) return json(['status'=>2,'msg'=>'活动id不能为空']);
    	$is_free = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('is_free');
        $hd_type = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('hd_type');
    	if($is_free == 1){
    		return json(['status'=>1,'msg'=>'免费课']);
    	}else{
			if($hd_type == 4){
				return json(['status'=>2,'msg'=>'收费课']);
			}else{
				return json(['status'=>1,'msg'=>'收费课']);
			}
    	}
    }

	  // 判断是否报名
    public function is_sign()
    {
        $openid = input('post.openid');
        $hd_id = input('post.hd_id');
        if(empty($openid)) return json(['status'=>2,'msg'=>'openid不能为空']);
        if(empty($hd_id)) return json(['status'=>2,'msg'=>'活动id不能为空']);
        $user_id = db('wx_user')->where(['openid'=>$openid])->value('user_id');
        $yuy_id = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('yuy_id');
        $yuy_id = explode(',',$yuy_id);
		if(empty($user_id)){
			return json(['status'=>1,'msg'=>'报名']);
		}
		// dump($yuy_id);die;
        if(in_array($user_id,$yuy_id)){
            return json(['status'=>2,'msg'=>'不能重复报名']);
        }else{
            return json(['status'=>1,'msg'=>'报名']);
        }
    }

	// 报名
	public function sign()
	{
		$hd_id = input('post.hd_id'); // 活动id
    	if(empty($hd_id)) return json(['status'=>2,'msg'=>'活动id不能为空']);
    	$name = input('post.name'); // 姓名
		$phone = input('post.phone'); // 手机号
		// $code = input('post.code'); // 验证码
		$centre_id = input('post.centre_id');  // 中心id
		if(empty($centre_id)) return json(['status'=>2,'msg'=>'中心id不能为空']);
		$openid = input('post.openid');  // openid
		// 根据手机号查询用户信息
		$where= [
            'phone1|phone2|phone3|phone4|phone5|phone6'=>$phone
        ];
    	$is_free = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('is_free');
		// 获取活动名称，用于报名成功后的提示
		$hd_name = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('jc_name');
    	if($is_free == 1){  // 免费课
    		// 没有输入信息就根据openid查询信息
    		if(empty($phone)){
	    		// 查询出当前用户信息
	    		$user_id = db('wx_user')->where(['openid'=>$openid,'status'=>1,'yon'=>2])->order('user_id')->value(['user_id']);
                $vip = db('wx_user')->where(['user_id'=>$user_id])->value('vip');
                if($vip == 1){
                    $is_qianke = 3;
                }else{
                    $is_qianke = 2;
                }
                db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->setField('is_qianke',$is_qianke);
    			if(empty($user_id)) return json(['status'=>3,'msg'=>'填写信息']);
    		}else{
    			if(empty($name)) return json(['status'=>2,'msg'=>'姓名不能为空']);
				if(empty($phone)) return json(['status'=>2,'msg'=>'手机号不能为空']);
				// if(empty($code)) return json(['status'=>2,'msg'=>'验证码不能为空']);
				// $yzm = db('yanzhengma')->where(['phone'=>$phone])->order('id desc')->value('yzm');
				// if($code != $yzm) return json(['status'=>2,'msg'=>'验证码不正确']);
				if(empty($centre_id)) return json(['status'=>2,'msg'=>'请选择中心']);
    			// 输入信息就根据手机号查询
                $user_id = db('wx_user')->where($where)->order('user_id')->value('user_id');

    			if(empty($user_id)){
    				// 如果没有当前用户就添加
    				$data1['baobao_name'] = $name;
		    		$data1['phone2'] = $phone;
			        $data1['belong'] = $centre_id;
			        $data1['vip'] = 0;
			        $data1['yon'] = 2;
					$data1['openid'] = $openid;
			        $laiyuan = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('jc_name');
			        $data1['laiyuan'] = $laiyuan;
			        $data1['hd_id'] = $hd_id;
			       	$user_id = db('wx_user')->insertGetId($data1);
                    db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->setField('is_qianke',2);
                    // unset($data);
    			}
    		}
			db('wx_user')->where(['user_id'=>$user_id])->setField('openid',$openid);
			$user_info = db('wx_user')->where(['user_id'=>$user_id])->find();
            $name = !empty($user_info['baobao_name']) ? $user_info['baobao_name'] : ' ';
            $phone = '';
            for($i=1;$i<=6;$i++){
                if(!empty($phone)){
                    continue;
                }
                $phone = $user_info['phone'.$i];
            }
            $yuy_id = explode(',',db('crm_huodong')->where(['hd_id'=>$hd_id])->value('yuy_id'));
            if(in_array($user_id,$yuy_id) && !empty($user_id)) return json(['status'=>2,'msg'=>'不能重复报名']);
            // 判断是否已记录过报名
            $ar = db('crm_hd_bm')->where(['user_id'=>$user_id,'hd_id'=>$hd_id,'centre_id'=>$centre_id])->find();
            if(!empty($ar)) return json(['status'=>2,'msg'=>'您取消报名，请到中心预约']);
            // 判断是否消耗课时
            $is_xiaohao = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('xiaohao');
             // 查询该活动是否有课时包
            $ke_pack = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('s_id');
            
            if($is_xiaohao > 0 || !empty($ke_pack)){
                $y_keshi = db('wx_user')->alias('a')
                    ->join('crm_kjilu b','a.kahao = b.card_num','left')
                    ->where(['a.user_id'=>$user_id])
                    ->value('b.y_keshi');

                if($y_keshi < $is_xiaohao || !empty($ke_pack)){  // 剩余课程小于消耗课时
                    // 生成消费码
                    $spend_code = $centre_id . $hd_id . $user_id . mt_rand(0,99);
                    $data['spend_code'] = $spend_code;
                    $data['user_id'] = $user_id;
                    $data['centre_id'] = $centre_id;
                    $data['price'] = 0;
                    $data['status'] = 1;
                    $data['hd_id'] = $hd_id;
                    $data['openid'] = $openid;
                    $data['s_id'] = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('s_id');
                    db('crm_hd_order')->insert($data);
                     // echo 1111;die;
                    $yuy_id = trim(implode(',',$yuy_id) . ",{$user_id}",',');
                    $res = db('crm_huodong')->where(['hd_id'=>$hd_id])->setField('yuy_id',$yuy_id);
                    $this->back_sing($openid,$hd_id,$phone,$name);
                    $this->bm_log($user_id,$hd_id,$centre_id);  // 记录报名
                    return json(['status'=>6,'msg'=>'报名成功','hd_name'=>$hd_name,'code'=>$spend_code]);
                }
            }

    		$yuy_id = trim(implode(',',$yuy_id) . ",{$user_id}",',');
    		$res = db('crm_huodong')->where(['hd_id'=>$hd_id])->setField('yuy_id',$yuy_id);
            $vip = db('wx_user')->where(['user_id'=>$user_id])->value('vip');
            if($vip == 1){
                $is_qianke = 3;
            }else{
                $is_qianke = 2;
            }
            db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->setField('is_qianke',$is_qianke);
    		$this->back_sing($openid,$hd_id,$phone,$name);
    		$this->bm_log($user_id,$hd_id,$centre_id);  // 记录报名
    		return $res !== false ? json(['status'=>1,'msg'=>'报名成功','hd_name'=>$hd_name]) : json(['status'=>2,'msg'=>'报名失败']);
    	}else{  // 收费课
    		// 判断当前是否已经报名
    		$yy_id = explode(',',db('crm_huodong')->where(['hd_id'=>$hd_id])->value('yuy_id'));
    		$u_id = db('wx_user')->where(['openid'=>$openid])->order('user_id')->value('user_id');
            $hd_type = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('hd_type'); // 活动类型。判断是否是线上收费课程
    		if(in_array($u_id,$yy_id) && !empty($u_id)) return json(['status'=>'2','msg'=>'不能重复报名']);
			// 判断是否点击的是立即购买
    		$is_type = input('post.is_type') ? input('post.is_type') : 0;
    		if($is_type == 1){
    			$user_id = db('wx_user')->where(['openid'=>$openid])->order('user_id')->value('user_id');
    			if(empty($user_id)){
//    				if(empty($name) || empty($phone) || empty($code)){
    				if(empty($name) || empty($phone)){
                        return json(['status'=>3,'msg'=>'填写信息']);
                    }
//                    $yzm = db('yanzhengma')->where(['phone'=>$phone])->order('id desc')->value('yzm');
//                    if($code != $yzm) return json(['status'=>2,'msg'=>'验证码不正确']);
                    if(empty($centre_id)) return json(['status'=>2,'msg'=>'请选择中心']);
                    // 如果没有当前用户就添加
                    $data['baobao_name'] = $name;
                    $data['phone2'] = $phone;
                    $data['belong'] = $centre_id;
                    $data['vip'] = 0;
                    $data['yon'] = 2;
                    $data['openid'] = $openid;
                    $laiyuan = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('jc_name');
                    $data['laiyuan'] = $laiyuan;
                    $data['hd_id'] = $hd_id;
                    $user_id = db('wx_user')->insertGetId($data);
                    db('wx_user')->where(['user_id'=>$user_id])->setField('openid',$openid);
                    $vip = db('wx_user')->where(['user_id'=>$user_id])->value('vip');
                    if($vip == 1){
                        $is_qianke = 3;
                    }else{
                        $is_qianke = 2;
                    }
                    db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->setField('is_qianke',$is_qianke);
                    return json(['status'=>5,'msg'=>'购买课时','user_id'=>$user_id]);
    			}else{
                    $vip = db('wx_user')->where(['user_id'=>$user_id])->value('vip');
                    if($vip == 1){
                        $is_qianke = 3;
                    }else{
                        $is_qianke = 2;
                    }
                    db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->setField('is_qianke',$is_qianke);
    				return json(['status'=>5,'msg'=>'购买课时','user_id'=>$user_id]);
    			}
    		}
    		// 判断是否是会员
    		if(empty($phone)){
    			// 查询当前会员课时是否足够
    			$user_id = db('wx_user')->where(['openid'=>$openid])->order('user_id')->value('user_id');
    			if(empty($user_id)) return json(['status'=>3,'msg'=>'填写信息']);
                $vip = db('wx_user')->where(['user_id'=>$user_id])->value('vip');
                if($vip == 1){
                    $is_qianke = 3;
                }else{
                    $is_qianke = 2;
                }
                db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->setField('is_qianke',$is_qianke);
                // 判断是否是线上收费课
				// dump($hd_type);die;
                if($hd_type !== 4){
                    return json(['status'=>5,'msg'=>'购买课时','user_id'=>$user_id]);
                }
    			$ar = db('crm_hd_bm')->where(['user_id'=>$user_id,'hd_id'=>$hd_id,'centre_id'=>$centre_id])->find();
    			if(!empty($ar)) return json(['status'=>2,'msg'=>'您取消报名，请到中心预约']);
    			// 查询当前用户信息
				$xiaohao = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('xiaohao');
        		$y_keshi = db('wx_user')->alias('a')
        			->join('crm_kjilu b','a.kahao = b.card_num','left')
        			->where(['a.user_id'=>$user_id,'b.status'=>1,'item_id'=>1])
        			->value('y_keshi');
        		if($y_keshi < $xiaohao) return json(['status'=>4,'msg'=>'课时不足','user_id'=>$user_id]);
        		$yuy_id = explode(',',db('crm_huodong')->where(['hd_id'=>$hd_id])->value('yuy_id'));
        		if(in_array($user_id,$yuy_id)) return json(['status'=>2,'msg'=>'不能重复报名']);
        		$yuy_id = trim(implode(',',$yuy_id).",{$user_id}" , ',');
        		$res = db('crm_huodong')->where(['hd_id'=>$hd_id])->setField('yuy_id',$yuy_id);
        		// 更新hd_user表数据
        		$user_info = db('wx_user')->where(['user_id'=>$user_id])->find();
				$name = !empty($user_info['baobao_name']) ? $user_info['baobao_name'] : ' ';
				$phone = '';
				for($i=1;$i<=6;$i++){
					if(!empty($phone)){
						continue;
					}
					$phone = $user_info['phone'.$i];
				}
				$this->back_sing($openid,$hd_id,$phone,$name);
				$this->bm_log($user_id,$hd_id,$centre_id);
        		return $res !== false ? json(['status'=>1,'msg'=>'报名成功','hd_name'=>$hd_name]) : json(['status'=>2,'msg'=>'报名失败']);
    		}else{
    			if(empty($name)) return json(['status'=>2,'msg'=>'姓名不能为空']);
				if(empty($phone)) return json(['status'=>2,'msg'=>'手机号不能为空']);
//				if(empty($code)) return json(['status'=>2,'msg'=>'验证码不能为空']);
//				$yzm = db('yanzhengma')->where(['phone'=>$phone])->order('id desc')->value('yzm');
//				if($code != $yzm) return json(['status'=>2,'msg'=>'验证码不正确']);
				if(empty($centre_id)) return json(['status'=>2,'msg'=>'请选择中心']);
				// 输入信息就根据手机号查询
    			$user_id = db('wx_user')->where($where)->value('user_id');
    			$yuy_id = explode(',',db('crm_huodong')->where(['hd_id'=>$hd_id])->value('yuy_id'));
    			if(in_array($user_id,$yuy_id) && !empty($user_id)) return json(['status'=>2,'msg'=>'不能重复报名']);
    			$ar = db('crm_hd_bm')->where(['user_id'=>$user_id,'hd_id'=>$hd_id,'centre_id'=>$centre_id])->find();
    			if(!empty($ar)) return json(['status'=>2,'msg'=>'您取消报名，请到中心预约']);
    			if(empty($user_id)){
    				// 如果没有当前用户就添加
    				$data['baobao_name'] = $name;
		    		$data['phone2'] = $phone;
			        $data['belong'] = $centre_id;
			        $data['vip'] = 0;
			        $data['yon'] = 2;
                    $data['openid'] = $openid;
			        $laiyuan = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('jc_name');
			        $data['laiyuan'] = $laiyuan;
			        $data['hd_id'] = $hd_id;
			       	$user_id = db('wx_user')->insertGetId($data);
			       	db('wx_user')->where(['user_id'=>$user_id])->setField('openid',$openid);
                    $vip = db('wx_user')->where(['user_id'=>$user_id])->value('vip');
                    if($vip == 1){
                        $is_qianke = 3;
                    }else{
                        $is_qianke = 2;
                    }
                    db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->setField('is_qianke',$is_qianke);
			       	return json(['status'=>5,'msg'=>'购买课时','user_id'=>$user_id]);
    			}else{
    				db('wx_user')->where(['user_id'=>$user_id])->setField('openid',$openid);
                    $vip = db('wx_user')->where(['user_id'=>$user_id])->value('vip');
                    if($vip == 1){
                        $is_qianke = 3;
                    }else{
                        $is_qianke = 2;
                    }
                    db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->setField('is_qianke',$is_qianke);
                    // 判断是否是线上收费课
                    if($hd_type !== 4){
                        return json(['status'=>5,'msg'=>'购买课时','user_id'=>$user_id]);
                    }
    			}
    			$xiaohao = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('xiaohao');
        		$y_keshi = db('wx_user')->alias('a')
        			->join('crm_kjilu b','a.kahao = b.card_num','left')
        			->where(['a.user_id'=>$user_id,'b.status'=>1,'item_id'=>1])
        			->value('y_keshi');
        		if($y_keshi < $xiaohao) return json(['status'=>4,'msg'=>'课时不足','user_id'=>$user_id]);
    		}
    	}
	}

	// 记录报名成功的记录表
	public function bm_log($user_id,$hd_id,$centre_id)
	{
		$data['user_id'] = $user_id;
		$data['hd_id'] = $hd_id;
		$data['centre_id'] = $centre_id;
		db('crm_hd_bm')->insert($data);
	}

	// 报名成功后更改hd_user数据 
	public function back_sing($openid,$hd_id,$phone,$baobao_name)
	{
		$da['is_active'] = 2;
		$da['is_baoming'] = 2;
        $da['phone'] = $phone;
        $da['real_name'] = $baobao_name;
        db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->update($da);
		// dump($ar);die;
	}

	// 支付接口
	public function pay()
	{
		$hd_id = input('post.hd_id');
        if(empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
		$data = db('crm_huodong')->alias('a')
			->join('crm_goods b','a.s_id = b.s_id','left')
			->where(['a.hd_id'=>$hd_id])
			->field('a.hd_id,b.s_name,b.k_shu,a.s_price as price')
			->find();
		return empty($data) ? json(['status'=>2,'msg'=>'未检测到数据']) : json(['status'=>1,'msg'=>'检索成功','data'=>$data]);
	}

	// 支付成功后查询的接口
	public function back_pay()
	{
		$order_id = input('post.order_id');
        if(empty($order_id)){
            return json(['status'=>2,'msg'=>'订单id不能为空']);
        }
		$data = db('crm_hd_order')->alias('a')
			->join('crm_goods b','a.s_id = b.s_id','left')
			->where(['a.order_id'=>$order_id,'a.status'=>1])
			->field('a.spend_code,b.s_name,b.k_shu,a.price')
			->find();
		return empty($data) ? json(['status'=>2,'msg'=>'未检测到数据']) : json(['status'=>1,'msg'=>'检索成功','data'=>$data]);
	}

	//生成分享二维码
    public function erwm()
    {
        // $img = 'https://hdapi.codeclassroom.org/uploads/hd/114_oxmpS0yFfB527YGjg0yvPjLScbLE.jpg';
         return json(['status'=>1,'msg'=>'请求成功','img'=>'']);
        Loader::import('phpqrcode.phpqrcode');
        // 生成二维码图片
        $object = new \QRcode();
        // $url = input('post.url');//网址或者是文本内容
        // $url = htmlspecialchars_decode($url);
        $openid = input('post.openid');
        $hd_id = input('post.hd_id');
        if(empty($openid) || empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        //根据活动ID获取中心ID
        $centre_id = Db::table('crm_huodong')->where('hd_id',$hd_id)->value('centre_id');
        //组装URL
        $url="https://wx.codeclassroom.org/activity/index.php?parent_openid=".$openid."&centre_id=".$centre_id."&hdid=".$hd_id;//网址或者是文本内容
        $level = 1;
        $size = 2;
        $filename = "uploads/Erwm/{$hd_id}_{$openid}.png"; //图片输出路径和文件名
        $errorCorrectionLevel = intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        $ar = $object->png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        $arr['erwm'] = "https://hdapi.codeclassroom.org/".$filename;
        // $arr['erwm'] = "http://www.saas.com/".$filename;
        // dump($arr);die;
        $json_img = Db::table('crm_huodong')
            ->alias('a')
            ->join('crm_template b','a.template_id=b.id','left')
            ->where('a.hd_id',$hd_id)
            ->value('b.img');
        $imgs = json_decode($json_img,true);
        $img = empty($imgs) ? '' :current($imgs);
        $img OR $img = "./fenxiangt.png";
        if(strpos($img,'yundongbaobei.cn')){
            $res = $this -> downloadfile($img);
            if($res['status']==1){
                $img = str_replace('https://hdapi.codeclassroom.org','.',$res['url']);
            }else{
                $img = './fenxiangt.png"';
            }
        }else{
            $img = str_replace('https://hdapi.codeclassroom.org','.',$img);
        }

        // $img = str_replace('http://www.s4.com','.',$img);
        $arr['img'] = $img;
        // dump($arr);die;
        $image = \think\Image::open($arr['img']);
        // dump($image);die;
        // 生成缩略图
        $location = array(250,418);
        // $ar = $image->open("./uploads/hd/img_$hd_id.png");
        $image->open($arr['img'])->thumb(375,528)->water("./uploads/Erwm/{$hd_id}_{$openid}.png",$location,100)->save("./uploads/hd/{$hd_id}_{$openid}.jpg");
        $img = "https://hdapi.codeclassroom.org/uploads/hd/".$hd_id .'_'.$openid.'.jpg';
        return json(['status'=>1,'msg'=>'请求成功','img'=>$img]);
    }

    //将总部活动的图片下载到本地
    public function downloadfile($url) 
    {

        // $url = "http://116.62.14.6:808/CallRecord/18577891293_0106744502_f155594b-f204-4e12-8866-ac778039045b.wav";
        if ($url=='') return ['status'=>2,'msg'=>'地址为空']; //判断是否存在url
        $filename = strrchr($url,'/');  //根据URL地址匹配文件名

        $fp = fopen($url, 'r');  //打开URL对象
        if(!$fp) return ['status'=>2,'msg'=>'打开url文件对象失败!'];
        $date = date('Ymd');    //当天日期
        $path = "uploads/hd/";  //组装存储路径

        $file_path = $path.$filename;   //完整文件路径名称
        $myfile = fopen($file_path, "w");   //打开存储文件对象
        if(!$myfile) return ['status'=>2,'msg'=>'打开存储文件对象失败!'];

        while(!feof($fp)) { //循环读取url对象
            fwrite($myfile, fgets($fp).""); //逐步写入存储文件对象
        }
        fclose($fp);    //关闭url对象资源
        fclose($myfile);    //关闭存储文件对象资源

        $domain = request()->domain();  //获取当前访问域名
        $fileurl = $domain.'/'.$file_path;  //组装完整网络路径
        return ['status'=>1,'msg'=>'文件下载成功','url'=>$fileurl];   //返回完整访问路径
    }

	//判断是否激活会员卡
    public function hyk()
    {
        $openid = input('post.openid');
        $hd_id = input('post.hd_id');
        if(empty($openid) || empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $arr = db('hd_user')
        	->where("open_id='$openid' and hd_id='$hd_id'")
        	->field('vip_card,is_active,sheng,shi,real_name,head_image,effect,influence,phone,birthday,address')
        	->find();
        // dump($arr);die;
        if(empty($arr)){
        	return json(['status'=>2,'msg'=>'会员卡没有激活']);
        }
        $arr['money'] = db('hd_red_record')->where("openid='$openid' and hd_id='$hd_id'")->value('money');
        if(empty($arr['money'])){
            $arr['money'] = 0;
        }
        $sheng = db('region')->where("REGION_ID='{$arr['sheng']}'")->value('REGION_NAME');
        $shi = db('region')->where("REGION_ID='{$arr['shi']}'")->value('REGION_NAME');
        $arr['shengshi'] = $sheng.' '.$shi;
        $arr['wxss'] = $sheng.'/'.$shi;
        return json(['status'=>1,'data'=>$arr]);
    }

    //激活会员卡
    public function jh()
    {
    	$phone = input('post.phone');  // 手机号
    	$birthday = input('post.birthday'); // 出生日期
    	$openid = input('post.openid');  // openid
    	$hd_id = input('post.hd_id');  // 活动id
//    	$code = input('post.code');	// 验证码
        if (empty($phone)) return json(['status'=>2,'msg'=>'手机号不能为空']);
        if (empty($birthday)) return json(['status'=>2,'msg'=>'出生日期不能为空']);
        if (empty($openid)) return json(['status'=>2,'msg'=>'openid不能为空']);
//        if (empty($code)) return json(['status'=>2,'msg'=>'验证码不能为空']);
//    	$yzm = db('yanzhengma')->where("phone='{$phone}'")->order('id desc')->find();
        // dump($yzm);die;
//    	if($code == $yzm['yzm']){
//
//    	}else{
//            return json(['status'=>2,'msg'=>'验证码失败']);
//        }
        $data['birthday'] = $birthday;
        $data['phone'] = $phone;
        $data['is_active']=2;
        $data['is_qianke'] = 2;
        $hd_id = $hd_id;
        $ae = db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->update($data);
        //个人信息修改成功 判断是否为潜客 如果不是潜客 插入潜客信息
        if(false !== $ae){
            $where= [
                'phone1|phone2|phone3|phone4|phone5|phone6'=>$data['phone']
            ];
            $wx_userid = db('wx_user')->where($where)->value('user_id');
            //如果潜客表不存在当前用户 插入潜客表
            if(!$wx_userid){
                $wxdata['baobao_name'] = db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->value('user_name');   //姓名获取昵称
                $wxdata['phone2'] = $data['phone'];
                //获取活动模版信息
                $mb_info = db('crm_huodong')->field('centre_id,jc_name')->where(['hd_id'=>$hd_id])->find();
                $wxdata['belong'] = $mb_info['centre_id'];
                $wxdata['vip'] = 0;
                $wxdata['yon'] = 2;
                $wxdata['laiyuan'] = $mb_info['jc_name'];
                $wxdata['hd_id'] = $hd_id;
                $res = db('wx_user')->insert($wxdata);
                if($res){
                    //添加成功开始给当前中心扣款
                    $money_info = db('a_money')->field('discount,remaining')->where(['centre_id'=>$mb_info['centre_id']])->find();   //获取当前中心信息
                    $discount = $money_info['discount'];    //获取当前中心折扣率
                    if($discount){  //如果折扣率不等于0 即有折扣
                        $cons_money = 1*($discount/10); //根据折扣率计算
                    }else{
                        $cons_money = 1;    //扣款一元
                    }
                    //执行扣款 减少充值余额
                    $remaining = $money_info['remaining']-$cons_money;
                    $money_res = db('a_money')->where(['centre_id'=>$mb_info['centre_id']])->update(['remaining'=>$remaining]);
                    //扣款成功 添加扣款记录
                    if(false !== $money_res){
                        $loginfo['money'] = $cons_money;
                        $loginfo['centre_id'] = $mb_info['centre_id'];
                        $loginfo['user_openid'] = $openid;
                        $loginfo['hd_id'] = $hd_id;
                        $loginfo['notes'] = "激活会员卡获取个人信息扣款";
                        M('a_consume_record')->insert($loginfo);
                    }
                }
            }
            return json(['status'=>1,'msg'=>'激活成功']);
        }
    }

    //修改会员信息
    public function xghy()
    {
    	$birthday = input('post.birthday');
    	$openid = input('post.openid');
//    	$code = input('post.code');
        if (empty($birthday)) return json(['status'=>'2','msg'=>"参数错误birthday"]);
        if (empty($openid)) return json(['status'=>'2','msg'=>"参数错误openid"]);
//        if(empty($code)) return json(['status'=>1,'msg'=>'验证码不能为空']);
        $hd_id = input('post.hd_id');
        $real_name = input('real_name');
        $phone = input('post.phone');
        $data['birthday']=$birthday;
        $data['real_name']=$real_name;
        $data['is_qianke'] = 2; //填写个人信息 默认变为潜客
		$data['is_active']=2;
        $data['phone'] = $phone;
//        $yzm = db('yanzhengma')->where(['phone'=>$phone])->order('id desc')->value('yzm');
        //判断验证码是否正确
//        if($code == $yzm){
//
//        }else{
//        	return json(['status'=>2,'msg'=>'验证码错误']);
//        }
        //验证通过 修改活动用户表用户信息
        $ae = db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->update($data);
        //个人信息修改成功 判断是否为潜客 如果不是潜客 插入潜客信息
        if(false !== $ae){
            $where= [
                'phone1|phone2|phone3|phone4|phone5|phone6'=>$data['phone']
            ];
            $wx_userid = db('wx_user')->where($where)->value('user_id');
            //如果潜客表不存在当前用户 插入潜客表
            if(!$wx_userid){
                $wxdata['baobao_name'] = $data['real_name'];
                $wxdata['phone2'] = $data['phone'];
                //获取活动模版信息
                $mb_info = db('crm_huodong')->field('jc_name,centre_id')->where(['hd_id'=>$hd_id])->find();
                $wxdata['belong'] = $mb_info['centre_id'];
                $wxdata['vip'] = 0;
                $wxdata['yon'] = 2;
                $wxdata['laiyuan'] = $mb_info['jc_name'];
                $wxdata['hd_id'] = $hd_id;
                $res = db('wx_user')->insert($wxdata);
                if($res){
                    //添加成功开始给当前中心扣款
                    $money_info = db('a_money')
                        ->field('discount,remaining')
                        ->where(['centre_id'=>$mb_info['centre_id']])
                        ->find();   //获取当前中心信息
                    $discount = $money_info['discount'];    //获取当前中心折扣率
                    if($discount){  //如果折扣率不等于0 即有折扣
                        $cons_money = 1*($discount/10); //根据折扣率计算
                    }else{
                        $cons_money = 1;    //扣款一元
                    }
                    //执行扣款 减少充值余额
                    $remaining = $money_info['remaining']-$cons_money;
                    $money_res = db('a_money')->where(['centre_id'=>$mb_info['centre_id']])->update(['remaining'=>$remaining]);
                    //扣款成功 添加扣款记录
                    if(false !== $money_res){
                        $loginfo['money'] = $cons_money;
                        $loginfo['centre_id'] = $mb_info['centre_id'];
                        $loginfo['user_openid'] = $openid;
                        $loginfo['hd_id'] = $hd_id;
                        $loginfo['notes'] = "填写个人信息扣款";
                        db('a_consume_record')->insert($loginfo);
                    }
                }
            }
            return json(['status'=>1,'msg'=>'修改成功']);
        }
    }

    //积分商城商品预览接口（积分规则添加页面）
    /*
    public function show_goods()
    {
        $centre_id = input('post.centre_id');  //更换为前台提交ID
        $openid = input('post.openid');
        $hd_id = input('post.hd_id');
        if (empty($openid)){
            return json(['status'=>'2','msg'=>"用户openid错误"]);
        }
        $where = [
            'a.centre_id'=>$centre_id,    //中心ID
            'a.is_use'=>1,    //已启用上架
            'a.status'=>1, //未删除
            'a.hd_id'=>$hd_id   //当前活动ID
        ];
        //获取商品信息
        $m = db('crm_rule as a');
        $res = $m->field('a.*,b.good_num,b.good_name,b.imgpath,b.mini_imgpath')
	        ->join('jxc_goods b','a.gid = b.id','left')
	        ->where($where)
	        ->select();
        //获取用户积分信息
        $user_info = db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->find();
        $use_integral = $user_info['use_integral'] ? $user_info['use_integral'] : 0; //获取用户已用积分
        $now_integral =  $user_info['integral'] ? $user_info['integral'] : 0;    //获取用户剩余积分
        $integral = ['yyjf'=>$use_integral,'kyjf'=>$now_integral,'ljjf'=>$use_integral+$now_integral];  //已用积分 可用积分 累计积分
        $info = ['jf'=>$integral,'info'=>$res];
        if (!$res){
            return json(['status'=>2,'msg'=>'暂无数据','data'=>$info]);
        }
        return json(['status'=>1,'msg'=>'检索成功','data'=>$info]);
    }

    //商品详情接口
    public function deta()
    {
        $id = input('post.id');
        $openid = input('post.openid');
        $centre_id = input('post.centre_id');
        $hd_id = input('post.hd_id');
        if(empty($openid)){
            return json(['status'=>2,'msg'=>'openid不能为空']);
        }
        $userinfo = db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->find(); //获取用户积分
        $arr = db('crm_rule')->alias('a')
            ->field('a.id,b.imgpath,b.good_name,a.notes,c.number,a.rules')
            ->join('jxc_goods b','a.gid = b.id','left')
            ->join('jxc_stock c','a.gid = c.good_id','left')
            ->where(['a.id'=>$id,'c.centre_id'=>$centre_id])
            ->find();
        $rules = $arr['rules']; //获取当前商品兑换所需积分
        $jf = $userinfo['integral'];    //获取当前用户积分余额
        //判断当前用户是否为会员(已报名用户)
        if($userinfo['is_qianke'] == 2){    //已报名为潜客
            //获取当前活动折扣率
            $discount = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('discount');
            //判断折扣率 并计算新的商品价值积分
            if($discount != 0){ //为0 不折扣
                $rules = $rules * ($discount/10);
            }
        }
        if($rules <= $jf){
            $arr['jf'] = 1;//积分充足
        }else{
            $arr['jf'] = 2;//积分不足
        }
        if($arr['number'] < 0){
        	$arr['number'] = 0;
        }
        return json(['status'=>1,'msg'=>'请求成功','data'=>$arr]);
        // $this -> ajaxReturn(['status'=>'10001','result'=>$arr]);
    }
    */
    /*
    // 积分商品兑换
    public function deal()
    {
        $ruleid = input('post.id');
        $centre_id = input('post.centre_id');
        $openid = input('post.openid');
        $hd_id= input('post.hd_id');
        if (empty($ruleid)){
            return json(['status'=>2,'msg'=>'商品id错误']);
        }
        if (empty($openid)){
            return json(['status'=>2,'msg'=>'openid参数错误']);
        }

        $order_num = $this->getNum($centre_id,'商品');
        // 单一商品逻辑
        $rule_sum = db('crm_rule')->where("id=".$ruleid)->value('rules');  //兑换商品所需积分
        if ($rule_sum){
            //查询当前用户信息
            $userinfo = db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->find();
            $user_integral = $userinfo['integral'];    //获取当前用户积分余额
            $user_use_integral = $userinfo['use_integral'];    //获取用户已用积分
            //判断当前用户是否为会员(已报名用户)
            if($userinfo['is_qianke'] == 2){    //已报名为潜客
                //获取当前活动折扣率
                $discount = db('a_mb')->where(['id'=>$hd_id])->value('discount');
                //判断折扣率 并计算新的商品价值积分
                if($discount != 0){ //为0 不折扣
                    $rule_sum = $rule_sum * ($discount/10);
                }
            }
            // 查询成功判断积分是否充足
            $now_integral = $user_integral - $rule_sum;
            if ($now_integral >= 0){
                //扣除积分余额，增加已用积分,生成兑换订单凭证存表
                $a_data['integral'] = $now_integral;
                $a_data['use_integral'] = $user_use_integral+$rule_sum;
                $a_res = db('hd_user')->where(['open_id'=>$openid])->update($a_data);
                if (false !== $a_res){
                    // 积分扣除成功生成订单号
                    $datas =[];
					$datas['consumption'] = $rule_sum;  //花费积分
					$datas['order_num']   = $order_num;   //订单号
					$datas['centre_id']   = $centre_id;   //中心ID
					$datas['ruleid']      = $ruleid; //商品规则ID
					$datas['open_id']     = $openid;    //用户openid
					$datas['hd_id']       = $hd_id;   //活动ID
					$datas['is_rec']      = 1;   //默认未领取
                    $re =  db('a_order')->insert($datas);
                    if (false !== $re){
                        return json(['status'=>1,'msg'=>'请求成功','data'=>$order_num]);
                    }else{
                        return json(['status'=>2,'msg'=>'兑换凭证生成失败']);
                    }
                }else{
                    return json(['status'=>2,'msg'=>'积分扣除失败']);
                }
            }else{
                return json(['status'=>2,'msg'=>'您的积分余额不足']);
            }

        }else{
            return json(['status'=>2,'msg'=>'查询失败']);
        }
    }
    */
   
    // 订单号生成
    public function getNum($centre_id,$type)
    {
        $date = date('Ymd');
        $where = [
            'centre_id'=>$centre_id,
            'create_time'=>[
                'gt',$date
            ]
        ];
        // 判断类别 设置编号开头并且获取当前数据库当天使用的最后一个流水号
        $lastNum = '';
        $first = '';
        if($type == "商品"){
            $first = 'DH';
            $lastNum = db('a_order')->where($where)->order('id desc')->find()['order_num'];
        }
        if($type == "优惠券"){
            $first = 'YH';
            $lastNum = db('a_coupon_details')->where($where)->order('id desc')->find()['coupon_num'];
        }

        //如果存在  截取后四位 并且加1  如果不存在生成当天第一条
        if ($lastNum){
            $last = substr($lastNum,-4);    //获取最后4位
            $a = substr($lastNum,0,-4);     //获取前面N位
            $var = sprintf("%04d", $last+1);
            $num = $a.$var;
            return $num;
        }else{
            $date = substr($date,2);
            $num = $first.$date.$centre_id.'0001';
            return $num;
        }
    }

    // 已兑换商品查看
    /*
    public function get_order_goods()
    {
        $openid = input('post.openid'); //获取当前用户的openid
        if(empty($openid)){
            return json(['status'=>2,'msg'=>'参数错误。']);
        }
        $hd_id = input('post.hd_id');
        //组装where条件
        $where = [
            'open_id'=>$openid,
            'hd_id'=>$hd_id
        ];
        $Order = db('a_order');  //实例化

        $order_infos = $Order->where($where)->select();
        //重组结构 以订单为基准查询订单下属的商品规则
        foreach($order_infos as $k => $v){
            //获取当前订单信息的中心名称
            $centre_name = db('wx_centre')->where(['centre_id'=>$v['centre_id']])->field('centre')->find()['centre'];
           // $centre_name = M('wx_centre')->where(['centre_id'=>$v['id']])->field('centre')->find()['centre'];
            $order_infos[$k]['centre'] = $centre_name;
            if(strpos($v['ruleid'],',')){   //判断是否为多条商品规则
                $rules = db('crm_rule')->where(['id'=>['IN',$v['ruleid']]])->select();
                $order_infos[$k]['data'] = $rules;
            }else{  //单条商品规则
                $rules = db('crm_rule')->where(['id'=>$v['ruleid']])->select();
                $order_infos[$k]['data'] = $rules;
            }
        }
        //二次重组 以一次重组结果为基准添加商品信息
        foreach($order_infos as $k => $v){
            //遍历data 商品规则信息 查询填充数据
            foreach($v['data'] as $kk => $vv){
                //根据商品gid查询相关信息
                $goods_info = db('jxc_goods')->where(['id'=>$vv['gid']])->find();
                $order_infos[$k]['data'][$kk]['good_name'] = $goods_info['good_name'];
                $order_infos[$k]['data'][$kk]['imgpath'] = $goods_info['imgpath'];
                $order_infos[$k]['data'][$kk]['mini_imgpath'] = $goods_info['mini_imgpath'];
            }
        }
        if(!$order_infos){
            // $this -> ajaxReturn(['status'=>'10003','error'=>"暂无数据"]);
            return json(['status'=>2,'msg'=>'暂无数据']);
        }
        // $this -> ajaxReturn(['status'=>'10001','result'=>$order_infos]);
        return json(['status'=>1,'msg'=>'检索成功','data'=>$order_infos]);
    }
    */
   
    // 查看当前活动是否拥有红包
    public function is_flrw()
    {
    	$hd_id = input('post.hd_id');  // 活动id
        if(empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
    	$res = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('red_type');
    	if($res !== 3){
    		return json(['status'=>1,'msg'=>'红包活动']);
    	}else{
    		return json(['status'=>2,'msg'=>'该活动没有红包']);
    	}
    }

    //福利任务接口
    public function flrw()
    {
        $hd_id = input('post.hd_id');
        $openid = input('post.openid');
        if(empty($openid)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $arr = db('hd_user')
	        ->join('hd_red_record','hd_user.open_id=hd_red_record.openid and hd_user.hd_id = hd_red_record.hd_id','left')
	        ->where("hd_user.open_id='$openid' and hd_user.hd_id='$hd_id' and hd_red_record.openid='$openid' and hd_red_record.hd_id='$hd_id'")
	        ->field('hd_user.zjf,hd_red_record.money')
	        ->find();
	    if(empty($arr['zjf'])) $arr['zjf'] = 0;
	    if(empty($arr['money'])) $arr['money'] = 0;
	    $red_info = db('crm_huodong')->where(['hd_id'=>$hd_id])->field('red_num,red_yfnum')->find();
	    if(empty($red_info)){
	    	$red = 0;
	    }else{
	    	// $red = $red_info['red_num'] - $red_info['red_yfnum'];
	    	$red = $red_info['red_num'];
	    }
	    return json(['status'=>1,'msg'=>'检索成功','data'=>$arr,'red'=>$red]);
        // $this->ajaxReturn($arr,'JSON');
    }

    //有奖任务
    public function yjrw()
    {
        $centre_id = input('post.centre_id');
        if(empty($centre_id)){
            return json(['status'=>2,'msg'=>'中心id不能为空']);
        }
        $times = date('Y-m-d',time());
        $arr = db('crm_huodong')
        	->where(['centre_id'=>$centre_id,'status'=>1,'fb_type'=>2,'hd_end_time'=>['GT',$times]])
        	->field('hd_id,template_id,title,fx_describe,centre_id')
        	->select();
        foreach($arr as $k => $v){
            $img = db('crm_template')->where(['id'=>$v['template_id']])->value('img');
            $arr[$k]['img'] = array_values(json_decode($img,true))[0];
        }
        // $this->ajaxReturn($arr,'JSON');
        return json(['status'=>1,'msg'=>'检索成功','data'=>$arr]);
    }

    //记录
    public function jl()
    {
        $openid = input('post.openid');
        $hd_id = input('post.hd_id');
        if(empty($openid) || empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $arr = db('hd_access_record')->where(['parentopenid'=>$openid,'hd_id'=>$hd_id])->field('openid')->select();
        if(empty($arr)) return json(['status'=>2,'msg'=>'暂无数据']);
        $ar = [];
        foreach ($arr as $key => $value) {
            $ar[]=$value['openid'];
        }
        $ar = array_flip($ar);
        $ar = array_keys($ar);
        $ae = [];
        foreach ($ar as $key => $value) {
            $ae[] = db('hd_user')->where("open_id='$value' and hd_id='$hd_id'")->field('user_name,open_id,head_image')->find();
        }
        foreach ($ae as $key => $value) {
            $ae[$key]['num'] = db('hd_access_record')->where(['openid'=>$value['open_id'],'hd_id'=>$hd_id])->count();
            $ae[$key]['last'] = db('hd_access_record')->where(['openid'=>$value['open_id'],'hd_id'=>$hd_id])->order('id desc')->value('create_time');
        }
        if(empty($ae)){
        	return josn(['status'=>2,'msg'=>'暂无数据']);
        }else{
        	return json(['status'=>1,'msg'=>'检索成功','data'=>$ae]);
        }
        // $this->ajaxReturn($ae,'JSON');
    }

    // 记录详情
    public function jldj()
    {
        $openid = input('post.openid');
        $hd_id = input('post.hd_id');
        if(empty($openid) || empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $arr['zong'] = db('hd_user')->where("open_id='$openid' and hd_id='$hd_id'")->field('user_name,open_id,head_image')->find();
        $arr['zong']['num'] = db('hd_access_record')->where(['openid'=>$openid,'hd_id'=>$hd_id])->count();
        $arr['fen'] = db('hd_access_record')
        	->where(['openid'=>$openid,'hd_id'=>$hd_id])
        	->order('create_time desc')
        	->field('cz_name,create_time')
        	->select();
        return json(['status'=>1,'msg'=>'检索成功','data'=>$arr]);
        // $this->ajaxReturn($arr,'JSON');
    }

    //分享设置
    public function fenx()
    {
        $hd_id = input('post.hd_id');
        if(empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $arr = db('crm_huodong')->alias('a')
        	->join('crm_template b','a.template_id = b.id')
        	->where(['a.hd_id'=>$hd_id])
        	->field('a.title,a.fx_describe,b.img')
        	->find();
        if(!empty($arr)){
        	db('crm_huodong')->where(['hd_id'=>$hd_id])->setInc('share_num');
        	$img = array_values(json_decode($arr['img'],true));
        	$arr['img'] = $img[0];
        }
        return empty($arr) ? json(['status'=>2,'msg'=>'暂无数据']) :json(['status'=>1,'msg'=>'请求成功','data'=>$arr]);
        // $this->ajaxReturn($arr,'JSON');
    }

    //分享改状态
    public function fxgzt()
    {
        $openid = input('post.openid');
        $hd_id = input('post.hd_id');
        if(empty($openid) || empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数检测失败']);
        }
        //活动增加分享次数
        Db::table('crm_huodong')->where('hd_id',$hd_id)->setInc('share_num');
        //用户增加分享次数  积分
        Db::table('hd_user')->where('open_id',$openid)->where('hd_id',$hd_id)->setInc('share_num');
        $now_is_first = Db::table('hd_user')->where('open_id',$openid)->where('hd_id',$hd_id)->find();
        if($now_is_first['is_first'] == 1){
            $res = db('hd_user')->where("open_id='$openid' and hd_id='$hd_id'")->setField('is_first',2);
        }else{
            $res = true;
        }

        if($res !== false){
        	return json(['status'=>1,'msg'=>'请求成功']);
        }else{
        	return json(['status'=>2,'msg'=>'请求失败']);
        }
    }

    //判断红包状态
    public function lqhb()
    {
        $openid = input('post.openid');
        $hd_id = input('post.hd_id');
        if(empty($openid) || empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $arr = db('hd_user')
            ->join('wx_user',' hd_user.open_id=wx_user.openid','left')
            ->where("hd_user.open_id='{$openid}' and hd_user.hd_id='{$hd_id}'")
            ->field('hd_user.is_first,wx_user.gj_type,hd_user.is_qianke')
            ->find();
        if($arr['is_first']==1){
            return json(['status'=>2,'msg'=>'未分享','zhuat'=>1]);
        }
        if($arr['is_first']==3){
            return json(['status'=>3,'msg'=>'已领取','zhuat'=>3]);
        }
        if($arr['is_qianke']==1){
            return json(['status'=>4,'msg'=>'未报名或未完善信息','zhuat'=>1]);
        }
        if($arr['gj_type']=='放弃'){
            return json(['status'=>5,'msg'=>'放弃','zhuat'=>1]);
        }
        if(empty($arr)){
            return json(['status'=>6,'msg'=>'未做任务','zhuat'=>1]);
        }else{
            return json(['status'=>1,'msg'=>'领取红包','zhuat'=>2]);
        }
    }

    // 红包活动微信分享 收费活动
    public function shares()
    {
		$url = input('post.url');
        if(empty($url)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
		// dump($url);die;
        $appid = 'xxxxxxxxxxxxxxxxxx';
        $appsecret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
        // Loader::import('jssdk.jssdk');
        // $jssdk = new \JSSDK($appid,$appsecret,$url);
		// dump($url);die;
		$jssdk = new \think\Jssdk2($appid, $appsecret,$url);
        $signPackage = $jssdk->GetSignPackage();
        // dump($signPackage);die;
        if (!empty($signPackage)){
            return json(['status'=>'1','msg'=>'请求成功','result'=>$signPackage]);
        }
    }

    // 订单信息
    public function order_info()
    {
    	$openid = input('post.openid');  // 用户id
    	$centre_id = input('post.centre_id');	// 中心id
        if(empty($openid)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $tmp_type = input('post.type');
    	$type = empty($tmp_type) ? 1 : $tmp_type;
    	$where = ['a.openid'=>$openid,'a.centre_id'=>$centre_id]; 
    	if($type == 1){
    		$where['a.status'] = ['neq',0];
    	}else{
    		$where['a.status'] = 1;
    	}
    	$data = db('crm_hd_order')->alias('a')
    		->join('crm_goods b','a.s_id = b.s_id','left')
    		->join('crm_huodong c','a.hd_id = c.hd_id','left')
    		->where($where)
    		->field('a.status,a.spend_code,a.order_id,c.jc_name,b.s_name,b.k_shu,a.price,a.create_time,c.hd_start_time,c.hd_end_time,c.xiaohao')
    		->select();
    	// dump($data);die;
    	return empty($data) ? json(['status'=>2,'msg'=>'暂无数据']) : json(['status'=>1,'msg'=>'检索成功','data'=>$data]);
    }

    // 订单详情
    public function order_dateli()
    {
    	$order_id = input('post.order_id');
        if(empty($order_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
    	$data = db('crm_hd_order')->alias('a')
    		->join('crm_goods b','a.s_id = b.s_id','left')
    		->join('wx_centre c','a.centre_id = c.centre_id','left')
    		->join('crm_huodong d','a.hd_id = d.hd_id','left')
    		->where(['a.order_id'=>$order_id])
    		->field('a.spend_code,a.create_time,a.status,b.s_name,b.k_shu,a.price,c.centre,c.site,c.l_phone,c.l_name,c.c_phone,d.xiaohao,d.jc_name,d.hd_start_time')
    		->select();
    	if(empty($data)){
    		return json(['status'=>2,'msg'=>'暂无数据']);
    	}else{
    		return json(['status'=>1,'msg'=>'检索成功','data'=>$data]);
    	}
    }

    //编辑时判断当前用户地址是否与数据库最后访问地址一致 返回信息
    public function edit_check_finalvisit()
    {
        $openid = input('post.openid'); //用户openid
        if(empty($openid)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $hd_id = input('post.hd_id');    //活动ID
        //获取当前用户省份 城市
        $userinfo = db('hd_user')->field('sheng,shi,final_visit')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->find();
        $sheng = db('region')->where(['REGION_ID'=>$userinfo['sheng']])->value('REGION_NAME');
        $shi = db('region')->where(['REGION_ID'=>$userinfo['shi']])->value('REGION_NAME');
        $db_address = $sheng.$shi;  //当前用户省份城市
        // dump($userinfo);die;
        $final_visit = explode(',',$userinfo['final_visit']);   //分割最后访问地址 即本次 XX省,XX市,经纬度
        $final_address = $final_visit[0].$final_visit[1];   //最后访问省份城市
        if($db_address == $final_address){  //判断最后访问省份城市是否与填写地址相同
            $data = ['status'=>1,'msg'=>'本次访问地址与填写地址相同'];
        }else{
            $data = ['status'=>2,'msg'=>'本次访问地址与填写地址不同'];
        }
        return json($data);
    }

    //修改快递信息
    public function xdz()
    {
        $openid = input('post.openid');
        $hd_id = input('post.hd_id');
        $tag = input('post.tag');
        $data = [];
        if(empty($tag) || empty($openid)){
            return json(['status'=>2,'msg'=>'参数错误tag']);
        }
        if($tag == 1){ //用户选择使用当前经纬度地址作为 详细地址
            $final_visit = db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->value('final_visit');   //获取最后访问地址
            $final_arrs= explode(',',$final_visit); //分割最后访问地址  XX省,XX市,经纬度
            $location = $final_arrs[2].','.$final_arrs[3]; //获取经纬度
            // dump($location);die;
            $now_address= $this-> get_address($location);
            $citys = ['北京市','上海市','天津市','重庆市'];    //直辖市列表
            //判断当前城市是否为直辖市  如果是直辖市 根据区县名查询二级城市为市辖区还是县
            if(in_array($now_address['city'],$citys)){
                $cityinfo = $this ->check_region_parent($now_address['province'],$now_address['district']);
                $now_address['city'] = $cityinfo['REGION_NAME'];
            }
            $sheng = $now_address['province'];  //省份
            $shi = $now_address['city'];    //市
            $address = $now_address['district'].$now_address['street'];
            $sheng_id = db('region')->where(['REGION_NAME'=>$sheng,'PARENT_ID'=>1])->value('REGION_ID');
            $shi_id = db('region')->where(['REGION_NAME'=>$shi,'PARENT_ID'=>$sheng_id])->value('REGION_ID');
            //组装数据
            $data['sheng'] = $sheng_id;
            $data['shi'] = $shi_id;
            $data['address'] = $address;
        }else{
        	$sheng = input('post.sheng');
        	$shi = input('post.shi');
        	$address = input('post.address');
            if (empty($sheng)) return json(['status'=>2,'msg'=>'参数错误sheng']);
            if (empty($shi)) return json(['status'=>2,'msg'=>'参数错误shi']);
            if (empty($address)) return json(['status'=>2,'msg'=>'参数错误address']);
            $data['sheng']=$sheng;
            $data['shi']=$shi;
            $data['address']=$address;
        }
        // dump($data);die;
        $ae = db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->update($data);
        if(false!==$ae){
           	return json(['status'=>1,'msg'=>'修改地址详细信息成功']);
        }else{
            return json(['status'=>2,'msg'=>'修改地址详细信息失败']);
        }
    }

    //根据经纬度获取用户当前省市区
    public function get_address($location)
    {
        $bd_ak = 'F609ea327058d7f14fd71ccc65e67bd1';    //百度地图开发者AK
        $url = "http://api.map.baidu.com/geocoder/v2/?location=$location&output=json&pois=1&ak=$bd_ak"; //百度地图API 经纬度获取详细地址
        $res = $this->doGet($url);  //执行获取结果信息
        $addressComponent= $res['result']['addressComponent'];
        return $addressComponent;    //json信息返回结果信息
    }
    //get方式执行curl方法
    public function doGet($turl)
    {
        $header[] = 'Accept:application/json';
        $header[] = 'Content-Type:application/json;charset=utf-8';
        $ch = curl_init();
        //设置选项
        curl_setopt($ch, CURLOPT_URL, $turl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        //执行
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data,true); //数组形式返回执行信息
    }

     //直辖市特殊情况 根据区名 返回属于市辖区还是县
    public function check_region_parent($province='北京市',$district='朝阳区')
    {
        //根据省名称 查询省ID
        $provinceid = db('region')->where(['REGION_NAME'=>$province])->value('REGION_ID');
        //根据省ID  查询二级城市ID
        $city_ids = db('region')->where(['PARENT_ID'=>$provinceid])->column('REGION_ID');
        $parent_ids= implode(',',$city_ids);
        //查询当前三级区县属于那个二级城市
        $city_id = db('region')->where(['REGION_NAME'=>$district,'PARENT_ID'=>['IN',$parent_ids]])->value('PARENT_ID');
        //根据查询出来的city_id 查询二级城市名称
        $city = db('region')->field('REGION_ID,REGION_NAME')->where(['REGION_ID'=>$city_id])->find();
        // dump($city);die;
        return $city;
    }

    /**
     * 活动模块地址判断相关
     */
    //活动首页判断当前用户地址与数据库内是否一致 不一致更新最后访问地址 省市 记录日志
    public function check_user_address()
    {
    	// echo 111;die;
		$openid    = input('post.openid'); //用户openid
		$hd_id     = input('post.hd_id');  // 活动id
		$longitude = input('post.longitude');    //经度
		$latitude  = input('post.latitude');  //纬度
        $location = $latitude.','.$longitude;   //组装经纬度  纬度,经度
       // $openid = 'oWprtwH7GGiFiyIlsS0yYVB_tbkw';
       // $hd_id = '32';
       // $location = '31.233953,121.45359';         //北京市,朝阳区,40.039973,116.431793   济南市,历下区,36.673323,117.148111  上海市,静安区,31.233953,121.45359
        $address = $this -> get_address($location); //获取当前访问地址
        $citys = ['北京市','上海市','天津市','重庆市'];    //直辖市列表
        //判断当前城市是否为直辖市  如果是直辖市 根据区县名查询二级城市为市辖区还是县
        if(in_array($address['city'],$citys)){
            $cityinfo = $this ->check_region_parent($address['province'],$address['district']);
            $address['city'] = $cityinfo['REGION_NAME'];
        }
        $now_visit = $address['province'].','.$address['city'].','.$location; //当前访问地址  XXX省,XXX市,经纬度  逗号分隔
        //判断当前用户是否为第一次访问
        $final_visit = db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->value('final_visit');
        // dump($final_visit);die;
        $data = []; //待更新数组
        if(!$final_visit){  //如果不存在最后访问地址为首次访问 首次访问同时更新用户详细地址
			//根据名字查询ID
			$sheng_id = db('region')->where(['REGION_NAME'=>$address['province'],'PARENT_ID'=>1])->value('REGION_ID');
            $shi_id = db('region')->where(['REGION_NAME'=>$address['city'],'PARENT_ID'=>$sheng_id])->value('REGION_ID');
            $data['sheng'] = $sheng_id;
            $data['shi'] = $shi_id;
            $data['address'] = $address['district'];
        }
        //判断当前地址与最后访问地址是否一致
        if(!empty($final_visit)){
        	$final_arrs = explode(',',$final_visit);
	        $final = $final_arrs[0].$final_arrs[1];//最后一次访问地址
	        $now = $address['province'].$address['city'];//当前访问地址
	        if($final != $now){
	            $data['final_visit'] = $now_visit;
	        }
        }else{
        	$data['final_visit'] = $now_visit;
        }
        if($data){  //如果参数存在 修改数据库信息
            db('hd_user')->where(['open_id'=>$openid,'hd_id'=>$hd_id])->update($data);
        }
        //执行记录用户访问日志
        $logs['open_id'] = $openid;
        $logs['hd_id'] = $hd_id;
        $logs['centre_id'] = db('crm_huodong')->where(['hd_id'=>$hd_id])->value('centre_id');
        $logs['province'] = $address['province'];
        $logs['city'] = $address['city'];
        $logs['location'] = $location;
        $ar = db('hd_visit_log')->insert($logs);
        return json(['status'=>1,'msg'=>'请求成功']);
    }

    // 添加浏览记录的接口
    public function record()
    {
		$openid       = input('post.openid');   // 用户openid
		$parentopenid = input('post.parentopenid');  // 父级openid
		$cz_name      = input('post.cz_name');  // 记录名称
		$hd_id        = input('post.hd_id');  // 活动id
		$centre_id    = input('post.centre_id'); // 中心id
    	if(empty($openid)) return json(['status'=>2,'msg'=>'openid不能为空']);
    	if(empty($cz_name)) return json(['status'=>2,'msg'=>'cz_name不能为空']);
    	if(empty($hd_id)) return json(['status'=>2,'msg'=>'活动id 不能为空']);
    	if(empty($parentopenid) || $parentopenid == 'null'){
    		$parentopenid = '';
    	} 
		$data['cz_time']      = date("H:i");
		$data['openid']       = $openid;
		$data['parentopenid'] = $parentopenid;
		$data['cz_name']      = $cz_name;
		$data['hd_id']        = $hd_id;
		$data['centre_id']    = $centre_id;
	    // dump($data);die;
	    $res = db('hd_access_record')->insert($data);
	    return $res !== false ? json(['status'=>1,'msg'=>'请求成功']) : json(['status'=>2,'msg'=>'请求失败']);
    }

    //省
    public function sheng()
    {
        $arr = db('region')->where("PARENT_ID=1")->select();
        return json(['status'=>1,'msg'=>'检索成功','data'=>$arr]);
    }

    //市
    public function shi()
    {
        $shi_id = input('post.sheng_id');
        if(empty($shi_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $arr = db('region')->where("PARENT_ID='$shi_id'")->select();
    	return json(['status'=>1,'msg'=>'检索成功','data'=>$arr]);
    }

    //判断用户是否关注公众号
    public function hq()
    {
    	$openid = input('post.openid');
      	$appid = 'xxxxxxxxxxxxxxxxxx';
        $appsecret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
        $jssdk = new \think\Jssdk2($appid, $appsecret);
        $signPackage = $jssdk->getUser($openid);
		if(isset($signPackage->subscribe) && $signPackage->subscribe){
			echo 1;	//已关注
		}else{
			echo 2;	//未关注
		}
    }

    // 红包活动微信分享 收费活动
    public function shares_gj()
    {
        $url = input('post.url');
        $appid = 'xxxxxxxxxxxxxxxxxx';
        $appsecret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
        $jssdk = new \think\Jssdk2($appid, $appsecret,$url);
        $signPackage = $jssdk->GetSignPackage();
        // dump($signPackage);die;
        if (!empty($signPackage)){
            return json(['status'=>'1','msg'=>'请求成功','result'=>$signPackage]);
        }
    }

    //判断用户是否关注公众号
    public function hq_gj()
    {
        $openid = input('post.openid');
        $appid = 'xxxxxxxxxxxxxxxxxx';
        $appsecret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
        $jssdk = new \think\Jssdk2($appid, $appsecret);
        $signPackage = $jssdk->getUser($openid);

        if(isset($signPackage->subscribe) && $signPackage->subscribe == 1){
            echo 1;	//已关注
        }else{
            echo 2;	//未关注
        }
    }

    //发送活动短信验证码
    public function send_code()
    {
        $phone = input('post.phone');   //获取要验证的手机号
        if(empty($phone)){
            return json(['status'=>2,'msg'=>'未检测到手机号']);
        }
        //获取到手机号 进行短信发送
        $ph = new \Photo();
        $yzm = $yzm=rand(1000,9999);
        $res = $ph -> number_activity($phone,$yzm);
        //发送成功  插入数据库
        $time = date('YmdHi');
        $result = json_encode($res);

        if(isset($res->result)){
            if($res -> result -> err_code == 0 ){   //成功
                Db::table('yanzhengma')->insert(['phone'=>$phone,'yzm'=>$yzm,'shijian'=>$time,'result'=>$result]);
                return json(['status'=>1,'msg'=>'验证码发送成功','yzm'=>$yzm]);
            }else{
                Db::table('yanzhengma_fail')->insert(['phone'=>$phone,'yzm'=>$yzm,'shijian'=>$time,'result'=>$result]);
                return json(['status'=>2,'msg'=>'验证码发送失败,请重试']);
            }
        }else{
            Db::table('yanzhengma_fail')->insert(['phone'=>$phone,'yzm'=>$yzm,'shijian'=>$time,'result'=>$result]);
            return json(['status'=>2,'msg'=>'验证码发送失败,请重试']);
        }
    }
}
