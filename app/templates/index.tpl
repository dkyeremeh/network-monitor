<!DOCTYPE html>
<html ng-controller="mainCtrl" lang="en-US">
	<head>
	<meta charset="UTF-8" />
	<title>{$_ENV.SITE_NAME}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link type="text/css" rel="stylesheet" href="{$_ENV.STATIC_FILES_URL}/css/min.css" />
	<link type="image/png" href="{$_ENV.STATIC_FILES_URL}/img/favicon.png" rel="shortcut icon" />
	<script type="text/javascript" src="{$_ENV.STATIC_FILES_URL}/js/min.js"></script>
	
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

	<header id="titlebar" class="clear">
		<div class="container">
			<div class="menu">{$_ENV.SITE_NAME}</div>
		</div>
	</header>

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