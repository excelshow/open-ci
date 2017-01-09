{literal}
<!-- 问题列表 -->
<script id="initformqlist" type="text/html">
 {{if isEmpty==0}}
   <div class="text-center pre-text pd10 text-muted">没有添加任何项目</div>
 {{/if}}
{{each list as itemobj rankorder}}
    <li type="{{itemobj.type}}" name="q{{itemobj.pk_enrol_form_item}}" id="q{{itemobj.pk_enrol_form_item}}" qid="{{itemobj.pk_enrol_form_item}}">
        <div class="q-top">
            <i class="q-order">{{rankorder+1}}、</i>
            <strong>
             {{itemobj.title}}
            </strong>
            <span class="fR set-R" style="color:#f00">
            {{if itemobj.is_required==1}}*{{/if}}
            </span>
        </div>
        {{if itemobj.type =="text"}}
        <div class="q-content">
            <div class="fi_txt">用户填写区域</div>
        </div>
        {{/if}}
        {{if itemobj.type =="phone"}}
        <div class="q-content">
            <div class="fi_txt">用户填写区域</div>
        </div>
        {{/if}}
        {{if itemobj.type =="city"}}
        <div class="q-content">
            <select><option>省</option></select>
            <select><option>市</option></select>
            <select><option>区</option></select>
        </div>
        {{/if}}
        {{if itemobj.type =="radio"}}
        <div class="q-content">
            <div class="option-list" qid="{{itemobj.pk_enrol_form_item}}">
               {{each itemobj.option_values as lab}}
                <label>
                <input type="radio"  name="r{{itemobj.pk_enrol_form_item}}" value="{{lab.labeltxt}}"> {{lab.labeltxt}}</span>
                </label>
                {{/each}}
            </div>
        </div>
        {{/if}}
        {{if itemobj.type =="checkbox"}}
        <div class="q-content">
            <div class="option-list" qid="{{itemobj.pk_enrol_form_item}}">
               {{each itemobj.option_values as lab}}
               <label>
                <input type="checkbox"  name="ck{{itemobj.pk_enrol_form_item}}[]" value="{{lab.labeltxt}}"> {{lab.labeltxt}}</span>
                </label>
                {{/each}}
            </div>
        </div>
        {{/if}} 
        {{if itemobj.type =="select"}}
        <div class="q-content">
             <div class="option-list" qid="{{itemobj.pk_enrol_form_item}}">
               <select class="formcontrol">
               {{each itemobj.option_values as lab}}
                 <option>{{lab.labeltxt}}</option>
                {{/each}}
              </select>
            </div>
        </div>
        {{/if}} 
        {{if itemobj.type =="date"}}
        <div class="q-content">
            <input type="date" name="" class="form-control" placeholder="填写日期" />
        </div>
        {{/if}} 
        {{if itemobj.type =="idcard"}}
        <div class="q-content">
            <div class="fi_txt">用户填写区域</div>
        </div>
        {{/if}}
        {{if itemobj.type =="email"}}
        <div class="q-content">
            <div class="fi_txt">用户填写区域</div>
        </div>
        {{/if}}
        {{if itemobj.type =="file"}}
        <div class="q-content">
           <div class="glyphicon glyphicon-picture" tyle="font-size:30px"></div>
        </div>
        {{/if}}
        {{if itemobj.type =="uploadfile"}}
        <div class="q-content">
            <div class="glyphicon glyphicon-picture" style="font-size:30px"></div>
        </div>
        {{/if}}
        {{if itemobj.type =="textarea"}}
        <div class="q-content">
            <div class="fi_area">用户填写区域</div>
        </div>
        {{/if}}
        {{if itemobj.type =="policy"}}
        <div class="q-content">
            
            <textarea class="form-control" rows="3" placeholder="请填写条款内容" name="">
            {{each itemobj.option_values as lab}}{{lab.labeltxt}}{{/each}}</textarea>
            
            <br>
            <input type="checkbox" name="" /> 我已阅读并同意参赛条款
        </div>
        {{/if}}
    </li>
{{/each}}
</script>
{/literal}
