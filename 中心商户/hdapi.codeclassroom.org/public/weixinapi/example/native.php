<?php
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
$numbe=$_POST['number'];
switch ($numbe) {
	case 1:
        $number=1000;
		$money=80;
		break;
	case 2:
        $number=5000;
		$money=400;
		break;
    case 3:
        $number=10000;
		$money=700;
		break;
    case 4:
        $number=80000;
		$money=5000;
		break;
	case 5:
        $number=1666;
		$money=100;
		break;
}
$monn=$money.'00';
$num=WxPayConfig::MCHID.date("YmdHis");//订单号
$link=mysql_connect("10.111.120.180","zxmstscgym","mn4Rx9aVcczx9sPj");
      mysql_select_db("db_gym_database",$link);
      mysql_query("set names utf8");
      $sql="insert into crm_dx_fei(f_leixing,f_hao,f_jine,dx_shu,centre_id,status)values('微信','$num','$money','$number','$centre_id','未支付')";
            mysql_query($sql);

$input = new WxPayUnifiedOrder();
$notify = new NativePay();
$input->SetBody("短信套餐".$number);
$input->SetAttach("短信");
$input->SetOut_trade_no($num);
$input->SetTotal_fee($monn);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("短信套餐".$money);
$input->SetNotify_url("http://crm.gymbaby.cn/weixinapi/example/native_notify.php");
$input->SetTrade_type("NATIVE");
$input->SetProduct_id("123456789");
$result = $notify->GetPayUrl($input);
$url2 = $result["code_url"];
$er=urlencode($url2);
$arr=array("er"=>$er,"dindan"=>$num);
echo json_encode($arr);
?>
