$(document).ready(function() 
{		
	$('#menu-primary-navigation li').hover(function() {
		$(this).find('.sub-menu-1').fadeIn();
	}, function() {
		$(this).find('.sub-menu-1').fadeOut();		
	});
	
	$('.toupper').on('keyup', function() {
		$(this).val( $(this).val().toUpperCase() );
	});
	$('.toucfirst').on('keyup', function() {
		var string = $(this).val();
		$(this).val( string.charAt(0).toUpperCase() + string.slice(1).toLowerCase() );
	});
});