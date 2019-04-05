var $=jQuery;

$(document).ready(function(){
	//Implement catsbar display/hide functionality
	$(".catsbar-toggle, .catsbar-hide, [href='#/browse']").click(function(event){$("body").toggleClass('catsbar-visible');});
	$(".search-toggle").click(function(event){
		var body = $("body").toggleClass('search-visible');
		if( body.hasClass("search-visible") ){
			setTimeout(function(){$("#item_search input[name=q]").focus()}, 50);
		}

	});
	$(".search-hide").click(function(event){$("body").removeClass('search-visible');});
	
	//Augment menu toggle for mobile devices
	$(".mobile-menu-toggle").click(function(event){$("body").toggleClass('mobile-menu-visible');});
	$(".mobile-menu-hide").click(function(event){$(".mobile-menu-toggle").click();});
	
	//Add dropdown for sub-menu
	$(".sub-menu").before("<span class='dropdown ion-ios-arrow-down' data-alt='&#9660;'></span>");
	$(".dropdown").parent().click(function(){$(this).toggleClass("hover");});
	
	
	//scroll to top button
	$('.scroll-to-top').on("click",function() {
		$("html body").animate({ scrollTop: 0 }, 400);
	});
	//admin menu on mobile
	$('#admin_menu').on("click",function(event) {
		if($(window).width() < 992)
			$(this).hide();
	});
	
	//Item Search
	$("#item_search").focusin( function(event){$("#item_search .advanced-search").addClass("show");})
	$("#item_search").focusout( function(event){
		var searchEl = this;
		 setTimeout( function(){
		 	if( $(searchEl).find("*:focus").length ) return;
		 	$("#item_search .advanced-search").removeClass("show"); 
		 }, 20);
	});

	//ADS

	$(".ad-item a, .ad-shop a").each( function(event){
		var url = btoa( $(this).attr("href") ),
			parent = $(this).parents(".ad"),
			target = parent.attr("data-href") + url;
		$(this).attr("href", target);
		setTimeout(function(){parent.removeAttr( "data-href" )}, 100);
	});
	
	jQueryOnReady();
	jQueryOnResize();
	
	//Toggle filters
	//$(".filter-options-title").mousedown(function(event){$(this.parentElement).toggleClass("visible-options")});
	//$(".filter-options-title").blur(function(event){$(this.parentElement).removeClass("visible-options")});
	
	//Tool Icons
	$("[data-admin-icon]").each(function(){
		var tool = $(this).attr("data-admin-icon");
		var map = {
			users: "ion-ios-people-outline",
			locations: "ion-ios-location-outline",
			categories: "ion-ios-pricetags-outline",
			tools: "ion-ios-unlocked-outline",
			
			items: "ion-ios-pricetag-outline",
			orders: "ion-ios-cart-outline",
			transactions: "ion-social-usd-outline",
			theme: "ion-ios-monitor-outline",
		};
		var iconClass = map[tool];
		if(!map[tool])
			iconClass = "ion-ios-gear-outline";
		$(this).addClass(iconClass);
	});
	
});
	
$(window).resize(function(){
	jQueryOnResize();
});

$(window).scroll(function(){
	if($("#masthead").length && $(window).scrollTop()>$("#masthead").offset().top-3){
		$("body").addClass("scrolled");
	}
	else
		$("body").removeClass("scrolled");
});


//Meant to be triggered when angular loads templates
function jQueryOnReady(){
	//Notification
	$('.admin-notices').on("click",function(event) {
		if(this.matches("#customer_notices") || $(window).width() < 1200 )
			$(this).hide();
	});
	//remove subref when user closes an active notification
	$('#notification_template #view .popup .close').click(function(){
		if(location.hash != "#/notification")
			location.replace("#/notification");
	});

	//init tabs
	$( ".tabs" ).tabs({});

	if( !Modernizr.touchevents){
		$(".image-preview-box").mousemove(function(event){
			var cursor = {
				x: event.pageX - $(this).offset().left,
				y: event.pageY - $(this).offset().top
			};
			var $img = $(this).find("img");

			var left = cursor.x * ( $img.width() - this.clientWidth ) / this.clientWidth;
			var top = cursor.y * ( $img.height() - this.clientHeight ) / this.clientHeight;

			$img.css({top: - top, left: - left});

			//$(this).css("background-position", x + "% " + y + "%");
		});
	}

	
	//Enforce readonly mode for checkboxes
	$("[type=checkbox][readonly]").change(function(event){console.log("event");event.preventDefault()});
	
}

function jQueryOnResize(){
	$("#masthead").css("min-height", $("#masthead .navbar").outerHeight() );
}

//Init cache
cache = new StorageData("cache");