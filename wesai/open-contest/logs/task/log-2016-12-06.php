<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2016-12-06 00:35:53 --> Not Found: softykt/NotifySoftykt/index
ERROR - 2016-12-06 00:36:44 --> Not Found: softykt/NotifySoftykt/index
ERROR - 2016-12-06 17:59:06 --> url:http://10.2.8.119/softykt/Order/list_need_sync.json?size=100 errormsg:Operation timed out after 300 milliseconds with 0 out of -1 bytes received
[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 18:03:04 --> url:http://10.2.8.119/softykt/Contest/get_by_oid.json errormsg:httpCode is 404
[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Contest\/get_by_oid.json","method":"POST","params":{"fk_order":"336"},"headers":[]}]
ERROR - 2016-12-06 18:03:04 --> Severity: Notice --> Undefined index: copies /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 64
ERROR - 2016-12-06 18:03:04 --> Severity: Notice --> Undefined index: out_trade_no /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 65
ERROR - 2016-12-06 18:03:04 --> Severity: Notice --> Undefined index: out_trade_no /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 84
ERROR - 2016-12-06 18:03:04 --> create 根据智慧体育订单号 生成金飞鹰订单出错，错误原因：scenicid 必传非空
ERROR - 2016-12-06 18:05:09 --> url:http://10.2.8.119/softykt/Contest/get_by_oid.json errormsg:httpCode is 404
[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Contest\/get_by_oid.json","method":"POST","params":{"fk_order":"336"},"headers":[]}]
ERROR - 2016-12-06 18:05:09 --> create 根据智慧体育订单号 生成金飞鹰订单出错，错误原因：scenicid 必传非空
ERROR - 2016-12-06 18:08:23 --> 获取景区信息、订单信息失败 ,失败原因: oid 必传非空
ERROR - 2016-12-06 18:08:23 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":54,"ordernumber":null}
ERROR - 2016-12-06 18:08:23 --> create 根据智慧体育订单号 生成金飞鹰订单出错，错误原因：scenicid 必传非空
ERROR - 2016-12-06 18:08:51 --> Severity: Notice --> Undefined index: scenicid /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 156
ERROR - 2016-12-06 18:08:51 --> Severity: Notice --> Undefined index: productid /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 157
ERROR - 2016-12-06 18:08:51 --> Severity: Notice --> Undefined index: order_info /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 158
ERROR - 2016-12-06 18:08:51 --> Severity: Notice --> Undefined index: order_info /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 159
ERROR - 2016-12-06 18:08:51 --> Severity: Notice --> Undefined index: order_info /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 160
ERROR - 2016-12-06 18:08:51 --> create 根据智慧体育订单号 生成金飞鹰订单出错，错误原因：scenicid 必传非空
ERROR - 2016-12-06 18:09:52 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Trying to get property of non-object</p>
<p>Filename: softykt/Order.php</p>
<p>Line Number: 48</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/application/controllers/softykt/Order.php<br />
			Line: 48<br />
			Function: _error_handler			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":-1,"cost":0.12318706512451,"info":"\u53c2\u6570\u9519\u8bef"}[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"165","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:09:52 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:09:52 --> url:http://10.2.8.119/softykt/Order/createParnter.json errormsg:httpCode is 404
[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/createParnter.json","method":"POST","params":{"ordernumber":"","out_refound_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:15:17 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Trying to get property of non-object</p>
<p>Filename: softykt/Order.php</p>
<p>Line Number: 48</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/application/controllers/softykt/Order.php<br />
			Line: 48<br />
			Function: _error_handler			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.15261197090149,"result":{"ordernumber":"792106346132","onlycode":"59B4057E7D6E08F3FAA3E63F8E609739","totoalsale":null}}[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"165","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:15:17 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:17:52 --> Bad response:NULL[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"165","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:17:52 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:19:31 --> Bad response:[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"165","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:19:31 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:19:40 --> Bad response:[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"165","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:19:40 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:20:16 --> Bad response:<pre class='xdebug-var-dump' dir='ltr'><font color='#3465a4'>null</font>
</pre><pre class='xdebug-var-dump' dir='ltr'><small>boolean</small> <font color='#75507b'>true</font>
</pre>[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"165","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:20:16 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:20:26 --> Bad response:[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"165","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:20:26 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:26:40 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined index: ext_mobile</p>
<p>Filename: softykt/Order.php</p>
<p>Line Number: 61</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/application/controllers/softykt/Order.php<br />
			Line: 61<br />
			Function: _error_handler			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined index: ext_mobile</p>
<p>Filename: softykt/Order.php</p>
<p>Line Number: 62</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/application/controllers/softykt/Order.php<br />
			Line: 62<br />
			Function: _error_handler			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":-1,"cost":0.08883810043335,"info":"\u53c2\u6570\u9519\u8bef"}[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"165","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:26:40 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:27:59 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined index: ext_mobile</p>
<p>Filename: softykt/Order.php</p>
<p>Line Number: 62</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/application/controllers/softykt/Order.php<br />
			Line: 62<br />
			Function: _error_handler			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined index: ext_mobile</p>
<p>Filename: softykt/Order.php</p>
<p>Line Number: 63</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/application/controllers/softykt/Order.php<br />
			Line: 63<br />
			Function: _error_handler			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":-1,"cost":0.11070680618286,"info":"\u53c2\u6570\u9519\u8bef"}[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"165","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:27:59 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:30:27 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: mobile</p>
<p>Filename: softykt/Order.php</p>
<p>Line Number: 84</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/application/controllers/softykt/Order.php<br />
			Line: 84<br />
			Function: _error_handler			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":-1,"cost":0.099352121353149,"info":"\u53c2\u6570\u9519\u8bef"}[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"3","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:30:27 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:31:05 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: mobile</p>
<p>Filename: softykt/Order.php</p>
<p>Line Number: 84</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/application/controllers/softykt/Order.php<br />
			Line: 84<br />
			Function: _error_handler			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":-1,"cost":0.10178589820862,"info":"\u53c2\u6570\u9519\u8bef"}[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"3","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:31:05 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:31:27 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: mobile</p>
<p>Filename: softykt/Order.php</p>
<p>Line Number: 84</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/application/controllers/softykt/Order.php<br />
			Line: 84<br />
			Function: _error_handler			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":-1,"cost":0.083194017410278,"info":"\u53c2\u6570\u9519\u8bef"}[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"3","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:31:27 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:31:55 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: mobile</p>
<p>Filename: softykt/Order.php</p>
<p>Line Number: 86</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/application/controllers/softykt/Order.php<br />
			Line: 86<br />
			Function: _error_handler			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":-1,"cost":0.16092801094055,"info":"\u53c2\u6570\u9519\u8bef"}[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"3","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:31:55 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:32:27 --> url:http://10.2.8.119/softykt/order/placeOrder.json errormsg:Operation timed out after 300 milliseconds with 0 out of -1 bytes received
[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"3","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:32:27 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:33:07 --> create 根据智慧体育订单号 2016120110000000336生成金飞鹰订单出错，错误原因：参数错误
ERROR - 2016-12-06 18:36:26 --> create 根据智慧体育订单号 2016120110000000336生成金飞鹰订单出错，错误原因：参数错误
ERROR - 2016-12-06 18:39:29 --> Bad response:<br />
<font size='1'><table class='xdebug-error xe-parse-error' dir='ltr' border='1' cellspacing='0' cellpadding='1'>
<tr><th align='left' bgcolor='#f57900' colspan="5"><span style='background-color: #cc0000; color: #fce94f; font-size: x-large;'>( ! )</span> Parse error: syntax error, unexpected ';' in /home/liangkaixuan/code/open-api/api/application/controllers/softykt/Order.php on line <i>45</i></th></tr>
<tr><th align='left' bgcolor='#e9b96e' colspan='5'>Call Stack</th></tr>
<tr><th align='center' bgcolor='#eeeeec'>#</th><th align='left' bgcolor='#eeeeec'>Time</th><th align='left' bgcolor='#eeeeec'>Memory</th><th align='left' bgcolor='#eeeeec'>Function</th><th align='left' bgcolor='#eeeeec'>Location</th></tr>
<tr><td bgcolor='#eeeeec' align='center'>1</td><td bgcolor='#eeeeec' align='center'>0.0000</td><td bgcolor='#eeeeec' align='right'>234472</td><td bgcolor='#eeeeec'>{main}(  )</td><td title='/home/liangkaixuan/code/open-api/api/webroot/index.php' bgcolor='#eeeeec'>.../index.php<b>:</b>0</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>2</td><td bgcolor='#eeeeec' align='center'>0.0001</td><td bgcolor='#eeeeec' align='right'>245816</td><td bgcolor='#eeeeec'>require_once( <font color='#00bb00'>'/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php'</font> )</td><td title='/home/liangkaixuan/code/open-api/api/webroot/index.php' bgcolor='#eeeeec'>.../index.php<b>:</b>293</td></tr>
</table></font>

<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Parsing Error</p>
<p>Message:  syntax error, unexpected ';'</p>
<p>Filename: softykt/Order.php</p>
<p>Line Number: 45</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
	
		
	

</div>[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"3","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:39:29 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:40:00 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PAR3AM_NOT_NULL_NOT_EMPTY - assumed 'PAR3AM_NOT_NULL_NOT_EMPTY'</p>
<p>Filename: softykt/Order.php</p>
<p>Line Number: 45</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/application/controllers/softykt/Order.php<br />
			Line: 45<br />
			Function: _error_handler			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":-1,"cost":0.090250015258789,"info":"\u53c2\u6570\u9519\u8bef"}[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"3","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:40:00 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:41:00 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PAR3AM_NOT_NULL_NOT_EMPTY - assumed 'PAR3AM_NOT_NULL_NOT_EMPTY'</p>
<p>Filename: softykt/Order.php</p>
<p>Line Number: 45</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/application/controllers/softykt/Order.php<br />
			Line: 45<br />
			Function: _error_handler			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":-1,"cost":0.078639030456543,"info":"\u53c2\u6570\u9519\u8bef"}[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"3","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:41:00 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 18:41:22 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PAR3AM_NOT_NULL_NOT_EMPTY - assumed 'PAR3AM_NOT_NULL_NOT_EMPTY'</p>
<p>Filename: softykt/Order.php</p>
<p>Line Number: 45</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/application/controllers/softykt/Order.php<br />
			Line: 45<br />
			Function: _error_handler			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-api/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":-1,"cost":0.094516038894653,"info":"\u53c2\u6570\u9519\u8bef"}[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/order\/placeOrder.json","method":"POST","params":{"scenicid":"53","productid":"160","fk_user":"3","number":"1","trade_no":"2016120110000000336"},"headers":[]}]
ERROR - 2016-12-06 18:41:22 --> {"msg":"softykt create order failed","file":"\/home\/liangkaixuan\/code\/open-contest\/task\/application\/controllers\/softykt\/PlaceOrder.php","line":74,"ordernumber":"2016120110000000336"}
ERROR - 2016-12-06 19:02:48 --> create 根据智慧体育订单号 2016120110000001336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:03:10 --> create 根据智慧体育订单号 2016120110000001336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:03:40 --> create 根据智慧体育订单号 2016120110000001336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:04:55 --> create 根据智慧体育订单号 2016120110000001336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:05:31 --> Severity: Notice --> Undefined index: ordernumber /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 88
ERROR - 2016-12-06 19:07:01 --> array (
  'error' => 0,
  'cost' => 0.18713593482971,
  'result' => 
  array (
    'error' => 0,
    'result' => 
    array (
      'ordernumber' => '592101270232',
      'onlycode' => 'C5E6D4BA3E31B8A492C5D3DB0C095502',
      'totoalsale' => NULL,
    ),
  ),
)
ERROR - 2016-12-06 19:07:01 --> Severity: Notice --> Undefined index: ordernumber /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 89
ERROR - 2016-12-06 19:07:53 --> array (
  'error' => 0,
  'cost' => 0.16453003883362,
  'result' => 
  array (
    'ordernumber' => '482108228532',
    'onlycode' => 'BEC07CDCCF71158689E615EEACECEFD5',
    'totoalsale' => NULL,
  ),
)
ERROR - 2016-12-06 19:09:54 --> array (
  'error' => 0,
  'cost' => 0.15564012527466001,
  'result' => 
  array (
    'ordernumber' => '972102136832',
    'onlycode' => '55724E34F4ECC131C30A1CC7CBF15FA4',
    'totoalsale' => NULL,
  ),
)
ERROR - 2016-12-06 19:14:24 --> array (
  'error' => 0,
  'cost' => 0.15008401870728,
  'result' => 
  array (
    'ordernumber' => '712103437032',
    'onlycode' => '64CF4FCFDF50FE58ED8AA0CB3CA896AD',
    'totoalsale' => NULL,
  ),
)
ERROR - 2016-12-06 19:20:26 --> array (
  'error' => 0,
  'cost' => 0.17515993118286,
  'result' => 
  array (
    'ordernumber' => '862104197832',
    'onlycode' => '95B684F89C4C15BA4B722E8AA0EFA716',
    'totoalsale' => NULL,
  ),
)
ERROR - 2016-12-06 19:22:51 --> array (
  'error' => 0,
  'cost' => 0.15901899337768999,
  'result' => 
  array (
    'ordernumber' => '842100985132',
    'onlycode' => '98A88C07B7392D72FCD9CFA45FFEB791',
    'totoalsale' => NULL,
  ),
)
ERROR - 2016-12-06 19:22:51 --> Severity: 4096 --> Object of class stdClass could not be converted to string /home/liangkaixuan/code/open-base/ci/system/core/Log.php 207
ERROR - 2016-12-06 19:22:51 --> 
ERROR - 2016-12-06 19:25:40 --> array (
  'error' => 0,
  'cost' => 0.15789818763732999,
  'result' => 
  array (
    'ordernumber' => '232101788032',
    'onlycode' => '084897B08DAAACEA4D191BD94DC601FD',
    'totoalsale' => NULL,
  ),
)
ERROR - 2016-12-06 19:26:04 --> array (
  'error' => '200078',
  'cost' => 0.097247838973998996,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:26:04 --> create 根据智慧体育订单号 2016120110000009336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:26:14 --> array (
  'error' => 0,
  'cost' => 0.1612389087677,
  'result' => 
  array (
    'ordernumber' => '012104032532',
    'onlycode' => '2164EC439F2EF174E5C228B46BB2FBE2',
    'totoalsale' => NULL,
  ),
)
ERROR - 2016-12-06 19:26:14 --> array (
  'error' => '200078',
  'cost' => 0.098231077194214006,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:26:14 --> create 根据智慧体育订单号 2016120110000009336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:26:25 --> array (
  'error' => '200078',
  'cost' => 0.10845708847046,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:26:25 --> create 根据智慧体育订单号 2016120110000005335生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:26:35 --> array (
  'error' => '200078',
  'cost' => 0.090996026992798004,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:26:35 --> create 根据智慧体育订单号 2016120110000009336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:26:45 --> array (
  'error' => 0,
  'cost' => 0.16895294189453,
  'result' => 
  array (
    'ordernumber' => '102106600132',
    'onlycode' => '7058BCF9B2BEA05727878E6EA9F2D3CA',
    'totoalsale' => NULL,
  ),
)
ERROR - 2016-12-06 19:26:45 --> array (
  'error' => '200078',
  'cost' => 0.090677022933960003,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:26:45 --> create 根据智慧体育订单号 2016120110000009336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:26:55 --> array (
  'error' => '200078',
  'cost' => 0.092447996139526006,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:26:55 --> create 根据智慧体育订单号 2016120110000006335生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:27:05 --> array (
  'error' => '200078',
  'cost' => 0.091295003890991003,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:27:05 --> create 根据智慧体育订单号 2016120110000009336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:27:15 --> array (
  'error' => '200078',
  'cost' => 0.091869115829467995,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:27:15 --> create 根据智慧体育订单号 2016120110000006335生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:27:25 --> array (
  'error' => '200078',
  'cost' => 0.085799932479857996,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:27:25 --> create 根据智慧体育订单号 2016120110000009336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:27:36 --> array (
  'error' => '200078',
  'cost' => 0.11666488647461,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:27:36 --> create 根据智慧体育订单号 2016120110000006335生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:27:46 --> array (
  'error' => '200078',
  'cost' => 0.11549806594849001,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:27:46 --> create 根据智慧体育订单号 2016120110000009336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:27:56 --> array (
  'error' => '200078',
  'cost' => 0.083380937576294001,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:27:56 --> create 根据智慧体育订单号 2016120110000006335生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:28:06 --> array (
  'error' => '200078',
  'cost' => 0.13154697418212999,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:28:06 --> create 根据智慧体育订单号 2016120110000009336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:28:16 --> array (
  'error' => '200078',
  'cost' => 0.079664945602417006,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:28:16 --> create 根据智慧体育订单号 2016120110000006335生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:28:26 --> array (
  'error' => '200078',
  'cost' => 0.095887899398804002,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:28:26 --> create 根据智慧体育订单号 2016120110000009336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:28:36 --> array (
  'error' => '200078',
  'cost' => 0.1013879776001,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:28:36 --> create 根据智慧体育订单号 2016120110000006335生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:28:46 --> array (
  'error' => '200078',
  'cost' => 0.098773956298828,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:28:46 --> create 根据智慧体育订单号 2016120110000009336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:28:57 --> array (
  'error' => '200078',
  'cost' => 0.11580491065979,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:28:57 --> create 根据智慧体育订单号 2016120110000006335生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:29:07 --> array (
  'error' => '200078',
  'cost' => 0.096162080764770994,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:29:07 --> create 根据智慧体育订单号 2016120110000009336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:29:17 --> array (
  'error' => '200078',
  'cost' => 0.10036802291870001,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:29:17 --> create 根据智慧体育订单号 2016120110000006335生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:29:27 --> array (
  'error' => '200078',
  'cost' => 0.088325977325438995,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:29:27 --> create 根据智慧体育订单号 2016120110000009336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:29:37 --> array (
  'error' => '200078',
  'cost' => 0.096229076385498005,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:29:37 --> create 根据智慧体育订单号 2016120110000006335生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:29:47 --> array (
  'error' => '200078',
  'cost' => 0.089071989059448006,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:29:47 --> create 根据智慧体育订单号 2016120110000009336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:29:57 --> array (
  'error' => '200078',
  'cost' => 0.10266304016113,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:29:57 --> create 根据智慧体育订单号 2016120110000006335生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:30:07 --> array (
  'error' => '200078',
  'cost' => 0.099374055862426994,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:30:07 --> create 根据智慧体育订单号 2016120110000000334生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:30:17 --> array (
  'error' => '200078',
  'cost' => 0.099344015121460003,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:30:17 --> create 根据智慧体育订单号 2016120110000009336生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:30:28 --> array (
  'error' => '200078',
  'cost' => 0.094501972198485995,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:30:28 --> create 根据智慧体育订单号 2016120110000006335生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:32:15 --> array (
  'error' => '200078',
  'cost' => 0.098721981048583998,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:32:15 --> create 根据智慧体育订单号 2016120110000000334生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:32:25 --> array (
  'error' => '200078',
  'cost' => 0.087936162948607996,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:32:25 --> create 根据智慧体育订单号 2016120110000000334生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:32:35 --> array (
  'error' => '200078',
  'cost' => 0.11719608306885,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:32:35 --> create 根据智慧体育订单号 2016120110000000334生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:32:45 --> array (
  'error' => '200078',
  'cost' => 0.094695806503295996,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:32:45 --> create 根据智慧体育订单号 2016120110000000334生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:32:55 --> array (
  'error' => '200078',
  'cost' => 0.091710805892944003,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:32:55 --> create 根据智慧体育订单号 2016120110000000334生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:33:05 --> array (
  'error' => '200078',
  'cost' => 0.13173413276672,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:33:05 --> create 根据智慧体育订单号 2016120110000000334生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:33:15 --> array (
  'error' => '200078',
  'cost' => 0.089968919754028001,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:33:15 --> create 根据智慧体育订单号 2016120110000000334生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:33:26 --> array (
  'error' => '200078',
  'cost' => 0.081707000732422,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:33:26 --> create 根据智慧体育订单号 2016120110000000334生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:33:36 --> array (
  'error' => '200078',
  'cost' => 0.095505952835082994,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:33:36 --> create 根据智慧体育订单号 2016120110000000334生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:33:46 --> array (
  'error' => '200078',
  'cost' => 0.092082977294922,
  'info' => '商品信息错误',
)
ERROR - 2016-12-06 19:33:46 --> create 根据智慧体育订单号 2016120110000000334生成金飞鹰订单出错，错误原因：商品信息错误
ERROR - 2016-12-06 19:33:56 --> array (
  'error' => 0,
  'cost' => 0.16416907310486001,
  'result' => 
  array (
    'ordernumber' => '712102116632',
    'onlycode' => '1889EAE640801E251DFBE8268EE37BF7',
    'totoalsale' => NULL,
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:07 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:07 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:08 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:08 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:09 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:09 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:09 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:09 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:09 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:09 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:09 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:09 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:09 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:09 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:09 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:47:09 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:47:09 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:48:28 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.013218879699707,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:48:38 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:48:38 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0043518543243408,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:48:48 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:48:48 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0043280124664307,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:48:58 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:48:58 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0043799877166748,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:49:08 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:49:08 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0044689178466797,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:49:18 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:49:18 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0040669441223145,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:49:28 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:49:28 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0047409534454346,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:49:38 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:49:38 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0065970420837402,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:49:48 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:49:48 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0044059753417969,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:49:58 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:49:58 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0044670104980469,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:50:08 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:50:08 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0046799182891846,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:50:18 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:50:18 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.00531005859375,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:50:28 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:50:28 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0044119358062744,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:50:38 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:50:38 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0045809745788574,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:50:47 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0051360130310059,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:50:57 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:50:57 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0042510032653809,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:51:07 --> Severity: Warning --> Invalid argument supplied for foreach() /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 33
ERROR - 2016-12-06 19:51:07 --> Bad response:
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Use of undefined constant PARTNER_SOFTYKT_ORDER_STATE_OK - assumed 'PARTNER_SOFTYKT_ORDER_STATE_OK'</p>
<p>Filename: softykt/OrderSoftykt_model.php</p>
<p>Line Number: 30</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/models/softykt/OrderSoftykt_model.php<br />
			Line: 30<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/application/controllers/softykt/Order.php<br />
			Line: 21<br />
			Function: listNeedSync			</p>

		
	
		
	
		
	
		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/liangkaixuan/code/open-contest/api/webroot/index.php<br />
			Line: 293<br />
			Function: require_once			</p>

		
	

</div>{"error":0,"cost":0.0047299861907959,"total":3,"page":1,"size":100,"data":[{"pk_order_partner_softykt":"2","fk_order":"335","out_trade_no":"2016120110000006335","partner_order_number":"102106600132","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 11:44:30","utime":"2016-12-06 19:26:45"},{"pk_order_partner_softykt":"3","fk_order":"334","out_trade_no":"2016120110000001334","partner_order_number":"712102116632","partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:29:39","utime":"2016-12-06 19:33:56"},{"pk_order_partner_softykt":"4","fk_order":"333","out_trade_no":"2016120110000000333","partner_order_number":null,"partner_order_detail_id":null,"partner_verify_number":null,"state":"0","ctime":"2016-12-06 19:47:03","utime":"0000-00-00 00:00:00"}]}[{"host":{"contest.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/Order\/list_need_sync.json","method":"GET","params":{"size":100},"headers":[]}]
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:19 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:19 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:20 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:20 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:52:21 --> Severity: Notice --> Use of undefined constant GETUSEREXTMOBILEYES - assumed 'GETUSEREXTMOBILEYES' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 180
ERROR - 2016-12-06 19:52:21 --> array (
  'msg' => '下单时，获取用户信息失败',
  'param' => 
  array (
    'fk_user' => '3',
  ),
)
ERROR - 2016-12-06 19:53:20 --> Severity: Warning --> Illegal string offset 'mobile' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 208
ERROR - 2016-12-06 19:53:20 --> Severity: Warning --> Illegal string offset 'mobile' /home/liangkaixuan/code/open-contest/task/application/controllers/softykt/PlaceOrder.php 217
ERROR - 2016-12-06 19:53:20 --> array (
  'error' => 0,
  'cost' => 0.14463400840759,
  'result' => 
  array (
    'ordernumber' => '062109327932',
    'onlycode' => '4100927C1D3D1A90900725733786EB4E',
    'totoalsale' => NULL,
  ),
)
ERROR - 2016-12-06 20:00:49 --> array (
  'error' => 0,
  'cost' => 0.16081809997558999,
  'result' => 
  array (
    'ordernumber' => '342104563632',
    'onlycode' => 'E78B5D25537A888C5AA28504F7BB0409',
    'totoalsale' => NULL,
  ),
)
