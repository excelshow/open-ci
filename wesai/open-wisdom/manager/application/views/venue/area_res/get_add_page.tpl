{include file='venue/_header.tpl'}
<link rel="stylesheet" type="text/css" href="{'manager/css/wangEditor.min.css'|cdnurl}">
<!--—自适应布局---->
<div class="container-fluid">
    <div class="row">
        <!-- leftStart -->
        {include file='venue/_leftside.tpl'}
        <!-- leftEnt -->
        <!-- rightStart-->
        <div class="right-main">
            <div class="breadcrumbs-box">
                <ol class="breadcrumb">
                    {include file="venue/_top_sub_navi.tpl"}
                    <li><a href="/venue/venue/get_list">场馆管理</a></li>
                    <li class="active">场地管理</li>
                    <li class="active">添加场地</li>
                </ol>
            </div>
            <div class="right-con">
                <div class="panel panel-blue">
                    <div class="panel-heading">
                        <h3 class="panel-title">添加场地</h3>
                    </div>
                    {include file="./_site_edit.tpl"}
                </div>
            </div>
        </div>
        <!-- rightEnd -->
    </div>
</div>
<!-- upload -->
<script type="text/javascript">
    var serverPath = '{RES_FILEVIEW_URL}/';
</script>
<script src="{'manager/lib/plupload/plupload.full.min.js'|cdnurl}"></script>
<script src="{'manager/lib/wangEditor.min.js'|cdnurl}"></script>
<script src="{'manager_contest/js/distpicker.data.js'|cdnurl}"></script>
<script src="{'manager_contest/js/distpicker.js'|cdnurl}"></script>
<script src="{'manager/venue/js/base.js'|cdnurl}"></script>
<script src="{'manager/venue/lib/handlebars.js'|cdnurl}"></script>
<script src="{'manager/venue/js/operation_area_res.js'|cdnurl}"></script>

{include file='venue/_foot.tpl'}

