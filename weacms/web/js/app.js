/** 
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @type			js file
*/

// Show page loader 
function load_show() {
    $('<div id="dialog-loading">'+
    	'<img src="'+ site_url +'web/images/ajax-loading.gif" />'+
    	'<h5>En cours de chargement...</h5></div>')
    	.dialog({ autoOpen:true, width: 360, dialogClass: 'dialog-loading', modal: true, stack: false, })
		.dialog('moveToTop');
	
	$overlay = $('.dialog-loading').prev();
	$overlay.css('zIndex', parseInt(parseInt($overlay.css('zIndex')) + 1));
}

// Hide page loader
function load_hide() {
	$('#dialog-loading').dialog('close').remove();
}

// Ajax post with reload page callback
function post_reload(url, data, location) {
	load_show();
	
    $.post(base_url + url, data,
        function(data) {
            if (data.success == 1) {    
            	if (typeof(location) === 'undefined') {
                	window.location.reload();   
            	} else {   
            		window.location.href = base_url + location;      		
            	}         
            } else {                
                $.pnotify({text:data.message, icon:false, type:'danger'});
                loadHide();
            }
        }, 'json')
        .error(function() { 
        	alert('Une erreur est survenue durant cette requête. Merci de prévenir l\'administrateur.'); 
			loadHide();
        });
}

// Format url for seo 
function format_url(url)
{
	return url.toLowerCase()
			.replace(/^\s+|\s+$/g, "") 
			.replace(/[_|\s]+/g, "-") 
			.replace(/[^a-z\u0400-\u04FF0-9-]+/g, "") 
			.replace(/[-]+/g, "-") 
			.replace(/^-+|-+$/g, "")
			.replace(/[-]+/g, "-");
}

$(function() {
	// Extends defaut dialog jqueryui config
	$.extend($.ui.dialog.prototype.options, {
	    modal: true,
	    autoOpen:false,
	    resizable: false,
	    draggable: false
	});
	
	$('a[data-dialog-open]').click(function () {
		$('#' + $(this).attr('data-dialog-open')).dialog('open');
	});
	$('a[data-dialog-close]').click(function () {
		$('#' + $(this).attr('data-dialog-close')).dialog('close');
	});
	
	$('.tip').tooltip();
	$('.chosen').chosen();
	$('.chosen-container').css('width', '333px');
		
	var ignore = [8,9,13,33,34,35,36,37,38,39,40,46];
	var runningRequest = false;
	var eventName = 'keypress';
	
	$('.nav-tabs').not('.nav-link').find('a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	}); 
	
	/* -------------------------------------------------------------------- */
	
	$('.search-box').find('.title').click(function() {
		$(this).next().slideToggle('fast');
		$(this).find('i.icon-plus-sign').toggleClass('icon-minus-sign');
	});	
	
	/* -------------------------------------------------------------------- */
	
	$('#tools').click(function() {
		$(this).find('.arrow').toggleClass('open');
		$('body').toggleClass('push-opened');
		$('#menu-tools').toggleClass('opened');
	});	
	
	/* -------------------------------------------------------------------- */
	
	$('#menu-tools-close').click(function() {
		$(this).find('.arrow').toggleClass('open');
		$('body').toggleClass('push-opened');
		$('#menu-tools').toggleClass('opened');
	});	
	
	/* -------------------------------------------------------------------- */
	
	$.datepicker.regional['fr'] = {
		closeText: 'Fermer',
		prevText: 'Précédent',
		nextText: 'Suivant',
		currentText: 'Aujourd\'hui',
		monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
		'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
		monthNamesShort: ['Janv.','Févr.','Mars','Avril','Mai','Juin',
		'Juil.','Août','Sept.','Oct.','Nov.','Déc.'],
		dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
		dayNamesShort: ['Dim.','Lun.','Mar.','Mer.','Jeu.','Ven.','Sam.'],
		dayNamesMin: ['D','L','M','M','J','V','S'],
		weekHeader: 'Sem.',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['fr']);	
	
	var date = new Date();
	$( '.date' ).datepicker({ minDate: '+1d', yearRange: parseInt(date.getFullYear() - 105) +':'+ parseInt(date.getFullYear()) });
	$( '.date-adh' ).datepicker({ minDate: 0 });
	$( '.date-max' ).datepicker({ changeMonth: true, changeYear: true, maxDate: '+1d', yearRange: parseInt(date.getFullYear() - 105) +':'+ parseInt(date.getFullYear()) });
	$( '.date-n' ).datepicker({ changeMonth: true, changeYear: true, defaultDate:new Date(1970, 01, 01), yearRange: parseInt(date.getFullYear() - 105) +':'+ parseInt(date.getFullYear()) });
	$( '.date-a' ).datepicker({ changeMonth: true, changeYear: true });
	//$('input.date, input.date-max, input.date-n').attr('readonly', 'readonly');	

	/* -------------------------------------------------------------------- */

	$('.page-sidebar').on('click', '.sidebar-toggler', function (e) {            
		var body = $('body');
		var sidebar = $('.page-sidebar');
		var is_collapsed = 1;
		$('#logo-mutuelle-var').css('margin', '0px');
		$(".sidebar-search", sidebar).removeClass("open");

		if (body.hasClass("page-sidebar-closed")) {
			is_collapsed = 0;
			body.removeClass("page-sidebar-closed");
			$('#logo-mutuelle-var').css('margin', '14px');
			if (body.hasClass('page-sidebar-fixed')) {
				sidebar.css('width', '');
			}
		} else {
			body.addClass("page-sidebar-closed");
		}
		
		$.post(base_url + 'services/update_collapse/'+ is_collapsed);
	});	
	
	$('.sidebar-search .submit').on('click', function (e) {
		e.preventDefault();
	  
			if ($('body').hasClass("page-sidebar-closed")) {
				if ($('.sidebar-search').hasClass('open') == false) {
					if ($('.page-sidebar-fixed').size() === 1) {
						$('.page-sidebar .sidebar-toggler').click(); //trigger sidebar toggle button
					}
					$('.sidebar-search').addClass("open");
				}
			}
	});	
	
	$('.go-top').click(function() {
		jQuery('html,body').animate({
				scrollTop: 0
			}, 'slow');	
	});
	
	/* -------------------------------------------------------------------- */
	
	$('.toupper').live('keyup', function() {
		$(this).val( $(this).val().toUpperCase() );
	});
	$('.toucfirst').live('keyup', function() {
		var string = $(this).val();
		$(this).val( string.charAt(0).toUpperCase() + string.slice(1).toLowerCase() );
	});	
	
	/* -------------------------------------------------------------------- */
	
	$('textarea[maxlength]')
		.live(eventName, function(event) {
			var self = $(this),
				maxlength = self.attr('maxlength'),
				code = $.data(this, 'keycode');
		
			if (maxlength && maxlength > 0) {
				
				var l = $(this).val().length;
				$(this).next('.help-block').html('Il vous reste ' + (maxlength-l) + ' caractères');		
				
				return ( self.val().length < maxlength
					|| $.inArray(code, ignore) !== -1 );
			}
		})
		.live('keydown', function(event) {
			$.data(this, 'keycode', event.keyCode || event.which);
		});
	
	/* -------------------------------------------------------------------- */
	
	$('.sidebar-search').submit(function(e) {
		e.preventDefault();
		var $q = $('#top-search');
		
		if($q.val().length < 2){ 
			$('div#top-search-results').hide().html('');
			return false;
		}
		
		if(runningRequest){
			return;
		}

		runningRequest=true;

		request = $.getJSON(base_url + 'services/search',{
			q:$q.val()
		},function(response){           
			showResults(response,$q.val());
			runningRequest=false;
		},'json');

		function showResults(data, highlight) {
			var r = '<h3>Votre recherche - '+ data.count +' résultats</h3>';
			r += '<div class="row-fluid">';
			r += '<div class="span4"><div class="portlet box blue "><div class="portlet-title"><div class="caption"><i class="icon-envelope"></i>Messages reçus</div></div><div class="portlet-body">';
			
			if (data.mails.length == 0) {
				r += '<div class="well danger">Aucun message trouvé.<i class="icon-remove-sign"></i></div>';
			}
			
			$.each(data.mails, function(i,item){
				item.description = item.name + ' - ' + item.title + ', du ' + item.date_creation;
							  
				r += '<blockquote class="result">';
				r += '<h4><a href="'+ base_url + item.link +'">'+ item.title +'</a></h4>';
				r += '<p class="np">'+item.description.replace(highlight, '<span class="highlight">'+highlight+'</span>').replace((highlight.charAt(0).toUpperCase() + highlight.slice(1)), '<span class="highlight">'+(highlight.charAt(0).toUpperCase() + highlight.slice(1))+'</span>')+'</p>';
				r += '<small><a href="'+ base_url + item.link +'" class="readMore">/'+ item.link +'</a></small><i class="icon-envelope"></i>';
				r += '</blockquote>';
			});
			
			r += '</div></div></div>';
			r += '<div class="span4"><div class="portlet box red"><div class="portlet-title"><div class="caption"><i class="icon-folder-open"></i>Fichiers intranet</div></div><div class="portlet-body">';

			if (data.fichiers.length == 0) {
				r += '<div class="well danger">Aucun fichier trouvé.<i class="icon-remove-sign"></i></div>';
			}
			
			$.each(data.fichiers, function(i,item){
				item.description = item.name + ' - ' + item.title + ', du ' + item.date_creation;
							  
				r += '<blockquote class="result">';
				r += '<h4><a href="'+ base_url + item.link +'">'+ item.title +'</a></h4>';
				r += '<p class="np">'+item.description.replace(highlight, '<span class="highlight">'+highlight+'</span>').replace((highlight.charAt(0).toUpperCase() + highlight.slice(1)), '<span class="highlight">'+(highlight.charAt(0).toUpperCase() + highlight.slice(1))+'</span>')+'</p>';
				r += '<small><a href="'+ base_url + item.link +'" class="readMore">/'+ item.link +'</a></small><i class="icon-folder-open></i>';
				r += '</blockquote>';
			});
			
			r += '</div></div></div>';
			r += '<div class="span4"><div class="portlet box green"><div class="portlet-title"><div class="caption"><i class="icon-group"></i>Adhérents</div></div><div class="portlet-body">';
			
			if (data.adherents.length == 0) {
				r += '<div class="well danger">Aucun adhérent trouvé.<i class="icon-remove-sign"></i></div>'
			}
						
			$.each(data.adherents, function(i,item){
				item.description = item.name + ' - ' + item.title + ', du ' + item.date_creation;
							  
				r += '<blockquote class="result">';
				r += '<h4><a href="'+ base_url + item.link +'">'+ item.title +'</a></h4>';
				r += '<p class="np">'+item.description.replace(highlight, '<span class="highlight">'+highlight+'</span>').replace((highlight.charAt(0).toUpperCase() + highlight.slice(1)), '<span class="highlight">'+(highlight.charAt(0).toUpperCase() + highlight.slice(1))+'</span>')+'</p>';
				r += '<small><a href="'+ base_url + item.link +'" class="readMore">/'+ item.link +'</a></small><i class="icon-user"></i>';
				r += '</blockquote>';
			});
			
			r += '</div></div></div>';

			$('#top-search-results').fadeIn().html(r);
		}
	});
	
	/* -------------------------------------------------------------------- */

	var timerId, timerInProgress, ping, ms;
	
	timerInProgress = false;
	ms =  60000;    // in milliseconds
    
    /* Récupère les mails de façon asynchrone
     * Permet d'afficher une notification en cas de nouveau mail
     */
    ping = function() {
        if (timerInProgress === false) {
            timerInProgress = true;
            
            $.ajax({
                url      	: base_url + 'services/refresh',
				cache       : false,
				timeout     : '55000',
				dataType	: 'json',
				type        : 'POST',
                success: function(data) {
					timerInProgress = false;				
					
					/* Remplit le tableau d'avancement */
					$('.count-messages').html(data.count);
					$('.count-messages-adherents').html(data.count_adherents);
					$('.count-max-days').html(data.max_days);
						
					if (data.news != 0) {
						$.each(data.data, function(i, item) {
							if (item[0] > 0) {
							    $.pnotify({
								    title: 'Nouveau message',
								    text: '<div class="clearfix"><p class="floatL">Un nouveau message est arrivé !</p><a href="'+ base_url + 'support/index/'+ item[2] +'" class="floatR">&gt; Voir le flux</a></div>',
								    icon: 'icon-envelope'
								});
							}
						});
					}
                }
            });
        }
    };
    
    timerId = setInterval(ping, ms);
	
	ping();
}); 