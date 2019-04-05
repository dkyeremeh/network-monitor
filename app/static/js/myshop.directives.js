
angular.module("myshop")
	.directive("ajaxForm",function(loadServ, msgServ){
		return {
			link: function(scope,form,atts){
				form.submit(function(event){
					event.preventDefault();
					
					var url = atts.action;
					var data = new FormData(form[0]);					
					
					msgServ.broadcast({type: "loading"});
					$.ajax({
						url: url,
						type: "POST",
						data: data,
						dataType: "json",
						processData: false,
						contentType: false,
						success: function(response){
							if(atts.onsuccess){
								scope.$eval(atts.onsuccess, response);
							}
							loadServ.onSuccess(response);
							form.find(".output").html(response.msg).attr("msg-status",response.status);
						}
					})
					
				});
			},
			restrict: "EAC",
		}
	})
	.directive("starRating",function(){
		return {
			template:"<span class='star-rating star-blank'><span class='star-fill'></span></span>",
			restrict: "EA",
			transclude: true,
			link: function(scope,el,atts){
				var rating = atts.rating || atts.stars;
				el.find(".star-fill").css("width", rating * 100/5 + "%");
			}
		}
	})
	.directive("msAccordion",function($timeout){
		return {
			restrict: "EA",
			link: function(scope,el,atts){
				$timeout(function(){
					el.accordion({
						header: "ms-accordion-title, .ms-accordion-title,[ms-accordion-title]", 
						heightStyle:"content",
						animate: 200,
						collapsible: true,
						active: false,
					});
				}, 500);
			}
		}
	})
	.directive("imagePreview",function($timeout){
		return {
			restrict: "EAC",
			link: function(scope,el,atts){
				el.uploadPreview();
			}
		}
	})
	.directive("captcha",function(loadServ){
		return {
			template: "<div class='captcha-controls'><i class='captcha-reload ion-ios-loop-strong'></i></div><div class='captcha-code'></div>",
			link: function(scope,el,atts,ngModel){
				var loadFn = function(){
					loadServ.load("/api/captcha", function(res){
						el.find(".captcha-code").html(res.data.figlet);
						ngModel.$setViewValue(res.data.id);
					});
				}
				el.find(".captcha-reload").click(loadFn);
				loadFn();
			},
			restrict: "EA",
			transclude: true,
			require: "ngModel",
		}
	})
	.directive('ngModel', function() {
		return {
			require: 'ngModel',
			link: function(scope, el, attrs, ngModel) {
				if (el[0].tagName =="INPUT" && attrs.type == "number"){
					ngModel.$formatters.push(function(modelValue) {
						return Number(modelValue);
					});

					ngModel.$parsers.push(function(viewValue) {
						return Number(viewValue);
					});
				}
			}
		};
	})
	.directive("tooltip", function() {
		return {
			restrict: "A",
			link: function(scope, el, attrs) {
				$(el).attr("data-html","true");
				$(el).tooltip();
			}
		};
	})
	.directive("dashMenu",function(loadServ){
		return {
			link: function(scope,el,atts){
				var markup="<div class='cards-container'>",
					count = 0;
				$(".admin-menu>*").each(function(){
					markup += count++ ? ("<div class='card'>" + this.outerHTML + "</div>") : "";
				})
				markup += "</div>";
				el.addClass("cards hover-cards");
				el.html(markup);
			},
			restrict: "EA"
		}
	})
	.directive("cloudLinkGen",function(loadServ){
		return {
			restrict: "AC",
			require: 'ngModel',
			link: function(scope, el, attrs, ngModel) {
				el.change (function(){
					var input = ngModel.$viewValue,
						output = input;

					if(!input){
						return;
					}

					if(input.match("dropbox")){
						output = input.replace("www.dropbox.com","dl.dropboxusercontent.com");
					}
					else if( input.match("drive.google") ){
						var id = input.match(/\/d\/(.+)\//g);
						if (!id.length) {
							return;
						}
						id = id[0].replace("/d/","").replace("/","");
						output = "https://drive.google.com/uc?export=download&id=" + id;
					}

					el.val(output);
					ngModel.$setViewValue(output);
				});
			}
		};
	})
	.directive("altImg",function(loadServ){
		return {
			restrict: "A",
			link: function(scope, el, attrs, ngModel) {
				el.error(function(){
					el.attr("src", attrs.altImg);
				})
			}
		};
	})
	/*
	.directive("subref",function(){
		return {
			restrict: "A",
			link: function(scope, el, attrs) {
				el.click(function(event){
					location.assign( "#/" + vars.tool + "/" + attrs["subref"] );
				})
			}
		};
	})*/