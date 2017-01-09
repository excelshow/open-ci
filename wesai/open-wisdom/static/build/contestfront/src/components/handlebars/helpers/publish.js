module.exports = function(v1,v2,options) {
	this['publish_state']=v2[v1].name;
	if(v1==3){
		return options.fn(this);
	}else{
		return options.inverse(this);
	}
};