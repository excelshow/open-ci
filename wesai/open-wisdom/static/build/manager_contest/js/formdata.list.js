var formConfig = window.formConfig || {};
//基础组件配置
// 姓名标识1
formConfig.namebox = {
	'_type_'    : 'namebox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
		    '_title_' : '参赛者姓名',
			'_name_'  : 'name',
			'_intro_' : '请您输入与身份证件上一致的姓名',
			'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{2,40}$',
			'_errortip_':'请输入正确的参赛者姓名',
			'_maxlength_' : 40
		}
	}
};
//性别标识2
formConfig.sexbox = {
	'_type_'    : 'sexbox',
	'_realize_' :{
		'_base_' : 'radio',
		'_override_' : {
			'_title_' : '性别',
			'_name_'  : 'sex',
			'_intro_' : '',
			'_regExp_': '^[0-9]{1}$',
			'_errortip_':'请选择性别',
			'_values_' :
				      [
				        {'txt':'男', 'val':1},
				        {'txt':'女', 'val':2},
				      ]
			}
		}
};
// 生日标识3
formConfig.birthbox = {
	'_type_'    : 'birthbox',
	'_realize_' :{
	'_base_' : 'date',
	'_override_' : {
		'_title_' : '生日',
		'_name_'  : 'birth',
		'_intro_' : 'yyyy-mm-dd',
		'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{2,40}$',
		'_errortip_':'请按照正确格式输入您的生日'
		}
	}
}
// 年龄4
formConfig.agebox = {
	'_type_'    : 'agebox',
	'_realize_' :{
		'_base_' : 'number',
		'_override_' : {
			'_title_' : '年龄',
			'_name_'  : 'age',
			'_intro_' : '请您输入年龄',
			'_regExp_': '^[0-9]{1,3}$',
			'_errortip_':'请输入正确的年龄,例如32'
			}
	}
};
// 证件类型5
formConfig.idtypebox = {
	'_type_'    : 'idtypebox',
	'_realize_' :{
		'_base_' : 'select',
		'_override_' : {
			'_title_' : '证件类型',
			'_name_'  : 'idtype',
			'_intro_' : '请选择',
			'_regExp_': '^[0-9]{1}$',
			'_errortip_':'请选择证件类型',
			'_options_' :[
				        	{'txt':'请选择', 'val':''},
					        // {'txt':'护照', 'val':1},
					        {'txt':'身份证', 'val':2},
					        // {'txt':'其他', 'val':3}
					      ],
			'_selected_': '',
		}
	}
}
//证件号码6
formConfig.idbox = {
	'_type_'    : 'idbox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
			'_title_' : '证件号码',
			'_name_'  : 'id',
			'_intro_' : '请输入证件号码',
			'_regExp_': '^[0-9]{15}([0-9]{2}[A-Za-z0-9])?$',
			'_errortip_':'请输入正确的证件号码'
		}
	}
}
//联系电话7
formConfig.phonebox = {
	'_type_'    : 'phonebox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
			'_title_' : '联系电话',
			'_name_'  : 'phone',
			'_intro_' : '请输入您的手机号',
			'_regExp_': '^[0-9]{11}$',
			'_errortip_':'您输入的手机号码不正确请重新输入',
		    }
	}
}
// 国籍8
formConfig.nationalitybox = {
	'_type_'    : 'nationalitybox',
	'_realize_' :{
		'_base_' : 'select',
		'_override_' : {
				'_title_' : '国籍',
				'_name_'  : 'nationality',
				'_intro_' : '请选择',
				'_regExp_': '^[0-9]{1}$',
				'_errortip_':'请选择国籍',
				'_options_' :
					      [
				        	{'txt':'请选择', 'val':''},
					        {'txt':'中国', 'val':1},
					        // {'txt':'中华台北', 'val':2},
					        // {'txt':'香港', 'val':3},
					        // {'txt':'澳门', 'val':4}
					      ],
			    '_selected_': '',
			}
	}
}
// 通信地址9
formConfig.addressbox = {
	'_type_'    : 'addressbox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
				'_title_' : '通讯地址',
				'_name_'  : 'address',
				'_intro_' : '请输入您的详细通讯地址',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{2,300}$',
				'_errortip_':'请输入您的地址',
				'_maxlength_':'100'
			}
	}
}
// 紧急联系人姓名10
formConfig.jjnamebox = {
	'_type_'    : 'jjnamebox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				    '_title_' : '紧急联系人姓名',
				    '_name_'  : 'jjname',
				    '_intro_' : '请输入紧急联系人姓名',
				    '_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{2,40}$',
				    '_errortip_':'请输入紧急联系人姓名',
				    '_maxlength_':'12'
				    }
			}
}
// 紧急联系人手机11
formConfig.jjphonebox = {
	'_type_'    : 'jjphonebox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				'_title_' : '紧急联系人手机',
				'_name_'  : 'jjphone',
				'_intro_' : '请输入紧急联系人手机',
				'_regExp_': '^[0-9]{11}$',
				'_errortip_':'请输入正确的紧急联系人手机号',
				'_maxlength_':'11'
			}
	}
}
// 紧急联系人通信地址12
formConfig.jjaddressbox = {
	'_type_'    : 'jjaddressbox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
				'_title_' : '紧急联系人通讯地址',
				'_name_'  : 'jjaddress',
				'_intro_' : '请输入详细通讯地址',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{2,300}$',
				'_errortip_':'请输入紧急联系人详细通讯地址',
				'_maxlength_':'100'
			}
	}
}
//与参赛人关系13
formConfig.joinralationshipbox = {
	'_type_'    : 'joinralationshipbox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
				'_title_' : '与参赛人关系',
				'_name_'  : 'joinralationship',
				'_intro_' : '如：夫妻，朋友等',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,40}$',
				'_errortip_':'请填写关系，不能为空',
				'_maxlength_':'100'
			}
	}
}
//血型14
formConfig.bloodtypebox = {
	'_type_'    : 'bloodtypebox',
	'_realize_' :{
		'_base_' : 'select',
		'_override_' : {
				'_title_' : '血型',
				'_name_'  : 'bloodtype',
				'_intro_' : '血型',
				'_regExp_': '^[0-9]{1}$',
				'_errortip_':'请选择血型',
				'_options_' :
					      [
				        	{'txt':'请选择', 'val':''},
					        {'txt':'不详', 'val':1},
					        {'txt':'A', 'val':2},
					        {'txt':'B', 'val':3},
					        {'txt':'O', 'val':4},
					        {'txt':'AB', 'val':5}
					      ],
			    '_selected_':'',
			}
	}
}
//衣服尺寸15
formConfig.clothessizebox = {
	'_type_'    : 'clothessizebox',
	'_realize_' :{
		'_base_' : 'select',
		'_override_' : {
				'_title_' : '衣服尺寸',
				'_name_'  : 'clothessize',
				'_intro_' : '',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,100}$',
				'_errortip_':'请选择衣服尺寸',
				'_options_' :
					      [
				        	{'txt':'请选择', 'val':''},
					        {'txt':'XXS', 'val':'XXS'},
					        {'txt':'XS', 'val':'XS'},
					        {'txt':'S', 'val':'S'},
					        {'txt':'M', 'val':'M'},
					        {'txt':'L', 'val':'L'},
					        {'txt':'XL', 'val':'XL'},
					        {'txt':'XXL', 'val':'XXL'},
					        {'txt':'XXXL', 'val':'XXXL'}
					      ],
			    '_selected_':'',
			}
	}
}
//身高cm16
formConfig.bodyheightbox = {
	'_type_'    : 'bodyheightbox',
	'_realize_' :{
		'_base_' : 'number',
		'_override_' : {
				'_title_' : '身高cm',
				'_name_'  : 'bodyheight',
				'_intro_' : '请输入您的身高,例如165',
				'_regExp_': '^[0-9]+\.{0,1}[0-9]{0,2}$',
				'_errortip_':'请输入正确身高,例如165',
				'_maxlength_':'100'
			}
	}
}
//体重kg17
formConfig.bodyweightbox = {
	'_type_'    : 'bodyweightbox',
	'_realize_' :{
		'_base_' : 'number',
		'_override_' : {
				'_title_' : '体重kg',
				'_name_'  : 'bodyweight',
				'_intro_' : '请输入您的体重,例如65',
				'_regExp_': '^[0-9]+\.{0,1}[0-9]{0,2}$',
				'_errortip_':'请输入体重',
				'_maxlength_':'100'
			}
	}
}
//现居地址18
formConfig.nowaddressbox = {
	'_type_'    : 'nowaddressbox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
				'_title_' : '现居地址',
				'_name_'  : 'nowaddress',
				'_intro_' : '请输入您的现居地址',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{2,300}$',
				'_errortip_':'请输入地址',
				'_maxlength_':'100'
			}
	}
}
//邮政编码19
formConfig.postcodebox = {
	'_type_'    : 'postcodebox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
				'_title_' : '邮政编码',
				'_name_'  : 'postcode',
				'_intro_' : '请输入您的邮政编码',
				'_regExp_': '^[0-9]{6,7}$',
				'_errortip_':'请输入正确的邮政编码',
				'_maxlength_':'100'
			}
	}
}
//电子邮件20
formConfig.emailbox = {
	'_type_'    : 'emailbox',
	'_realize_' :{
		'_base_' : 'email',
		'_override_' : {
				'_title_' : '电子邮件',
				'_name_'  : 'email',
				'_intro_' : '请输入您的邮箱',
				'_regExp_': '(^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9_-]+$)|(^$)',
				'_errortip_':'请输入正确的邮箱',
				'_maxlength_':'100'
			}
	}
}
//民族21
formConfig.volkbox =  {
	'_type_'    : 'volkbox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
				'_title_' : '民族',
				'_name_'  : 'volk',
				'_intro_' : '请输入您的民族',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,20}$',
				'_errortip_':'请输入您的民族',
				'_maxlength_':'10'
			}
	}
}
//固定电话22
formConfig.telphonebox =  {
	'_type_'    : 'telphonebox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
				'_title_' : '固定电话',
				'_name_'  : 'telphone',
				'_intro_' : '请输入您的固定电话号码',
				'_regExp_': '^0[0-9]{2,3}-?[0-9]{7,8}$',
				'_errortip_':'您输入的固定电话不正确',
				'_maxlength_':'13'
			}
	}
}
//工作单位23
formConfig.workcompanybox =  {
	'_type_'    : 'workcompanybox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
				'_title_' : '工作单位',
				'_name_'  : 'workcompany',
				'_intro_' : '请输入您的工作单位',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,300}$',
				'_errortip_':'请输入工作单位',
				'_maxlength_':'100'
			}
	}
}
//学历24
formConfig.educationbox =  {
	'_type_'    : 'educationbox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
				'_title_' : '学历',
				'_name_'  : 'education',
				'_intro_' : '请输入您的学历',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,20}$',
				'_errortip_':'请输入学历',
				'_maxlength_':'20'
			}
	}
}
//单位固定电话25
formConfig.worktelphonebox =  {
	'_type_'    : 'worktelphonebox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
				'_title_' : '单位固定电话',
				'_name_'  : 'worktelphone',
				'_intro_' : '请输入您的单位固定电话号码',
				'_regExp_': '^0[0-9]{2,3}-?[0-9]{7,8}$',
				'_errortip_':'您输入的单位电话不正确',
				'_maxlength_':'13'
			}
	}
}
//单位性质26
formConfig.companytypebox =  {
	'_type_'    : 'companytypebox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
				'_title_' : '单位性质',
				'_name_'  : 'companytype',
				'_intro_' : '请输入您的单位性质',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,20}$',
				'_errortip_':'请输入单位性质',
				'_maxlength_':'100'
			}
	}
}
//职位27
formConfig.workpositionbox =  {
	'_type_'    : 'workpositionbox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
				'_title_' : '职位',
				'_name_'  : 'workposition',
				'_intro_' : '请输入您的职位',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,20}$',
				'_errortip_':'请输入职位',
				'_maxlength_':'100'
			}
	}
}
//月收入水平28
formConfig.earningsbox =  {
	'_type_'    : 'earningsbox',
	'_realize_' :{
		'_base_' : 'text',
		'_override_' : {
				'_title_' : '月收入水平',
				'_name_'  : 'earnings',
				'_intro_' : '请输入您的月收入水平',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,100}$',
				'_errortip_':'请输入您的月收入水平',
				'_maxlength_':'100'
			}
	}
}
// 监护人联系方式29
formConfig.jhrphonebox = {
	'_type_'    : 'jhrphonebox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				'_title_' : '监护人电话',
				'_name_'  : 'jhrphone',
				'_intro_' : '请输入您的监护人电话',
				'_regExp_': '^[0-9]{11}$',
				'_errortip_':'请输入监护人正确手机号',
				'_maxlength_':'11'
			}
	}
}
// 监护人姓名30
formConfig.jhrnamebox = {
	'_type_'    : 'jhrnamebox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				'_title_' : '监护人签名',
				'_name_'  : 'jhrname',
				'_intro_' : '',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,100}$',
				'_errortip_':'监护人签名不能为空',
				'_maxlength_':'10'
			}
	}
}
//参赛最好成绩31
formConfig.bestrecordbox = {
	'_type_'    : 'bestrecordbox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				'_title_' : '参赛最好成绩',
				'_name_'  : 'bestrecord',
				'_intro_' : '请输入成绩，例如3小时25分4秒',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,20}$',
				'_errortip_':'请输入参赛最好成绩',
				'_maxlength_':'10'
			}
	}
}
//体检病史32
formConfig.deseasehistorybox = {
	'_type_'    : 'deseasehistorybox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				'_title_' : '体检病史',
				'_name_'  : 'deseasehistory',
				'_intro_' : '',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,300}$',
				'_errortip_':'请输入体检病史',
				'_maxlength_':'100'
			}
	}
}
//心肺功能33
formConfig.heartfeibox = {
	'_type_'    : 'heartfeibox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				'_title_' : '心肺功能',
				'_name_'  : 'heartfei',
				'_intro_' : '',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,100}$',
				'_errortip_':'请输入心肺功能',
				'_maxlength_':'100'
			}
	}
}
//心率34
formConfig.heartradiobox = {
	'_type_'    : 'heartradiobox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				'_title_' : '心率',
				'_name_'  : 'heartradio',
				'_intro_' : '如：60-100 次／分',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,100}$',
				'_errortip_':'请输入心率',
				'_maxlength_':'100'
			}
	}
}
//主检医生签名35
formConfig.doctornamebox = {
	'_type_'    : 'doctornamebox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				'_title_' : '主检医生签名',
				'_name_'  : 'doctorname',
				'_intro_' : '',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,100}$',
				'_errortip_':'请输入主检医生名字',
				'_maxlength_':'100'
			}
	}
}
//是否深圳户籍36
formConfig.isbirthshenzhenbox = {
	'_type_'    : 'isbirthshenzhenbox',
	'_realize_' :{
		'_base_' : 'select',
		'_override_' : {
			'_title_' : '是否深圳户籍',
			'_name_'  : 'isbirthshenzhen',
			'_intro_' : '',
			'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,10}$',
			'_errortip_':'请选择是否深圳户籍',
			'_options_' :
				      [
			        	{'txt':'请选择', 'val':''},
				        {'txt':'是', 'val':'是'},
				        {'txt':'不是', 'val':'不是'},
				      ],
			'_selected_':'',
			}
		}
};
//是否有深圳居住证37
formConfig.ishasshenzhenbox = {
	'_type_'    : 'ishasshenzhenbox',
	'_realize_' :{
		'_base_' : 'select',
		'_override_' : {
			'_title_' : '是否有深圳居住证',
			'_name_'  : 'ishasshenzhen',
			'_intro_' : '',
			'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,10}$',
			'_errortip_':'请选择是否有深证户籍',
			'_options_' :
				      [
			        	{'txt':'请选择', 'val':''},
				        {'txt':'有', 'val':'有'},
				        {'txt':'没有', 'val':'没有'},
				      ],
			'_selected_':'',
			}
		}
};
//报名费（邀请码）38
formConfig.baomingfeebox = {
	'_type_'    : 'baomingfeebox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				'_title_' : '报名费（邀请码）',
				'_name_'  : 'baomingfee',
				'_intro_' : '',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,100}$',
				'_errortip_':'请填写报名费（邀请码）',
				'_maxlength_':'100'
			}
	}
}
//参赛宣言39
formConfig.joinwordsbox = {
	'_type_'    : 'joinwordsbox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				'_title_' : '参赛宣言',
				'_name_'  : 'joinwords',
				'_intro_' : '',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,300}$',
				'_errortip_':'请输入参赛宣言',
				'_maxlength_':'100'
			}
	}
}
//体检报告上传 40 uploadfile
formConfig.tjreportsbox = {
	'_type_'    : 'tjreportsbox',
	'_realize_' :{
			'_base_' : 'uploadfile',
			'_override_' : {
				'_title_' : '体检报告上传',
				'_name_'  : 'tjreports',
				'_name2_' :'tjreportstxt',
				'_intro_' : '一年内的体检证明（比赛前必须提交才可参赛）',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_.]{1,300}$',
				'_errortip_':'请上传体检报告图片',
				'_maxlength_':'100'
			}
	}
}
//成绩单上传 41 uploadfile
formConfig.recordreportsbox = {
	'_type_'    : 'recordreportsbox',
	'_realize_' :{
			'_base_' : 'uploadfile',
			'_override_' : {
				'_title_' : '成绩单上传',
				'_name_'  : 'recordreports',
				'_name2_' :'recordreportstxt',
				'_intro_' : '近1年内50公里及以上2年内2次正式越野比赛或者马拉松全程比赛官方竞赛证书',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_.]{1,300}$',
				'_errortip_':'请上传成绩单图片',
				'_maxlength_':'100'
			}
	}
}
//免责申明 42
formConfig.policybox = {
	'_type_'    : 'policybox',
	'_realize_' :{
			'_base_' : 'policy',
			'_override_' : {
				'_title_': '免责申明',
				'_name_': 'policy',
				'_name2_': 'policyboxcheck',
				'_intro_': '请在这里输入关于本活动的免责申明',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,10}$',
				'_errortip_': '请阅读并同意参赛条款',
				'_maxlength_': '100'
			}
	}
}
//现居住省份43
formConfig.provincebox = {
	'_type_'    : 'provincebox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				'_title_' : '现居住省份',
				'_name_'  : 'province',
				'_intro_' : '请输入现居住省份',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,10}$',
				'_errortip_':'请输入现居住省份',
				'_maxlength_':'10'
			}
	}
}
//现居住城市44
formConfig.citybox = {
	'_type_': 'citybox',
	'_realize_': {
		'_base_': 'text',
		'_override_': {
			'_title_': '现居住城市',
			'_name_': 'city',
			'_intro_': '请输入现居住城市',
			'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,10}$',
			'_errortip_': '请输入现居住城市',
			'_maxlength_': '10'
		}
	}
}
//职业45
formConfig.jobbox = {
	'_type_'    : 'jobbox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				'_title_' : '职业',
				'_name_'  : 'job',
				'_intro_' : '请输入职业',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,10}$',
				'_errortip_':'请输入职业',
				'_maxlength_':'10'
			}
	}
}
//个人年收入46
formConfig.incomingybox = {
	'_type_'    : 'incomingybox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				'_title_' : '个人年收入',
				'_name_'  : 'incomingy',
				'_intro_' : '请输入个人年收入',
				'_regExp_': '^[0-9]{1,10}$',
				'_errortip_':'请输入个人年收入',
				'_maxlength_':'10'
			}
	}
}
//护照号
formConfig.passportnobox = {
	'_type_'    : 'passportnobox',
	'_realize_' :{
			'_base_' : 'text',
			'_override_' : {
				'_title_' : '护照号码',
				'_name_'  : 'passportno',
				'_intro_' : '请输入护照号码',
				'_regExp_': '^[A-Za-z]{1}[0-9]{8}$',
				'_errortip_':'请输入正确的护照号码',
				'_maxlength_':'9'
			}
	}
}
//护照签发日期
formConfig.passportissuedaybox = {
	'_type_'    : 'passportissuedaybox',
	'_realize_' :{
		'_base_' : 'date',
		'_override_' : {
			'_title_' : '护照签发日期',
			'_name_'  : 'passportissueday',
			'_intro_' : 'yyyy-mm-dd',
			'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{2,40}$',
			'_errortip_':'请按照正确格式输入您的护照签发日期',
			'_maxlength_':'20'
		}
	}
}
//护照有效日期
formConfig.passportdeadlinebox = {
	'_type_'    : 'passportdeadlinebox',
	'_realize_' :{
		'_base_' : 'date',
		'_override_' : {
			'_title_' : '护照有效日期',
			'_name_'  : 'passportdeadline',
			'_intro_' : 'yyyy-mm-dd',
			'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{2,40}$',
			'_errortip_':'请按照正确格式输入您的护照有效日期',
			'_maxlength_':'20'
		}
	}
}

//装备领取地点
formConfig.receivelocationbox = {
	'_type_'    : 'receivelocationbox',
	'_realize_' :{
		'_base_' : 'select',
		'_override_' : {
				'_title_' : '装备领取地点',
				'_name_'  : 'receivelocation',
				'_intro_' : '',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_]{1,100}$',
				'_errortip_':'请选择装备领取地点',
				'_options_' :
					      [
				        	{'txt':'请选择', 'val':''},
					        {'txt':'石景山环球文化金融城售楼处', 'val':'1'},
					        {'txt':'望京绿地中心售楼处', 'val':'2'},
					        {'txt':'海淀绿地中央广场售楼处', 'val':'3'},
					      ],
			    '_selected_':'',
		}
	}
}

//通行人信息备注
formConfig.remarkbox = {
	'_type_'    : 'remarkbox',
	'_realize_' :{
		'_base_' : 'textarea',
		'_override_' : {
				'_title_' : '备注',
				'_name_'  : 'remark',
				'_intro_' : '请输入一同参赛人员姓名+衣服尺码以空格分隔，如：张三 M',
				'_regExp_': '^[\u4e00-\u9fa5A-Za-z0-9-_\n\r +＋]{1,30}$',
				'_errortip_':'请输入正确的备注内容',
		}
	}
}

//业务基础组件列表
formConfig.formdataList = {
	title: '表单组件',
	list: [formConfig.namebox,//1
			formConfig.sexbox,
			formConfig.birthbox,
		    formConfig.agebox,
		    formConfig.idtypebox,
			formConfig.idbox,
			formConfig.phonebox,
			formConfig.nationalitybox,
			formConfig.addressbox,
			formConfig.jjnamebox,//10
			formConfig.jjphonebox,
			formConfig.jjaddressbox,
			formConfig.joinralationshipbox,
			formConfig.bloodtypebox,
			formConfig.clothessizebox,
			formConfig.bodyheightbox,
			formConfig.bodyweightbox,
			formConfig.nowaddressbox,
			formConfig.postcodebox,
			formConfig.emailbox,//20
			formConfig.volkbox,
			formConfig.telphonebox,
			formConfig.workcompanybox,
			formConfig.educationbox,
			formConfig.worktelphonebox,
			formConfig.companytypebox,
			formConfig.workpositionbox,
			formConfig.earningsbox,
			formConfig.jhrphonebox,
			formConfig.jhrnamebox,//30
			formConfig.bestrecordbox,
			formConfig.deseasehistorybox,
			formConfig.heartfeibox,
			formConfig.heartradiobox,
			formConfig.doctornamebox,
			formConfig.isbirthshenzhenbox,
			formConfig.ishasshenzhenbox,
			formConfig.baomingfeebox,
			formConfig.joinwordsbox,
			formConfig.tjreportsbox,//40
			formConfig.recordreportsbox,
			formConfig.policybox,
			formConfig.provincebox,
			formConfig.citybox,
			formConfig.jobbox,
			formConfig.incomingybox,
			formConfig.passportnobox,
			formConfig.passportissuedaybox,
			formConfig.passportdeadlinebox,
			formConfig.receivelocationbox,
			formConfig.remarkbox
	]
};
