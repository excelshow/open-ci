<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2016-12-07 15:28:53 --> Bad response:<br />
<font size='1'><table class='xdebug-error xe-fatal-error' dir='ltr' border='1' cellspacing='0' cellpadding='1'>
<tr><th align='left' bgcolor='#f57900' colspan="5"><span style='background-color: #cc0000; color: #fce94f; font-size: x-large;'>( ! )</span> Fatal error: Call to undefined method Softykt_model::getToken() in /home/liangkaixuan/code/open-api/api/application/controllers/Softykt_Base.php on line <i>19</i></th></tr>
<tr><th align='left' bgcolor='#e9b96e' colspan='5'>Call Stack</th></tr>
<tr><th align='center' bgcolor='#eeeeec'>#</th><th align='left' bgcolor='#eeeeec'>Time</th><th align='left' bgcolor='#eeeeec'>Memory</th><th align='left' bgcolor='#eeeeec'>Function</th><th align='left' bgcolor='#eeeeec'>Location</th></tr>
<tr><td bgcolor='#eeeeec' align='center'>1</td><td bgcolor='#eeeeec' align='center'>0.0000</td><td bgcolor='#eeeeec' align='right'>233064</td><td bgcolor='#eeeeec'>{main}(  )</td><td title='/home/liangkaixuan/code/open-api/api/webroot/index.php' bgcolor='#eeeeec'>.../index.php<b>:</b>0</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>2</td><td bgcolor='#eeeeec' align='center'>0.0001</td><td bgcolor='#eeeeec' align='right'>244408</td><td bgcolor='#eeeeec'>require_once( <font color='#00bb00'>'/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php'</font> )</td><td title='/home/liangkaixuan/code/open-api/api/webroot/index.php' bgcolor='#eeeeec'>.../index.php<b>:</b>293</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>3</td><td bgcolor='#eeeeec' align='center'>0.0036</td><td bgcolor='#eeeeec' align='right'>784720</td><td bgcolor='#eeeeec'><a href='http://www.php.net/function.call-user-func-array:{/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php:514}' target='_new'>call_user_func_array:{/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php:514}</a>
(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php' bgcolor='#eeeeec'>.../CodeIgniter.php<b>:</b>514</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>4</td><td bgcolor='#eeeeec' align='center'>0.0036</td><td bgcolor='#eeeeec' align='right'>785528</td><td bgcolor='#eeeeec'>Rest_Controller->_remap(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php' bgcolor='#eeeeec'>.../CodeIgniter.php<b>:</b>514</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>5</td><td bgcolor='#eeeeec' align='center'>0.0036</td><td bgcolor='#eeeeec' align='right'>787080</td><td bgcolor='#eeeeec'>DIY_Controller->_fire_method(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/libraries/REST_Controller.php' bgcolor='#eeeeec'>.../REST_Controller.php<b>:</b>433</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>6</td><td bgcolor='#eeeeec' align='center'>0.0036</td><td bgcolor='#eeeeec' align='right'>787128</td><td bgcolor='#eeeeec'><a href='http://www.php.net/function.call-user-func-array:{/home/liangkaixuan/code/open-base/ci/system/libraries/DIY-Controller.php:21}' target='_new'>call_user_func_array:{/home/liangkaixuan/code/open-base/ci/system/libraries/DIY_Controller.php:21}</a>
(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/libraries/DIY_Controller.php' bgcolor='#eeeeec'>.../DIY_Controller.php<b>:</b>21</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>7</td><td bgcolor='#eeeeec' align='center'>0.0036</td><td bgcolor='#eeeeec' align='right'>787504</td><td bgcolor='#eeeeec'>Product->get_product_post(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/libraries/DIY_Controller.php' bgcolor='#eeeeec'>.../DIY_Controller.php<b>:</b>21</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>8</td><td bgcolor='#eeeeec' align='center'>0.0040</td><td bgcolor='#eeeeec' align='right'>790640</td><td bgcolor='#eeeeec'>Softykt_Base->callExternalLink(  )</td><td title='/home/liangkaixuan/code/open-api/api/application/controllers/softykt/Product.php' bgcolor='#eeeeec'>.../Product.php<b>:</b>42</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>9</td><td bgcolor='#eeeeec' align='center'>0.0040</td><td bgcolor='#eeeeec' align='right'>791280</td><td bgcolor='#eeeeec'>Softykt_Base->getToken(  )</td><td title='/home/liangkaixuan/code/open-api/api/application/controllers/Softykt_Base.php' bgcolor='#eeeeec'>.../Softykt_Base.php<b>:</b>55</td></tr>
</table></font>

<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Warning</p>
<p>Message:  Cannot modify header information - headers already sent by (output started at /home/liangkaixuan/code/open-api/api/application/controllers/Softykt_Base.php:19)</p>
<p>Filename: core/Common.php</p>
<p>Line Number: 573</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
	
		
	
		
	
		
	
		
	

</div>
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Error</p>
<p>Message:  Call to undefined method Softykt_model::getToken()</p>
<p>Filename: controllers/Softykt_Base.php</p>
<p>Line Number: 19</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
	
		
	

</div>[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/product\/get_product.json","method":"POST","params":{"corp_id":"1","user_id":"35"},"headers":[]}]
ERROR - 2016-12-07 15:28:53 --> 金飞鹰产品信息同步失败
ERROR - 2016-12-07 15:28:53 --> Bad response:<br />
<font size='1'><table class='xdebug-error xe-fatal-error' dir='ltr' border='1' cellspacing='0' cellpadding='1'>
<tr><th align='left' bgcolor='#f57900' colspan="5"><span style='background-color: #cc0000; color: #fce94f; font-size: x-large;'>( ! )</span> Fatal error: Call to undefined method Softykt_model::getToken() in /home/liangkaixuan/code/open-api/api/application/controllers/Softykt_Base.php on line <i>19</i></th></tr>
<tr><th align='left' bgcolor='#e9b96e' colspan='5'>Call Stack</th></tr>
<tr><th align='center' bgcolor='#eeeeec'>#</th><th align='left' bgcolor='#eeeeec'>Time</th><th align='left' bgcolor='#eeeeec'>Memory</th><th align='left' bgcolor='#eeeeec'>Function</th><th align='left' bgcolor='#eeeeec'>Location</th></tr>
<tr><td bgcolor='#eeeeec' align='center'>1</td><td bgcolor='#eeeeec' align='center'>0.0000</td><td bgcolor='#eeeeec' align='right'>233064</td><td bgcolor='#eeeeec'>{main}(  )</td><td title='/home/liangkaixuan/code/open-api/api/webroot/index.php' bgcolor='#eeeeec'>.../index.php<b>:</b>0</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>2</td><td bgcolor='#eeeeec' align='center'>0.0002</td><td bgcolor='#eeeeec' align='right'>244408</td><td bgcolor='#eeeeec'>require_once( <font color='#00bb00'>'/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php'</font> )</td><td title='/home/liangkaixuan/code/open-api/api/webroot/index.php' bgcolor='#eeeeec'>.../index.php<b>:</b>293</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>3</td><td bgcolor='#eeeeec' align='center'>0.0037</td><td bgcolor='#eeeeec' align='right'>780880</td><td bgcolor='#eeeeec'><a href='http://www.php.net/function.call-user-func-array:{/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php:514}' target='_new'>call_user_func_array:{/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php:514}</a>
(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php' bgcolor='#eeeeec'>.../CodeIgniter.php<b>:</b>514</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>4</td><td bgcolor='#eeeeec' align='center'>0.0037</td><td bgcolor='#eeeeec' align='right'>781688</td><td bgcolor='#eeeeec'>Rest_Controller->_remap(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php' bgcolor='#eeeeec'>.../CodeIgniter.php<b>:</b>514</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>5</td><td bgcolor='#eeeeec' align='center'>0.0037</td><td bgcolor='#eeeeec' align='right'>783240</td><td bgcolor='#eeeeec'>DIY_Controller->_fire_method(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/libraries/REST_Controller.php' bgcolor='#eeeeec'>.../REST_Controller.php<b>:</b>433</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>6</td><td bgcolor='#eeeeec' align='center'>0.0037</td><td bgcolor='#eeeeec' align='right'>783288</td><td bgcolor='#eeeeec'><a href='http://www.php.net/function.call-user-func-array:{/home/liangkaixuan/code/open-base/ci/system/libraries/DIY-Controller.php:21}' target='_new'>call_user_func_array:{/home/liangkaixuan/code/open-base/ci/system/libraries/DIY_Controller.php:21}</a>
(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/libraries/DIY_Controller.php' bgcolor='#eeeeec'>.../DIY_Controller.php<b>:</b>21</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>7</td><td bgcolor='#eeeeec' align='center'>0.0037</td><td bgcolor='#eeeeec' align='right'>783664</td><td bgcolor='#eeeeec'>Product->get_product_post(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/libraries/DIY_Controller.php' bgcolor='#eeeeec'>.../DIY_Controller.php<b>:</b>21</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>8</td><td bgcolor='#eeeeec' align='center'>0.0042</td><td bgcolor='#eeeeec' align='right'>786800</td><td bgcolor='#eeeeec'>Softykt_Base->callExternalLink(  )</td><td title='/home/liangkaixuan/code/open-api/api/application/controllers/softykt/Product.php' bgcolor='#eeeeec'>.../Product.php<b>:</b>42</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>9</td><td bgcolor='#eeeeec' align='center'>0.0042</td><td bgcolor='#eeeeec' align='right'>787440</td><td bgcolor='#eeeeec'>Softykt_Base->getToken(  )</td><td title='/home/liangkaixuan/code/open-api/api/application/controllers/Softykt_Base.php' bgcolor='#eeeeec'>.../Softykt_Base.php<b>:</b>55</td></tr>
</table></font>

<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Warning</p>
<p>Message:  Cannot modify header information - headers already sent by (output started at /home/liangkaixuan/code/open-api/api/application/controllers/Softykt_Base.php:19)</p>
<p>Filename: core/Common.php</p>
<p>Line Number: 573</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
	
		
	
		
	
		
	
		
	

</div>
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Error</p>
<p>Message:  Call to undefined method Softykt_model::getToken()</p>
<p>Filename: controllers/Softykt_Base.php</p>
<p>Line Number: 19</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
	
		
	

</div>[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/product\/get_product.json","method":"POST","params":{"corp_id":"1","user_id":"35"},"headers":[]}]
ERROR - 2016-12-07 15:28:53 --> 金飞鹰产品信息同步失败
ERROR - 2016-12-07 15:28:53 --> 404 Page Not Found: Faviconico/index
ERROR - 2016-12-07 15:29:04 --> 404 Page Not Found: contest//index
ERROR - 2016-12-07 15:29:09 --> url:http://10.2.2.5/order/get_total.json?seller_corp_id=1 errormsg:httpCode is 404
[{"host":{"contest.api.local.wechatsport.cn":["10.2.2.5"]},"api":"order\/get_total.json","method":"GET","params":{"seller_corp_id":"1"},"headers":[]}]
ERROR - 2016-12-07 15:29:09 --> url:http://10.2.2.5/order/get_total.json?seller_corp_id=1&start_time=2016-12-07+00%3A00%3A00&end_time=2016-12-07+23%3A59%3A59 errormsg:httpCode is 404
[{"host":{"contest.api.local.wechatsport.cn":["10.2.2.5"]},"api":"order\/get_total.json","method":"GET","params":{"seller_corp_id":"1","start_time":"2016-12-07 00:00:00","end_time":"2016-12-07 23:59:59"},"headers":[]}]
ERROR - 2016-12-07 15:29:11 --> url:http://10.2.2.5/order/get_total.json?seller_corp_id=1 errormsg:httpCode is 404
[{"host":{"contest.api.local.wechatsport.cn":["10.2.2.5"]},"api":"order\/get_total.json","method":"GET","params":{"seller_corp_id":"1"},"headers":[]}]
ERROR - 2016-12-07 15:29:11 --> url:http://10.2.2.5/order/get_total.json?seller_corp_id=1&start_time=2016-12-07+00%3A00%3A00&end_time=2016-12-07+23%3A59%3A59 errormsg:httpCode is 404
[{"host":{"contest.api.local.wechatsport.cn":["10.2.2.5"]},"api":"order\/get_total.json","method":"GET","params":{"seller_corp_id":"1","start_time":"2016-12-07 00:00:00","end_time":"2016-12-07 23:59:59"},"headers":[]}]
ERROR - 2016-12-07 15:29:13 --> Bad response:<br />
<font size='1'><table class='xdebug-error xe-fatal-error' dir='ltr' border='1' cellspacing='0' cellpadding='1'>
<tr><th align='left' bgcolor='#f57900' colspan="5"><span style='background-color: #cc0000; color: #fce94f; font-size: x-large;'>( ! )</span> Fatal error: Call to undefined method Softykt_model::getToken() in /home/liangkaixuan/code/open-api/api/application/controllers/Softykt_Base.php on line <i>19</i></th></tr>
<tr><th align='left' bgcolor='#e9b96e' colspan='5'>Call Stack</th></tr>
<tr><th align='center' bgcolor='#eeeeec'>#</th><th align='left' bgcolor='#eeeeec'>Time</th><th align='left' bgcolor='#eeeeec'>Memory</th><th align='left' bgcolor='#eeeeec'>Function</th><th align='left' bgcolor='#eeeeec'>Location</th></tr>
<tr><td bgcolor='#eeeeec' align='center'>1</td><td bgcolor='#eeeeec' align='center'>0.0000</td><td bgcolor='#eeeeec' align='right'>233064</td><td bgcolor='#eeeeec'>{main}(  )</td><td title='/home/liangkaixuan/code/open-api/api/webroot/index.php' bgcolor='#eeeeec'>.../index.php<b>:</b>0</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>2</td><td bgcolor='#eeeeec' align='center'>0.0001</td><td bgcolor='#eeeeec' align='right'>244408</td><td bgcolor='#eeeeec'>require_once( <font color='#00bb00'>'/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php'</font> )</td><td title='/home/liangkaixuan/code/open-api/api/webroot/index.php' bgcolor='#eeeeec'>.../index.php<b>:</b>293</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>3</td><td bgcolor='#eeeeec' align='center'>0.0029</td><td bgcolor='#eeeeec' align='right'>780880</td><td bgcolor='#eeeeec'><a href='http://www.php.net/function.call-user-func-array:{/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php:514}' target='_new'>call_user_func_array:{/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php:514}</a>
(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php' bgcolor='#eeeeec'>.../CodeIgniter.php<b>:</b>514</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>4</td><td bgcolor='#eeeeec' align='center'>0.0029</td><td bgcolor='#eeeeec' align='right'>781688</td><td bgcolor='#eeeeec'>Rest_Controller->_remap(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php' bgcolor='#eeeeec'>.../CodeIgniter.php<b>:</b>514</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>5</td><td bgcolor='#eeeeec' align='center'>0.0029</td><td bgcolor='#eeeeec' align='right'>783240</td><td bgcolor='#eeeeec'>DIY_Controller->_fire_method(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/libraries/REST_Controller.php' bgcolor='#eeeeec'>.../REST_Controller.php<b>:</b>433</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>6</td><td bgcolor='#eeeeec' align='center'>0.0029</td><td bgcolor='#eeeeec' align='right'>783288</td><td bgcolor='#eeeeec'><a href='http://www.php.net/function.call-user-func-array:{/home/liangkaixuan/code/open-base/ci/system/libraries/DIY-Controller.php:21}' target='_new'>call_user_func_array:{/home/liangkaixuan/code/open-base/ci/system/libraries/DIY_Controller.php:21}</a>
(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/libraries/DIY_Controller.php' bgcolor='#eeeeec'>.../DIY_Controller.php<b>:</b>21</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>7</td><td bgcolor='#eeeeec' align='center'>0.0029</td><td bgcolor='#eeeeec' align='right'>783664</td><td bgcolor='#eeeeec'>Product->get_product_post(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/libraries/DIY_Controller.php' bgcolor='#eeeeec'>.../DIY_Controller.php<b>:</b>21</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>8</td><td bgcolor='#eeeeec' align='center'>0.0034</td><td bgcolor='#eeeeec' align='right'>786800</td><td bgcolor='#eeeeec'>Softykt_Base->callExternalLink(  )</td><td title='/home/liangkaixuan/code/open-api/api/application/controllers/softykt/Product.php' bgcolor='#eeeeec'>.../Product.php<b>:</b>42</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>9</td><td bgcolor='#eeeeec' align='center'>0.0034</td><td bgcolor='#eeeeec' align='right'>787440</td><td bgcolor='#eeeeec'>Softykt_Base->getToken(  )</td><td title='/home/liangkaixuan/code/open-api/api/application/controllers/Softykt_Base.php' bgcolor='#eeeeec'>.../Softykt_Base.php<b>:</b>55</td></tr>
</table></font>

<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Warning</p>
<p>Message:  Cannot modify header information - headers already sent by (output started at /home/liangkaixuan/code/open-api/api/application/controllers/Softykt_Base.php:19)</p>
<p>Filename: core/Common.php</p>
<p>Line Number: 573</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
	
		
	
		
	
		
	
		
	

</div>
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Error</p>
<p>Message:  Call to undefined method Softykt_model::getToken()</p>
<p>Filename: controllers/Softykt_Base.php</p>
<p>Line Number: 19</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
	
		
	

</div>[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/product\/get_product.json","method":"POST","params":{"corp_id":"1","user_id":"35"},"headers":[]}]
ERROR - 2016-12-07 15:29:13 --> 金飞鹰产品信息同步失败
ERROR - 2016-12-07 15:29:23 --> Bad response:<br />
<font size='1'><table class='xdebug-error xe-fatal-error' dir='ltr' border='1' cellspacing='0' cellpadding='1'>
<tr><th align='left' bgcolor='#f57900' colspan="5"><span style='background-color: #cc0000; color: #fce94f; font-size: x-large;'>( ! )</span> Fatal error: Call to undefined method Softykt_model::getToken() in /home/liangkaixuan/code/open-api/api/application/controllers/Softykt_Base.php on line <i>19</i></th></tr>
<tr><th align='left' bgcolor='#e9b96e' colspan='5'>Call Stack</th></tr>
<tr><th align='center' bgcolor='#eeeeec'>#</th><th align='left' bgcolor='#eeeeec'>Time</th><th align='left' bgcolor='#eeeeec'>Memory</th><th align='left' bgcolor='#eeeeec'>Function</th><th align='left' bgcolor='#eeeeec'>Location</th></tr>
<tr><td bgcolor='#eeeeec' align='center'>1</td><td bgcolor='#eeeeec' align='center'>0.0000</td><td bgcolor='#eeeeec' align='right'>233064</td><td bgcolor='#eeeeec'>{main}(  )</td><td title='/home/liangkaixuan/code/open-api/api/webroot/index.php' bgcolor='#eeeeec'>.../index.php<b>:</b>0</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>2</td><td bgcolor='#eeeeec' align='center'>0.0002</td><td bgcolor='#eeeeec' align='right'>244408</td><td bgcolor='#eeeeec'>require_once( <font color='#00bb00'>'/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php'</font> )</td><td title='/home/liangkaixuan/code/open-api/api/webroot/index.php' bgcolor='#eeeeec'>.../index.php<b>:</b>293</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>3</td><td bgcolor='#eeeeec' align='center'>0.0033</td><td bgcolor='#eeeeec' align='right'>780880</td><td bgcolor='#eeeeec'><a href='http://www.php.net/function.call-user-func-array:{/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php:514}' target='_new'>call_user_func_array:{/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php:514}</a>
(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php' bgcolor='#eeeeec'>.../CodeIgniter.php<b>:</b>514</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>4</td><td bgcolor='#eeeeec' align='center'>0.0033</td><td bgcolor='#eeeeec' align='right'>781688</td><td bgcolor='#eeeeec'>Rest_Controller->_remap(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/core/CodeIgniter.php' bgcolor='#eeeeec'>.../CodeIgniter.php<b>:</b>514</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>5</td><td bgcolor='#eeeeec' align='center'>0.0033</td><td bgcolor='#eeeeec' align='right'>783240</td><td bgcolor='#eeeeec'>DIY_Controller->_fire_method(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/libraries/REST_Controller.php' bgcolor='#eeeeec'>.../REST_Controller.php<b>:</b>433</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>6</td><td bgcolor='#eeeeec' align='center'>0.0033</td><td bgcolor='#eeeeec' align='right'>783288</td><td bgcolor='#eeeeec'><a href='http://www.php.net/function.call-user-func-array:{/home/liangkaixuan/code/open-base/ci/system/libraries/DIY-Controller.php:21}' target='_new'>call_user_func_array:{/home/liangkaixuan/code/open-base/ci/system/libraries/DIY_Controller.php:21}</a>
(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/libraries/DIY_Controller.php' bgcolor='#eeeeec'>.../DIY_Controller.php<b>:</b>21</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>7</td><td bgcolor='#eeeeec' align='center'>0.0033</td><td bgcolor='#eeeeec' align='right'>783664</td><td bgcolor='#eeeeec'>Product->get_product_post(  )</td><td title='/home/liangkaixuan/code/open-base/ci/system/libraries/DIY_Controller.php' bgcolor='#eeeeec'>.../DIY_Controller.php<b>:</b>21</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>8</td><td bgcolor='#eeeeec' align='center'>0.0037</td><td bgcolor='#eeeeec' align='right'>786800</td><td bgcolor='#eeeeec'>Softykt_Base->callExternalLink(  )</td><td title='/home/liangkaixuan/code/open-api/api/application/controllers/softykt/Product.php' bgcolor='#eeeeec'>.../Product.php<b>:</b>42</td></tr>
<tr><td bgcolor='#eeeeec' align='center'>9</td><td bgcolor='#eeeeec' align='center'>0.0037</td><td bgcolor='#eeeeec' align='right'>787440</td><td bgcolor='#eeeeec'>Softykt_Base->getToken(  )</td><td title='/home/liangkaixuan/code/open-api/api/application/controllers/Softykt_Base.php' bgcolor='#eeeeec'>.../Softykt_Base.php<b>:</b>55</td></tr>
</table></font>

<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Warning</p>
<p>Message:  Cannot modify header information - headers already sent by (output started at /home/liangkaixuan/code/open-api/api/application/controllers/Softykt_Base.php:19)</p>
<p>Filename: core/Common.php</p>
<p>Line Number: 573</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
	
		
	
		
	
		
	
		
	

</div>
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Error</p>
<p>Message:  Call to undefined method Softykt_model::getToken()</p>
<p>Filename: controllers/Softykt_Base.php</p>
<p>Line Number: 19</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
	
		
	

</div>[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/product\/get_product.json","method":"POST","params":{"corp_id":"1","user_id":"35"},"headers":[]}]
ERROR - 2016-12-07 15:29:23 --> 金飞鹰产品信息同步失败
ERROR - 2016-12-07 15:40:13 --> url:http://10.2.8.119/softykt/product/get_product.json errormsg:Operation timed out after 300 milliseconds with 0 out of -1 bytes received
[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/product\/get_product.json","method":"POST","params":{"corp_id":"1","user_id":"35"},"headers":[]}]
ERROR - 2016-12-07 15:40:13 --> 金飞鹰产品信息同步失败
ERROR - 2016-12-07 15:41:37 --> 金飞鹰产品信息同步失败
ERROR - 2016-12-07 15:42:31 --> url:http://10.2.8.119/softykt/product/get_product.json errormsg:Operation timed out after 300 milliseconds with 0 out of -1 bytes received
[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/product\/get_product.json","method":"POST","params":{"corp_id":"1","user_id":"35"},"headers":[]}]
ERROR - 2016-12-07 15:42:31 --> 金飞鹰产品信息同步失败
ERROR - 2016-12-07 15:45:00 --> Bad response:Array
(
    [rcode] => 666
    [result] => Array
        (
            [product] => Array
                (
                    [0] => Array
                        (
                            [productid] => 160
                            [scenicid] => 53
                            [productname] => 全天滑雪票111
                            [producttype] => 1
                            [usepeoplenum] => 1
                            [validstatus] => 1
                            [validbegindate] => 0000-00-00 00:00:00
                            [validenddate] => 0000-00-00 00:00:00
                            [price] => 150.00
                            [sellprice] => 130.00
                            [agentprice] => 100.00
                            [returnflag] => 0
                            [numberflag] => 0
                            [number] => 0
                            [useflag] => 0
                            [memo] => 全天滑雪票详情
                            [webpic] => 
                            [consumestate] => 1
                            [consumebegindate] => 2016-07-06 12:12:12
                            [consumeenddate] => 2017-07-06 12:12:12
                            [hour] => 0
                            [createtime] => 2016-10-20 14:46:38
                        )

                    [1] => Array
                        (
                            [productid] => 161
                            [scenicid] => 53
                            [productname] => 滑雪教练
                            [producttype] => 2
                            [usepeoplenum] => 0
                            [validstatus] => 2
                            [validbegindate] => 2016-08-02 00:00:00
                            [validenddate] => 2016-12-02 00:00:00
                            [price] => 200.00
                            [sellprice] => 150.00
                            [agentprice] => 120.00
                            [returnflag] => 0
                            [numberflag] => 0
                            [number] => 0
                            [useflag] => 0
                            [memo] => 滑雪教练详情
                            [webpic] => 
                            [consumestate] => 1
                            [consumebegindate] => 2016-08-02 00:00:00
                            [consumeenddate] => 2016-12-02 00:00:00
                            [hour] => 0
                            [createtime] => 2016-10-20 14:46:38
                        )

                    [2] => Array
                        (
                            [productid] => 162
                            [scenicid] => 53
                            [productname] => 滑雪板
                            [producttype] => 3
                            [usepeoplenum] => 0
                            [validstatus] => 2
                            [validbegindate] => 2016-08-02 00:00:00
                            [validenddate] => 2016-12-02 00:00:00
                            [price] => 300.00
                            [sellprice] => 280.00
                            [agentprice] => 200.00
                            [returnflag] => 0
                            [numberflag] => 0
                            [number] => 0
                            [useflag] => 0
                            [memo] => 滑雪板详情
                            [webpic] => 
                            [consumestate] => 2
                            [consumebegindate] => 0000-00-00 00:00:00
                            [consumeenddate] => 0000-00-00 00:00:00
                            [hour] => 2
                            [createtime] => 2016-10-20 14:46:38
                        )

                )

            [paging] => 1
        )

)
[{"host":{"api.api.liangkaixuan.wechatsport.cn":["10.2.8.119"]},"api":"softykt\/product\/get_product.json","method":"POST","params":{"corp_id":"6","user_id":"35"},"headers":[]}]
ERROR - 2016-12-07 15:45:00 --> 金飞鹰产品信息同步失败
ERROR - 2016-12-07 15:46:20 --> url:http://10.2.2.5/order/get_total.json?seller_corp_id=1 errormsg:httpCode is 404
[{"host":{"contest.api.local.wechatsport.cn":["10.2.2.5"]},"api":"order\/get_total.json","method":"GET","params":{"seller_corp_id":"1"},"headers":[]}]
ERROR - 2016-12-07 15:46:20 --> url:http://10.2.2.5/order/get_total.json?seller_corp_id=1&start_time=2016-12-07+00%3A00%3A00&end_time=2016-12-07+23%3A59%3A59 errormsg:httpCode is 404
[{"host":{"contest.api.local.wechatsport.cn":["10.2.2.5"]},"api":"order\/get_total.json","method":"GET","params":{"seller_corp_id":"1","start_time":"2016-12-07 00:00:00","end_time":"2016-12-07 23:59:59"},"headers":[]}]
ERROR - 2016-12-07 18:34:22 --> url:http://10.2.2.5/order/get_total.json?seller_corp_id=1 errormsg:httpCode is 404
[{"host":{"contest.api.local.wechatsport.cn":["10.2.2.5"]},"api":"order\/get_total.json","method":"GET","params":{"seller_corp_id":"1"},"headers":[]}]
ERROR - 2016-12-07 18:34:22 --> url:http://10.2.2.5/order/get_total.json?seller_corp_id=1&start_time=2016-12-07+00%3A00%3A00&end_time=2016-12-07+23%3A59%3A59 errormsg:httpCode is 404
[{"host":{"contest.api.local.wechatsport.cn":["10.2.2.5"]},"api":"order\/get_total.json","method":"GET","params":{"seller_corp_id":"1","start_time":"2016-12-07 00:00:00","end_time":"2016-12-07 23:59:59"},"headers":[]}]
ERROR - 2016-12-07 18:34:22 --> url:http://10.2.2.5/order/get_total.json?seller_corp_id=1 errormsg:httpCode is 404
[{"host":{"contest.api.local.wechatsport.cn":["10.2.2.5"]},"api":"order\/get_total.json","method":"GET","params":{"seller_corp_id":"1"},"headers":[]}]
ERROR - 2016-12-07 18:34:22 --> url:http://10.2.2.5/order/get_total.json?seller_corp_id=1&start_time=2016-12-07+00%3A00%3A00&end_time=2016-12-07+23%3A59%3A59 errormsg:httpCode is 404
[{"host":{"contest.api.local.wechatsport.cn":["10.2.2.5"]},"api":"order\/get_total.json","method":"GET","params":{"seller_corp_id":"1","start_time":"2016-12-07 00:00:00","end_time":"2016-12-07 23:59:59"},"headers":[]}]
ERROR - 2016-12-07 18:34:25 --> Severity: Warning --> in_array() expects parameter 2 to be array, null given /home/liangkaixuan/code/open-wisdom/manager/application/controllers/contest/Contest.php 133
ERROR - 2016-12-07 18:34:34 --> 金飞鹰产品信息同步失败
