

(function(){
	
	String.prototype.translate =  function(pairs){
	var str = this.toString(), key, exp;
	for (key in pairs)
		if(pairs.hasOwnProperty(key)){
			exp = new RegExp(key, "g");
			str = str.replace(exp, pairs[key]);
		}
	return str;
}
	
	//Make the Math functions global
	var props = Object.getOwnPropertyNames(Math),
		prop = "";
	
	for(var x=0; prop = props[x]; x++){
		if(Math[prop] instanceof Function)
			window[prop] = Math[prop];
	}
}())


function evaluate ($exp, $vars){
	$vars = $vars || {};
	$exp = $exp.translate($vars);	//Replace variables
	$exp = $exp.replace(/[;\s{}\[\]\"\']/g,"");	//replace some special characters to invalidate a function or command
	try{var $return = eval($exp)}catch(err){};	//Evaluate expression
	return $return || 0;	//output
}