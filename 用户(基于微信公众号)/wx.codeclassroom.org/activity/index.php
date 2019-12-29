<?php 
   //scope=snsapi_base 实例
$appid = 'wx739ca3a16733ba72';
$parent_openid = !empty($_GET['parent_openid']) ? $_GET['parent_openid'] : '' ;
$centre_id = $_GET['centre_id'];
$hdid = $_GET['hdid'];
$redirect_uri = urlencode ( "https://wx.codeclassroom.org/activity/getUserInfo.php?parent_openid=$parent_openid&centre_id=$centre_id&hdid=$hdid" );
$url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=weixin#wechat_redirect";
// var_dump($url);die;
header("Location:".$url);
 ?>