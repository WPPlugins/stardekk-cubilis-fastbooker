(function($) {
	$(document).ready(function(){
				
		// init colors on div
		$( ".cubilis-fastbooker-div" ).each( function( index, el ) {
			//$( el ).css({ "color": $( el ).data('font-color') });
		});
		
		// init colors on button
		$( ".btnCheckAvail" ).each( function( index, el ) {
			$(this).hover(function(){
				$(this).css("background-color", $( el ).data('button-hover-color'));
				$(this).css("border-color", $( el ).data('button-hover-color'));
				}, function(){
				$(this).css("background-color", $( el ).data('button-back-color'));
				$(this).css("border-color", $( el ).data('button-back-color'));
			});
		});
		
		// init colors on cubilis fancy (general availability view)
		$( ".fancybox-cubilis" ).each( function( index, el ) {
			$(this).hover(function(){
				$(this).css("color", $( el ).data('button-hover-color'));
				}, function(){
				$(this).css("color", $( el ).data('button-back-color'));
			});
		});

		// init datepickers
		// startdate
		$(".startdate-widget").datepicker({
				dateFormat: "dd-mm-yy",
				showOn: "both",
                buttonImage: "https://static.cubilis.eu/images/datepicker_dark.png",
                buttonImageOnly: true,
				minDate: 0
		});
		var today = new Date();
		$(".startdate-widget").datepicker("setDate", new Date());
		
		// enddate
		$(".enddate-widget").datepicker({
			dateFormat: "dd-mm-yy", 
			showOn: "both",
			buttonImage: "https://static.cubilis.eu/images/datepicker_dark.png",
			buttonImageOnly: true,
			minDate: 1
		});
		var tomorrow = new Date();
		tomorrow.setDate(today.getDate() + 1);
		$(".enddate-widget").datepicker("setDate", tomorrow);
		
		// when startdate is changed add +1 days to enddate
		$(".startdate-widget").change(function () {
			var d = toDate($(this).val());
			d.setDate(d.getDate() + 1);
			$(".enddate-widget").datepicker("option", "minDate", d);
		});
		
		// submit fastbooker form
		$( ".fastbooker-form-widget" ).submit(function( event ) {
			event.preventDefault();
			
			var integration = $( ".integration-widget" ).val();
			if (integration == "iframe") {
				openIframe($(this));
			} else if (integration == "fullscreen") {
				openFullscreen($(this));
			}

			return false;
		});
		
		// fancybox - general - availability
		$('.fancybox-cubilis').fancybox({
			width: 730,
			height: 625,
			autoDimensions: true,
			autoScale: true,
			type : 'iframe', 
			helpers: {
				overlay: {
					closeClick: false
				}
			}
		});
	});
	
	function openIframe(element)
	{
		var href = element.attr('action') + "?logisid=" + $( ".logis-widget" ).val()
			+ "&taal=" + $( ".lang-widget" ).val() + "&startdatum=" + $(".startdate-widget").val() + "&einddatum=" + $(".enddate-widget").val();
		if ($('.discount-widget').length) {
			if ($('.discount-widget').val() != "") {
				href += "&discount=" + $(".discount-widget").val();
			}
		}

		jQuery.fancybox({ href: href ,type: 'iframe', width: 500, height: 550, overlayColor: '#000', hideOnContentClick: false, hideOnOverlayClick: false });
	}
	
	function openFullscreen(element) 
	{
		var arrivalDate = $(".startdate-widget").val();
		var departureDate = $(".enddate-widget").val();

		var s = arrivalDate.split('-');
		arrivalDate = s[2] + '-' + s[1] + '-' + s[0];

		var e = departureDate.split('-');
		departureDate = e[2] + '-' + e[1] + '-' + e[0];
	
		var href = element.attr('action') + "?Language=" + $( ".lang-long-widget" ).val() + "&Arrival=" + arrivalDate + "&Departure=" + departureDate;
		if ($('.discount-widget').length) {
			if ($('.discount-widget').val() != "") {
				href += "&Room=&DiscountCode=" + $(".discount-widget").val();
			}
		}
		
		window.open(href, "_blank");
	}
	
	function toDate(dateStr) {
		var parts = dateStr.split("-");
		return new Date(parts[2], parts[1] - 1, parts[0]);
	}
	
}(jQuery));