<?php
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
	$link=mysql_connect("10.111.120.180","zxmstscgym","mn4Rx9aVcczx9sPj");
      mysql_select_db("db_gym_database",$link);
    mysql_query("set names utf8");
	if($zhi=='SUCCESS'){
      $sql="SELECT centre_id,dx_shu FROM crm_dx_fei where f_hao='$out_trade_no'";
      $re=mysql_query($sql);
      $ar=mysql_fetch_assoc($re);
      $f_id=$ar['f_id'];
      $centre_id=$ar['centre_id'];
      $dx_shu=$ar['dx_shu'];
      $sql1="SELECT dx_y FROM wx_centre where centre_id='$centre_id'";
      $re1=mysql_query($sql1);
      $ar1=mysql_fetch_assoc($re1);
      $dx_y=$ar1['dx_y']+$dx_shu;
      $sql2="update crm_dx_fei set openid='$openid',status='支付成功' where f_hao='$out_trade_no'";
      mysql_query($sql2);
      $sql3="update wx_centre set dx_y='$dx_y' where centre_id='$centre_id'";
      mysql_query($sql3);
	}
	echo json_encode($zhi);
	exit();
}
?>