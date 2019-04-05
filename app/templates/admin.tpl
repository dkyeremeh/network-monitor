<!DOCTYPE html>
<html ng-controller="mainCtrl" class="bg-white" lang="en-US">
<head>
	<meta charset="UTF-8" />
	{literal}
	<title ng-bind-template="{{title}} | Network Monitor {{account.module|uppercase}}">Home | Network Monitor </title>
	{/literal}
	<link type="image/png" rel="icon" href="{$SITE_INFO.static_base}/img/favicon.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link type="text/css" rel="stylesheet" href="{$SITE_INFO.static_base}/css/min.css" />
	<script type="text/javascript" src="{$SITE_INFO.static_base}/js/min.js"></script>
	{block name="head"}
	<!--script type="text/javascript" src="{$SITE_INFO.static_base}/modules/tinymce/tinymce.min.js"></script-->
	<!-- tinymce cdn. Replaces above script during production -->
	<script type="text/javascript"  src='//cdn.tinymce.com/4/tinymce.min.js'></script>
	{/block}
</head>

<body id="admin" myshop-module="{$module}" myshop-route>
	{block name="navbar"}
	<header id="navbar" class="clear fixed-top">
		<div class="container-fluid">
			<nav id="account-nav" class="menu">
				<li><i class="icon ion-android-menu" data-toggle="#admin_menu"></i></li>
				<li class="full capitalize">
					<span id="admin_title" class="hidden-xs" ng-cloak>
						{literal}
							{{title}} 
						{/literal}
					</span>
				</li>
				{foreach $menu as $item}
				<li>
					<a 
						{foreach $item as $key => $val}
							{$key} = "{$val}" 
						{/foreach}>
						{$item.title}
					</a>
				</li>
				{/foreach}
				<li>
					<span ng-click = "logout()">
						<i class="icon ion-log-out"></i>
						<span class="hidden-xs">Logout</span>
					</span>
				</li>
			</nav>
		</div>
	</header>
	{/block}
	
	

	{block name="content"}
		<div id="admin_workarea" ng-controller="adminCtrl">
			<div id="admin_menu" class="admin-menu">
				<a href="/{$module}/#/dashboard">
					<i class="ion-ios-speedometer-outline"></i>
					<span>Dashboard</span>
				</a>
				{foreach $tools as $tool}
				<a href="/{$module}/#/{$tool.tool}">
					{if $tool.icon_class}
						<i class="{$tool.icon_class}"></i>
						{else}
						<i data-admin-icon="{$tool.tool}"></i>
					{/if}
					<span>{$tool.title}</span>
				</a>
				{/foreach}
			</div>
			<div id="admin_content" class="clear">
				<ui-view class="col-lg-9 col-xs-12"></ui-view>
			</div>
		</div>
	{/block}
	{accountStatusJS nocache}
</body>
</htmls>