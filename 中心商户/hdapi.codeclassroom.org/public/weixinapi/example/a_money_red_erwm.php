<?php
//微信充值红包金额获取二维码
header("access-control-allow-origin:*");
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once "WxPay.NativePay.php";
require_once 'log.php';

//模式一
/**
 * 流程：
 * 1、组装包含支付信息的url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、确定支付之后，微信服务器会回调预先配置的回调地址，在【微信开放平台-微信支付-支付配置】中进行配置
 * 4、在接到回调通知之后，用户进行统一下单支付，并返回支付信息以完成支付（见：native_notify.php）
 * 5、支付完成之后，微信服务器会通知支付成功
 * 6、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$notify = new NativePay();
$url1 = $notify->GetPrePayUrl("123456789");

//模式二
/**
 * 流程：
 * 1、调用统一下单，取得code_url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、支付完成之后，微信服务器会通知支付成功
 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$centre_id=$_POST['centre_id'];
$money=$_POST['money'];
$monn=$money*100;
$num=WxPayConfig::MCHID.date("YmdHis");//订单号
// $link = mysql_connect("192.168.1.65","lz","lz");
//       mysql_select_db("db_gym_database4",$link);
//       mysql_query("set names utf8");
	$dsn = "mysql:host=192.168.1.65;dbname=db_momy_database";
	$pdo = new PDO($dsn,'lz','lz');
    $sql="insert into a_money_order(hao,money,centre_id,status,czzt)values('$num','$money','$centre_id','未支付','2')";
    // mysql_query($sql);
    $pdo->exec($sql);
$input = new WxPayUnifiedOrder();
$notify = new NativePay();
$input->SetBody("充值");
$input->SetAttach("充值");
$input->SetOut_trade_no($num);
$input->SetTotal_fee($monn);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("充值".$money);
$input->SetNotify_url("http://crm.gymbaby.cn/weixinapi/example/native_notify.php");
$input->SetTrade_type("NATIVE");
$input->SetProduct_id("123456789");
$result = $notify->GetPayUrl($input);
$url2 = $result["code_url"];
$er=urlencode($url2);
$arr=array("er"=>$er,"dindan"=>$num);
echo json_encode($arr);
?>
