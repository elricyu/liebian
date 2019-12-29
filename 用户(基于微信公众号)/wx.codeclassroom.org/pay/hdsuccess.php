<?php
/**
 * @Date    2019-03-28 19:44:55
 * @Version ${id}
 * @Description 在此替换文件描述
 */

$hd_id     = $_POST['hd_id'];  // 活动id
$centre_id = $_POST['centre_id']; // 中心id
$user_id   = $_POST['user_id'];  // 会员id
$order_id  = $_POST['order_id'];  // 订单id
$openid    = $_POST['openid'];
// 连接数据库
$dns = 'mysql:host=xxxxxxxxxxxxxxx;dbname=database_bc_hd';
$pdo = new PDO($dns,'xxxx','xxxxxxxx');
// 获取消费码
$spend_code = $centre_id . $hd_id . $user_id . mt_rand(0,99);
// 修改订单状态
$up_sql = "UPDATE crm_hd_order SET status=1,spend_code='{$spend_code}' WHERE order_id = '{$order_id}'";
$res = $pdo->exec($up_sql);
if($res){
	unset($res);
	// 付款后更新预约id
	$sql = "SELECT yuy_id FROM crm_huodong WHERE hd_id = {$hd_id} LIMIT 1";
	$result = $pdo->query($sql);
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$yuy_id = explode(',',$row['yuy_id']);
	if(!in_array($user_id,$yuy_id)){
		$yuy_id = trim(implode(',',$yuy_id) . ",{$user_id}",',');
	}else{
		$yuy_id = implode(',',$yuy_id);
	}
	$sql1 = "UPDATE crm_huodong SET yuy_id='{$yuy_id}' WHERE hd_id = '{$hd_id}'";
	$res = $pdo->exec($sql1);
	if($res){
		unset($row);
		unset($sql);
		unset($result);
		// 报名成功更新a_user表数据
		$sql = "SELECT * FROM wx_user WHERE user_id = {$user_id} LIMIT 1";
		$result = $pdo->query($sql);
		$row = $result->fetch(PDO::FETCH_LAZY);
		$name = empty($row['baobao_name']) ? '' : $row['baobao_name'];
		$phoen = '';
		for($i=1;$i<=6;$i++){
			if(!empty($phone)){
				continue;
			}
			$phone = $row['phone'.$i];
		}
		$sql2 = "UPDATE hd_user SET is_active='2',is_baoming='2',phone='{$phone}',real_name='{$name}' WHERE open_id = '{$openid}' AND hd_id = '{$hd_id}'";
		$pdo->exec($sql2);
		// 添加报名记录表
		$sql3 = "INSERT INTO crm_hd_bm (user_id,hd_id,centre_id) VALUES('{$user_id}','{$hd_id}','{$centre_id}')";
		$res = $pdo->exec($sql3);
		echo json_encode(['status'=>1,'msg'=>'报名成功','order_id'=>$order_id]);
	}else{
		echo json_encode(['status'=>2,'msg'=>'报名失败']);
	}
}