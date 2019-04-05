angular.module("dekyfin.ui",[])
	.directive("modal",function(){
		return {
			restrict: 'EAC',
			transclude: true,
			template: "<div class='popup'><div class='content'><i class='close ion-ios-close-outline'></i><div ng-transclude></div></div></div>",
			link: function(scope,el,attr){
				$(el).find(".close").click(function(event){
					var popup=$(el);
					if(popup.hasClass("hide"))
						popup.hide();
					else
						popup.remove();
				})
			}
		}
	})
	.directive("alignMiddle",function(){
		return {
			restrict: 'C',
			link: function(scope,element,attr){
				/*var el = element[0];
				var halfHeight = function(o){ return o.offsetHeight/2;}
				el.style.top="0";
				var ext=(halfHeight(el) + el.offsetTop)//el.offsetParent.offsetHeight;	// Value to move up by
				el.style.top=(halfHeight(el.offsetParent) - ext)+ "px";
				if(el.offsetTop<0){el.style.top="0";}*/
			}
		}
	})
	.directive("toggle",function(){
		return {
			link: function(scope,el,attr){
				el.on("click",function(){
					$(attr.toggle).toggle();
				})
			}
		}
	})
	.directive("click",function(){
		return {
			link: function(scope,el,attr){
				el.on("click",function(){
					$(attr.click).click();
				})
			}
		}
	})
	.directive("formValidate",function(){
		return {
			require: "^form",
			link: function(scope,el,attr, form){
				el.click(function(event){
					if(!form.$valid){
						event.preventDefault();
						event.stopImmediatePropagation();
						el.parents("form").find(':submit').click()
					}
				})
			}
		}
	})
	.filter('prettyurl',function(){
		return function(url){
			if(typeof(url) != "string")
				return url;
			
			url = url.toLowerCase();
			url = url.replace(/[^\w]/gm, "-", url);
			url = url.replace(/[_\s]/gm, "-", url); 
			url = url.replace(/-+/gm, "-", url);
			
			return url.replace(/^-|-$/gm,'');
		}
	})
	.filter('toDate',function(dateFilter){
		return function(str, format){
			if(typeof(str) != "string")
				return str;
			str = str.split(/[: -]/);
			var date = new Date( Date.UTC(str[0], str[1]-1, str[2], str[3]||0, str[4]||0, str[5]||0) );
			date = dateFilter(date, format);
			return date;
		}
	})
	.filter('simpleTime', function(dateFilter){
		return function(str) {
			if(typeof(str) != "string")
				return str;
			str = str.split(/[: -]/);

			var today = new Date().getTime();
			var date = new Date( Date.UTC(str[0], str[1]-1, str[2], 0, 0, 0) );
			

			var oneDay = 24*60*60*1000;

			var diff = Math.floor( ( today - date.getTime() )/oneDay ) ;

			switch(diff){
				case 0:
					return str[3] + ":"+ str[4];
				case 1:
					return "y'day";
				default:
					return  dateFilter(date, "dd MMM");
			}
		};
	})
	.directive("form",function($timeout){	//Disable multiple submission
		return {
			link: function(scope,el,attr){
				el.on("submit",function(event){
					if( el.attr("submitted") ){
						event.stopImmediatePropagation();
						event.preventDefault();
						return
					}
					el.attr("submitted",  "submitted");
					$timeout( function(){el.removeAttr("submitted")}, 6000);
				})
			}
		}
	})
