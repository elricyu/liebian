<?php
$appid = "wx739ca3a16733ba72";
$secret = "ba5a63c51d93c614892b3beea8ba7088";
$code = $_GET["code"];
$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $secret . '&code=' . $code . '&grant_type=authorization_code';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $get_token_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$res = curl_exec($ch);
curl_close($ch);
$json_obj = json_decode($res, true);

//根据openid和access_token查询用户信息
$access_token = $json_obj['access_token'];
$openid = $json_obj['openid'];
$get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $get_user_info_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$res = curl_exec($ch);
curl_close($ch);
//解析json  
$parent_openid = !empty($_GET['parent_openid']) ? $_GET['parent_openid'] : '';//分享人的openid
$centre_id = $_GET['centre_id'];  //中心ID
$hd_id = $_GET['hdid'] ? $_GET['hdid'] : '0';//活动id
$user_obj = json_decode($res, true);
$openid = $user_obj['openid'];  // 当前用户的openid

$img = $user_obj['headimgurl']; // 微信头像
$nickname = $user_obj['nickname'];  // 微信昵称
$expires = time() + 365 * 24 * 3600;
// 判断活动是否结束
$dns = 'mysql:host=101.201.146.27;dbname=liebian';
$pdo = new PDO($dns,'liebian','lb_20191229');
$sql6 = "select bm_start_time,bm_end_time,hd_start_time,hd_end_time,integral_type1,hd_type,fb_type from crm_huodong where hd_id='$hd_id' and status = 1";
$re1 = $pdo->query($sql6);
$ar1 = $re1->fetch(PDO::FETCH_ASSOC);
if ($ar1['fb_type'] == 3) {
    echo "<script>alert('活动已下线')</script>";
    die;
}
if (empty($ar1)) {
    echo "<script>alert('活动已结束')</script>";
    die;
}
$time = date("Y-m-d");

if ($ar1['hd_type'] == 4) {
    // echo 111;die;
    if ($ar1['bm_end_time'] < $time) {
        echo "<script>alert('活动已结束，结束时间是" . $ar1['bm_end_time'] . "')</script>";
        die;
    }
    if ($ar1['bm_start_time'] > $time) {
        echo "<script>alert('活动未开始，开始时间是" . $ar1['bm_start_time'] . "')</script>";
        die;
    }
} else {
    // echo 222;die;
    if ($ar1['hd_start_time'] > $time) {
        echo "<script>alert('活动未开始，开始时间是" . $ar1['hd_start_time'] . "')</script>";
        die;
    }
    if ($ar1['hd_end_time'] < $time) {
        echo "<script>alert('活动已结束，结束时间是" . $ar1['hd_end_time'] . "')</script>";
        die;
    }
}

//判断链接是否为自己分享出去的
$sql_openid = "select parentopenid from hd_access_record where openid='$openid' and hd_id='$hd_id'";
$ac_info = $pdo -> query($sql_openid)->fetch(PDO::FETCH_ASSOC);
$tag = true;   //定义标志 是否有浏览记录
if(empty($ac_info)){
    $old_parent_openid = '';
    $tag = false;   //没有浏览记录
}else{
    $old_parent_openid = $ac_info['parentopenid'];
}
if(($openid == $parent_openid || ($parent_openid != $old_parent_openid && $tag)) && !empty($openid)){
    $parent_openid = $old_parent_openid;
}
//访问链接记录日志
$cz_time=date('H:i');
$sql_log="insert into hd_access_record(openid,centre_id,parentopenid,hd_id,cz_name,cz_time)values('$openid','$centre_id','$parent_openid','$hd_id','进入活动','$cz_time')";
$pdo -> exec($sql_log);
//活动正常进行时 管理活动cookie
setcookie("openid", '', time() - 3600, '/');
setcookie("parentopenid", '', time() - 3600, '/');
setcookie("centre_id", '', time() - 3600, '/');
setcookie("hd_id", '', time() - 3600, '/');
setcookie("hd_id", '', time() - 3600);
setcookie("headimg", '', time() - 3600, '/');
setcookie("openid", $openid, 0, '/');
setcookie("parentopenid", $parent_openid, 0, '/');
setcookie("centre_id", $centre_id, 0, '/');
setcookie("hd_id", $hd_id, 0, '/');
setcookie("headimg", $img, 0, '/');
function filterNickname($nickname)
{
    $nickname = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $nickname);
    $nickname = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $nickname);
    $nickname = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $nickname);
    $nickname = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $nickname);
    $nickname = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $nickname);
    $nickname = str_replace(array('"', '\''), '', $nickname);
    return addslashes(trim($nickname));
}

$nickname = filterNickname($nickname);

//查询是否存在这个用户
$sql5 = "select * from hd_user where open_id='$openid' and hd_id='$hd_id'";
$ress = $pdo->query($sql5);
$arrr = $ress->fetch(PDO::FETCH_ASSOC);
if (!$arrr) {  // 不存在就创建用户
    $vip_sql = "select vip from wx_user where openid = '$openid' and belong = '$centre_id'";
    $vip_exec = $pdo->query($vip_sql);
    $vip = $vip_exec->fetch(PDO::FETCH_ASSOC);
    if ($vip['vip'] == 1) {
        $is_qianke = 3;
    } else {
        $is_qianke = 1;
    }
    $sql4 = "insert into hd_user(open_id,user_name,head_image,centre_id,hd_id,parent_openid,is_qianke)values('$openid','$nickname','$img','$centre_id','$hd_id','$parent_openid','$is_qianke')";
    $aw = $pdo->exec($sql4);
    $integral_type1 = $ar1['integral_type1'];
    $integral = $arrr['integral'] + $integral_type1;
    $sql6 = "update hd_user set integral=$integral,zjf=zjf+$integral_type1 where open_id='$parent_openid' and hd_id='$hd_id'";
    $pdo->exec($sql6);
    $sql7 = "update hd_user set share_num=share_num+1 where open_id='$parent_openid' and hd_id='$hd_id'";
    $ar = $pdo->exec($sql7);
}
//  更新用户的影响力
$sql = "update hd_user set influence=influence+1 where open_id='$parent_openid' and hd_id='$hd_id'";

$ar = $pdo->exec($sql);


header("Location:https://wx.codeclassroom.org/activity/index.html?parent_openid=$parent_openid&centre_id=$centre_id&hdid=$hd_id");
?>
