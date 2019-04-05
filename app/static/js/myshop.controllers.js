"use strict";

angular.module("myshop")
	.controller('mainCtrl', function($scope, loadServ, accountServ,  $location, $filter){
		$scope.$on('$stateChangeStart',  function(event, toState, toParams, fromState, fromParams, options){
			//Prevent inter-tool navigation from reloading the view
			/*
			if(toParams.tool == vars.tool){
				$location.path("/test").replace();
				event.preventDefault();
			}
			*/
		});
		//Set route params
		$scope.$on('$viewContentLoading',  function(event, viewConfig){
			setRouteParams(viewConfig.params);
		});
		$scope.$on( 'logout', function(){
			//Clear caches
			var caches = ["toolData", "cache","moduleData", "notices", "noticeInfo", "chart"];

			for( var i=0; caches[i]; i++ ){
				if(localStorage[ caches[i] ])
					delete ( localStorage[ caches[i] ] );
			}
		});
		
		//Account related
		$scope.credentials={};
		$scope.accountLogin = function(){
			accountServ.login($scope.credentials);
		}
		$scope.logout= accountServ.logout;
	
		$scope.newData = {};
		$scope.siteInfo = siteInfo;

		//General tools ()
		if(!($("[myshop-route]").length))
			return;
	
		$scope.create= function(option){
			option = option || "";
			//Clear newData if entry is successully created
			var onSuccess = function(res){$scope.newData = {};$(".modal").hide();}
			loadServ.load("/api/" + vars.module + "/"  + vars.tool + "/create/" + option, $scope.newData, onSuccess);
		}
		$scope.edit = function(data, directive){
			directive = "edit_" + directive;
			$scope.editData = angular.copy(data);
			$("#" + directive).show();
		}
		$scope.add = function(directive){
			if(vars.lastAdd != directive)
				$scope.newData = {};
			vars.lastAdd = directive;
			directive = "add_" + directive;
			$("#" + directive).show();
		}
		$scope.save = function(data, option, callback){//option can be data to be sent or an option 
			if(typeof(data) != "object"){
				callback = option;
				option = data;
				data = undefined;
			}
			if( angular.isFunction(option) ){
				callback = option;
				option = undefined;
			}
			//Remove Editing dialog if editing is successful
			var onSuccess = function(res){
				$scope.editData = {};
				$(".modal").hide();
				if(angular.isFunction(callback))
					callback(res);
			}
			
			data = data || $scope.editData;
			option = option || "";
			loadServ.load("/api/" + vars.module + "/"  + vars.tool + "/edit/" + option, data, onSuccess);
		}
		$scope.delete = function(id, option){
			option = option || "";
			if(confirm("You are deleting an entry. Continue?"))
				loadServ.load("/api/" + vars.module + "/"  + vars.tool + "/delete/" + option,{id:id});
		}
		$scope.setVar = function(variable, value){
			variable = value;
		}
		$scope.markRelated= function(id, itemData, relationsData, fields){
			//We are trying to mark the related items in itemData
			var rel, itemList, item,
				relations = $filter("filter")(relationsData, id, true);
			//Create Relation Items
			itemList = angular.copy(itemData);
			for(var x in relations){
				rel = relations[x][fields[1]] == id  ? relations[x][fields[0]] : relations[x][fields[1]];
				item = $filter("filter")(itemList, {id:rel}, true)[0] ;
				item.selected = true;
			}
			return itemList;
		}

	})

	.controller('adminCtrl', function($rootScope, $scope, $timeout, loadServ, msgServ){
		
		
		$rootScope.$on("$stateChangeStart",function(){
			msgServ.loading();
            
		});
		$rootScope.$on("$stateChangeError", function(){
			//Remove loading
			msgServ.unload();
		});
		//Autoload
		$scope.$on('$viewContentLoaded', function(){

			msgServ.unload();

			if( !(vars.module && vars.tool) )
				return;
			jQueryOnReady();
			//Set active menu
			$(".admin-menu a.active").removeClass("active");
			var active = $(".admin-menu a[href$='"+vars.tool+"']").addClass("active");
			$rootScope.title = active.text().trim();

			$rootScope.$broadcast("loadModule");
			if(! $("[no-autoload]").length )
				$rootScope.$broadcast("load");
			else
				$rootScope.$broadcast("loaded");
		});
		
		$scope.statusList = {};
		$scope.statusList.attending = ["unavailable","unattended","processing request","sending for delivery"];
		$scope.statusList.delivering = ["sending for delivery", "submitted to delivery","processing delivery","transporting", "delivered"];
		$scope.statusList.returning = ["returning to delivery", "returning to merchant", "returned"];
		$scope.statusList.replacing = ["sending replacement to delivery", "delivering replacement", "replacement delivered", "delivering replacement"];
		
		//$scope.statusList.deliveryReturn = ["sending replacement to delivery", "delivering replacement", "replacement delivered", "delivering replacement"];
		//$scope.statusList.returning = ["returning", "returned", "delivering replacement"];
		
		//The loaded event is triggered by the load event handler after the data has been loaded
		$scope.$on('loaded', function(event){
			//Enable subtool
			$timeout(function() {
				if(vars.subtool){
					var target = $(".tabs-nav a[href=#" + vars.subtool + "], [data-subref=" + vars.subtool + "]")[0];
					if( target )
						target.click()
				}
			});
		});

		var loadToolData = function(event,tool){
			tool  = tool || vars.tool;
			var timestamp = Math.floor(Date.now() / 1000);
			var cacheExpired;
			var onLoaded = function(){
				$scope.toolData = angular.copy(toolData);
				//set $scope data if data was requested for the current tool
				if(tool == vars.tool)
					$scope.data = toolData[tool];
				//Events
				$rootScope.$broadcast("toolDataLoaded");
				$rootScope.$broadcast("loaded");
			}
			//Check if cache has expired
			if( toolData[tool] && toolData[tool].timestamp <= timestamp - 15 * 60 ){
				cacheExpired = true;
			}
			if( event.name != "reload" && toolData[tool] && !$("[no-cache]").length && !cacheExpired ){
				onLoaded();
				return;
			}
			loadServ.load("/api/" + vars.module + "/" + tool + "/load", function(res){
				res.data.timestamp = Math.floor(Date.now() / 1000);
				toolData.set(tool, res.data);
				onLoaded();
			});
		}

		var loadModuleData = function(event, part){
			part = part || "",
				dataLoaded = Object.keys(moduleData).length > 1; //Data has already been loaded
			if( event.name != "reloadModule" && dataLoaded ){
				var $return = true;

				if( part && !moduleData[part]  ) { //If the requested partial info does not exist
					$return = false;
				}
				if($return){
					$timeout(function(){
						$scope.moduleData = angular.copy(moduleData);
						$rootScope.$broadcast("moduleDataLoaded");
					});
					return;
				}
			}
			loadServ.load("/api/" + vars.module + "/load/" + part, function(res){
				//FUTURE: enable partial reloading
				/*if(part)
					moduleData.set(part, res.data[part]);
				else*/
					moduleData.set(res.data);
				$scope.moduleData = angular.copy(moduleData);
				$rootScope.$broadcast("moduleDataLoaded");
			});
		}
		var moduleData = new StorageData("moduleData");
		var toolData = new StorageData("toolData");

		//ModuleData Events
		$scope.$on('loadModule', loadModuleData);
		$scope.$on('reloadModule', loadModuleData);

		//Load Event
		$scope.$on('load', loadToolData);
		$scope.$on('reload', loadToolData);
	})
	
	.controller('systemToolsCtrl', function($scope, loadServ){
		$scope.$on("loaded",function(){
			//Show first tab if no tab is active
			if($scope.m)
				setTimeout(function(){$scope.setModule($scope.m);},50);
			else
				setTimeout(function(){if(! $(".ui-tabs-nav .ui-tabs-active").length)$(".ui-tabs-nav li:first-child a").click();},50);
		})
		$scope.addUser = function(tool){
			$scope.add('user')
			$scope.newData.tool = tool.tool;
			$scope.newData.module = tool.module;
		}
		$scope.addTool = function(module){
			$scope.add('tool');
			$scope.newData.module = module;
		}
		$scope.setModule= function(module){
			$scope.m = module;
			var el = $("[data-subref=" + module + "]");
			$(".ui-tabs-active").removeClass("ui-tabs-active");
			$(el).parents("li").addClass("ui-tabs-active");
		}
		
	})

	.controller('dev_servCtrl', function($scope){
		$scope.$on("loaded",function(res){
			//Show first tab if no tab is active
			if($scope.cat)
				setTimeout(function(){$scope.setModule($scope.cat);},50);
			else 
				setTimeout(function(){if(! $(".ui-tabs-nav .ui-tabs-active").length)$(".ui-tabs-nav li:first-child a").click();},50);

			// Reload data every 20 min
			setInterval(function(){$scope.$root.$broadcast("reload");}, 2*60*1000);
		});

		$scope.device = "";
		$scope.f = "";
		
	})

	.controller('optionsCtrl', function($scope){
		$scope.$root.$broadcast("reload");
	})

	.controller('reportCtrl', function($scope, loadServ){
		$scope.$emit("load", "dev_serv");
		$scope.options = {};

		$scope.$on("loaded",function(event){
			$scope.dev_serv = $scope.toolData.dev_serv;
		});

		$scope.viewReport = function(){
			loadServ.load("/api/system/report/fetch", $scope.options, function( res ){
				$scope.report = res.data;
			})
		}
	})
