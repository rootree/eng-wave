
/* ========================================================
*
* Londinium - premium responsive admin template
*
* ========================================================
*
* File: application.js;
* Description: General plugins and layout settings.
* Version: 1.0
*
* ======================================================== */



$(function() {



/* # Data tables
================================================== */




/* # Select2 dropdowns 
================================================== */






/* # Form Validation
================================================== */



/* # Bootstrap Multiselects
================================================== */




/* # jQuery UI Components
================================================== */




//===== Jquery UI sliders =====//



/* # Bootstrap Plugins
================================================== */

	
	//===== Tooltip =====//

	$('.tip').tooltip();


	//===== Popover =====//

	$("[data-toggle=popover]").popover().click(function(e) {
		e.preventDefault()
	});

	//===== Add fadeIn animation to dropdown =====//

	$('.dropdown, .btn-group').on('show.bs.dropdown', function(e){
		$(this).find('.dropdown-menu').first().stop(true, true).fadeIn(100);
	});


	//===== Add fadeOut animation to dropdown =====//

	$('.dropdown, .btn-group').on('hide.bs.dropdown', function(e){
		$(this).find('.dropdown-menu').first().stop(true, true).fadeOut(100);
	});


	//===== Prevent dropdown from closing on click =====//

	$('.popup').click(function (e) {
		e.stopPropagation();
	});





/* # Form Related Plugins
================================================== */



	//===== Elastic textarea =====//
	
	$('.elastic').autosize();


	//===== Form elements styling =====//
	
	$(".styled, .multiselect-container input").uniform({ radioClass: 'choice', selectAutoWidth: false });




/* # Interface Related Plugins
================================================== */


	//===== jGrowl notifications defaults =====//

	$.jGrowl.defaults.closer = false;
	$.jGrowl.defaults.easing = 'easeInOutCirc';

    //===== Disabling main navigation links =====//

    $('.navigation .disabled a, .navbar-nav > .disabled > a').click(function (e){
        e.preventDefault();
    });




/* # Default Layout Options
================================================== */


	//===== Applying offcanvas class =====//

	$(document).on('click', '.offcanvas', function () {
		$('body').toggleClass('offcanvas-active');
	});




	//===== Toggling active class in accordion groups =====//

	$('.panel-trigger').click(function(e){
		e.preventDefault();
		$(this).toggleClass('active');
	});


});