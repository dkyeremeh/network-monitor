//For storing various variables
$ = jQuery;
vars = {};

angular.element(document).ready(function(){
	$("[myshop-module]").each(function(){vars.module = $(this).attr("myshop-module")}); //Required
	$("[myshop-tool]").each(function(){vars.tool = $(this).attr("myshop-tool")});	//optional
	setTimeout(function(){angular.bootstrap(document, ['myshop']);}, 50);
})

angular.module('myshop',["dekyfin.ui", "ui.router", "angular.filter", "angularjs-datetime-picker"])
	.config(function($stateProvider,$urlRouterProvider, $sceDelegateProvider ){
		if($("[myshop-route]").length){
			$stateProvider
				.state('tool',{
					url: '/:tool',
					templateUrl: function($stateParams){
						return siteInfo.staticBase + "/templates/" + vars.module + "/" + $stateParams.tool + ".html";
					},
				})
				.state('subtool',{
					url: '/:tool/:subtool',
					templateUrl: function($stateParams){
						return siteInfo.staticBase + "/templates/" + vars.module + "/" + $stateParams.tool + ".html";
					},
				})
			$urlRouterProvider.otherwise('/dashboard');
		}

		$sceDelegateProvider.resourceUrlWhitelist([
			// Allow same origin resource loads.
			'self',
			// Allow loading from our assets domain.  Notice the difference between * and **.
			siteInfo.staticBase + '/**'
		]);
	})
function setRouteParams(params){
	vars.tool = params.tool;
	vars.subtool = params.subtool;
}