{include file='_header.tpl'}
<link rel="stylesheet" type="text/css" href="{'manager/css/base.css'|cdnurl}">
<link rel="stylesheet" type="text/css" href="{'manager/css/menu.css'|cdnurl}">
<link rel="stylesheet" type="text/css" href="{'manager/css/menu2.css'|cdnurl}">
<!--—自适应布局---->
<div class="container-fluid">
	<div class="row">
		<!-- rightStart-->
		<div class="">
			<div class="breadcrumbs-box">
				<ol class="breadcrumb">
					<li>
						<a href="/wxapps/auth">公众号</a>
					</li>
					<li class="active">菜单管理</li>
				</ol>
			</div>
			<div class="right-con">
				<div class="row">
					<div class="col-sm-12">
						<div class="ibox float-e-margins">
							<div class="ibox-content">
								<div class="mrg-b20">
									<h5>编辑公众号菜单</h5>
								</div>
								<div class="pre-mpmenu-box">  
									<div class="pre-mpmenu pull-left" id="pre-mpmenu"></div>
									<div class="menu_form_area" id="edit-box">
										<div id="js_none" class="menu_initial_tips tips_global" >点击左侧菜单进行编辑操作</div>
									</div>
								</div>
								{literal}
									<!-- 编辑菜单 -->
									<script type="text/html" id="menu-edit">
											<div id="js_rightBox" class="portable_editor to_left" style="display: block;">
												<div class="editor_inner">
													<div class="global_mod float_layout menu_form_hd js_second_title_bar">
														<h4 class="global_info">{{menuTitle}}名称</h4>
														<div class="global_extra">
															<a href="javascript:void(0);" id="jsDelBt">删除{{menuTitle}}</a>
														</div>
													</div>
													<div class="menu_form_bd" id="view">
														<div id="js_innerNone" style="display:{{displayNone}};" class="msg_sender_tips tips_global">{{alterText}}</div>
														<div class="frm_control_group js_setNameBox">
															<label for="" class="frm_label"> <strong class="title js_menuTitle">{{menuTitle}}名称</strong>
															</label>
															<div class="frm_controls">
																<span class="frm_input_box with_counter counter_in append">
																	<input type="text" class="frm_input js_menu_name" id="menu-name" value="{{name}}"></span>
																<p class="frm_msg fail js_titleEorTips dn" style="display: none;">字数超过上限</p>
																<p class="frm_msg fail js_titlenoTips dn" style="display: none;">请输入菜单名称</p>
																<p class="frm_tips js_titleNolTips">字数不超过8个汉字或16个字母</p>
															</div>
														</div>
														<div class="frm_control_group" style="display:{{displayBlock}};">
															<label for="" class="frm_label"> <strong class="title js_menuContent">{{menuTitle}}内容</strong>
															</label>
															<div class="frm_controls frm_vertical_pt">
																<label class="frm_radio_label js_radio_selected selected" data-editing="0" style="display:none">
																	<i class="icon_radio"></i>
																	<span class="lbl_content">发送消息</span>
																	<input type="radio" name="hello" class="frm_radio"></label>
																<label class="frm_radio_label js_radio_selected" data-editing="0">
																	<i class="icon_radio"></i>
																	<span class="lbl_content">跳转网页</span>
																	<input type="radio" name="hello" class="frm_radio"></label>
															</div>
														</div>
														<div class="menu_content_container" style="display:{{displayBlock}};">
															<div class="menu_content send jsMain" id="edit" style="display: block;">
																<div class="msg_sender" id="editDiv">
																	<div class="msg_tab">
																		<div class="tab_navs_panel">
																			<span class="tab_navs_switch_wrp switch_prev js_switch_prev">
																				<span class="tab_navs_switch"></span>
																			</span>
																			<span class="tab_navs_switch_wrp switch_next js_switch_next" style="display: block;">
																				<span class="tab_navs_switch"></span>
																			</span>
																			<div class="tab_navs_wrp">
																				<ul class="tab_navs js_tab_navs" style="margin-left:0;">
																					<li class="tab_nav tab_text  width4 selected" data-type="10" data-tab=".js_appmsgArea" data-tooltip="文字">
																						<a href="javascript:void(0);" onclick="return false;">
																							&nbsp;
																							<i class="icon_msg_sender"></i>
																							<span class="msg_tab_title">文字</span>
																						</a>
																					</li>
																				</ul>
																			</div>
																		</div>
																		<div class="tab_text">
																			<div class="tab_content">
																				<div class="js_appmsgArea inner ">
																					<div class="tab_cont_cover jsMsgSendTab" data-index="0">
																						<textarea dir='left' placeholder="文本" id="menu-text">{{value}}</textarea>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<p class="profile_link_msg_global menu_send mini_tips warn dn js_warn" style="display: none;">请勿添加其他公众号的主页链接</p>
															</div>
															<div class="menu_content url jsMain" id="url" style="display: none;">
																<form action="" id="urlForm" onsubmit="return false;">
																	<p class="menu_content_tips tips_global">订阅者点击该子菜单会跳到以下链接</p>
																	<div class="frm_control_group">
																		<label for="" class="frm_label">页面地址</label>
																		<div class="frm_controls">
																			<span class="frm_input_box">
																				<input type="text" class="frm_input" placeholder="跳转链接" id="menu-url" value="{{url}}"></span>
																			<p class="profile_link_msg_global menu_url mini_tips warn dn js_warn" style="display: none;">请输入正确的链接地址</p>
																		</div>
																	</div>
																	<div class="frm_control_group btn_appmsg_wrap">
																		<div class="frm_controls">
																			<a href="javascript:;" id="js_appmsgPop">获取更多链接</a>
																		</div>
																	</div>
																</form>
															</div>
															<div id="js_errTips" style="display:none;" class="msg_sender_msg mini_tips warn"></div>
														</div>
												</div>
											</div>
											<span class="editor_arrow_wrp">
												<i class="editor_arrow editor_arrow_out"></i>
												<i class="editor_arrow editor_arrow_in"></i>
											</span>
										</div>
									</script>
									<!-- 显示菜单 -->
									<script id="menu-show" type="text/html">
										<div class="menu_preview_area">
											<div class="phone-box mobile_menu_preview">
												<div class="mobile_hd tc">{{title}}</div>
												<div class="mobile_bd">
													<ul class="pre_menu_list grid_line menulist">
													{{each list as value i}}
													{{if i < 3}}
														<li class="edit_menu  jsMenu pre_menu_item grid_item jslevel1 size1of3"
														level="1"
						                              	vname="{{value.name}}" 
						                              	mtype="{{value.type}}"
						                              	murl="{{value.url}}" 
						                              	mkey="{{value.key}}"
						                              	mediaId="{{value.mmedia_id}}"
						                              	subButton="{{value.sub_button}}">
															<a href="javascript:void(0);" class="pre_menu_link" draggable="false"> 
															<i class="icon_menu_dot js_icon_menu_dot dn"></i> 
															<i class="icon20_common sort_gray"></i>
																<span class="js_l1Title" >{{value.name}}</span>
															</a>

															<div class="sub_pre_menu_box js_l2TitleBox">
																<ul class="sub-btn-list sub_pre_menu_list menu-btn">
																	{{if value.sub_button}}
																	{{each value.sub_button.list as subvalue j}}
																	<li class="sub-edit-menu jslevel2"
																		level="2"
																    	vname="{{subvalue.name}}" 
																    	mtype="{{subvalue.type}}"
																    	murl="{{subvalue.url}}" 
																    	mkey="{{subvalue.key}}"
																    	mediaId="{{subvalue.mmedia_id}}"
																    	subButton="{{subvalue.sub_button}}">
																		<a href="javascript:void(0);" class="jsSubView" draggable="false">
																			<span class="sub_pre_menu_inner js_sub_pre_menu_inner">
																				<i class="icon20_common sort_gray"></i>
																				<span class="js_l2Title">{{subvalue.name}}</span>
																			</span>
																		</a>
																	</li>
																	{{/each}}
																	{{/if}}
																	<li class="list-item js_addMenuBox second-level">
																		<a href="javascript:void(0);" class="jsSubView js_addL2Btn" title="最多添加5个子菜单" draggable="false">
																			<span class="sub_pre_menu_inner js_sub_pre_menu_inner">
																				<i class="icon14_menu_add"></i>
																			</span>
																		</a>
																	</li>

																</ul>
																<i class="arrow arrow_out"></i>
																<i class="arrow arrow_in"></i>
															</div>
														</li>
													{{/if}}
													{{/each}}
														<li class="menu-btn plus first-level js_addMenuBox pre_menu_item grid_item no_extra">
															<a href="javascript:void(0);" class="pre_menu_link js_addL1Btn" title="最多添加3个一级菜单" draggable="false">
																<i class="icon14_menu_add"></i>
															</a>
														</li>
													</ul>
												</div>
											</div>
											
											<!-- <div class="sort_btn_wrp">
												<a id="orderBt" class="btn btn_default" href="javascript:void(0);" style="display: inline-block;">菜单排序</a>
												<span id="orderDis" class="dn btn btn_disabled" style="display: none;">菜单排序</span>
												<a id="finishBt" href="javascript:void(0);" class="dn btn btn_default">完成</a>
											</div> -->
										</div>
									</script>
									<!-- 预览菜单 -->
									<script id="menu-preview" type="text/html">
										<div class="mobile_preview" id="mobileDiv" style="display: block;">
										    <div class="mobile_preview_hd"> <strong class="nickname">{{dataTitle}}</strong>
										    </div>
										    <div class="mobile_preview_bd">
										        <ul id="viewShow" class="show_list"></ul>
										    </div>
										    <div class="mobile_preview_ft">
										        <ul class="pre_menu_list grid_line" id="viewList">
										        {{each mlist as mvalue m}}
										        
										        {{if m < 3}}
										            <li class="pre_menu_item grid_item size1of{{size1of}} jsViewLi " id="menu_0" vname="{{mvalue.name}}" mtype="{{mvalue.type}}" murl="{{mvalue.url}}" mkey="{{mvalue.key}}">
										                <a href="javascript:void(0);" class="jsView pre_menu_link" title="{{mvalue.name}}" draggable="false"> <i class="icon_menu_dot"></i>{{mvalue.name}}</a>
										                {{if mvalue.sub_button}}
											                <div class="sub_pre_menu_box jsSubViewDiv" style="display:none">
											                    <ul class="sub_pre_menu_list">
																	{{each mvalue.sub_button as msubvalue mj}}
											                        <li id="subMenu_menu_0_0">
											                            <a href="javascript:void(0);" class="jsSubView" title="子菜单名称" draggable="false" level="2" vname="{{msubvalue.name}}"
																   		mtype="{{msubvalue.type}}" murl="{{msubvalue.url}}"
																   		mkey="{{msubvalue.key}}"
																   		mvalue="{{msubvalue.value}}">{{msubvalue.name}}</a>
											                        </li>
																	{{/each}}
																	
											                    </ul> 
											                    <i class="arrow arrow_out"></i>
											                    <i class="arrow arrow_in"></i>
											                </div>
										                {{/if}}
										            </li>
									            {{/if}}
									            {{/each}}
										        </ul>
										    </div>
										    <a href="javascript:void(0);" class="mobile_preview_closed btn btn_default" id="viewClose">退出预览</a>
										</div>
									</script>
								{/literal}
							</div>
							<!-- 发布预览 -->
							<div class="form-group pdbt10 pre-mpmenu-box">
                                {if empty($authorizer_app_verifyed)}
                                <div style="text-align:center;">您的公众号未认证，不能使用本功能！</div>
                                {else if empty($menu_handle_check_ok)}
                                <div style="text-align:center;">您设置的菜单项不兼容，请确认！</div>
                                {else}
								<div class="col-sm-4 col-sm-offset-1">
									<button class="btn  btn-default" id="order-start">菜单排序</button>
									<button class="btn  btn-default hidden" id="order-end">完成</button>
								</div>
								<div class="col-sm-4 col-sm-offset-3 save-act-btn">
									<button id="submit_button" class="btn btn-success">保存并发布</button>
									<button class="btn  btn-default" id="preview-menu" >
										预览
									</button>
								</div>
                                {/if}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- rightEnd -->
	</div>
</div>

<script src="{'manager/lib/template.js'|cdnurl}"></script>
<script src="{'manager/lib/jquery-ui.js'|cdnurl}"></script>
<script>
	var app_menu = {$menu};
	{if empty($menu) || $menu == 'null'}
	app_menu        = {};
	app_menu.button = [];
	{/if}
	var app_mpinfo = {$mpinfo|json_encode};


	var links      ={$links|json_encode}

			    function getAppid() {
				    var appid = "{$appid}";
				    return appid;
			    }
</script>
<script src="{'manager/js/wxmenu.js'|cdnurl}"></script>
{include file="_foot.tpl"}
<!-- <script>
  $(function() {
    $( "#sortable" ).sortable();
    $( "#sortablea" ).sortable();
    $( "#sortable" ).disableSelection();
  });
  </script> -->