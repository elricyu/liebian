<?php
/**
 * 关于微信现金红包的说明
 * 1.微信现金红包要求必传证书，需要到https://pay.weixin.qq.com 账户中心->账户设置->API安全->下载证书
 * 2.默认的使用场景是抽奖（即scene_id参数为PRODUCT_2），额度是1-200元，所以测试时的最低金额是1元。如需修改在产品中心->产品大全->现金红包->产品设置中修改
 * 3.错误码参照 ：https://pay.weixin.qq.com/wiki/doc/api/tools/cash_coupon.php?chapter=13_4&index=3
 */
header('Content-type:text/html; Charset=utf-8');
// $centre_id=$_POST['centre_id'];//传入活动id
$mchid = 'xxxxxxxx1xx';          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
$appid = 'xxxxxxxxxxxxxxxxxx';  //微信支付申请对应的公众号的APPID
$appKey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';   //微信支付申请对应的公众号的APP Key
$apiKey = 'xxxxxxxx2xx';   //帐户设置-安全设置-API安全-API密钥-设置API密钥

//填写证书所在位置，证书在https://pay.weixin.qq.com 账户中心->账户设置->API安全->下载证书，下载后将apiclient_cert.pem和apiclient_key.pem上传到服务器。
$apiclient_cert = getcwd() . '/cert/apiclient_cert.pem';
$apiclient_key = getcwd() . '/cert/apiclient_key.pem';

//①、获取当前访问页面的用户openid（如果给指定用户发送红包，则填写指定用户的openid)
$wxPay = new WxpayService($mchid, $appid, $appKey, $apiKey, $apiclient_cert, $apiclient_key);

$hd_id = $_POST['hd_id'];//传入活动id
$openid = $_POST['openid'];
$centre_id = $_POST['centre_id'];
$hd_id = 218;
$centre_id = 621;
$openid = "o8qM5xF9Z4pT6SvA-UJ-n7qV7rXs";

//②、发送红包


$dns = 'mysql:host=xxxxxxxxxxxxxxx;dbname=database_bc_hd';
$pdo = new PDO($dns,'xxxx','xxxxxxxx');

$sql3 = "select * from hd_user where open_id='$openid' and hd_id='$hd_id'";
$res1 = $pdo->query($sql3);
$arr1 = $res1->fetch(PDO::FETCH_ASSOC);//获取用户信息

$sql = "select integral_type1,centre_id,red_type,red_total,red_single,red_num,red_yfnum,red_yftotal,red_rand,red_balance from crm_huodong where hd_id='$hd_id'";
 //获取活动设置红包相关信息
$res = $pdo->query($sql);
$arr = $res->fetch(PDO::FETCH_ASSOC);
//获取中心红包充值金额余额
$sql8 = "select red_remaining from a_money where centre_id='{$centre_id}'";
$re2 = $pdo->query($sql8);
$ar2 = $re2->fetch(PDO::FETCH_ASSOC);

//判断红包余额是否充足
if ($ar2['red_remaining'] > 0 and $arr['red_balance'] > 0) {
	//判断红包类型
	if ($arr['red_type'] == 1) {
		//随机红包
		$red_yfnum = $arr['red_yfnum'];
		$red_rand = explode(",", $arr['red_rand']);
		$red_money = isset($red_rand[$red_yfnum]) ? $red_rand[$red_yfnum] : 0;//推送随机红包金额
		// echo "<pre>";
		// print_r($red_yfnum);
		// echo "--------------";
		// print_r($red_rand);
		// echo "--------------";
		// print_r($red_money);
		// die;
	} else {
		//固定金额红包
		$red_money = $arr['red_single'];//推送固定红包金额
	}
	//判断红包金额 如小于最小要求发放金额0.3元 更改金额为0.3元
	$red_money = $red_money < 0.3 ? 0.3 : $red_money;
	$outTradeNo = uniqid();     //你自己的商品订单号
	$re_yfnum = $arr['red_yfnum'] + 1;//修改库数据
	$re_yftotal = $arr['red_yftotal'] + $red_money;
	$re_balance = $arr['red_balance'] - $red_money;
	$sql1 = "update crm_huodong set red_yfnum='$re_yfnum',red_yftotal='$re_yftotal',red_balance='$re_balance' where hd_id='$hd_id'";
	// mysql_query($sql1);
	$sql2 = "insert into hd_red_record(hd_id,openid,money,type,centre_id,mch_billno)values('{$hd_id}','{$openid}','{$red_money}','{$arr['red_type']}','{$arr['centre_id']}','{$outTradeNo}')";
	// mysql_query($sql2);
	$sql4 = "update hd_user set is_first=3 where open_id='$openid' and hd_id='$hd_id'";
	// mysql_query($sql4);
	$sql6 = "update a_money set red_remaining=red_remaining-$red_money where centre_id = $centre_id";
	// mysql_query($sql6);
	//执行红包发送
	$payAmount = $red_money * 100;//红包金额，单位:
	// $payAmount = 0;//红包金额，单位:
	$sendName = '编程云';    //红包发送者名称
	$wishing = '恭喜发财';      //红包祝福语
	$act_name = '分享得红包';           //活动名称
	$result = $wxPay->createJsBizPackage($openid, $payAmount, $outTradeNo, $sendName, $wishing, $act_name);
	
	if ($result === false || $result->return_code != 'SUCCESS' || $result->result_code != 'SUCCESS' ) {
		echo 2;die;	//发送失败 红包领取异常
	}else{
		//发送成功 执行修改数据库
		$pdo->exec($sql1);	//更新红包发放数量 红包余额
		$pdo->exec($sql2);	//插入红包发放记录
		$pdo->exec($sql4);	//将活动用户分享红包状态更改为3 已领取
		$pdo->exec($sql6);	//中心红包充值总金额减少
		echo 1; die;    //发送成功
	}
	
}else{
	echo 2;die; //红包余额不足  发放失败
}
    
// echo 'success';
class WxpayService
{
    protected $mchid;
    protected $appid;
    protected $appKey;
    protected $apiKey;
    protected $apiclient_cert;
    protected $apiclient_key;
    public $data = null;

    public function __construct($mchid, $appid, $appKey, $key, $apiclient_cert, $apiclient_key)
    {
        $this->mchid = $mchid;
        $this->appid = $appid;
        $this->appKey = $appKey;
        $this->apiKey = $key;
        $this->apiclient_cert = $apiclient_cert;
        $this->apiclient_key = $apiclient_key;
    }

    /**
     * 通过跳转获取用户的openid，跳转流程如下：
     * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
     * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
     * @return 用户的openid
     */
    // public function GetOpenid()
    // {
    //     //通过code获得openid
    //     if (!isset($_GET['code'])){
    //         //触发微信返回code码
    //         $scheme = $_SERVER['HTTPS']=='on' ? 'https://' : 'http://';
    //         $baseUrl = urlencode($scheme.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    //         $url = $this->__CreateOauthUrlForCode($baseUrl);
    //         Header("Location: $url");
    //         // var_dump($url);
    //         exit();
    //     } else {
    //         //获取code码，以获取openid
    //         $code = $_GET['code'];
    //         $openid = $this->getOpenidFromMp($code);
    //         return $openid;
    //     }
    // }

    /**
     * 通过code从工作平台获取openid机器access_token
     * @param string $code 微信跳转回来带上的code
     * @return openid
     */
    public function GetOpenidFromMp($code)
    {
        $url = $this->__CreateOauthUrlForOpenid($code);
        $res = self::curlGet($url);
        //取出openid
        $data = json_decode($res, true);
        $this->data = $data;
        $openid = $data['openid'];
        return $openid;
    }

    /**
     * 构造获取open和access_toke的url地址
     * @param string $code，微信跳转带回的code
     * @return 请求的url
     */
    private function __CreateOauthUrlForOpenid($code)
    {
        $urlObj["appid"] = $this->appid;
        $urlObj["secret"] = $this->appKey;
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?" . $bizString;
    }

    /**
     * 构造获取code的url连接
     * @param string $redirectUrl 微信服务器回跳的url，需要url编码
     * @return 返回构造好的url
     */
    private function __CreateOauthUrlForCode($redirectUrl)
    {
        $urlObj["appid"] = $this->appid;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        $urlObj["state"] = "STATE" . "#wechat_redirect";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?" . $bizString;
    }

    /**
     * 拼接签名字符串
     * @param array $urlObj
     * @return 返回已经拼接好的字符串
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v) {
            if ($k != "sign") $buff .= $k . "=" . $v . "&";
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 统一下单
     * @param string $openid 调用【网页授权获取用户信息】接口获取到用户在该公众号下的Openid
     * @param float $totalFee 收款总费用 单位元
     * @param string $outTradeNo 唯一的订单号
     * @param string $orderName 订单名称
     * @param string $notifyUrl 支付结果通知url 不要有问号
     * @param string $timestamp 支付时间
     * @return string
     */
    public function createJsBizPackage($openid, $totalFee, $outTradeNo, $sendName, $wishing, $actName)
    {
        $config = array(
            'mch_id' => $this->mchid,
            'appid' => $this->appid,
            'key' => $this->apiKey,
        );
        $unified = array(
            'wxappid' => $config['appid'],
            'send_name' => $sendName,
            'mch_id' => $config['mch_id'],
            'nonce_str' => self::createNonceStr(),
            're_openid' => $openid,
            'mch_billno' => $outTradeNo,
            'client_ip' => '47.94.45.52',
            'total_amount' => $totalFee,       //单位 转为分
            'total_num' => 1,     //红包发放总人数
            'wishing' => $wishing,      //红包祝福语
            'act_name' => $actName,           //活动名称
            'remark' => 'remark',               //备注信息，如为中文注意转为UTF8编码
            'scene_id' => 'PRODUCT_2',      //发放红包使用场景，红包金额大于200时必传。https://pay.weixin.qq.com/wiki/doc/api/tools/cash_coupon.php?chapter=13_4&index=3
        );
        $unified['sign'] = self::getSign($unified, $config['key']);
        $responseXml = $this->curlPost('https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack', self::arrayToXml($unified));
		$filename = 'redlog/redlog'.date('Y-m-d').'.txt';
        file_put_contents($filename, print_r($responseXml, true),FILE_APPEND);
        file_put_contents($filename, PHP_EOL,FILE_APPEND);
		file_put_contents($filename, '\r\n换行',FILE_APPEND);
        file_put_contents($filename, json_encode($unified),FILE_APPEND);
		file_put_contents($filename, PHP_EOL,FILE_APPEND);
        file_put_contents($filename, '\r\n换行',FILE_APPEND);
//        print_r($responseXml,true);die;
        $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        // if ($unifiedOrder === false) {
            // die('parse xml error');
        // }
        // if ($unifiedOrder->return_code != 'SUCCESS') {
            // die($unifiedOrder->return_msg);
        // }
        // if ($unifiedOrder->result_code != 'SUCCESS') {
            // die($unifiedOrder->err_code);
        // }
        return $unifiedOrder;
    }

    public static function curlGet($url = '', $options = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //第一种方法，cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLCERT, getcwd() . '/cert/apiclient_cert.pem');
        //默认格式为PEM，可以注释
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLKEY, getcwd() . '/cert/apiclient_key.pem');
        //第二种方式，两个文件合成一个.pem文件
//        curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');

        $data = curl_exec($ch);
        // var_dump($data);die;
        curl_close($ch);
        return $data;
    }

    public static function createNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        file_put_contents('1.txt', $xml);
        return $xml;
    }

    public static function getSign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }

    protected static function formatQueryParaMap($paraMap, $urlEncode = false)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v) {
                if ($urlEncode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
}

?>
