<?php
namespace app\index\controller;
use think\Loader;
class Index
{	


    public function index()
    {
        return view();
    }


    //微信端支付
    public function jsapi(){
    	require_once EXTEND_PATH."/wechat/lib/WxPay.Api.php";
		require_once EXTEND_PATH."/wechat/example/WxPay.JsApiPay.php";
		require_once EXTEND_PATH.'/wechat/example/log.php';

		//初始化日志
		$logHandler= new \wechat\log\CLogFileHandler(EXTEND_PATH."/wechat/logs/".date('Y-m-d').'.log');
		$log = \wechat\log\Log::Init($logHandler, 15);

		//打印输出数组信息
		function printf_info($data)
		{
		    foreach($data as $key=>$value){
		        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
		    }
		}

		//①、获取用户openid
		$tools = new \wechat\jsapi\JsApiPay();
		$openId = $tools->GetOpenid();

		//②、统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody("test");
		$input->SetAttach("test");
		$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
		$input->SetTotal_fee("1");
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = WxPayApi::unifiedOrder($input);
		echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
		printf_info($order);
		$jsApiParameters = $tools->GetJsApiParameters($order);

		//获取共享收货地址js函数参数
		$editAddress = $tools->GetEditAddressParameters();

		//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
		/**
		 * 注意：
		 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
		 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
		 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
		 */
    }

    public function micropay(){

		require_once EXTEND_PATH."/wechat/lib/WxPay.Api.php";
		require_once EXTEND_PATH."/wechat/example/WxPay.MicroPay.php";
		require_once EXTEND_PATH.'/wechat/example/log.php';

		//初始化日志
		$logHandler= new \wechat\log\CLogFileHandler(EXTEND_PATH."/wechat/logs/".date('Y-m-d').'.log');
		$log = \wechat\log\Log::Init($logHandler, 15);

		//打印输出数组信息
		function printf_info($data)
		{
		    foreach($data as $key=>$value){
		        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
		    }
		}

		if(isset($_REQUEST["auth_code"]) && $_REQUEST["auth_code"] != ""){
			$auth_code = $_REQUEST["auth_code"];
			$input = new \wechat\data\WxPayMicroPay();
			$input->SetAuth_code($auth_code);
			$input->SetBody("刷卡测试样例-支付");
			$input->SetTotal_fee("1");
			$input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
			
			$microPay = new \MicroPay();
			printf_info($microPay->pay($input));
		}

		/**
		 * 注意：
		 * 1、提交被扫之后，返回系统繁忙、用户输入密码等错误信息时需要循环查单以确定是否支付成功
		 * 2、多次（一半10次）确认都未明确成功时需要调用撤单接口撤单，防止用户重复支付
		 */


        return view('');
    }

    //扫码支付
    public function native(){
    	require_once EXTEND_PATH."/wechat/lib/WxPay.Api.php";
		require_once EXTEND_PATH."/wechat/example/WxPay.NativePay.php";
		require_once EXTEND_PATH.'/wechat/example/log.php';

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
		$notify = new \wechat\native\NativePay();
		$url1 = $notify->GetPrePayUrl("123456789");


		//模式二
		/**
		 * 流程：
		 * 1、调用统一下单，取得code_url，生成二维码
		 * 2、用户扫描二维码，进行支付
		 * 3、支付完成之后，微信服务器会通知支付成功
		 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
		 */
		$input = new \wechat\data\WxPayUnifiedOrder();
		$input->SetBody("test");
		$input->SetAttach("test");
		$input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
		$input->SetTotal_fee("1");
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://pay.com/index/index/notify.html");
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id("123456789");

		$result = $notify->GetPayUrl($input);
		$url2 = $result["code_url"];
		return view('',['url1'=>$url1,'url2'=>$url2]);
    }

    //订单查询
    public function orderquery(){
    	require_once EXTEND_PATH."/wechat/lib/WxPay.Api.php";
		require_once EXTEND_PATH.'/wechat/example/log.php';

		//初始化日志
		$logHandler= new \wechat\log\CLogFileHandler(EXTEND_PATH."/wechat/logs/".date('Y-m-d').'.log');
		$log = \wechat\log\Log::Init($logHandler, 15);

		function printf_info($data)
		{
		    foreach($data as $key=>$value){
		        echo "<font color='#f00;'>$key</font> : $value <br/>";
		    }
		}


		if(isset($_REQUEST["transaction_id"]) && $_REQUEST["transaction_id"] != ""){
			$transaction_id = $_REQUEST["transaction_id"];
			$input = new \wechat\data\WxPayOrderQuery();
			$input->SetTransaction_id($transaction_id);
			printf_info(\wechat\api\WxPayApi::orderQuery($input));
			exit();
		}

		if(isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != ""){
			$out_trade_no = $_REQUEST["out_trade_no"];
			$input = new \wechat\data\WxPayOrderQuery();
			$input->SetOut_trade_no($out_trade_no);
			printf_info(\wechat\queryWxPayApi::orderQuery($input));
			exit();
		}

		return view('');
    }

    //订单退款
    public function refund(){
    	error_reporting(E_ERROR);
		require_once EXTEND_PATH."/wechat/lib/WxPay.Api.php";
		require_once EXTEND_PATH.'/wechat/example/log.php';

		//初始化日志
		$logHandler= new \wechat\log\CLogFileHandler(EXTEND_PATH."/wehcat/logs/".date('Y-m-d').'.log');
		$log = \wechat\log\Log::Init($logHandler, 15);

		function printf_info($data)
		{
		    foreach($data as $key=>$value){
		        echo "<font color='#f00;'>$key</font> : $value <br/>";
		    }
		}

		if(isset($_REQUEST["transaction_id"]) && $_REQUEST["transaction_id"] != ""){
			$transaction_id = $_REQUEST["transaction_id"];
			$total_fee = $_REQUEST["total_fee"];
			$refund_fee = $_REQUEST["refund_fee"];
			$input = new \wechat\data\WxPayRefund();
			$input->SetTransaction_id($transaction_id);
			$input->SetTotal_fee($total_fee);
			$input->SetRefund_fee($refund_fee);
		    $input->SetOut_refund_no(\WxPayConfig::MCHID.date("YmdHis"));
		    $input->SetOp_user_id(\WxPayConfig::MCHID);
			printf_info(\wechat\api\WxPayApi::refund($input));
			exit();
		}

		//$_REQUEST["out_trade_no"]= "122531270220150304194108";
		///$_REQUEST["total_fee"]= "1";
		//$_REQUEST["refund_fee"] = "1";
		if(isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != ""){
			$out_trade_no = $_REQUEST["out_trade_no"];
			$total_fee = $_REQUEST["total_fee"];
			$refund_fee = $_REQUEST["refund_fee"];
			$input = new \wechat\data\WxPayRefund();
			$input->SetOut_trade_no($out_trade_no);
			$input->SetTotal_fee($total_fee);
			$input->SetRefund_fee($refund_fee);
		    $input->SetOut_refund_no(\WxPayConfig::MCHID.date("YmdHis"));
		    $input->SetOp_user_id(\WxPayConfig::MCHID);
			printf_info(\wechat\api\WxPayApi::refund($input));
			exit();
 	   }

 	   return view('');

	}

    //退款查询
    public function refundquery(){
	
		require_once EXTEND_PATH."/wechat/lib/WxPay.Api.php";
		require_once EXTEND_PATH.'/wechat/example/log.php';

		//初始化日志
		$logHandler= new \wechat\log\CLogFileHandler(EXTEND_PATH."/wechat/logs/".date('Y-m-d').'.log');
		$log = \wechat\log\Log::Init($logHandler, 15);


		function printf_info($data)
		{
		    foreach($data as $key=>$value){
		        echo "<font color='#f00;'>$key</font> : $value <br/>";
		    }
		}

		if(isset($_REQUEST["transaction_id"]) && $_REQUEST["transaction_id"] != ""){
			$transaction_id = $_REQUEST["transaction_id"];
			$input = new \wechta\data\WxPayRefundQuery();
			$input->SetTransaction_id($transaction_id);
			printf_info(\wechat\api\WxPayApi::refundQuery($input));
		}

		if(isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != ""){
			$out_trade_no = $_REQUEST["out_trade_no"];
			$input = new \wechat\data\WxPayRefundQuery();
			$input->SetOut_trade_no($out_trade_no);
			printf_info(\wechat\api\WxPayApi::refundQuery($input));
			exit();
		}

		if(isset($_REQUEST["out_refund_no"]) && $_REQUEST["out_refund_no"] != ""){
			$out_refund_no = $_REQUEST["out_refund_no"];
			$input = new \wechat\data\WxPayRefundQuery();
			$input->SetOut_refund_no($out_refund_no);
			printf_info(\wechat\api\WxPayApi::refundQuery($input));
			exit();
		}

		if(isset($_REQUEST["refund_id"]) && $_REQUEST["refund_id"] != ""){
			$refund_id = $_REQUEST["refund_id"];
			$input = new \wechat\data\WxPayRefundQuery();
			$input->SetRefund_id($refund_id);
			printf_info(\wechat\api\WxPayApi::refundQuery($input));
			exit();
		}

		return view('');
			
    }

    //下载订单
    public function download(){
		require_once EXTEND_PATH."/wechat/lib/WxPay.Api.php";
		//require_once "../lib/WxPay.MicroPay.php";


		if(isset($_REQUEST["bill_date"]) && $_REQUEST["bill_date"] != ""){
			$bill_date = $_REQUEST["bill_date"];
		    $bill_type = $_REQUEST["bill_type"];
			$input = new \wechat\data\WxPayDownloadBill();
			$input->SetBill_date($bill_date);
			$input->SetBill_type($bill_type);
			$file = \wechat\api\WxPayApi::downloadBill($input);
			echo $file;
			//TODO 对账单文件处理
		    exit(0);

 	   }

 	   return view();
	}

	public function notify(){
		require_once EXTEND_PATH.'/wechat/example/notify.php';
	}

    //开源php二维码
    public function qrcode(){
    	error_reporting(E_ERROR);
		require_once EXTEND_PATH.'/wechat/example/phpqrcode/phpqrcode.php';
		$url = urldecode($_GET["data"]);
		\qrcode\QRcode::png($url);
		exit();
    }
}
