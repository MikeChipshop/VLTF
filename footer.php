<footer>
	<a id="back-to-top">Back to top</a>
	<div class="container thin flex">
        <div class="left-content">
            <span class="copy">&copy; Heritage Arts / VAULT Festival 2017</span>
            <span class="links">
            <?php
            foreach(array(51,476) as $page){
                echo '<a href="'.get_the_permalink($page).'">'.get_the_title($page).'</a>';
            }
            ?>
            </span>
    	</div>
        <?php
		vault_social();
		?>
	</div>
</footer>
</div>
<?php wp_footer(); ?>
<script>
jQuery(function($){
	var menu_timeout;
	$("#main-menu .closer").on("click",function(){
		$("#main-menu").removeClass("open");
		menu_timeout = setTimeout(function(){$("#main-menu").removeClass("loading");},300);
	});
	
	$("header #menu-toggle .bar-container").on("click",function(){
		$("#main-menu").addClass("loading open");
		clearTimeout(menu_timeout);
	});
	
	$("#main-details .cta").on("click",function(){
		console.log($("#event-details").offset().top);
		$('html,body').animate({ scrollTop: $("#event-details").offset().top }, 500);
	});
	
	$("#lightgallery").lightGallery({
		mode: 'lg-fade',
		download: false,	
	}); 
	
	$("#search-toggle").on("click",function(){
		$("body").toggleClass("searching");
		if($("body").hasClass("searching")){
			$("#search-form input[type=search]").trigger("focus");	
		}
	});
	
	<?php
	if(is_post_type_archive("spektrix_event")){
	?>
	$( "#datepicker" ).datepicker({
		minDate: earliest_date,
		maxDate: new Date("31 March 2018"),
		dateFormat: 'd MM yy',
		onSelect: function(date,inst){
			//console.log(date);
			$("#whatson menu.date > span").removeClass("current");
			$("#whatson menu.date > span:last-child").addClass("current");
			
			date_ref = new Date(date);
			if(date_ref<=earliest_date){
				$("#whatson h2 span a.previous").addClass("disabled");
			} else {
				$("#whatson h2 span a.previous").removeClass("disabled");
			}
			$("#whatson h2 b").html(formatDate(date_ref,""));
			params["week"] = "";
			params["date"] = formatDate(date_ref,"db");
			load_listings();
		}
    });
	
	if(typeof(week_ref)=="undefined") var week_ref = 1;
	var date_ref = new Date(earliest_date.getTime());
	var params = {
		"week":"week-"+week_ref,
		"programme":"",
		"date":"",
		"interest":"",
	};
	
	if(window.location.hash) {
		var hash = window.location.hash.replace("#","");
		if(hash!=""){
			if(hash.indexOf("week")>=0){
				params["week"] = hash;
				week_ref = parseInt(hash.replace("week-",""));
				$("#whatson h2").removeClass("day")
				$("#whatson h2 b").html("Week " + weeks[week_ref-1]);
				$("#whatson .dates .date.current").removeClass("current");
				$("#whatson .dates .date:eq("+(week_ref-1)+")").addClass("current");
			} else {
				params["programme"] = hash;
				$("#whatson menu.genre > span").removeClass("current");
				var uc = hash.charAt(0).toUpperCase() + hash.slice(1);
				$("#whatson menu.genre > span:contains("+uc+")").addClass("current");
			}
		}
	}
	
	//console.log(params);
	load_listings();
	
	$("#whatson menu.genre > span").on("click",function(){
		if($(this).hasClass("current")) return false;
		var ref = $(this).text().toLowerCase();
		if(ref=="search"){
		} else {
			$("#whatson menu.genre > span").removeClass("current");
			if(ref=="all") ref="";
			
			var h = ref=="" ? "" : "#" + ref;
			
			if(history.pushState) {
				history.pushState(null, null, h);
			}
			else {
				location.hash = h;
			}
			
			$(this).addClass("current");
			params["programme"] = ref;
			load_listings();
		}
	});
	
	$("#whatson menu.date > span").on("click",function(){
		var ref = $(this).text().toLowerCase();
		if($(this).hasClass("current")&&ref!="pick a date") return false;
			
		//console.log(ref);
		if(ref=="day"){
			$("#whatson menu.date > span").removeClass("current");
			$(this).addClass("current");
	
			params["week"] = "";
			params["date"] = formatDate(date_ref,"db");
			
			if(date_ref<=earliest_date){
				$("#whatson h2 span a.previous").addClass("disabled");
			} else {
				$("#whatson h2 span a.previous").removeClass("disabled");
			}
			
			$("#whatson h2").addClass("day")
			$("#whatson h2 b").html(formatDate(date_ref,""));
			
			var h = "";
			
			if(history.pushState) {
				history.pushState(null, null, h);
			}
			else {
				location.hash = h;
			}
			
			load_listings();
		} else if(ref=="week"){
			$("#whatson menu.date > span").removeClass("current");
			$(this).addClass("current");
			
			params["date"] = "";
			params["week"] = "week-"+week_ref;
			
			if(week_ref<=1){
				$("#whatson h2 span a.previous").addClass("disabled");
			} else {
				$("#whatson h2 span a.previous").removeClass("disabled");
			}
			if(week_ref>=week_max){
				$("#whatson h2 span a.next").addClass("disabled");
			} else {
				$("#whatson h2 span a.next").removeClass("disabled");
			}
			
			$("#whatson h2").removeClass("day")
			$("#whatson h2 b").html("Week " + weeks[week_ref-1]);
			
			var h = week_ref=="" ? "" : "#week-"+week_ref;
			
			if(history.pushState) {
				history.pushState(null, null, h);
			}
			else {
				location.hash = h;
			}
			
			load_listings();
		}
	});
	
	$("#whatson h2 span a").on("click",function(){
		if($(this).hasClass("disabled")) return false;
		
		if(params["week"]!=""){
			if($(this).hasClass("previous")){
				week_ref--;
			} else {
				week_ref++;
			}
			
			if(week_ref<=1){
				$("#whatson h2 span a.previous").addClass("disabled");
			} else {
				$("#whatson h2 span a.previous").removeClass("disabled");
			}
			if(week_ref>=week_max){
				$("#whatson h2 span a.next").addClass("disabled");
			} else {
				$("#whatson h2 span a.next").removeClass("disabled");
			}
			
			var h = week_ref=="" ? "" : "#week-"+week_ref;
			
			if(history.pushState) {
				history.pushState(null, null, h);
			}
			else {
				location.hash = h;
			}
	
			$("#whatson h2 b").html("Week " + weeks[week_ref-1]);
			$("#whatson .dates .date.current").removeClass("current");
			$("#whatson .dates .date:eq("+(week_ref-1)+")").addClass("current");
			params["week"] = "week-"+week_ref;
		}
		
		if(params["date"]!=""){
			if($(this).hasClass("previous")){
				date_ref.setDate(date_ref.getDate() - 1);
			} else {
				date_ref.setDate(date_ref.getDate() + 1);
			}
			
			//console.log(date_ref, earliest_date, date_ref<=earliest_date);
			
			if(date_ref<=earliest_date){
				$("#whatson h2 span a.previous").addClass("disabled");
			} else {
				$("#whatson h2 span a.previous").removeClass("disabled");
			}
			/*
			if(week_ref>=week_max){
				$("#whatson h2 span a.next").addClass("disabled");
			} else {
				$("#whatson h2 span a.next").removeClass("disabled");
			}
			*/
			$("#whatson h2 b").html(formatDate(date_ref,""));
			//$("#whatson .dates .date.current").removeClass("current");
			//$("#whatson .dates .date:eq("+(week_ref-1)+")").addClass("current");
			params["date"] = formatDate(date_ref,"db");
		}
		
		load_listings();
	});
	
	$("#interests").on("change",function(){
		params["interest"] = $(this).val();
		//console.log(params);
		load_listings();
	});
	
	function formatDate(date,frmt) {
	  var monthNames = [
		"January", "February", "March",
		"April", "May", "June", "July",
		"August", "September", "October",
		"November", "December"
	  ];
	
	  var day = date.getDate();
	  var monthIndex = date.getMonth();
	  var year = date.getFullYear();
	
	  return frmt=="db" ? year + '-' + ("00" + (monthIndex+1)).slice(-2) + '-' + ("00" + (day)).slice(-2) : day + ' ' + monthNames[monthIndex];
	}
	
	var loading_listings, listings_timeout;
	
	function load_listings(){
		console.log("Loading listings...");
		//console.log(params);
		if(typeof(loading_listings)!="undefined") loading_listings.abort();
		if(typeof(listings_timeout)!="undefined") clearTimeout(listings_timeout);
		$("#whatson #listings").addClass("zindex loading");
		
		var d = {
			"action":"load_listings",
			"params":params,
		};
		
		console.log(params);
		
		listings_timeout = setTimeout(function(){
			loading_listings = $.ajax({
				url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
				data: d,
				//dataType: "JSON",
				success: function(r){
					$("#whatson #listings").html(r);
				},
				complete: function(r){
					$("#whatson #listings").removeClass("loading");
					listings_timeout = setTimeout(function(){
						$("#whatson #listings").removeClass("zindex");
					},300);
				},
			});
		},300);
	}
	<?php
	}
	?>

	$("#event-details nav li").on("click",function(){
		$("#event-details nav li").removeClass("active");
		$(this).addClass("active");
		var ref = $(this).data("ref");
		$("#event-variable > div").hide();
		$("#event-variable > div[data-ref="+ref+"]").show();
	});
	
	var isOldIE = (navigator.userAgent.indexOf("MSIE") !== -1); // Detect IE10 and below
	iFrameResize({checkOrigin:false,heightCalculationMethod:isOldIE ? 'max' : 'lowestElement'}, '#SpektrixIFrame');
	
	<?php
	if(is_singular('spektrix_event')){
	?>
	if(window.location.hash) {
		var hash = window.location.hash.replace("#","");
		if(hash!=""){
			var tmp = hash.split("/");
			hash = tmp[0];
			if(jQuery("#event-details nav li[data-ref="+hash+"]").length>0){
				//console.log("Trigger " + hash);
				jQuery("#event-details nav li[data-ref="+hash+"]").trigger("click");
				var ref = tmp[1];
				if(parseInt(ref)>0){
					$("#SpektrixIFrame").addClass("loading").attr("src","https://system.spektrix.com/<?php echo spektrix_get_client(); ?>/website/chooseseats.aspx?EventInstanceId="+ref);
				}
			}
		}

	}

	$("body").on("click","#event-details a[href^=#]",function(){
		var hash = $(this).attr("href").replace("#","");
		if(jQuery("#event-details nav li[data-ref="+hash+"]").length>0){
			jQuery("#event-details nav li[data-ref="+hash+"]").trigger("click");
			if(hash=="tickets"){
				var ref = $(this).data("ref");
				if(parseInt(ref)>0){
					$("#SpektrixIFrame").addClass("loading").attr("src","https://system.spektrix.com/<?php echo spektrix_get_client(); ?>/website/chooseseats.aspx?EventInstanceId="+ref);
				}
			}
		}
		return false;
	});
	
	$("#SpektrixIFrame").on("load",function(){
		$(this).removeClass("loading");
	})
	<?php
	}
	?>
	
	$("#back-to-top").on("click",function(){
		$('html,body').animate({ scrollTop: 0 }, 500);
        return false; 
	});
	
	$(".text iframe, #event-variable > div[data-ref=details] iframe").each(function(i,el){
		if($(el).attr("src").indexOf("youtube")>0){
			$(el).wrap('<div class="iframe-wrapper"></div>');
		}
	});
	
	$("body").on("submit",".mailchimp_signup",function(e){
		e.preventDefault();
		
		var form = $(this);
		
		if(form.hasClass("loading")) return false;
		
		form.addClass("loading");
		form.find(".signup-status").removeClass("active success error");
		
		$.ajax({
			type: "POST",
			url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
			dataType: "json",
			data: {
				"action":"subscribe_to_mailchimp",
				"email":form.find("input[type=email]").val(),
				"security":"<?php echo wp_create_nonce("subscribe_to_mailchimp"); ?>"
			},
			success: function(response){
				console.log(response);
				
				if(response.success){
					if (response.exists){
						form.find(".signup-status").html("It looks you're already subscribed!").addClass("active error");
					} else {
						form.find(".signup-status").html("Awesome! You're now subscribed.").addClass("active success");
					}
				} else {
					if (response.exists){
						form.find(".signup-status").html("You've previously unsubscribed - please email us.").addClass("active error");
					} else {
						form.find(".signup-status").html("Oh no! Something went wrong - please try again.").addClass("active error");
					}
				}
				
				form.removeClass("loading");
			},
			error: function(response){
				console.log(response);
				form.find(".signup-status").html("Oh no! Something went wrong - please try again.").addClass("active error");
				form.removeClass("loading");
			},
		});
		
		return false;		
	});
});
</script>
</body>
</html>