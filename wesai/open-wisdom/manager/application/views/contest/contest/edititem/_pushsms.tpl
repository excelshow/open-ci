 <div class="create-form sms-mod hide">
                <div class="mark-item">
                    <h5>报名成功＋领取装备（无抽签）</h5>
                    <div class="form-group">
                        <table class="table">
                            <tr>
                                <td>
                                    <p>预览:</p>
                                    <p class="preword">{if !empty($smstemplatedata)}{$smstemplatedata[0]->content}{/if}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class=" pd10 text-right">还可以输入<strong class="countword">200</strong>个字</p>
                                    <textarea style="width:100%;height:120px;" placeholder="请严格按照标准格式填写短信内容"
                                              id="smscontent">{if !empty($smstemplatedata)}{$smstemplatedata[0]->content}{/if}</textarea>
                                    <div class="mgb10 row">
                                        <span class="mgr20 text-muted">{literal}{$original}（原始报名成功后发送短信内容） {$code}(装备领取码)  {$address}(装备领取地点)  {$ordernum}(微赛订单号){/literal}</span>
                                        <div class="fR pd10">
                                            <button type="button" class="btn btn-success" id="add-sms"
                                                    onclick="return addSmsPush()">保存短信
                                            </button>
                                            <a href="#" class="btn btn-cancel">取消</a>
                                        </div>
                                    </div>
                                    <p class="text-muted bg-danger pd10">
                                        备注：运营同学在文本编辑器中编辑短信文案时带入变量识别符，程序根据识别符判定在数据库中取相关的数据加入到短信中,请参考以下示例：<a href="#" class="viewdemo">点击查看</a>
                                    </p>
                                    <p style="display: none;" class="demopic">
                                    <img src="{'manager_contest/img/smsdemo.jpg'|cdnurl}" style="max-width:100%">
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <h5>请上传领取装备码</h5>
                        <table class="table">
                            <tr>
                                <td>
                                    <div class="row">
                                        <select name="a_itemid" id="a_itemid">
                                            <option value="">请选择项目</option>
                                            {foreach from=$itemdata item=pitem}
                                                <option value="{$pitem->pk_contest_items}">{$pitem->name}</option>
                                            {/foreach}
                                        </select>
                                        <input type="file" name="codefile" id="codefile"
                                               style="display: inline-block;width:50%" class="form-control mgr20"/>
                                        <button class="btn btn-success"
                                                onclick="return ajaxSmsFileUpload('codefile','smserror')">上传
                                        </button>
                                        <p>
                                            <span>请严格按照模版上传装备码文件，请参考：<a href="http://mini.wesai.com/excel/example1.xls"
                                                                        target="_blank">excel数据标准模版</a></span>
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="alert-danger" id="smserror"></div>
                                    <div class="codelist">
                                        <p>一共上传<span class="totalcount">0</span>个号码</p>
                                        <textarea style="width:100%;height:100px" class="codecontent"
                                                  id="codecontent"></textarea>
                                        <div class="fR">
                                            <button class="btn btn-success" onclick="return savesmscode()">保存装备码
                                            </button>
                                        </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
{literal}
<script type="text/javascript">
//短信推送
        $(".viewdemo").click(
                function () {
                    $(".demopic").slideToggle();
                    return false;
                }
        )
        function countWord() {
            var ctxt         = $("#smscontent").val();
            //字符替换
            var original_sms = '您好，上海国际马拉松赛全马比赛您已经成功报名，请继续关注后续活动事宜，祝您好运！';
            var code_sms     = 'xxxxx';
            var address_sms  = '浦东新区国展路1099号(近世博轴西侧)';
            var ordernum_sms = 'xxxxxxxxxxxx';

            var newctxt = ctxt.replace(/\{\$original\}/g, original_sms).replace(/\{\$code\}/g, code_sms).replace(/\{\$address\}/g, address_sms).replace(/\{\$ordernum\}/g, ordernum_sms);
            var total   = max_count;
            //var txtlen  = newctxt.length;
            var txtlen  = ctxt.length;
            var lastlen = total - txtlen;
            $(".countword").text(lastlen);
            $(".preword").html(newctxt).show();
        }
        $("#smscontent").keyup(
                function () {
                    countWord();
                }
        )
        countWord();
        function addSmsPush() {
            var cid = contestConfig.pk_contest;
            var name = contestConfig.name;
            var content = $("#smscontent").val();
            var type = 1;
            if (content == "") {
                alert("短信内容不能为空");
                return false;
            }
            if (content.length > max_count) {
                alert("短信内容最多不能超过" + max_count + "字");
                return false;
            }
            var postData = {
                "cid": cid,
                "name": name,
                "content": content,
                "type": type
            }
            if (pk_sms_templateData.length > 0) {
                //更新模版
                var updateData = {
                    "templid": pk_sms_templateData[0].pk_sms_template,
                    "name": name,
                    "content": content
                }
                $.post(
                        "/contest/contest/ajax_smstemplateupdate", updateData, function (data) {
                            if (data['error'] == '0') {
                                window.location.reload();
                            } else {
                                alert(data['info']);
                            }
                        }, "json"
                );
            } else {
                //新增模版
                $.post(
                        "/contest/contest/ajax_smstemplateadd", postData, function (data) {
                            if (data['error'] == '0') {
                                window.location.reload();
                            } else {
                                alert(data['info']);
                            }
                        }, "json"
                );
            }
            return false;
        }
        //上传装备码
        function ajaxSmsFileUpload(fileElementId, fileTargetId) {
            $("#smserror").text("上传中");
            $.ajaxFileUpload(
                    {
                        url: '/contest/res/excel_reader_codelist',
                        type: 'post',
                        data: {}, //此参数非常严谨，写错一个引号都不行
                        secureuri: false, //一般设置为false
                        fileElementId: "codefile", //文件上传空间的id属性  <input type="file" id="file" name="file" />
                        dataType: 'json', //返回值类型 一般设置为json
                        success: function (data, status)  //服务器成功响应处理函数
                        {
                            if (data.error == 0) {
                                $('#' + fileTargetId).text(data.msg);
                                $(".codelist").show();
                                $(".totalcount").text(data.data.count);
                                $(".codecontent").text(data.data.data);
                            } else {
                                $('#' + fileTargetId).text(data.msg);
                            }
                        },
                        error: function (data, status, e)//服务器响应失败处理函数
                        {
                            alert(e);
                        }
                    }
            )
            return false;
        }
        //保存装备码
        function savesmscode() {
            var itemid = $("#a_itemid").val();
            if (itemid == "") {
                alert("请选择项目");
                return false;
            }
            var smscode = $("#codecontent").val();
            if (smscode.length < 3) {
                alert("请上传领取装备码");
                return false;
            }
            var postData = {
                "itemid": itemid,
                "code": smscode
            }
            $.post(
                    "/contest/contest/ajax_itemcodeadd", postData, function (data) {
                        if (data['error'] == 0) {
                            alert("保存成功");
                        } else {
                            alert('保存失败');
                        }
                    }, 'json'
            )
            return false;
        }
        $(".btn-cancel").on(
                "click", function () {
                    window.location.reload();
                    return false;
                }
        )
</script>{/literal}