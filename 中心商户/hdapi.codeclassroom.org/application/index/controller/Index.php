<?php
namespace app\index\controller;
use think\Db;

class Index extends Comm
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }
    public function get_user_auth()
    {
        header("access-control-allow-origin:*");
        ob_clean();
        $user_id = $this -> token_data['user_id'];
        //中心
        $centre_id = $this -> token_data['centre_id'];
        $nav_id = Db::table('xueyuan_baoming')->where('user_id',$user_id)->where('centre_id',$centre_id)->value('nav_id');
        $auth = explode(',',$nav_id);
        //判断岗位
        // $gangwei = $this -> token_data['gangwei'];
        // if($gangwei == '总监' || $gangwei == '中心总监'){
        //     $ja_auth = 2;
        // }else if($gangwei == '老师'){
        //     $ja_auth = 1;
        // }else if($gangwei == '老师主管'){
        //     $ja_auth = 5;   //拥有总监的教案权限 其他与老师一致
        // }else if($gangwei == "前台" || $gangwei == '前台主管'){
        //     $ja_auth = 4;
        // }else{
        //     $ja_auth = 3;
        // }
        $centre = Db::table('wx_centre')->where(['centre_id'=>$this->token_data['centre_id']])->value('centre');
        $username = $this -> token_data['name'];
        $glf_auth = 2;
        return json(['status'=>1,'msg'=>'权限信息获取成功','auth'=>$auth,'glf_auth'=>$glf_auth,'username'=>$username,'centre_id'=>$centre_id,'centre'=>$centre]);
    }
}
