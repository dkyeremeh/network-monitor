<!DOCTYPE html>
<html ng-controller="mainCtrl" lang="en-US">
	<head>
	<meta charset="UTF-8" />
	<title>Network Device Monitor</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link type="text/css" rel="stylesheet" href="{$SITE_INFO.static_base}/css/min.css" />
	<link type="image/png" href="{$SITE_INFO.static_base}/img/favicon.png" rel="shortcut icon" />
	<script type="text/javascript" src="{$SITE_INFO.static_base}/js/min.js"></script>
	
	<meta property="al:android:app_name" content="Network Monitor">
	<meta property="og:type" content="website" />
	<meta property="og:title" content="{$page_title|default:$title}" />
	
	{if $description}
	<meta name="description" content="{$description}">
	{/if}
	{if $keywords}
	<meta name="keywords" content="{$keywords}">
	{/if}
		
	{block name="head"}{/block}
	</head>

<body class="client-{$CLIENT}">

	<div id="content" class="site-content">
		{block name="title"}
			{if $CLIENT != "ionic" || $breadcrumb}
				<header class="title-banner border-color">
					{if $CLIENT != "ionic"}
						<h1 class="page-title">{$page_title|default:$title|escape}</h1>
					{/if}
				</header><!-- .entry-header -->
			{/if}
		{/block}
		{block name="content"}
			{$content}
		{/block}
	</div><!-- #content -->
	{accountStatusJS nocache}
</body>
</html>