<?php
/**
 * Created by PhpStorm.
 * User: dc
 * Date: 2019/3/25
 * Time: 10:36
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Validate;
use think\Loader;
use redrand\Redrand;
use think\Request;

header("access-control-allow-origin:*");
ob_clean();
//活动
class Huodong  extends Comm
{
    //首页 我的
    public function index()
    {
        $ar = $this->token_data['centre_id'];
        // dump($ar);die;
        $type = input('post.type'); //0全部 1进行中 2已完成  3未发布  4已禁用
        if(!isset($type)){
            return json(['stauts'=>2,'msg'=>'参数错误']);
        }
        $tp_type = input('post.tp_type') ? input('post.tp_type') : 0;    //模板类型  0创建  1主题活动 2节日活动
        $set     = input('post.set');                                   //搜索
        $user_id = $this->token_data['user_id'];   //用户
        $centre_id = $this->token_data['centre_id'];   //中心
        $page = input('post.page') ? input('post.page') : 1;  // 当前页
        $pageone = input('post.pageone') ? input('post.pageone') : 10; // 每页显示条数
        //当前时间
        $now_time = date('Y-m-d');
        //模板类型判断中心
        if($tp_type !=0){
            $where['a.centre_id'] =0;
        }else{
            $where['a.centre_id'] = $centre_id ;
        }
        if($type ==1){
            $where['b.fb_type'] =2;
            $where['hd_end_time'] = ['>=',$now_time];
        }elseif($type==2){
            $where['b.fb_type'] = 2;
            $where['hd_end_time'] = ['<',$now_time];
        }elseif($type==4){
            $where['b.fb_type'] = 3;
        }
        if(!empty($set)){
            $where['b.jc_name'] = ['like',"%$set%"];
        }

        //获取数据
        $info =  db('crm_template')
                ->alias('a')
                ->join('crm_huodong b','b.template_id=a.id','left')
                ->where(['a.baocun'=>2,'a.status'=>1,'a.type'=>$tp_type])
                ->where($where)
                ->orderRaw("FIELD('b.fb_type','2','1','3'),b.hd_end_time desc,a.id desc")
                ->field('a.*,b.hd_id,b.jc_name,b.hd_type,b.fb_type,b.hd_end_time,b.hd_qrcode')
                ->select();

        if(empty($info)){
            return json(['stauts'=>2,'msg'=>'暂时没有数据']);
        }
        foreach($info as $k=>$v){
            if($tp_type ==0){
                if($v['fb_type'] ==1 || empty($v['fb_type'])){
                    $info[$k]['fb_type'] = '未发布';
                }elseif($v['fb_type'] == 2){      //发布
                    if($now_time<=$v['hd_end_time']){
                        $info[$k]['fb_type'] = '进行中';
                    }elseif($now_time>$v['hd_end_time']){
                        $info[$k]['fb_type'] = '已完成';
                    }
                }else{
                    $info[$k]['fb_type'] ='已禁用';
                }
            }

            //收藏总数
            $info[$k]['shoucang'] = db('crm_shoucang')->where('template_id',$v['id'])->where('sc_status',1)->count();
            //收藏状态
            $info[$k]['sc_status'] = db('crm_shoucang')->where(['template_id'=>$v['id'],'user_id'=>$user_id])->value('sc_status');
            if(empty($info[$k]['sc_status'])){
                $info[$k]['sc_status'] = 2;
            }

            $info[$k]['img']          = json_decode($v['img'],true)[1];     //图片
            $info[$k]['text1']        = json_decode($v['text1'],true)[1];      //图片上边文字
            $info[$k]['left1']        = json_decode($v['left1'],true)[1];
            $info[$k]['top1']         = json_decode( $v['top1'],true)[1];
            $info[$k]['underline1']   = json_decode($v['underline1'],true)[1];
            $info[$k]['ziti1']        = json_decode($v['ziti1'],true)[1];     //上边字体
            $info[$k]['zihao1']       = json_decode( $v['zihao1'],true)[1];     //上边字号
            $info[$k]['jianju1']      = json_decode($v['jianju1'],true)[1];    //上边间距
            $info[$k]['color1']       = json_decode($v['color1'],true)[1];     //上边颜色
            $info[$k]['yangshi1']     = json_decode($v['yangshi1'],true)[1];   //上边样式
            $info[$k]['text2']        = json_decode($v['text2'],true)[1];       //图片中间文字
            $info[$k]['left2']        = json_decode($v['left2'],true)[1];
            $info[$k]['top2']         = json_decode($v['top2'],true)[1];
            $info[$k]['underline2']   = json_decode($v['underline2'],true)[1];
            $info[$k]['ziti2']        = json_decode($v['ziti2'],true)[1];      //中间字体
            $info[$k]['zihao2']       = json_decode($v['zihao2'],true)[1];     //中间字号
            $info[$k]['jianju2']      = json_decode($v['jianju2'],true)[1];    //中间间距
            $info[$k]['color2']       = json_decode($v['color2'],true)[1];     //中间颜色
            $info[$k]['yangshi2']     = json_decode($v['yangshi2'],true)[1];   //中间样式
            $info[$k]['text3']        = json_decode($v['text3'],true)[1];    //图片下边文字
            $info[$k]['left3']        = json_decode($v['left3'],true)[1];
            $info[$k]['top3']         = json_decode($v['top3'],true)[1];
            $info[$k]['underline3']   = json_decode($v['underline3'],true)[1];
            $info[$k]['ziti3']        = json_decode($v['ziti3'],true)[1];      //下边字体
            $info[$k]['zihao3']       = json_decode($v['zihao3'],true)[1];     //下边字号
            $info[$k]['jianju3']      = json_decode($v['jianju3'],true)[1];    //下边间距
            $info[$k]['color3']       = json_decode($v['color3'],true)[1];     //下边颜色
            $info[$k]['yangshi3']     = json_decode($v['yangshi3'],true)[1];   //下边样式
        }
        $info1 = [];
        if($tp_type == 0 && $type ==3){
            foreach($info as $k=>$v){
                if($v['fb_type']=='未发布'){
                    $info1[] = $v;
                }
            }
            $info = $info1;
        }

        //分页
        $counts = count($info);
        $pagetotal = ceil($counts/$pageone);
        $start = ($page-1)*$pageone;
        $data = array_slice($info,$start,$pageone);
        $map = [
            'page' => $page,
            'pageone' => $pageone,
            'pagetotal' => $pagetotal,
            'count'	=> $counts
        ];
        return json(['stauts'=>1,'msg'=>'检索成功','data'=>$data,'map'=>$map]);//返回结果信息
    }



    //收藏
    public function shoucang(){
        $user_id = $this->token_data['user_id'];    //用户id
        //获取结果集
        $info    = db('crm_template')
            ->alias('a')
            ->join('crm_shoucang b','a.id=b.template_id','left')
            //->join('crm_huodong c','a.id=c.template_id','left')
            ->where(['a.status'=>1,'b.user_id'=>$user_id,'b.sc_status'=>1,'a.baocun'=>2])
            ->select();
        //获取每个模板的收藏数量
        foreach($info as $k=>$v){
            $info[$k]['shoucang']    = db('crm_shoucang')
                ->where(['template_id'=>$v['template_id'],'sc_status'=>1])
                ->count();
            $info[$k]['hd_id']        = db('crm_huodong')->where('template_id',$v['template_id'])->value('hd_id');
            $info[$k]['img']          = json_decode($v['img'],true)[1];     //图片
            $info[$k]['text1']        = json_decode($v['text1'],true)[1];   //图片上边文字
            $info[$k]['left1']        = json_decode($v['left1'],true)[1];
            $info[$k]['top1']         = json_decode( $v['top1'],true)[1];
            $info[$k]['underline1']   = json_decode($v['underline1'],true)[1];
            $info[$k]['ziti1']        = json_decode($v['ziti1'],true)[1];     //上边字体
            $info[$k]['zihao1']       = json_decode( $v['zihao1'],true)[1];     //上边字号
            $info[$k]['jianju1']      = json_decode($v['jianju1'],true)[1];    //上边间距
            $info[$k]['color1']       = json_decode($v['color1'],true)[1];     //上边颜色
            $info[$k]['yangshi1']     = json_decode($v['yangshi1'],true)[1];   //上边样式
            $info[$k]['text2']        = json_decode($v['text2'],true)[1];       //图片中间文字
            $info[$k]['left2']        = json_decode($v['left2'],true)[1];
            $info[$k]['top2']         = json_decode($v['top2'],true)[1];
            $info[$k]['underline2']   = json_decode($v['underline2'],true)[1];
            $info[$k]['ziti2']        = json_decode($v['ziti2'],true)[1];      //中间字体
            $info[$k]['zihao2']       = json_decode($v['zihao2'],true)[1];     //中间字号
            $info[$k]['jianju2']      = json_decode($v['jianju2'],true)[1];    //中间间距
            $info[$k]['color2']       = json_decode($v['color2'],true)[1];     //中间颜色
            $info[$k]['yangshi2']     = json_decode($v['yangshi2'],true)[1];   //中间样式
            $info[$k]['text3']        = json_decode($v['text3'],true)[1];    //图片下边文字
            $info[$k]['left3']        = json_decode($v['left3'],true)[1];
            $info[$k]['top3']         = json_decode($v['top3'],true)[1];
            $info[$k]['underline3']   = json_decode($v['underline3'],true)[1];
            $info[$k]['ziti3']        = json_decode($v['ziti3'],true)[1];      //下边字体
            $info[$k]['zihao3']       = json_decode($v['zihao3'],true)[1];     //下边字号
            $info[$k]['jianju3']      = json_decode($v['jianju3'],true)[1];    //下边间距
            $info[$k]['color3']       = json_decode($v['color3'],true)[1];     //下边颜色
            $info[$k]['yangshi3']     = json_decode($v['yangshi3'],true)[1];   //下边样式
        }
        if(empty($info)){
            return json(['status'=>2,'msg'=>'暂无数据']);
        }
        return json(['status'=>1,'msg'=>'检索成功','data'=>$info]);
    }

    //总统计
    public function zongtj(){
        $user_id = $this->token_data['user_id'];    //用户id
        $centre_id = $this->token_data['centre_id']; //中心id
        $time_s = input('post.time_s'); //开始时间
        $time_e = input('post.time_e'); //结束时间
       /* $chose = input('post.chose');   //选择 1 上月 2下月*/
       /* $yue = input('post.yue');       //月份*/
        $where['b.centre_id'] = $centre_id ;
        if(!empty($time_s) || !empty($time_e)){
            $where['hd_start_time'] = ['between',[$time_s,$time_e]];
        }
        $parms = input('post.page');
        $page = (isset($parms) && !empty($parms)) ? $parms : 1;//当前页数
        $pageone= input('post.pageone') ? input('post.pageone') : 10;//每页数据
        //获取活动信息
        $info = db('crm_huodong')
            ->alias('a')
            ->join('crm_template b','a.template_id=b.id')
            ->where(['b.baocun'=>2,'a.fb_type'=>['neq',1],'a.status'=>1])
            ->field('hd_id,jc_name,hd_type,hd_start_time,hd_end_time')
            ->where($where)
            ->order("a.hd_start_time desc")
            ->select();
        //活动类型
        $hd_type = ['1'=>'砍价活动','2'=>'拼团活动','3'=>'促单(线上)','4'=>'促单(线上+线下)'];
        $browse = 0;   //总浏览量
        $zrs    = 0;   //参与总人数
        $fxs    = 0;   //分享总人数
        $zsr    = 0;    //总收入
        //处理数据
        foreach($info as $k=>$v){
            $info[$k]['hd_type'] = $hd_type[$v['hd_type']] ;
            $info[$k]['browse'] = db('hd_access_record')->where("hd_id={$v['hd_id']}  and cz_name='进入活动' ")->where('centre_id',$centre_id)->count();//浏览量
            $info[$k]['zrs'] = db('hd_user')->where("hd_id={$v['hd_id']}")->where('centre_id',$centre_id)->count();//活动总人数
            $info[$k]['fxs'] = db('hd_user')->where('hd_id',$v['hd_id'])->where('centre_id',$centre_id)->sum('share_num');  //分享数
            $info[$k]['zsr'] = db('crm_hd_order')->where('centre_id',$centre_id)->where('hd_id',$v['hd_id'])->where('status','neq',0)->sum('price');
            $browse +=  $info[$k]['browse'];
            $zrs    +=  $info[$k]['zrs'];
            $fxs    +=  $info[$k]['fxs'];
            $zsr    +=  $info[$k]['zsr'];
        }
        //分页
        $counts = count($info);
        $pagetotal = ceil($counts/$pageone);
        $start = ($page-1)*$pageone;
        $data = array_slice($info,$start,$pageone);
        $map = [
            'page' => $page,
            'pageone' => $pageone,
            'pagetotal' => $pagetotal,
            'count'	=> $counts
        ];
        //总数
        $zong = [
            'browse' => $browse,
            'zrs'    => $zrs,
            'fxs'    => $fxs,
            'zsr'    => $zsr
        ];
        if(empty($info)){
            return json(['stauts'=>2,'msg'=>'暂无数据']);
        }
        return json(['stauts'=>1,'msg'=>'检索成功','data'=>$data,'zong'=>$zong,'map'=>$map]);

    }

    //钱包
    public function qianbao(){
        $token = $this -> token_data;
        $centre_id=$token['centre_id'];
        $user_id = $token['user_id'];
        $time_s = input('post.time_s'); //开始时间
        $time_e = input('post.time_e'); //结束时间
        $chose = input('post.chose');   //选择 1 本周2 本月
        $parms = input('post.page');
        $page = (isset($parms) && !empty($parms)) ? $parms : 1;//当前页数
        $pageone = input('post.pageone') ? input('post.pageone') : 10; // 每页显示条数
        //红包信息
        $hd=Db::table('a_money')->where("centre_id='$centre_id'")->find();
        if(empty($hd)){
            $hd['red_remaining']=0;
            $hd['red_total']    =0;
        }else{
            $hd['red_remaining'] = sprintf("%.2f", $hd['red_remaining']);
            $hd['red_total']     = sprintf("%.2f", $hd['red_total']);
        }
        $arr['hd'] = $hd;
        //举办活动次数
        $cs = db('crm_huodong')->where(['status'=>1,'fb_type'=>2,'centre_id'=>$centre_id])->count();
        //使用红包次数
        $whb = db('crm_huodong')->where(['status'=>1,'fb_type'=>2,'centre_id'=>$centre_id,'red_type'=>['in',[1,2]]])->count();
        //使用情况
        $arr['shiyong'] = ['cs'=>$cs,'whb'=>$whb];
        //组装where条件
        $where = [
            'centre_id'=>$centre_id,
            'czzt'=>2,
            'status'=>'支付成功'
        ];
        ///判断 是否选择1 本周 2 本月  修改对应的开始时间结束时间
        $date_w = date('w') ? date('w') : 7;    //判断当天时间是否为周日返回0  如果为周日 设置为7
        if($chose){
            switch ($chose){
                case 1: //本周
                    $time_s = date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-$date_w+1,date("Y")));
                    $time_e = date("Y-m-d",mktime(23,59,59,date("m"),date("d")-$date_w+7,date("Y")));
                    break;
                case 2: //本月
                    $time_s = date("Y-m-d",mktime(0, 0 , 0,date("m"),1,date("Y")));
                    $time_e = date("Y-m-d",mktime(23,59,59,date("m"),date("t"),date("Y")));
                    break;
            }
        }
        //判断是否存在查询条件
        $where_str= '';
        if($time_s){
            $where_str .="and create_time >= '{$time_s}' ";
        }
        if($time_e){
            $time_e = $time_e.' 23:59:59';
            $where_str .="and create_time <= '{$time_e}' ";
        }
        //判断是否存在查询条件
        if($where_str){
            $where_str = substr($where_str,4);
        }
        $num = Db::table('a_money_order') -> where($where)->where($where_str)->count();
        $pagetotal=ceil($num/$pageone);
        $pyl=($page-1)*$pageone;//偏移量

        $arr['list'] = Db::table('a_money_order') -> where($where)->where($where_str)->order('id desc')->limit($pyl,$pageone) -> select();   //获取结果信息
        foreach($arr['list'] as $k => $v){
            $arr['list'][$k]['xuhao'] = $k+1;
            $arr['list'][$k]['money'] = $v['money'].'元';
        }
        //分页
        $map['page'] = $page ? $page : 1;
        $map['pagetotal'] = $pagetotal ? $pageone : 0;
        $map['num'] = $num ? $num : 0;
        $map['pageone'] = $pageone ? $pageone : 10;

        $data = ['map'=>$map,'data'=>$arr];
        return json(['stauts'=>1,'msg'=>'检索成功','data'=>$data]);
    }

    //发布
    public function fabu(){
        $template_id = input('post.template_id');     //模板id
        if(empty($template_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $info = db('crm_huodong')->where('template_id',$template_id)->find();
        if(empty($info)){
            return json(['status'=>3,'msg'=>'活动未创建完成']);
        }
        if($info['fb_type']==1){
            if(empty($info['hd_qrcode'])){
                $hd_qrcode = $this -> set_qrcode($info['hd_id'],$info['centre_id']);
                Db::table('crm_huodong')->where(['hd_id'=>$info['hd_id']])->update(['hd_qrcode'=>$hd_qrcode]);
            }
            $res = db('crm_huodong')->where('template_id',$template_id)->update(['fb_type'=>2]);
            if($res !==false){
                return json(['status'=>1,'msg'=>'发布成功']);
            }else{
                return json(['status'=>2,'msg'=>'发布失败']);
            }
        }else{
            return json(['status'=>1,'msg'=>'已发布']);
        }
    }

    //点击收藏，取消
    public function sc_change(){
        $template_id = input('post.template_id'); //模板id
        if(empty($template_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $user_id = $this->token_data['user_id'];      //用户id
        $info = db('crm_shoucang')->where(['template_id'=>$template_id,'user_id'=>$user_id])->find();

        if(empty($info)){   //不存在
            $data = [
                'user_id'     => $user_id,
                'template_id' => $template_id,
                'sc_status'      => 1,
                'create_time' => date("Y-m-d H:i:s")
            ];
            $res = db('crm_shoucang')->insert($data);
        }elseif($info['sc_status']==1){    //取消
            $res = db('crm_shoucang')->where(['template_id'=>$template_id,'user_id'=>$user_id])->update(['sc_status'=>2]);
        }else{                             //收藏
            $res = db('crm_shoucang')->where(['template_id'=>$template_id,'user_id'=>$user_id])->update(['sc_status'=>1]);
        }
        if($res !==false){
            return json(['status'=>1,'msg'=>'操作成功']);
        }else{
            return json(['status'=>2,'msg'=>'操作失败']);
        }
    }

    //作废
    public function zuofei(){
        $template_id = input('post.template_id'); //模板id
        if(empty($template_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $res = db('crm_template')->where('id',$template_id)->update(['status'=>2]);
        $hd_id = input('post.hd_id');         //活动id
        if(!empty($hd_id)){
            $res = db('crm_huodong')->where('hd_id',$hd_id)->update(['status'=>2]);
        }
        if($res !==false){
            return json(['status'=>1,'msg'=>'操作成功']);
        }else{
            return json(['status'=>2,'msg'=>'操作失败']);
        }
    }

    //模板下
    public function template(){
        $centre_id = $this->token_data['centre_id'];  //中心id
        $post = input('post.');
        $tp_type         = $post['tp_type'];   //模板类型  0创建  1主题活动 2节日活动
        $img = $post['img'];
        $p               = $post['p'];       //页数
        $baocun =  $post['baocun'];

        if(empty($img) || empty($p) || !isset($tp_type)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $template_id  = input('post.template_id');     //模板id
        if(is_array($img)){
            if(preg_match('/.*(\.png|\.jpg|\.jpeg|\.gif)$/',implode('',$img))){
                $hd_img = implode('',$img);
            }else{
                $hd_img       =  $this ->decode_base64_update($img,$centre_id,'img');//判断是否存在base64图片编码数组 存在执行转换 不存在存空
            }
        }else{
            $hd_img = $img;
        }


        $text1        = input('post.text1');      //图片上边文字
        $left1        = input('post.left1');
        $top1         = input('post.top1');
        $underline1   = input('post.underline1');
        $ziti1        = input('post.ziti1');     //上边字体
        $zihao1       = input('post.zihao1');     //上边字号
        $jianju1      = input('post.jianju1');    //上边间距
        $color1       = input('post.color1');     //上边颜色
        $yangshi1     = input('post.yangshi1');   //上边样式
        $text2        = input('post.text2');       //图片中间文字
        $left2        = input('post.left2');
        $top2         = input('post.top2');
        $underline2   = input('post.underline2');
        $ziti2        = input('post.ziti2');      //中间字体
        $zihao2       = input('post.zihao2');     //中间字号
        $jianju2      = input('post.jianju2');    //中间间距
        $color2       = input('post.color2');     //中间颜色
        $yangshi2     = input('post.yangshi2');   //中间样式
        $text3        = input('post.text3');    //图片下边文字
        $left3        = input('post.left3');
        $top3         = input('post.top3');
        $underline3   = input('post.underline3');
        $ziti3        = input('post.ziti3');      //下边字体
        $zihao3       = input('post.zihao3');     //下边字号
        $jianju3      = input('post.jianju3');    //下边间距
        $color3       = input('post.color3');     //下边颜色
        $yangshi3     = input('post.yangshi3');   //下边样式

        if(empty($template_id)){                                                //
            $data['img']         = json_encode(["$p"=>$hd_img]);     //图片
            $data['text1']       = json_encode(["$p"=>$text1]);      //图片上边文字
            $data['left1']       = json_encode(["$p"=>$left1]);
            $data['top1']        = json_encode(["$p"=>$top1]);
            $data['underline1']  = json_encode(["$p"=>$underline1]);
            $data['ziti1']       = json_encode(["$p"=>$ziti1]);     //上边字体
            $data['zihao1']      = json_encode(["$p"=>$zihao1]);     //上边字号
            $data['jianju1']      = json_encode(["$p"=>$jianju1]);    //上边间距
            $data['color1']       = json_encode(["$p"=>$color1]);     //上边颜色
            $data['yangshi1']     = json_encode(["$p"=>$yangshi1]);   //上边样式
            $data['text2']        = json_encode(["$p"=>$text2]);       //图片中间文字
            $data['left2']        = json_encode(["$p"=>$left2]);
            $data['top2']         = json_encode(["$p"=>$top2]);
            $data['underline2']   = json_encode(["$p"=>$underline2]);
            $data['ziti2']        = json_encode(["$p"=>$ziti2]);      //中间字体
            $data['zihao2']       = json_encode(["$p"=>$zihao2]);     //中间字号
            $data['jianju2']      = json_encode(["$p"=>$jianju2]);    //中间间距
            $data['color2']       = json_encode(["$p"=>$color2]);     //中间颜色
            $data['yangshi2']     = json_encode(["$p"=>$yangshi2]);   //中间样式
            $data['text3']        = json_encode(["$p"=>$text3]);    //图片下边文字
            $data['left3']        = json_encode(["$p"=>$left3]);
            $data['top3']         = json_encode(["$p"=>$top3]);
            $data['underline3']   = json_encode(["$p"=>$underline3]);
            $data['ziti3']        = json_encode(["$p"=>$ziti3]);      //下边字体
            $data['zihao3']       = json_encode(["$p"=>$zihao3]);     //下边字号
            $data['jianju3']      = json_encode(["$p"=>$jianju3]);    //下边间距
            $data['color3']       = json_encode(["$p"=>$color3]);     //下边颜色
            $data['yangshi3']     = json_encode(["$p"=>$yangshi3]);   //下边样式

            $data['type']           = 0;                         //类型  1创建
            if($baocun ==2){
                $data['baocun'] = 2;
            }
            $data['centre_id']  =  $centre_id;                       //中心id
            $data['create_user'] = $this->token_data['user_id'];    //用户id
            $data['create_time'] = date("Y-m-d H:i:s");        //创建时间
            $template_id = db('crm_template')->insertGetId($data);
            if($template_id !==false){
                return json(['status'=>1,'msg'=>'创建成功','template_id'=>$template_id,'p'=>$p]);
            }else{
                return json(['status'=>2,'msg'=>'创建失败']);
            }
        }else{
            $info = db('crm_template')->where(['id'=>$template_id,'status'=>1])->find();

            if($baocun==2){
                $data['baocun'] =2;
            }
            if($tp_type !=0){
                $data['img']      = $info['img'];
                $arr = json_decode($info['img'],true);
                $str = [];
                foreach($arr as $k=>$v){
                    $str[$k] = '' ;     //空字符串
                }
                $str = json_encode($str);
                $data['text1']    = !empty($info['text1']) ? $info['text1']:$str;
                $data['left1']    = !empty($info['left1']) ? $info['left1']:$str;
                $data['top1']     = !empty($info['top1']) ? $info['top1']:$str;
                $data['underline1'] = !empty($info['underline1']) ? $info['underline1']:$str;
                $data['ziti1']    = !empty($info['ziti1']) ? $info['ziti1']:$str;
                $data['zihao1']   = !empty($info['zihao1']) ? $info['zihao1']:$str;
                $data['jianju1']  = !empty($info['jianju1']) ? $info['jianju1']:$str;
                $data['color1']   = !empty($info['color1']) ? $info['color1']:$str;
                $data['yangshi1'] = !empty($info['yangshi1']) ? $info['yangshi1']:$str;
                $data['text2']    = !empty($info['text2']) ? $info['text2']:$str;
                $data['left2']    = !empty($info['left2']) ? $info['left2']:$str;
                $data['top2']     = !empty($info['top2']) ? $info['top2']:$str;
                $data['underline2'] = !empty($info['underline2']) ? $info['underline2']:$str;
                $data['ziti2']    = !empty($info['ziti2']) ? $info['ziti2']:$str;
                $data['zihao2']   = !empty($info['zihao2']) ? $info['zihao2']:$str;
                $data['jianju2']  = !empty($info['jianju2']) ? $info['jianju2']:$str;
                $data['color2']   = !empty($info['color2']) ? $info['color2']:$str;
                $data['yangshi2'] = !empty($info['yangshi2']) ? $info['yangshi2']:$str;
                $data['text3']    = !empty($info['text3']) ? $info['text3']:$str;
                $data['left3']    = !empty($info['left3']) ? $info['left3']:$str;
                $data['top3']     = !empty($info['top3']) ? $info['top3']:$str;
                $data['underline3']     = !empty($info['underline3']) ? $info['underline3']:$str;
                $data['ziti3']    = !empty($info['ziti3']) ? $info['ziti3']:$str;
                $data['zihao3']   = !empty($info['zihao3']) ? $info['zihao3']:$str;
                $data['jianju3']  = !empty($info['jianju3']) ? $info['jianju3']:$str;
                $data['color3']   = !empty($info['color3']) ? $info['color3']:$str;
                $data['yangshi3'] = !empty($info['yangshi3']) ? $info['yangshi3']:$str;
                $data['type']           = 0;                         //类型  1创建
                $data['centre_id']  =  $centre_id;                       //中心id
                $data['create_user'] = $this->token_data['user_id'];    //用户id
                $data['create_time'] = date("Y-m-d H:i:s");        //创建时间
                $template = db('crm_template')->insertGetId($data);

                if($template !==false){
                    return json(['status'=>1,'msg'=>'成功','template_id'=>$template,'p'=>$p]);
                }else{
                    return json(['status'=>2,'msg'=>'失败']);
                }
            }else{
                $hdi =json_decode($info['img'],true);
                $hdi[$p] = $hd_img ;
                $data['img']      = json_encode($hdi);

                $text_1 =json_decode($info['text1'],true);
                $text_1[$p] = $text1;
                $data['text1']    = json_encode($text_1);

                $left_1 =json_decode($info['left1'],true);
                $left_1[$p] = $left1;
                $data['left1']    = json_encode($left_1);

                $top_1 =json_decode($info['top1'],true);
                $top_1[$p] = $top1;
                $data['top1']     = json_encode($top_1);

                $underline_1 =json_decode($info['underline1'],true);
                $underline_1[$p] = $underline1;
                $data['underline1']     = json_encode($underline_1);

                $ziti_1 =json_decode($info['ziti1'],true);
                $ziti_1[$p] = $ziti1;
                $data['ziti1']    = json_encode($ziti_1);

                $zihao_1 =json_decode($info['zihao1'],true);
                $zihao_1[$p] = $zihao1;
                $data['zihao1']   = json_encode($zihao_1);

                $jianju_1 =json_decode($info['jianju1'],true);
                $jianju_1[$p] = $jianju1;
                $data['jianju1']  = json_encode($jianju_1);

                $color_1 =json_decode($info['color1'],true);
                $color_1[$p] = $color1;
                $data['color1']   = json_encode($color_1);

                $yangshi_1 =json_decode($info['yangshi1'],true);
                $yangshi_1[$p] = $yangshi1;
                $data['yangshi1'] = json_encode($yangshi_1);

                $text_2 =json_decode($info['text2'],true);
                $text_2[$p] = $text2;
                $data['text2']    = json_encode($text_2);

                $left_2 =json_decode($info['left2'],true);
                $left_2[$p] = $left2;
                $data['left2']    = json_encode($left_2);

                $top_2 =json_decode($info['top2'],true);
                $top_2[$p] = $top2;
                $data['top2']     = json_encode($top_2);

                $underline_2 =json_decode($info['underline2'],true);
                $underline_2[$p] = $underline2;
                $data['underline2']     = json_encode($underline_2);

                $ziti_2 =json_decode($info['ziti2'],true);
                $ziti_2[$p] = $ziti2;
                $data['ziti2']    = json_encode($ziti_2);

                $zihao_2 =json_decode($info['zihao2'],true);
                $zihao_2[$p] = $zihao2;
                $data['zihao2']   = json_encode($zihao_2);

                $jianju_2 =json_decode($info['jianju2'],true);
                $jianju_2[$p] = $jianju2;
                $data['jianju2']  = json_encode($jianju_2);

                $color_2 =json_decode($info['color2'],true);
                $color_2[$p] = $color2;
                $data['color2']   = json_encode($color_2);

                $yangshi_2 =json_decode($info['yangshi2'],true);
                $yangshi_2[$p] = $yangshi2;
                $data['yangshi2'] = json_encode($yangshi_2);

                $text_3 =json_decode($info['text3'],true);
                $text_3[$p] = $text3;
                $data['text3']    = json_encode($text_3);

                $left_3 =json_decode($info['left3'],true);
                $left_3[$p] = $left3;
                $data['left3']    = json_encode($left_3);

                $top_3 =json_decode($info['top3'],true);
                $top_3[$p] = $top3;
                $data['top3']     = json_encode($top_3);

                $underline_3 =json_decode($info['underline3'],true);
                $underline_3[$p] = $underline3;
                $data['underline3']     = json_encode($underline_3);

                $ziti_3 =json_decode($info['ziti3'],true);
                $ziti_3[$p] = $ziti3;
                $data['ziti3']    = json_encode($ziti_3);

                $zihao_3 =json_decode($info['zihao3'],true);
                $zihao_3[$p] = $zihao3;
                $data['zihao3']   = json_encode($zihao_3);

                $jianju_3 =json_decode($info['jianju3'],true);
                $jianju_3[$p] = $jianju3;
                $data['jianju3']  = json_encode($jianju_3);

                $color_3 =json_decode($info['color3'],true);
                $color_3[$p] = $color3;
                $data['color3']   = json_encode($color_3);

                $yangshi_3 =json_decode($info['yangshi3'],true);
                $yangshi_3[$p] = $yangshi3;
                $data['yangshi3'] = json_encode($yangshi_3);
                $res = db('crm_template')->where(['id'=>$template_id,'type'=>0,'status'=>1])->update($data);

                if($res !==false){
                    return json(['status'=>1,'msg'=>'成功','template_id'=>$template_id,'p'=>$p]);
                }else{
                    return json(['status'=>2,'msg'=>'失败']);
                }
            }

        }
    }

    //模板上内容
    public function shang(){
        $template_id = input('post.template_id');    //模板id
        $p = input('post.p');     //页数
        if(empty($template_id)||empty($p)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $info = db('crm_template')->where(['id'=>$template_id,'status'=>1])->find();
        if(empty($info)){
                return json(['status'=>2,'msg'=>'暂时没有数据']);
        }
        if(!isset(json_decode($info['img'],true)[$p])){
            return json(['status'=>2,'msg'=>'暂时没有数据']);
        }
        $data['img']         = isset(json_decode($info['img'],true)[$p])?json_decode($info['img'],true)[$p]:'';     //图片
        $data['text1']       = isset(json_decode($info['text1'],true)[$p])?json_decode($info['text1'],true)[$p]:'';      //图片上边文字
        $data['left1']       = isset(json_decode($info['left1'],true)[$p])?json_decode($info['left1'],true)[$p]:'';
        $data['top1']        = isset(json_decode( $info['top1'],true)[$p])?json_decode( $info['top1'],true)[$p]:'';
        $data['underline1']  = isset(json_decode($info['underline1'],true)[$p])?json_decode($info['underline1'],true)[$p]:'';
        $data['ziti1']       = isset(json_decode($info['ziti1'],true)[$p])?json_decode($info['ziti1'],true)[$p]:'';     //上边字体
        $data['zihao1']      = isset(json_decode( $info['zihao1'],true)[$p])?json_decode( $info['zihao1'],true)[$p]:'';     //上边字号
        $data['jianju1']      = isset(json_decode($info['jianju1'],true)[$p])?json_decode($info['jianju1'],true)[$p]:'';    //上边间距
        $data['color1']       = isset(json_decode($info['color1'],true)[$p])?json_decode($info['color1'],true)[$p]:'';     //上边颜色
        $data['yangshi1']     = isset(json_decode($info['yangshi1'],true)[$p])?json_decode($info['yangshi1'],true)[$p]:'';   //上边样式
        $data['text2']        = isset(json_decode($info['text2'],true)[$p])?json_decode($info['text2'],true)[$p]:'';       //图片中间文字
        $data['left2']        = isset(json_decode($info['left2'],true)[$p])?json_decode($info['left2'],true)[$p]:'';
        $data['top2']         = isset(json_decode($info['top2'],true)[$p])?json_decode($info['top2'],true)[$p]:'';
        $data['underline2']   = isset(json_decode($info['underline2'],true)[$p])?json_decode($info['underline2'],true)[$p]:'';
        $data['ziti2']        = isset(json_decode($info['ziti2'],true)[$p])?json_decode($info['ziti2'],true)[$p]:'';      //中间字体
        $data['zihao2']       = isset(json_decode($info['zihao2'],true)[$p])?json_decode($info['zihao2'],true)[$p]:'';     //中间字号
        $data['jianju2']      = isset(json_decode($info['jianju2'],true)[$p])?json_decode($info['jianju2'],true)[$p]:'';    //中间间距
        $data['color2']       = isset(json_decode($info['color2'],true)[$p])?json_decode($info['color2'],true)[$p]:'';     //中间颜色
        $data['yangshi2']     = isset(json_decode($info['yangshi2'],true)[$p])?json_decode($info['yangshi2'],true)[$p]:'';   //中间样式
        $data['text3']        = isset(json_decode($info['text3'],true)[$p])?json_decode($info['text3'],true)[$p]:'';    //图片下边文字
        $data['left3']        = isset(json_decode($info['left3'],true)[$p])?json_decode($info['left3'],true)[$p]:'';
        $data['top3']         = isset(json_decode($info['top3'],true)[$p])?json_decode($info['top3'],true)[$p]:'';
        $data['underline3']   = isset(json_decode($info['underline3'],true)[$p])?json_decode($info['underline3'],true)[$p]:'';
        $data['ziti3']        = isset(json_decode($info['ziti3'],true)[$p])?json_decode($info['ziti3'],true)[$p]:'';      //下边字体
        $data['zihao3']       = isset(json_decode($info['zihao3'],true)[$p])?json_decode($info['zihao3'],true)[$p]:'';     //下边字号
        $data['jianju3']      = isset(json_decode($info['jianju3'],true)[$p])?json_decode($info['jianju3'],true)[$p]:'';    //下边间距
        $data['color3']       = isset(json_decode($info['color3'],true)[$p])?json_decode($info['color3'],true)[$p]:'';     //下边颜色
        $data['yangshi3']     = isset(json_decode($info['yangshi3'],true)[$p])?json_decode($info['yangshi3'],true)[$p]:'';   //下边样式

        $data['xiabiao']    =  count(json_decode($info['img'],true));
       return json(['status'=>1,'msg'=>'检索','data'=>$data]);
    }

    //获取中心，课时信息
    public function getinfo(){
        $centre_id = $this ->token_data['centre_id'];
        //中心信息
        $centre = db('wx_centre')->where('centre_id',$centre_id)->field('centre_id,centre,site,c_phone')->find();
        //课时信息
        $goods = db('crm_goods')
            ->where(['centre_id'=>$centre_id,'l_id'=>10,'item_id'=>1,'status'=>1])
            ->field('s_id,s_name,price,k_shu')
            ->select();
        foreach($goods as $k=>$v){
            if(empty($v['k_shu'])){
                $goods[$k]['k_shu'] =0;
            }else{
                $goods[$k]['k_shu'] = (int)$v['k_shu'];
            }
        }
        $data = [
            'centre'    => $centre,
            'goods'     => $goods
        ];
        return json(['stauts'=>1,'msg'=>'检索成功','data'=>$data]);//返回结果信息
    }
    //添加课时包
    public function add_goods($g_data)
    {
        if(empty($g_data['s_name']) || empty($g_data['k_shu'])){
            return '';
        }
        $g_data['l_id'] = 10;
        $sid = Db::table('crm_goods')->insertGetId($g_data);
        return $sid;
    }
    //创建活动
    public function set_huodong(){

        $params = input('post.');
        if(!isset($params['Data'])){
            return json(['status'=>3,'msg'=>'参数不存在']);
        }
        $request_data = $params['Data'][0];
        $type = $request_data['type'];        //状态  1保存  2发布
        if(empty($type)){
            return json(['status'=>3,'msg'=>'参数错误']);
        }
        $template_id = $request_data['template_id'];      //模板id
        $tp_type         = $request_data['tp_type'];   //模板类型  0创建  1主题活动 2节日活动
        if(empty($template_id) || !isset($tp_type)){
            return json(['status'=>3,'msg'=>'参数错误']);
        }
        $hd_id                   = isset($request_data['hd_id']) ? $request_data['hd_id'] : '';           //活动id

        $data['template_id']     = $template_id;      //模板id
        $data['hd_type']         = $request_data['hd_type'];         //活动类型
        $data['jc_name']         = $request_data['jc_name'];         //基础-活动名称
        $data['jc_describe']     = $request_data['jc_describe'];     //基础-活动描述
        $data['linkhref']        = $request_data['linkhref'];     //基础-了解更多
        $data['guize']           = $request_data['guize'];           //活动规则
        $data['bm_start_time']   =  $request_data['bm_start_time'];   //报名开始日期
        $data['bm_end_time']     =  $request_data['bm_end_time'];     //报名结束日期
        $data['hd_start_time']   =  $request_data['hd_start_time'];    //活动开始日期
        $data['hd_end_time']     =  $request_data['hd_end_time'];    //活动结束日期
        $data['hd_site']         =  $request_data['hd_site'];         //活动地址
        $data['xiaohao']         =  $request_data['xiaohao'];         //耗课数
        $data['s_price']         =  $request_data['s_price'];         //课时包价格
        $data['is_free']         =  $request_data['is_free'];         //类型 1免费赠送 2购买
        $data['centre_id']       =  $request_data['centre_id'];          //中心id
        $data['c_phone']         =  $request_data['c_phone'];          //联系电话
        $data['c_site']          =  $request_data['c_site'];           //中心地址
        //获取课时包信息
        $g_data['s_name'] =   $request_data['s_name'];
        $g_data['k_shu'] =   $request_data['k_shu'];
        $g_data['price'] =   $request_data['s_price'];
        $g_data['centre_id'] =   $data['centre_id'] ;
        $data['s_id'] = $this -> add_goods($g_data);
        if($data['is_free'] ==2){
            if($data['s_price'] <=0){
                return json(['status'=>3,'msg'=>'课时包价格必须大于0']);
            }
            if(empty($data['s_id'])){
                return json(['status'=>3,'msg'=>'课时包必须填写']);
            }
        }
        // 字段验证
        $rule = [
            'hd_type'        => 'require',
            'jc_name'        => 'require',
            'jc_describe'    => 'require',
            'hd_site'        => 'require',
            'is_free'        => 'require',
            'centre_id'      => 'require',
            'c_phone'        => 'require',
            'c_site'         => 'require',
            'guize'          => 'require'
        ];
        $msg = [
            'hd_type.require'           => '活动类型不能为空',
            'jc_name.require'           => '活动名称不能为空',
            'jc_describe.require'       => '活动描述不能为空',
            'hd_site.require'           => '活动地址不能为空',
            'is_free.require'           => '类型不能为空',
            'centre_id.require'         => '中心名称不能为空',
            'c_phone.require'           => '联系电话不能为空',
            'c_site.require'            => '中心地址不能为空',
            'guize.require'             => '活动规则不能为空'
        ];
        $validate = new Validate($rule,$msg);
        if(!$validate->check($data)){
            return json(['status'=>3,'msg'=>$validate->getError()]);
        }

        $data['create_user'] = $this->token_data['user_id'];     //创建人id
        if($type ==1){       //保存
            $data['fb_type'] = 1;       //未发布
        }else{
          $data['fb_type'] =2;  //创建
        }
        $data['title']          = $request_data['title'];        //分享-活动标题
        if(empty($data['title'])) {
            $data['title']  =  $data['jc_name']     ;
        }
        $data['fx_describe']     = isset($request_data['fx_describe']) ? $request_data['fx_describe'] : '';    //分享-活动描述
        $data['img']             = $request_data['img'];         //分享图片
        $data['red_type']        = isset($request_data['red_type']) ? $request_data['red_type'] : '';       //红包配置
        $data['red_total']       = isset($request_data['red_total']) ? $request_data['red_total'] : '';       //红包总金额
        $data['red_num']         = isset($request_data['red_num']) ? $request_data['red_num'] : '';         //红包个数
        $data['discount']        = isset($request_data['discount']) ? $request_data['discount'] : '';        //会员折扣
        $data['integral_type1']  = isset($request_data['integral_type1']) ?  $request_data['integral_type1'] : '';    //分享后每增加一次点击可为分享者增加积分
        $data['integral_type2']  = isset($request_data['integral_type2']) ? $request_data['integral_type2'] : '';    //每增加一个到访(试听)客增加积分
        $data['integral_type3']  = isset($request_data['integral_type3']) ? $request_data['integral_type3'] : '';    //没增加一个留言信息增加积分
        $data['integral_type4']  = isset($request_data['integral_type4']) ? $request_data['integral_type4'] : '';    //签订合同增加积分
        $good_rules              = isset($request_data['rules']) ? $request_data['rules']:[];           //获取积分商城设置数组
        $data['red_single']      = isset($request_data['red_single']) ? $request_data['red_single']:'';      //单个红包金额
        $data['red_balance'] = $data['red_total'];  //红包余额  首次余额与总额相等
        //判断当前红包总金额 是否大于当前中心红包余额
        $red_type = $data['red_type'];  //获取红包类型
        $red_total = $data['red_total'];    //红包总金额
        if($red_type == 2){ //如果为固定红包
            $red_total = $data['red_single'] * $data['red_num'];    //固定红包  总金额为单个金额X红包个数
        }
        $red_remaining = Db::table('a_money')->where(['centre_id'=>$data['centre_id']])->value('red_remaining');
        if(is_null($red_remaining)){
            $red_remaining = 0;
        }
        // dump($red_total);
        // dump($red_remaining);die;
        if($red_total > $red_remaining){    //如果红包总金额大于当前中心红包充值余额
            return json(['status'=>3,'msg'=>'当前设置红包总金额大于中心红包充值金额,请重新设置或充值.']);
        }
        //会员折扣设置 判断
        $discount = $data['discount'];
        if($discount){  //如果填写了会员折扣获取 否则等于0   0 代表不折扣
            if($discount >= 0 && $discount < 10){   //折扣大于等于0 小于10 否则非法
                $data['discount'] = $discount;
            }else{
                return json(['status'=>3,'msg'=>'会员折扣设置失败,请检查格式是否符合要求']);
            }
        }else{
            $data['discount']=0;
        }
        if($data['red_type'] == 1){ //如果为随机红包  生成随机红包数组
            $min = 0.3;   //最小金额
            $total = (int)$data['red_total'];
            $num = (int)$data['red_num'];
            //判断红包金额是否满足最小金额
            $need = $min*$num;  //  满足最小金额情况下当前红包数量需要多少金额
            if($need > $total){
                return json(['status'=>3,'msg'=>'当前设定红包金额不足,单个随机红包金额最小为0.3元']);
            }
            $max = ceil($total/$num)+1;   //红包最大金额 进一取整(红包总额/红包总数)
            $argv = [$total,$num,$min,$max];
            $rand = new Redrand();
            $data['red_rand'] = $rand->gomain($argv);
            $data['red_rand'] = implode(',',$data['red_rand']); //逗号分割随机红包数组
        }else if($data['red_type'] == 2){
            $data['red_rand'] = '';
            $data['red_total'] = $data['red_single'] * $data['red_num'];
            $data['red_balance'] = $data['red_total'];  //红包余额  首次余额与总额相等
        }else{
            $data['red_rand'] = '';
            $data['red_total'] = 0;
            $data['red_balance'] = $data['red_total'];  //红包余额  首次余额与总额相等
        }
        $share_img = $data['img'];       //分享/标题图片   base64数组

        if(!empty($share_img)){
            if(!is_array($share_img)){
                $share_img = [$share_img];
            }
            if(preg_match('/.*(\.png|\.jpg|\.jpeg|\.gif)$/',implode('',$share_img))){
                $data['img'] = implode('',$share_img);
            }else{
                $data['img'] = $this ->decode_base64_update($share_img,$data['centre_id'],'shareimg');//判断是否存在base64图片编码数组 存在执行转换 不存在存空
            }
        }else{
            $data['img']   ='';
        }

        //创建时间
        $data['create_time'] = date("Y-m-d H:i:s");
        if(empty($hd_id)){
            $hid = Db::table('crm_huodong')->insertGetId($data);    //执行添加 并获取添加成功数据的ID
        }else{
            if($tp_type!=0){
                $hid = Db::table('crm_huodong')->insertGetId($data);    //执行添加 并获取添加成功数据的ID
            }else{
                $hid = Db::table('crm_huodong')->where('hd_id',$hd_id)->update($data);    //执行修改 并获取修改成功数据的ID
            }

        }
        //中心
        $centre_id = $data['centre_id'];
        $hd_qrcode ='';
        $url       = '';
        if(false !== $hid){
            if(empty($hd_id)){
                if($type==2){
                    $hd_qrcode = $this -> set_qrcode($hid,$data['centre_id']);
                    Db::table('crm_huodong')->where(['hd_id'=>$hid])->update(['hd_qrcode'=>$hd_qrcode]);
                    $url = "https://wx.codeclassroom.org/activity/index.php?parent_openid=0&centre_id=$centre_id&hdid=$hid";
                }
                $res = !empty($good_rules) ? $this -> set_integral_mall($good_rules,$hid,$data['centre_id']) : '无商品信息' ;    //判断是否存在积分商城商品信息 存在则执行添加兑换规则
            }else{
                if($type==2){
                    if($tp_type !=0){
                        $hd_qrcode = $this -> set_qrcode($hid,$data['centre_id']);
                        Db::table('crm_huodong')->where(['hd_id'=>$hid])->update(['hd_qrcode'=>$hd_qrcode]);
                        $url = "https://wx.codeclassroom.org/activity/index.php?parent_openid=0&centre_id=$centre_id&hdid=$hid";
                    }else{
                        $hd_qrcode =  Db::table('crm_huodong')->where(['hd_id'=>$hd_id])->value('hd_qrcode');
                        if(empty($hd_qrcode)){
                            $hd_qrcode = $this -> set_qrcode($hd_id,$data['centre_id']);
                            Db::table('crm_huodong')->where(['hd_id'=>$hd_id])->update(['hd_qrcode'=>$hd_qrcode]);
                        }
                        $url = "https://wx.codeclassroom.org/activity/index.php?parent_openid=0&centre_id=$centre_id&hdid=$hd_id";
                    }

                }
                $res = !empty($good_rules) ? $this -> set_integral_mall($good_rules,$hd_id,$data['centre_id'],2) : '无商品信息' ;    //判断是否存在积分商城商品信息 存在则执行添加兑换规则
            }

            if(false !== $res){
                if($type == 1){
                    $arr = [
                        'jc_name'   => $data['jc_name']  //活动名称
                    ];
                    return json(['status'=>1,'msg'=>'保存成功','data'=>$arr]);
                }else{
                    $arr = [
                        'jc_name'   => $data['jc_name'],  //活动名称
                        'hd_qrcode' => $hd_qrcode ,       //二维码图片
                        'url'       => $url
                    ];
                    return json(['status'=>2,'msg'=>'发布成功','data'=>$arr]);
                }

            }else{
                return json(['status'=>3,'msg'=>'设置失败,请检查重试']);
            }
            // $res = $this -> set_integral_mall($rules,$hd_id,$centre_id);   //执行添加积分商城兑换规则 参数 $rules 积分商城设置数组 $hd_id 活动设置成功返回的ID

        }else{
            return json(['status'=>3,'msg'=>'设置失败,请检查重试']);
        }
    }

    //编辑信息
    public function get_huodong()
    {
        $hd_id = input('post.hd_id');   //活动id
        if(empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        //获取结果集
        $data = db('crm_huodong')
            ->alias('a')
            ->join('crm_goods b','a.s_id=b.s_id','left')
            ->join('wx_centre c','a.centre_id=c.centre_id','left')
            ->where('a.hd_id',$hd_id)
            ->field('a.*,b.s_id,b.s_name,b.price,b.k_shu,c.centre')
            ->find();
        if(empty($data)){
            return json(['status'=>2,'msg'=>'未检索到数据']);
        }
        if($data['discount'] ==0){
            $data['discount'] ='';
        }
        if($data['centre_id']==''){
            $centre_id = $this ->token_data['centre_id'];
            $centre = db('wx_centre')->where('centre_id',$centre_id)->field('centre_id,centre,site,c_phone')->find();
            $data['centre_id'] = $centre_id;
            $data['c_phone'] = $centre['c_phone'];
            $data['c_site']  = $centre['site'];
            $data['centre'] = $centre['centre'];
            $data['hd_site'] = $centre['site'];
        }

        return json(['stauts'=>1,'msg'=>'检索成功','data'=>$data]);//返回结果信息
    }

    //禁用
    public function jinyong(){
        $hd_id = input('post.hd_id');     //活动id
        if(empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $data['fb_type'] = 3;
        $res = db('crm_huodong')->where('hd_id',$hd_id)->update($data);
        if($res !==false){
            return json(['status'=>1,'msg'=>'禁用成功']);
        }else{
            return json(['status'=>1,'msg'=>'禁用失败']);
        }
    }

    //开启
    public function kaiqi(){
        $hd_id = input('post.hd_id');     //活动id
        if(empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $data['fb_type'] = 2;
        $res = db('crm_huodong')->where('hd_id',$hd_id)->update($data);
        if($res !==false){
            return json(['status'=>1,'msg'=>'启用成功']);
        }else{
            return json(['status'=>1,'msg'=>'启用失败']);
        }
    }

    //单个活动统计接口
    public function deta(){
        $hd_id=input('post.hd_id');
        if(empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $token = $this ->  token_data;
        //中心
        $centre_id = $token['centre_id'];
        $arr=Db::table('crm_huodong')->where(['hd_id'=>$hd_id,'centre_id'=>$centre_id])->field('hd_id,hd_type,jc_name,hd_start_time,hd_end_time,xiaohao,s_price,yuy_id,qian_id,share_num,red_total,red_balance,red_num')->find();
//        dd(Db::getlastsql());
        if(!empty($arr)){
            //预约人数
            if(empty($arr['yuy_id'])){
                $arr['yuy_id'] =0;
            }else{
                $arr['yuy_id'] = count(explode(',',$arr['yuy_id']));
            }
            //签到人数
            if(empty($arr['qian_id'])){
                $arr['qian_id'] =0;
            }else{
                $arr['qian_id'] = count(explode(',',$arr['qian_id']));
            }
            $arr['xiaohao'] = (int)$arr['xiaohao'] * (int)$arr['qian_id'];   //耗课时
            $arr['s_price'] = (int)$arr['s_price'] * (int)$arr['qian_id'];   //耗课额
        }
        $status=Db::table('hd_red_record')->where("centre_id = '{$centre_id}' and (status=0 or status=1 or status=2)")->select();

        if(!empty($status)){
            $url='https://wx.gymbaby.org/pay/cxhb.php';
            foreach ($status as $key => $value) {
                $centre['mch_billno']=$value['mch_billno'];
                $a=request_post($url,$centre);
                $b=json_decode($a,true);
                $data['status']=isset($b['status']) ? $b['status'] : $value['status'];
                $data['send_listid']=isset($b['send_listid']) ? $b['send_listid'] : '';
                Db::table('hd_red_record')->where("mch_billno='{$b['mch_billno']}'")->update($data);
            }
        }
        $zhi=Db::table('hd_red_record')->where("zt=1 and (status=4 or status=6)")->select();
        foreach ($zhi as $key => $value) {
            Db::table('a_money')->where("centre_id='$centre_id'")->setInc('red_remaining',$value['money']);
            $dd['zt']=2;//状态改成已退款
            Db::table('hd_red_record')->where("id='{$value['id']}'")->where("centre_id='$centre_id'")->update($dd);
        }

        $arr['red']=Db::table('hd_red_record')->where("hd_id='$hd_id' and status=5")->where('centre_id',$centre_id)->count();//红包领取数
        $arr['browse']=Db::table('hd_access_record')->where("hd_id='$hd_id' and cz_name='进入活动' ")->where('centre_id',$centre_id)->count();//浏览量
        $arr['zrs']=Db::table('hd_user')->where("hd_id='$hd_id'")->where('centre_id',$centre_id)->count();//活动总人数
        $integral=Db::table('hd_user')->where("hd_id='$hd_id'")->where('centre_id',$centre_id)->sum('integral');
        $arr['yhl']=Db::table('hd_user')->where("hd_id='$hd_id'")->where('centre_id',$centre_id)->sum('use_integral');//用户已用积分
        $arr['zjf'] = $integral+$arr['yhl'];        //用户总积分

        $arr['red_sheng'] = $arr['red_num'] - $arr['red'];
        if(empty($arr['zjf'])){
            $arr['zjf']=0;
        }
        if(empty($arr['yhl'])){
            $arr['yhl']=0;
        }
        return json($arr);
    }

    //单个活动统计接口下边
    public function gethd(){
        $token = $this -> token_data;
        $centre_id=$token['centre_id'];
        $hd_id=input('post.hd_id');
        if(empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $search=input('post.search');
        $status=input('post.status');//1是全部，2是会员，3是潜客，4已报名
        if(empty($status)){
            $status=1;
        }
        $parms = input('post.p');
        $page = (isset($parms) && !empty($parms)) ? $parms : 1;//当前页数
        $yuy_id = db('crm_huodong')->where(['hd_id'=>$hd_id,'status'=>1,'is_kai'=>1])->value('yuy_id');
        $where = '1 = 1';
        switch ($status){
            case 1:
                $where="hd_user.hd_id='$hd_id' and hd_user.centre_id='$centre_id'";
                break;
            case 2:
                $where="hd_user.hd_id='$hd_id' and wx_user.vip=1 and hd_user.centre_id='$centre_id' and hd_user.open_id !=''";
                break;
            case 3:
                $where="hd_user.hd_id='$hd_id' and wx_user.vip = 0 and hd_user.centre_id='$centre_id'";
                break;
            case 4:
                if(empty($yuy_id)){
                    $where="hd_user.hd_id='$hd_id' and hd_user.centre_id='$centre_id'";
                }else{
                    $where="hd_user.hd_id='$hd_id' and wx_user.user_id in ($yuy_id) and hd_user.centre_id='$centre_id'";
                }

        }
        if(!empty($search)){
            $where.=" and (hd_user.user_name like '%$search%' or hd_user.real_name like '%$search%')";
        }

        $num=Db::table('hd_user')
            ->join('hd_red_record',"hd_user.open_id=hd_red_record.openid and hd_red_record.hd_id='{$hd_id}'",'left')
            ->join("wx_user","hd_user.open_id=wx_user.openid",'left')
            ->where($where)
            ->field('hd_user.user_name,hd_red_record.money')
            // ->fetchSql()
            ->count();
            // dump($num);die;
        $pageone=20;//每页数据
        $pagetotal=ceil($num/$pageone);
        $pyl=($page-1)*$pageone;//偏移量

        $arr=Db::table('hd_user')
            ->join('hd_red_record',"hd_user.open_id=hd_red_record.openid and hd_red_record.hd_id='{$hd_id}'",'left')
            ->join("wx_user","hd_user.open_id=wx_user.openid",'left')
            ->where($where)
            ->field('hd_user.id,hd_user.open_id,hd_user.user_name,hd_user.phone,hd_user.real_name,hd_user.integral,hd_user.use_integral,hd_user.share_num,hd_red_record.money,hd_red_record.status')
            ->order('hd_user.id desc')
            ->limit($pyl,$pageone)
            ->select();
        foreach ($arr as $key => $value) {
            if($value['status']!=5){
                $arr[$key]['money']=0;
            }else{
                $arr[$key]['money']=$value['money'];
            }
            $arr[$key]['zjf'] = $value['integral']+$value['use_integral'];
            $arr[$key]['llzs']=Db::table('hd_access_record')->where("openid='{$value['open_id']}'and hd_id='$hd_id' and cz_name='进入活动'")->count();
            $order = Db::table('crm_hd_order')->where("openid='{$value['open_id']}'and hd_id='$hd_id' and status !=0")->field('create_time,price')->find();
            //付款时间
            $arr[$key]['fktime'] = $order['create_time'];
            //付款金额
            $arr[$key]['price']  = $order['price'];
        }
        $map = [];
        $map['page'] = $page ? $page : 1;
        $map['pagetotal'] = $pagetotal ? $pagetotal : 0;
        $map['num'] = $num ? $num : 0;
        $map['pageone'] = $pageone ? $pageone : 10;

        $data = ['map'=>$map,'data'=>$arr];
        return json($data);
    }

    //预约
    public function yylist(){
        $hd_id = input('post.hd_id');     //活动id
        $set = input('post.set');        //搜索
        if(empty($hd_id) || empty($set)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $centre_id = $this-> token_data['centre_id'];
        //活动
        $hd = db('crm_huodong')->where(['hd_id'=>$hd_id,'status'=>1,'is_kai'=>1])->find();
        if($hd['hd_type'] !=4){
            return json(['status'=>2,'msg'=>'此活动不能预约']);
        }
        $where = [
            'yon'=>2,
            'status'=>1,
            'belong'=>$centre_id,
            'baobao_name|baobao_name2|name2|user_id'=>['like',"%{$set}%"]
        ];
        if(!empty($hd['yuy_id'])){
            $where['user_id'] = ['not in',$hd['yuy_id']];
        }
        $w = Db::table('wx_user')->where($where)
            ->select();
        if(empty($w)){
            return json(['status'=>2,'msg'=>'暂时没有数据']);
        }
        foreach ($w as $key => $val) {
            //计算月龄
            $f = get_month_diff($val['baobao_birthday']);
            $w[$key]['yueling'] = $f;
            $w[$key]['xiaohao'] = $hd['xiaohao'];
            $w[$key]['jc_name'] = $hd['jc_name'];
        }

        return json(['status'=>1,'msg'=>'检索成功','w'=>$w]);
    }

    //已预约列表
    public function yyylist(){
        $hd_id = input('post.hd_id');     //活动id
        if(empty($hd_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $page = input('post.p') ? input('post.p') : 1;  // 当前页
        $pageone = input('post.pageone') ? input('post.pageone') : 10; // 每页显示条数
        $centre_id = $this-> token_data['centre_id'];
        //活动
        $hd = db('crm_huodong')->where(['hd_id'=>$hd_id,'status'=>1,'is_kai'=>1])->find();
        if($hd['hd_type'] !=4){
            return json(['status'=>2,'msg'=>'此活动没有已预约列表']);
        }
        if(empty($hd['yuy_id'])){
            return json(['status'=>2,'msg'=>'暂时没有数据']);
        }
        $y =  Db::table('wx_user')->where([
            'yon'=>2,
            'status'=>1,
            'belong'=>$centre_id])
            ->where('user_id','in',$hd['yuy_id'])
            ->select();
        foreach ($y as $key => $val) {
            //计算月龄
            $f = get_month_diff($val['baobao_birthday']);
            $y[$key]['yueling'] = $f;
            $y[$key]['xiaohao'] = $hd['xiaohao'];
            $y[$key]['jc_name'] = $hd['jc_name'];
        }
        //分页
        $counts = count($y);
        $pagetotal = ceil($counts/$pageone);
        $start = ($page-1)*$pageone;
        $data = array_slice($y,$start,$pageone);
        $map = [
            'page' => $page,
            'pageone' => $pageone,
            'pagetotal' => $pagetotal,
            'count'	=> $counts
        ];
        return json(['status'=>1,'msg'=>'检索成功','y'=>$data,'map'=>$map]);

    }

    //点击预约
    public function yuyue(){
        $hd_id = input('post.hd_id');
        $user_id = input('post.user_id');
        if(empty($hd_id)|| empty($user_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $huodong = Db::table('crm_huodong')->where("hd_id='$hd_id'")->find();
        $id = $huodong['yuy_id'];
        $d = explode(",", $id);
        if (in_array($user_id, $d)) {
            return json(['status'=>3,'msg'=>'已预约成功,不可重复预约']);
        } else {
            $wxpush = new Wxpush();
            if ($id != null) {
                $data['yuy_id'] = $id . "," . $user_id;
            } else {
                $data['yuy_id'] = $user_id;
            }
            $res = Db::table('crm_huodong')->where("hd_id='$hd_id'")->update($data);
            if (false !== $res) {
                $wxpush->hd_push($user_id,$huodong);
                return json(['status'=>1,'msg'=>'预约成功']);
            }else{
                return json(['status'=>2,'msg'=>'预约失败']);
            }
        }
    }

    //取消预约
    public function quxiaoyy()
    {
        $user_id = input('post.user_id');
        $hd_id = input('post.hd_id');
        if(empty($hd_id)|| empty($user_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        $id =  Db::table('crm_huodong')->where("hd_id='$hd_id'")->value('yuy_id');
        $d = explode(",", $id);
        $aa = [];
        foreach ($d as $key => $value) {
            if ($user_id != $value) {
                $aa[] = $value;
            }
        }
        $data['yuy_id'] = implode(",", $aa);
        $res = Db::table('crm_huodong')->where("hd_id='$hd_id'")->update($data);
        if (false !== $res) {
            return json(['status'=>1,'msg'=>'取消预约成功']);
        }else{
            return json(['status'=>2,'msg'=>'取消预约失败']);
        }
    }

    //签到列表
    public function qiandaolist(){
        //线下活动
        $time = date("Y-m-d");    //当天时间
        $centre_id = $this -> token_data['centre_id'];    //中心id
        $hd_id = input('post.hd_id');    //活动id
        $set   = input('post.set');      //搜索
        $where = [];
        if(!empty($hd_id) || !empty($set)){
            $where = [
                'yon'=>2,
                'status'=>1,
                'vip'=>1,
                'baobao_name|baobao_name2|name2'=>['like',"%{$set}%"]
            ];
        }
        //获取结果集
        $arr = Db::table("crm_huodong")
            ->alias('a')
            ->join('crm_template b','a.template_id=b.id','left')
            ->where("b.baocun=2 and a.hd_type=4 and a.status=1 and a.is_kai=1 and a.fb_type=2 and a.hd_start_time='$time' and a.centre_id='$centre_id'")
            ->field('a.hd_id,a.template_id,a.hd_type,a.jc_name,a.jc_describe,a.hd_start_time,a.hd_end_time,a.xiaohao,a.yuy_id,a.qian_id')
            ->select();
        if(empty($arr)){
            return json(['status'=>2,'msg'=>'未检索到相关活动']);
        }
        //活动类型
        $hd_type = ['1'=>'砍价活动','2'=>'拼团活动','3'=>'促单(线上)','4'=>'促单(线上+线下)'];
        //处理数据
        foreach ($arr as $key => $value) {
            $arr[$key]['hd_type'] = $hd_type[$value['hd_type']];
            $d = explode(",", $value['yuy_id']);
            $c = explode(",", $value['qian_id']);
            //签到用户列表
            $arr[$key]['user'] = [];
            foreach ($d as $k => $val) {
                if(empty($val)){
                    continue;
                }
                $user = Db::table("wx_user")
                    ->where(['user_id'=>$val,'belong'=>$centre_id])
                    ->where($where)
                    ->field('user_id,baobao_name,baobao_birthday,phone1,phone2,phone3,phone4,phone5,phone6')
                    ->find();
                if(!empty($user)){
                    //计算月龄
                    $user['yueling'] = get_month_diff($user['baobao_birthday']);
                    if(in_array($user['user_id'],$c)){
                        $user['qd_type'] = 1;  //已签到
                    }else{
                        $user['qd_type'] = 2;  //未签到
                    }
                    $arr[$key]['user'][] = $user;
                }
            }
            $arr[$key]['user_id'] = $d;
            $arr[$key]['qian_id'] = $c;
            $arr[$key]['set']    ='';
         }
        return json(['status'=>1,'msg'=>'活动检索成功','data'=>$arr]);
    }

    //签到
    public function qiandao()
    {
        $hd_id = input('post.hd_id');
        $user_id = input('post.user_id');
        if(empty($hd_id) || empty($user_id)){
            return json(['status'=>2,'msg'=>'参数错误']);
        }
        //中心
        $centre_id = $this -> token_data['centre_id'];
        $arr = Db::table('crm_huodong')->where("hd_id='$hd_id'")->where('centre_id',$centre_id)->field('qian_id,xiaohao,hd_start_time')->find();
        $d = explode(",", $arr['qian_id']);
        if (in_array($user_id, $d)) {   //已签到  执行取消签到

            //默认查询编程云剩余课时
            $y_keshi = Db::table('wx_user')
                ->alias('a')
                ->join('crm_kjilu b','a.kahao = b.card_num and b.status = 1 and b.item_id = 1')
                ->where('a.user_id',$user_id)
                ->where('a.belong',$centre_id)
                ->order('b.jl_id desc')
                ->field('b.y_keshi,b.jl_id')
                ->find();

            $last_keshi = $y_keshi['y_keshi'] + $arr['xiaohao'];  //获取耗课后的剩余课时
            Db::table('crm_kjilu')->where('jl_id',$y_keshi['jl_id'])->update(['y_keshi' => $last_keshi]);    //更新剩余课时

            Db::table('crm_user_skjl')->where(['user_id'=>$user_id,'source'=>'活动课','create_time'=>$arr['hd_start_time']])->update(['status'=>0]);

            $qian_id = Db::table('crm_huodong')->where("hd_id='$hd_id'")->where('centre_id',$centre_id)->value('qian_id');
            $d = explode(",", $qian_id);
            $user = [];
            foreach ($d as $key => $value) {
                if ($user_id != $value) {
                    $user[] = $value;  //循环排除该用户
                }
            }
            $data['qian_id'] = implode(",", $user);  //数组转成字符
            $res = Db::table('crm_huodong')->where("hd_id='$hd_id'")->where('centre_id',$centre_id)->update($data);
            if (false !== $res) {
                //组装日志数据
                $logdata['syuserid'] = $user_id; //会员ID
                $logdata['jl_id'] = $y_keshi['jl_id']; //当前合约记录ID
                $logdata['user_id'] = $this -> token_data['user_id'];  //当前操作用户ID
                $logdata['centre_id'] = $this -> token_data['centre_id']; //中心ID
                $logdata['before_keshi'] = $y_keshi['y_keshi'];   //修改前课时
                $logdata['last_keshi'] = $last_keshi;   //修改后课时
                $logdata['source'] = '取消活动课扣课,活动ID' . $hd_id . ',活动时间' . $arr['hd_start_time'] .'扣合约金额ID'.$y_keshi['jl_id'];   //操作记录内容
                $logdata['controller_name'] = \request()->controller();  //操作的控制器名称
                $logdata['function_name'] = \request()->action();    //操作的方法名称
                //执行记录LOG日志
                $rs = Db::table('crm_keshi_log')->insert($logdata);
                if($rs !==false){
                    return json(['status'=>1,'msg'=>'取消签到成功']);
                }else{
                    return json(['status'=>2,'msg'=>'取消签到失败']);
                }
            }else{
                return json(['status'=>2,'msg'=>'取消签到失败']);
            }
        } else {    //执行签到
            //默认查询编程云剩余课时
            $y_keshi = Db::table('wx_user')
                ->alias('a')
                ->join('crm_kjilu b','a.kahao = b.card_num and b.status = 1 and b.item_id = 1')
                ->where('a.user_id',$user_id)
                ->where('a.belong',$centre_id)
                ->order('b.jl_id desc')
                ->field('b.y_keshi,b.jl_id,b.avg_price')
                ->find();
            if (empty($y_keshi['y_keshi']) || $y_keshi['y_keshi'] < $arr['xiaohao']) {
                return json(['status'=>3,'msg'=>'剩余课时不足']);
            }
            $last_keshi = $y_keshi['y_keshi'] - $arr['xiaohao'];  //获取耗课后的剩余课时
            Db::table('crm_kjilu')->where('jl_id',$y_keshi['jl_id'])->update(['y_keshi' => $last_keshi]);    //更新剩余课时

            //添加记录
            $w['user_id'] = $user_id;
            $w['pk_id'] = 0;
            $w['xiaohao'] = $arr['xiaohao'];
            $w['status'] = 2;   //已扣课
            $w['jk_status'] = 2;    //已结课
            $w['create_time'] = $arr['hd_start_time'];
            $w['centre_id'] = $this-> token_data['centre_id'];
            $w['create_name'] = $this->token_data['user_id'];
            $w['source'] = "活动课";
            $use_jl_info = find_contract($user_id,$this -> token_data['centre_id'],1);    //获取扣课合约
            $w['ke_price'] = $use_jl_info['avg_price'];
            $w['kk_jlid'] = $use_jl_info['jl_id'];
            $w['item_id'] = 1;
            Db::table('crm_user_skjl')->insert($w);
            //扣除使用合约金额
            $money = $w['xiaohao'] * $w['ke_price'];
            modify_now_money($use_jl_info['jl_id'],'sub',$money);


            $qian_id = Db::table('crm_huodong')->where("hd_id='$hd_id'")->value('qian_id');
            if ($qian_id != null) {
                $data['qian_id'] = $qian_id . "," . $user_id;
            } else {
                $data['qian_id'] = $user_id;
            }
            $res = Db::table('crm_huodong')->where("hd_id='$hd_id'")->where('centre_id',$centre_id)->update($data);
            if (false !== $res ) {
                //组装日志数据
                $logdata['syuserid'] = $user_id; //会员ID
                $logdata['jl_id'] = $use_jl_info['jl_id']; //当前合约记录ID
                $logdata['user_id'] = $this -> token_data['user_id'];  //当前操作用户ID
                $logdata['centre_id'] = $this -> token_data['centre_id']; //中心ID
                $logdata['before_keshi'] = $y_keshi['y_keshi'];   //修改前课时
                $logdata['last_keshi'] = $last_keshi;   //修改后课时
                $logdata['source'] = '活动课扣课,活动ID' . $hd_id . ',活动时间' . $arr['hd_start_time'] .'扣合约金额ID'.$use_jl_info['jl_id'];   //操作记录内容
                $logdata['controller_name'] = \request()->controller();  //操作的控制器名称
                $logdata['function_name'] = \request()->action();    //操作的方法名称
                //执行记录LOG日志
                $rs = Db::table('crm_keshi_log')->insert($logdata);
                if($rs !==false){
                    return json(['status'=>1,'msg'=>'签到成功']);
                }else{
                    return json(['status'=>2,'msg'=>'签到失败']);
                }
            }else{
                return json(['status'=>2,'msg'=>'签到失败']);
            }
        }
    }

    //个人信息列表
    public function userinfo()
    {
        $token = $this -> token_data;
        $uid = input('post.uid');   //获取当前查看用户ID
        $page = input('post.page') ? input('post.page') : 1;    //当前第几页
        $pageone=input('post.pageone') ? input('post.pageone') : 10;//每页数据
        //拼装返回条件参数
        $map = [
            'page'=>$page,
            'pageone'=>$pageone
        ];
        //中心
        $centre_id = $this->token_data['centre_id'];
        //获取当前个人信息
        $where_self = [
            'a.id'        => $uid,
            'a.centre_id' => $centre_id
        ];
        $userinfo = Db::table('hd_user')
            ->alias('a')
            ->field('a.*,b.jc_name as hd_title,c.REGION_NAME as province,d.REGION_NAME as city')
            ->join('crm_huodong b','a.hd_id = b.hd_id','left')
            ->join('region c','a.sheng = c.REGION_ID','left')
            ->join('region d','a.shi = d.REGION_ID','left')
            ->where($where_self)
            ->find();
        //定义直辖市数组
        $citys = ['北京市','上海市','天津市','重庆市'];
        if(in_array($userinfo['province'],$citys)){
            $userinfo['address'] = $userinfo['province'].$userinfo['address'];
        }else{
            $userinfo['address'] = $userinfo['province'].$userinfo['city'].$userinfo['address'];
        }
        if($userinfo['is_qianke'] == 1){
            $userinfo['baoming'] = "未报名";
        }else{
            $userinfo['baoming'] = "已报名";
        }
        $hd_id = $userinfo['hd_id'];
        $openid = $userinfo['open_id'];
        //获取当前活动参与总人数
        $people_total = Db::table('hd_user')->where(['hd_id'=>$hd_id])->where('centre_id',$centre_id)->count();
        //获取当前用户分享参与人数
        $share_total = Db::table('hd_user')->where(['hd_id'=>$hd_id,'parent_openid'=>$openid])->where('centre_id',$centre_id)->count();
        //获取当前活动领取红包总金额
        $red_total = Db::table('hd_red_record')->where(['hd_id'=>$hd_id])->where('centre_id',$centre_id)->sum('money');
        $red_total = sprintf('%.2f', (float)$red_total);
        //获取当前用户分享人领取红包总金额
        $share_red_total = Db::table('hd_user')->alias('a')
            ->join('hd_red_record b','a.open_id = b.openid','left')
            ->where(['a.hd_id'=>$hd_id,'a.parent_openid'=>$openid,'b.hd_id'=>$hd_id,'b.status'=>5,'a.centre_id'=>$centre_id])
            ->sum('b.money');
        $share_red_total = sprintf('%.2f', (float)$share_red_total);
        //填充数据
        $userinfo['people_total'] = $people_total ? $people_total : 0;  //活动参与总人数
        $userinfo['share_total'] = $share_total ? $share_total :0;    //当前用户分享参与人数
        $userinfo['red_total'] = $red_total ? $red_total : 0;    //当前活动领取红包总金额
        $userinfo['share_red_total'] = $share_red_total ? $share_red_total : 0;    //当前用户分享人领取红包总金额

        //获取当前用户分享人信息记录
        $where_share = [
            'a.hd_id'        => $hd_id,
            'a.parent_openid'=> $openid,
            'a.centre_id'    => $centre_id
        ];

        $Shares = Db::table('hd_user')->alias('a')
            ->field('a.id,a.user_name,a.open_id,a.real_name,a.phone,a.zjf,a.share_num,b.money,b.status')
            ->join("hd_red_record b","a.open_id = b.openid and b.hd_id = {$hd_id}",'left');

        $m = clone $Shares;

        $count = $Shares ->where($where_share)->count();   //获取数据总数
        $pagetotal=ceil($count/$pageone);   //总页数
        $map['pagetotal'] = $pagetotal; //返回参数 总页数
        $map['counttotal'] = $count; //返回参数 总数据条数
        $pyl=($page-1)*$pageone;//偏移量

        $Shares = $m;
        $share_users = $Shares  -> where($where_share)->order('a.id desc')->limit($pyl,$pageone) -> select();   //获取结果信息

        //循环结果 查找当前被分享用户浏览次数
        foreach($share_users as $k => $v){
            $where_browse = [
                'hd_id'     => $hd_id,
                'openid'    => $v['open_id'],
                'cz_name'   => '进入活动',
                'centre_id' => $centre_id
            ];
            $browse_num = Db::table('hd_access_record')->where($where_browse)->count();
            $share_users[$k]['browse_num'] = $browse_num;
            if($v['money'] && $v['status'] == 5){
                $share_users[$k]['money'] = $v['money'].'元';
            }else{
                $share_users[$k]['money'] = "未领取";
            }

        }
        $data = ['map'=>$map,'userinfo'=>$userinfo,'shareinfo'=>$share_users]; //拼装数据
        return json($data);//返回结果信息
    }

    //转换base64编码为图片并存储
    public function decode_base64_update($images,$centre_id,$type)
    {
        $request = Request::instance();
        $domain = $request->domain();   //获取当前域名
        $back_content = '';  //定义内容字段 用于循环拼接
        $dir = 'uploads/'.$centre_id.'/'; //组装存储目录
        if(!is_dir($dir)){
            mkdir($dir,0777);  //判断是否存在当前目录 不存在就创建 并赋予权限
        }
        //循环$contents 遍历执行解码 存储
        foreach($images as $k => $v){
            $base64_string= explode(',', $v); //截取data:image/png;base64, 这个逗号后的字符
            $data= base64_decode($base64_string[1]);//对截取后的字符使用base64_decode进行解码
            if(preg_match('/^(data:\s*image\/(\w+);base64)/', $base64_string[0], $result)){    //匹配当前图片格式 获取后缀
                $ext = '.'.$result[2];
            }else{
                $ext = '.jpg';
            }
            $filename= $type.time().$k.$ext;   //拼接文件名  时间戳+当前键+后缀
            $file = $dir.$filename; //拼接完整存储路径
            $res = file_put_contents($file, $data); //写入文件并保存
            if($res){   //如果文件写入成功 拼接内容字段 使用分号分隔
                $back_content .= $domain.'/'.$file;
            }
        }
        return $back_content;
    }

    // 积分商城设置模块
    public function set_integral_mall($rules,$hd_id,$centre_id,$type=1)
    {
        //判断是否传入规定参数
        if(!$hd_id || !$centre_id){
            return false;
        }
        //循环商品数组 组装数据
        $data = [];
        foreach($rules as $k => $v){
            if(!empty($v['gid'])){
                $data[$k]['hd_id'] = $hd_id;    //归属活动ID
                $data[$k]['centre_id'] = $centre_id;    //归属中心ID
                $data[$k]['gid'] = $v['gid'];   //商品ID
                $data[$k]['rules'] = isset($v['score']) ? $v['score'] :'';   //商品所需兑换积分
                $data[$k]['is_use'] = 1;    //默认启用状态
            }
            if(!empty($v['score'])){
                if(empty($v['gid'])){
                    return false;
                }
            }
        }
        if($type == 2){ //如果是更新数据  先删除旧的积分商品
            Db::table('crm_rule')->where('hd_id',$hd_id)->setField('status',0);
        }
        if(!empty($data)){
            $res = Db::table('crm_rule')->insertAll($data);
            return $res;
        }
    }

    //生成分享二维码
    public function set_qrcode($hd_id,$centre_id){
        $request = Request::instance();
        $domain = $request->domain();   //获取当前域名
        Loader::import('phpqrcode.phpqrcode');   //引入二维码文件
        // 生成二维码图片
        $object = new \QRcode();
        // $hd_id="39";
        // $centre_id='223';
        $parent_openid = 0;
        $url="https://wx.codeclassroom.org/activity/index.php?parent_openid=".$parent_openid."&centre_id=".$centre_id."&hdid=".$hd_id;//网址或者是文本内容
        $level=1;
        $size=8;
        $dir = 'uploads/'.$centre_id.'/'; //组装存储目录
        if(!is_dir($dir)) mkdir($dir,0700);  //判断是否存在当前目录 不存在就创建 并赋予权限
        $filename =$dir.'format'.$hd_id.time().'.jpeg'; //图片输出路径和文件名
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        $object->png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        $hd_qrcode = $domain.'/'.$filename;
        return $hd_qrcode;
    }
}