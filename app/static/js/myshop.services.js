"use strict"

angular.module("myshop")
	.factory("msgServ", function(){
	
		//create a container to hold all notices
		var notices = document.createElement("div");
		notices.id = "notices";
		$("body").append(notices);
	
		// message responseTypes: error, failure, success, info, loading
		var responseTypes ={
			failure: ["failed", false, "failure"],
			success: ["success", true, "ok"],
			error: ["error"],
			info: ["", "info", undefined],
		}
				
		var service = {
			broadcast: function(message){
				
				//Get type of message
				var type = service.getResponseType(message);
				var msg = message.msg;
				var title = message.title || "";
				
				//Create Message based on type
				switch(type){
					case "error":
						console.log("Error:", message);
						break;
					case "loading":
						if($("#notices .loading").length>0)
							return;
						var notice = document.createElement("div");
						$(notice).addClass(type);
						if(message.type)
							msg= "Loading...";
						$(notice).append("<i class='ion-spin ion-load-c'> </i> " + msg);
						$(notices).append(notice);
					break;
					
					case "failure":
					case "success":
						service.unload();
					default:
						if(!msg)
							return;
						var notice = document.createElement("div");
						$(notice).addClass(type);
						$(notice).append("<i class='close ion-ios-close-outline color-red'></i>" + msg);
						$(notice).find("i").click(function(e){$(this.parentElement).remove();});
						$(notices).append(notice);
						setTimeout(function(){$(notice).remove()}, 5000);
				}
			},
			getResponseType: function(message){
				if (message.type)
					return message.type;
				var status = message.status;
				for(x in responseTypes)
					if(responseTypes[x].indexOf(status)>-1){
						return x;
					}
			},
			loading: function(msg){
				service.broadcast({type: "loading", msg: msg});
			},
			unload: function(){
				$("#notices .loading").remove();
			}
		}
		return service;
	})
	.factory("loadServ", function($http, $rootScope, $state, msgServ){
		var service = {
			load: function(url, data, success, failure){
                //re-assign parameters depending on the values provided
				if(data instanceof Function){
					failure = success;
					success = data;
					data = {};
				}
                			msgServ.broadcast({type: "loading"});
				$http.post(url, data)
					.success(function(response){
						service.onSuccess(response,success,failure)
					})
					.error(function(response){
						console.log("error", response);
					})
				
			},
			onSuccess: function (response,success,failure){
				var responseType=msgServ.getResponseType(response);
				if(responseType=="success"){
					if(angular.isFunction(success))
						success(response);
				}
				else if(responseType == "failure"){
					if(angular.isFunction(failure))
						failure(response);
				}
				msgServ.broadcast(response);
				$rootScope.$broadcast("notification", response.notification );

				if(response.reload_module !== undefined){
					//$state.reload();
					$rootScope.$broadcast("reloadModule", response.reload_module);
				}
				if(response.reload){
					//$state.reload();
					$rootScope.$broadcast("reload");
				}
				if(response.redirect)
					location.assign(response.redirect);
			}
		}
		return service;
	})
	.factory('accountServ', function($rootScope,  $timeout, loadServ, msgServ){
		var service={
			login: function(credentials){
				var onlogin = function(res){
					service.onLogin(res.data);
				}
				credentials.module = vars.module;
				loadServ.load("/api/session/login", credentials, onlogin);
			},
			logout: function(){
				loadServ.load("/api/session/logout", service.onLogout);
			},
			onLogin: function(account){
				//broadcast event
				$rootScope.$broadcast("logout");

				$rootScope.account = $.extend($rootScope.account,account);

				//Load login based features
			},
			onLogout: function(){
				//broadcast event
				$rootScope.$broadcast("logout");
		
				$rootScope.account = {};
			}
			
		}
		
		//Runs when page loads
		if(typeof(account) != "undefined")
			service.onLogin(account);
		else
			$timeout(function(){service.onLogout();})

		return service;
	})