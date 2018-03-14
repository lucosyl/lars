<?php 
namespace app\index\controller;
use pay\alipay\AlipayTradePagePayContentBuilder;
use alipay\service\AlipayTradeService;
use think\Session;

class Alipay
{
	public function __construct(){
		Session::set('usr_id','1223');
	}

	public function index()
	{

		return view('');
	}


	//发起支付
	public function pagepay(){

		require_once EXTEND_PATH.'/alipay/config.php';
		require_once EXTEND_PATH.'/alipay/pagepay/service/AlipayTradeService.php';
		require_once EXTEND_PATH.'/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

	    //商户订单号，商户网站订单系统中唯一订单号，必填
	    $out_trade_no = trim($_POST['WIDout_trade_no']);

	    //订单名称，必填
	    $subject = trim($_POST['WIDsubject']);

	    //付款金额，必填
	    $total_amount = trim($_POST['WIDtotal_amount']);

	    //商品描述，可空
	    $body = trim($_POST['WIDbody']);

		//构造参数
		$payRequestBuilder = new AlipayTradePagePayContentBuilder();
		$payRequestBuilder->setBody($body);
		$payRequestBuilder->setSubject($subject);
		$payRequestBuilder->setTotalAmount($total_amount);
		$payRequestBuilder->setOutTradeNo($out_trade_no);

		$aop = new AlipayTradeService($config);

		//储存订单信息
		$user = model('Payment');
		$user->out_trade_no= $out_trade_no;
		$user->subject= $subject;
		$user->total_amount= $total_amount;
		$user->body= $body;
		$user->buyer_id= Session::get('usr_id');
		$user->createtime = date('Y-m-d H:i:s',time());
		$user->status = '0';
		$res = $user->save();
		if( !$res ){
			echo "<script>alert('写入数据库错误')</script>;";
			die;
		}

		/**
		 * pagePay 电脑网站支付请求
		 * @param $builder 业务参数，使用buildmodel中的对象生成。
		 * @param $return_url 同步跳转地址，公网可以访问
		 * @param $notify_url 异步通知地址，公网可以访问
		 * @return $response 支付宝返回的信息
	 	*/
		$response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);

		//输出表单
		return view('',['response'=>$response]);
	}

	//支付信息查询
	public function query(){
		require_once EXTEND_PATH.'/alipay/config.php';
		require_once EXTEND_PATH.'/alipay/pagepay/service/AlipayTradeService.php';
		require_once EXTEND_PATH.'/alipay/pagepay/buildermodel/AlipayTradeQueryContentBuilder.php';

		    //商户订单号，商户网站订单系统中唯一订单号
		    $out_trade_no = trim($_POST['WIDTQout_trade_no']);

		    //支付宝交易号
		    $trade_no = trim($_POST['WIDTQtrade_no']);
		    //请二选一设置
		    //构造参数
			$RequestBuilder = new \alipay\query\AlipayTradeQueryContentBuilder();
			$RequestBuilder->setOutTradeNo($out_trade_no);
			$RequestBuilder->setTradeNo($trade_no);

			$aop = new \alipay\service\AlipayTradeService($config);
			
			/**
			 * alipay.trade.query (统一收单线下交易查询)
			 * @param $builder 业务参数，使用buildmodel中的对象生成。
			 * @return $response 支付宝返回的信息
		 	 */
			$response = $aop->Query($RequestBuilder);
			dump($response);

	}

	//退款
	public function refund(){

		require_once EXTEND_PATH.'/alipay/config.php';
		require_once EXTEND_PATH.'/alipay/pagepay/service/AlipayTradeService.php';
		require_once EXTEND_PATH.'/alipay/pagepay/buildermodel/AlipayTradeRefundContentBuilder.php';

		    //商户订单号，商户网站订单系统中唯一订单号
		    $out_trade_no = trim($_POST['WIDTRout_trade_no']);

		    //支付宝交易号
		    $trade_no = trim($_POST['WIDTRtrade_no']);
		    //请二选一设置

		    //需要退款的金额，该金额不能大于订单金额，必填
		    $refund_amount = trim($_POST['WIDTRrefund_amount']);

		    //退款的原因说明
		    $refund_reason = trim($_POST['WIDTRrefund_reason']);

		    //标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传
		    $out_request_no = trim($_POST['WIDTRout_request_no']);

		    //构造参数
			$RequestBuilder=new \alipay\refund\AlipayTradeRefundContentBuilder();
			$RequestBuilder->setOutTradeNo($out_trade_no);
			$RequestBuilder->setTradeNo($trade_no);
			$RequestBuilder->setRefundAmount($refund_amount);
			$RequestBuilder->setOutRequestNo($out_request_no);
			$RequestBuilder->setRefundReason($refund_reason);
			// dump($RequestBuilder);die;

			$aop = new AlipayTradeService($config);
			
			/**
			 * alipay.trade.refund (统一收单交易退款接口)
			 * @param $builder 业务参数，使用buildmodel中的对象生成。
			 * @return $response 支付宝返回的信息
			 */
			$response = $aop->Refund($RequestBuilder);
			if( $response->code == 10000 ){
				$pay = model('Refund');
				$pay->out_trade_no = $response->out_trade_no;
				$pay->refund_amount = $response->refund_fee;
				$pay->buyer = $response->buyer_logon_id;
				$pay->buyer_id = Session::get('usr_id');
				$pay->refund_time = $response->gmt_refund_pay;
				$pay->trade_no = $response->trade_no;
				$pay->refund_reason = $refund_reason;
				$pay->refund_request_no = $out_request_no;
				$res = $pay->save();
				if( !$res ) {
					echo "数据库添加错误";die;
				}
			}

			var_dump($response);;
	}

	//退款查询
	public function refundquery(){
		require_once EXTEND_PATH.'/alipay/config.php';
		require_once EXTEND_PATH.'/alipay/pagepay/service/AlipayTradeService.php';
		require_once EXTEND_PATH.'/alipay/pagepay/buildermodel/AlipayTradeFastpayRefundQueryContentBuilder.php';

		    //商户订单号，商户网站订单系统中唯一订单号
		    $out_trade_no = trim($_POST['WIDRQout_trade_no']);

		    //支付宝交易号
		    $trade_no = trim($_POST['WIDRQtrade_no']);
		    //请二选一设置

		    //请求退款接口时，传入的退款请求号，如果在退款请求时未传入，则该值为创建交易时的外部交易号，必填
		    $out_request_no = trim($_POST['WIDRQout_request_no']);

		    //构造参数
			$RequestBuilder=new \alipay\refundquery\AlipayTradeFastpayRefundQueryContentBuilder();
			$RequestBuilder->setOutTradeNo($out_trade_no);
			$RequestBuilder->setTradeNo($trade_no);
			$RequestBuilder->setOutRequestNo($out_request_no);

			$aop = new AlipayTradeService($config);
			
			/**
			 * 退款查询   alipay.trade.fastpay.refund.query (统一收单交易退款查询)
			 * @param $builder 业务参数，使用buildmodel中的对象生成。
			 * @return $response 支付宝返回的信息
			 */
			$response = $aop->refundQuery($RequestBuilder);
			var_dump($response);
	}

	//交易关闭
	public function close(){
		require_once EXTEND_PATH.'/alipay/config.php';
		require_once EXTEND_PATH.'/alipay/pagepay/service/AlipayTradeService.php';
		require_once EXTEND_PATH.'/alipay/pagepay/buildermodel/AlipayTradeCloseContentBuilder.php';

		    //商户订单号，商户网站订单系统中唯一订单号
		    $out_trade_no = trim($_POST['WIDTCout_trade_no']);

		    //支付宝交易号
		    $trade_no = trim($_POST['WIDTCtrade_no']);
		    //请二选一设置

			//构造参数
			$RequestBuilder=new \alipay\close\AlipayTradeCloseContentBuilder();
			$RequestBuilder->setOutTradeNo($out_trade_no);
			$RequestBuilder->setTradeNo($trade_no);

			$aop = new \alipay\service\AlipayTradeService($config);

			/**
			 * alipay.trade.close (统一收单交易关闭接口)
			 * @param $builder 业务参数，使用buildmodel中的对象生成。
			 * @return $response 支付宝返回的信息
			 */
			$response = $aop->Close($RequestBuilder);
			var_dump($response);
	}

	//支付宝服务器异步通知页面
	public function notify_url(){
		require_once EXTEND_PATH.'/alipay/config.php';
		require_once EXTEND_PATH.'/alipay/pagepay/service/AlipayTradeService.php';

		$arr=$_POST;
		$alipaySevice = new AlipayTradeService($config); 
		$alipaySevice->writeLog(var_export($_POST,true));
		$result = $alipaySevice->check($arr);

		/* 实际验证过程建议商户添加以下校验。
		1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
		2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
		3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
		4、验证app_id是否为该商户本身。
		*/
		if($result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代

			
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			
		    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			
			//商户订单号

			$out_trade_no = $_POST['out_trade_no'];

			//支付宝交易号

			$trade_no = $_POST['trade_no'];

			//交易状态
			$trade_status = $_POST['trade_status'];


		    if($_POST['trade_status'] == 'TRADE_FINISHED') {

				//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
					//如果有做过处理，不执行商户的业务程序
						
				//注意：
				//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
		    }
		    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
				//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
					//如果有做过处理，不执行商户的业务程序			
				//注意：
				//付款完成后，支付宝系统发送该交易状态通知
		    }
			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
				$pay = model('Payment');
				$data =['status'=>2];
				$pay->where('trade_no',$_POST['trade_no'])->update($data);
				echo "success";	//请不要修改或删除
			}else {
			    //验证失败
			    $pay = model('Payment');
				$data =['body'=>$_POST['sign']];
				$pay->where('trade_no',$_POST['trade_no'])->update($data);
			    echo "fail";

		}
	}


	//接受返回数据
	public function return_url(){
		require_once EXTEND_PATH."/alipay/config.php";
		require_once EXTEND_PATH.'/alipay/pagepay/service/AlipayTradeService.php';

		$arr=$_GET;
		$alipaySevice = new \alipay\service\AlipayTradeService($config); 
		$result = $alipaySevice->check($arr);
		// echo "<pre>";
		// dump($arr);
		// dump($alipaySevice);die;
		// echo "<pre/>";

		/* 实际验证过程建议商户添加以下校验。
		1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
		2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
		3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
		4、验证app_id是否为该商户本身。
		*/
		if($result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代码
			//获取成功
			$pay = model('Payment');
			
   			$data=	['trade_no'=>$_GET['trade_no'],'dealtime'=>date('Y-m-d H:i:s',time()), 'status'=>'1'];
   			$where['out_trade_no']=$_GET['out_trade_no'];
   			$where['buyer_id']=Session::get('usr_id');

   			$check_no = $pay->where($where)->find();
   			if ( !$check_no ){
   				echo "数据库查询错误";die;
   			}else{
				$res = $pay->where('out_trade_no',$_GET['out_trade_no'])->update($data);
				if( !$res ){
					echo "数据库更新错误";die;
				} 

   			}

			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

			//商户订单号
			$out_trade_no = htmlspecialchars($_GET['out_trade_no']);

			//支付宝交易号
			$trade_no = htmlspecialchars($_GET['trade_no']);
				
			return( "验证成功<br />支付宝交易号：".$trade_no);

			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else {
		    //验证失败
		    return ("验证失败");
		}
	}
}

