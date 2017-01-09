{literal}
<!-- 问题列表 -->
<script id="formqlist" type="text/html">
 {{if isEmpty==0}}
   <div class="text-center  pd10">暂无信息</div>
 {{/if}}
{{each list as itemobj rankorder}}
    <li type="{{itemobj.type}}" name="q{{itemobj.pk_enrol_form_item}}" id="q{{itemobj.pk_enrol_form_item}}" qid="{{itemobj.pk_enrol_form_item}}" isrequired="{{itemobj.is_required}}">
        <div class="q-top">
            <h6>
             <span class="set-R">
            {{if itemobj.is_required==1}}*{{/if}}
            </span>
            {{rankorder+1}}、
             <span class="q-title">{{itemobj.title}}</span>
            </h6>
        </div>
        {{if itemobj.type =="text"}}
        <div class="q-content">
            <input type="text" name="{{itemobj.pk_enrol_form_item}}" class="form-control" />
        </div>
        {{/if}}
        
        {{if itemobj.type =="radio"}}
        <div class="q-content">
               {{each itemobj.option_values as lab}}
                <label>
                <input type="radio"  name="{{itemobj.pk_enrol_form_item}}" value="{{lab.labeltxt}}">
                <span>{{lab.labeltxt}}</span>
                </label>
                {{/each}}
        </div>
        {{/if}}
        {{if itemobj.type =="checkbox"}}
        <div class="q-content">
               {{each itemobj.option_values as lab}}
               <label>
                <input type="checkbox"  name="{{itemobj.pk_enrol_form_item}}[]" value="{{lab.labeltxt}}"> <span>{{lab.labeltxt}}</span>
                </label>
                {{/each}}
        </div>
        {{/if}} 
        {{if itemobj.type =="select"}}
        <div class="q-content">
               <select class="form-control" name="{{itemobj.pk_enrol_form_item}}">
                <option value="">请选择</option>
               	{{each itemobj.option_values as lab}}
                  <option value="{{lab.labeltxt}}">{{lab.labeltxt}}</option>
                {{/each}}
               </select>
               <i class="icon drop-down"></i>
        </div>
        {{/if}}
        {{if itemobj.type =="phone"}}
        <div class="q-content">
            <input type="tel" name="{{itemobj.pk_enrol_form_item}}" class="form-control" placeholder="手机号码" />
        </div>
        {{/if}}
        {{if itemobj.type =="city"}}
        <div class="q-content selectcity">
            <label style="position: relative;">
                <select class="form-control province" name="province{{itemobj.pk_enrol_form_item}}">
                  {{each citylist as icity c}}
                    <option value="{{icity.name}}" title="{{c}}">{{icity.name}}</option>
                  {{/each}}
                  </select>
                  <i class="icon drop-down"></i>
            </label>
            <label style="position: relative;">
                <select  name="city{{itemobj.pk_enrol_form_item}}" class="form-control city">
                 <option value="请选择" title="0">请选择</option>
                </select>
                <i class="icon drop-down"></i>
            </label>
            
        </div>
        {{/if}}
        {{if itemobj.type =="date"}}
        <div class="q-content">
            <input type="date" style="min-height:20px;" name="{{itemobj.pk_enrol_form_item}}" class="form-control" placeholder="日期" />
            <i class="icon drop-down"></i>
        </div>
        {{/if}} 
        {{if itemobj.type =="idcard"}}
        <div class="q-content">
            <input type="text" name="{{itemobj.pk_enrol_form_item}}" class="form-control" placeholder="请输入正确的身份证号码" />
        </div>
        {{/if}}
        {{if itemobj.type =="email"}}
        <div class="q-content">
            <input type="email" name="{{itemobj.pk_enrol_form_item}}" class="form-control" placeholder="请输入正确的email"/>
        </div>
        {{/if}}
        {{if itemobj.type =="file"}}
        <div class="q-content">
          <input type="file" name="{{itemobj.pk_enrol_form_item}}file" class="form-control" />
        </div>
        {{/if}}
        {{if itemobj.type =="uploadfile"}}
        <div class="q-content">
            <input type="hidden" name="{{itemobj.pk_enrol_form_item}}file" class="form-control" />
            <div class="uploadImage" qid="{{itemobj.pk_enrol_form_item}}">上传图片</div>
            <div class="wx-imglist"></div>
        {{/if}}
        {{if itemobj.type =="textarea"}}
        <div class="q-content">
            <textarea name="{{itemobj.pk_enrol_form_item}}" class="form-control"></textarea>
        </div>
        {{/if}}
        {{if itemobj.type =="policy"}}
        <div class="q-content">
            <div class="policy">
            {{each itemobj.option_values as lab}}
             {{lab.labeltxt}}
            {{/each}}
            </div>
            <label><input type="checkbox" name="{{itemobj.pk_enrol_form_item}}" value="yes" /> 我已阅读并同意条款</label>
            
        </div>
        {{/if}}
    </li>
{{/each}}
</script>
{/literal}
