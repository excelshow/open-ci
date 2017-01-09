        <li type="{{itemobj.type}}" name="q{{itemobj.pk_enrol_form_item}}" id="q{{itemobj.pk_enrol_form_item}}">
        <div class="q-top">
            <i class="q-order">{{itemobj.seq}}、</i>
            <strong>
             <input type="text" placeholder="{{itemobj.title}}" value="{{itemobj.title}}" class="form-control q-title" data-value="{{itemobj.title}}" qid="{{itemobj.pk_enrol_form_item}}"/>
            </strong>
            <span class="fR set-R">
	        <label>
	        <input type="checkbox" value="1" {{if itemobj.is_required==1}} checked="checked" {{/if}} class="isRequired" qid="{{itemobj.pk_enrol_form_item}}"> 必填</label>
	        <label class="del_item" did="{{itemobj.pk_enrol_form_item}}" title="移除组件"><span class="glyphicon glyphicon-remove"></span></label>
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
            <div class="option-list">
               {{each itemobj.option_values as lab}}
                <label>
                <span class="glyphicon glyphicon-th-list"></span>
                <input type="radio"  name="r{{itemobj.pk_enrol_form_item}}" value="{{lab.labeltxt}}"><input type="text" data-value="{{lab.labeltxt}}" value="{{lab.labeltxt}}" class="iRtxt" qid="{{itemobj.pk_enrol_form_item}}"/><span class="del_option glyphicon glyphicon-minus" title="删除选项" qid="{{itemobj.pk_enrol_form_item}}"></span>
                </label>
                {{/each}}
                <div  title="添加新选项"><span class="glyphicon glyphicon-plus add-option" qid="{{itemobj.pk_enrol_form_item}}"></span></div>
            </div>
        </div>
        {{/if}}
        {{if itemobj.type =="checkbox"}}
        <div class="q-content">
           <div class="option-list">
               {{each itemobj.option_values as lab}}
               <label>
                <span class="glyphicon glyphicon-th-list"></span>
                <input type="checkbox"  name="ck{{itemobj.pk_enrol_form_item}}[]" value="{{lab.labeltxt}}"><input type="text" data-value="{{lab.labeltxt}}" value="{{lab.labeltxt}}" class="iRtxt" qid="{{itemobj.pk_enrol_form_item}}"/><span class="del_option glyphicon glyphicon-minus" title="删除选项" qid="{{itemobj.pk_enrol_form_item}}"></span>
                </label>
                {{/each}}
               <div  title="添加新选项"><span class="glyphicon glyphicon-plus add-option" qid="{{itemobj.pk_enrol_form_item}}"></span></div>
            </div>
        </div>
        {{/if}} 
        {{if itemobj.type =="select"}}
        <div class="q-content">
            <div class="option-list">
               {{each itemobj.option_values as lab}}
               <label>
                <span class="glyphicon glyphicon-th-list"></span>
                <input type="text"  data-value="{{lab.labeltxt}}" value="{{lab.labeltxt}}" class="iRtxt" qid="{{itemobj.pk_enrol_form_item}}"/><span class="del_option glyphicon glyphicon-minus" title="删除选项" qid="{{itemobj.pk_enrol_form_item}}"></span>
                </label>
                {{/each}}
                <div  title="添加新选项"><span class="glyphicon glyphicon-plus add-option" qid="{{itemobj.pk_enrol_form_item}}"></span></div>
            </div>
        </div>
        {{/if}} 
        {{if itemobj.type =="date"}}
        <div class="q-content">
            <input type="date" name="" class="form-control" />
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
            <textarea class="form-control" rows="3" placeholder="请填写条款内容" name=""></textarea>
            <br>
            <input type="checkbox" name="" /> 我已阅读并同意参赛条款
        </div>
        {{/if}}
    </li>