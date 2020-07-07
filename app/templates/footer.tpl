	{literal}
	<div id="secondary" class="widget-area" role="complementary">
		<!--div class="catsbar-toggle">
			<i class="ion ion-android-more-vertical" data-alt="&#128269;"></i>
		</div-->
		<div class="catsbar">
			<h3 class="catsbar-title">Categories</h3>
			<div class="tree" ms-accordion>
				<ul>
					<li class="tree-node" ng-repeat="cat in storage.categories|hierarchify|orderBy:'name'">
						<div class="ms-accordion-title">{{cat.name}}</div>
						<ul>
							<li class="tree-node" ng-repeat="sub in cat.children">
								<a href="/category/{{sub.name|prettyurl}}">{{sub.name}}</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div><!-- #secondary -->
	{/literal}



	<footer id="footer" class="site-footer border-color" role="contentinfo">
		
		<div class="container">
			{block name="footer"}
				{if $CLIENT != "ionic"}
					<div class="row">
						<div id="footer_contacts" class="col-md-4 col-sm-7">
							<h3>Contacts</h3>
							<div>
								<i class="ion-ios-email"></i>
								<a href="mailto:info@myshopgh.com">info@myshopgh.com</a>
							</div>
							<div>
								<i class="ion-help-circled"></i>
								<a href="mailto:support@myshopgh.com">support@myshopgh.com</a>
							</div>
							<div>
								<i class="ion-ios-telephone"></i>
								<a href="tel:+233 31 200 3457">+233 31 200 3457</a>
							</div>
						</div>
						<div class="col-md-4 col-sm-5">
							<h3>Information</h3>
							<nav class="links">
								<a href="/faq">FAQ (Frequently Asked Questions)</a>
								<a href="/privacy-policy">Privacy Policy</a>
								<a href="/terms-conditions">Terms &amp; Conditions</a>
								<a href="/knowledge-base/tag/startup">Startup Guide</a>
							</nav>
						</div>
						<div class="col-xs-12 col-sm-3 col-md-2">
							<img src="{$_ENV.STATIC_FILES_URL}/img/payments.jpg" />
						</div>
						<div class="col-xs-12 col-sm-4 col-md-2">
							<img src="{$_ENV.STATIC_FILES_URL}/img/ems.png" />
							<a class="block padding-top" href="https://play.google.com/store/apps/details?id=com.myshopgh.mobile.app" target="_blank">
								<img src="{$_ENV.STATIC_FILES_URL}/img/playstore.png" />
							</a>
						</div>
					</div>
				{/if}
			{/block}
		</div>
		<div class="container padding">
			<div>Powered by: <a href="https://dekyfinweb.com">Dekyfin IT Solutions</a></div>
		</div>
		<div title="Top" href="javascript:null" class="scroll-to-top ion-chevron-up"></div>
	</footer><!-- #colophon -->

	<div class="catsbar-hide"></div>
