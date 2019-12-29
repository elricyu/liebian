<?php
//微信获取二维码支付状态
header("access-control-allow-origin:*");
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "../lib/WxPay.Api.php";
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#f00;'>$key</font> : $value <br/>";
    }
}


if(isset($_REQUEST["transaction_id"]) && $_REQUEST["transaction_id"] != ""){
	$transaction_id = $_REQUEST["transaction_id"];
	$input = new WxPayOrderQuery();
	$input->SetTransaction_id($transaction_id);
	printf_info(WxPayApi::orderQuery($input));
	exit();
}
$out_trade_no=$_POST['out_trade_no'];
if(isset($out_trade_no) && $out_trade_no != ""){
	$out_trade_no = $out_trade_no;
	$input = new WxPayOrderQuery();
	$input->SetOut_trade_no($out_trade_no);
	$arr=WxPayApi::orderQuery($input);
	$zhi=$arr['trade_state'];
	$openid=$arr['openid'];
  $transaction_id1=$arr['transaction_id'];
	$link=mysql_connect("10.111.120.180","zxmstscgym","mn4Rx9aVcczx9sPj");
      mysql_select_db("db_gym_database",$link);
    mysql_query("set names utf8");
	if($zhi=='SUCCESS'){
      $sql="SELECT centre_id,money FROM a_money_order where hao='$out_trade_no'";
      $re=mysql_query($sql);
      $ar=mysql_fetch_assoc($re);
      $centre_id=$ar['centre_id'];
      $money=$ar['money'];
      $sql2="update a_money_order set openid='$openid',status='支付成功',transaction_id='$transaction_id1' where hao='$out_trade_no'";
      mysql_query($sql2);
      $sql4="SELECT centre_id FROM a_money where centre_id='$centre_id'";
      $re1=mysql_query($sql4);
      $ar1=mysql_fetch_assoc($re1);
      if($ar1){
      	$sql3="update a_money set total=total+$money,remaining=remaining+$money where centre_id='$centre_id'";
        mysql_query($sql3);
      }else{
      	$sql3="insert into a_money(centre_id,total,remaining)values('$centre_id','$money','$money')";
      	mysql_query($sql3);
      }
      
	}
	echo json_encode($zhi);
	exit();
}
?>