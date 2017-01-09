
var Index = function(){

}

Index.prototype = {
	size:function(){
		var w = window.innerWidth;
		var size = w / 75;
		if ( w < 750 && size > 10 ) {
			$("html").css("font-size",""+size+"px")
		}
			console.log( $("html").css("font-size") )
	}
}
var init = new Index();
init.size();
