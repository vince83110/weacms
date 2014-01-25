<?=button('', 'Enregistrer le formulaire', 'download', 'btn-info update')?>
<hr />
<div class="tabbable tabbable-custom" style="border-bottom: 1px solid #ddd;">
	<ul class="nav nav-tabs">
        <li class="active"><a href="#tab_infos">Informations générales</a></li>
        <li><a href="#tab_builder">Construction du formulaire</a></li>
    </ul>
</div>
    
<div class="tab-content">
	<div id="tab_infos" class="active tab-pane">		
	
	<form id="general" class="form-horizontal">	
        <?=input('title', 'Titre du formulaire', 'T', $form)?>
        <?=input('sending', 'Envoyer vers l\'adresse', '@', $form)?>
		<?=textarea('description', 'Description', $form)?>
        
        <? if (isset($form)) : ?>
        	<h5>Code HTML du formulaire</h5>
        	<blockquote>
            	<?=nl2br(htmlspecialchars($form_html))?>
            </blockquote>
            
        <? endif; ?>
    </form>
	</div>

	<div id="tab_builder" class="tab-pane">
		<div class="well notice">
			Vous pouvez faire glisser les champs disponibles dans la zone principale pour construire votre formulaire.
		</div>
    
		<div class="wcol wcol2 width_300">
			<div class="alert alert-success">Champs disponibles</div>
			<div id="dfields" class="wcontent">
				<? 
				foreach ($fields as $o) 
				{
					echo '<div rel="'. $o->id .'" class="field portlet field_'. $o->id .'"><span></span>'. $o->name .'</div>';
				
				} ?>
				<div class="clear"></div>
			</div>
		</div>
		<div class="wcol wcol1 wcol_right">
			<div class="alert alert-info">Votre formulaire</div>
			<form class="wcontent relative"> 
				<div class="loader hide"></div>			  
				<ul id="zone" class="form-horizontal">
					<?
					foreach ($form_fields as $o)
					{
						echo 
						'<div class="ui-draggable clearfix" rel="'. $o->fdid .'">
							'. $this->form->show_field($o->fdid, $o->name, count($o->options) ? $o->options : NULL) .'
						</div>';
					} ?>
					<li id="empty" class="alert-message block-message warning<?=(count($form_fields) ? ' hide' : '')?>">
						<p>Aucun champ actuellement...</p>
					</li>
				</ul> 
			</form>
		</div>
	</div>
</div>

<div id="dfield" class="hide">
    <form id="fform" class="form-horizontal">
        <?=input('fname', 'Label du champ')?>
        <?=checkbox('frequired', 'Champs requis ?', NULL, NULL, array('1' => 'Oui'))?>
        
        <div id="dvalues" class="hide">
        	<?=input('fvalues', 'Options', '1')?>
            
            <a class="btn btn-success" id="insert_option"><i class="icon-plus"></i></a>
        </div>
        
		<hr />
        <?=button('', 'Valider', 'ok', 'btn-info pull-left', 'dvalid')?>
        <?=button('', 'Annuler', 'remove', 'pull-right', 'dcancel')?>
    </form>
</div>

<script type="text/javascript">
/*	Quelques variables globales
 *  	- id : entier qui contient l'ID du formulaire si on est en mode modification, sinon 0 pour indiquer qu'il s'agit d'un nouveau
 *		- drag_type : entier qui correspond au type de formulaire que l'utilisateur déplace, fait le lien entre l'objet déplacé et l'objet droppé
 *		- step : entier qui indique sur quel onglet on se trouve
 *		- field : objet qui correspond au champ qui est en cours de modification
 */
var id = <?=($form ? $form->id : 0)?>;
var drag_type = 0;
var step = 0;
var field = null;

/*	Gestion de la navigation par onglet
 *  param - @obj : objet cliqué
 */
function openTab(obj) {
		var id = obj.attr('rel');
		$('#nav a').removeClass('on');
		obj.addClass('on');
		step = obj.index();
		$('.opened').hide();
		$('#' + id).show().addClass('opened');
		
		if (step === 2)
		{
			$('#next').hide();
		} else {
			$('#next').show();	
		}	
}

/* Enregistre la page via une requête AJAX
 * param - @type : callback à effectuer
 */
function saveDraft(type)
{
	/* On récupère la liste des champs */
	var fields = '';
	$('#zone .ui-draggable').each(function(i, o)
	{
		var rel = $(o).attr('rel');
		var opts = '';
		
		/* Si on des options associées au champ (select, radio) */
		if (rel == 5 || rel == 6 || rel == 7) {
			
			if (rel == 7) {
				
				$(o).find('option').each(function(i, o) 
				{		
					opts += $(o).html() + '|';
				});	
			}
			else {
				
				$(o).find('.inputs-list').find('span').each(function(i, o) 
				{		
					opts += $(o).html() + '|';
				});			
			}
		}		
		
		fields += '&' + i + '=' + rel + '_' + encodeURIComponent($(o).find('label:first').html()) + '_' + $(o).find('.important').length + '_' + opts;
	});	
	
	$.post('<?=base_url_admin()?>forms/update', 'id=' + id + '&' + $('#general').serialize() + fields, 
		function(data)
		{
			if (data.length > 10) {
				jQnotice(data);
				return;
			}
			
			id = parseInt(data);
			switch(type) {
				case 0: 	
					jQnotice('Formulaire bien enregistré !');
				break;
				case 1:
					$('.topbar, .container').hide();
					$('#visu').show().find('iframe').attr('src', '<?=base_url_admin()?>forms/voir/'+id); 
				break;
				case 2:
					window.location.href= '<?=base_url_admin()?>forms'; 
				break;
			}
		});	
}

/* Fonction pour fermer la fenêtre de visualisation */
function closeVisu()
{
	$('#visu').hide().find('iframe').html(''); 		
	$('.topbar, .container').show();
}

/* DOM chargé, on associe les événements aux éléments */
$(function() {
	/* JS trick to get visualisation view */
	$('body').append('<div id="visu" class="hide"><a class="close" onclick="closeVisu();">Fermer</a><iframe height="100%" width="100%" frameborder="0"></iframe></div>');
	//.attr('onBeforeUnload', 'return(\'Les données non-sauvegardées seront perdues\')');
	
	/* On attribue les événements pour les boutons de la page */
	$('.update').click(function() {
		saveDraft(0);
	});
	
	$('.view').click(function() {
		saveDraft(1);
	});
	
	$('.save').click(function() {
		saveDraft(2);
	});
	
	$('#nav a').click(function() {
		openTab($(this));
	});
	
	$('#next').click(function() {
		openTab($('#nav a:eq(' + (step+1) + ')'));
	});
	
	$('#zone .control-group').append('<a class="btn btn-danger pull-right delete_field"><i class="icon-remove"></i></a><a class="btn pull-right edit_field"><i class="icon-gear"></i></a>');
	
	/* Gestion de la fenêtre qui permet de modifier les champs */
	$('#dfield').dialog({
		width:	460,
		modal: true,
		autoOpen: false,
		resizable: false,
		draggable: false,
		title: 'Edition d\'un champ'
	});
	
	$('#dcancel').click(function() {
		$('#dfield').dialog('close');			 
	});	
	
	/* Gestion du drag'n'drop des champs
	 * #zone reçoit les champs, à chaque drop un appel AJAX récupère le code HTML du champ
	 * Les champs sont disponibles dans : /libraries/Form.php
	 */
	$( '#zone' ).sortable({
		revert: true,
		stop: function(event, ui) {
			$('#empty').hide();		
			
			if ( ui.item.hasClass('field') ) {
				var rel = ui.item.attr('rel');
				$('.loader').show();
				$.post('<?=base_url_admin()?>forms/generate', {type:rel},
				 function(data) {
					 /* Pas très MVC tout ça ... 
					  * On récupère via un service le HTML du champ, ceci permet de garder des Helpers PHP
					  */
					 $('.loader').hide();
					 ui.item.html( data.toString() )
						.removeClass('field portlet field_' + rel)
						.find('.control-group')
						.append('<a class="btn btn-danger pull-right delete_field"><i class="icon-remove"></i></a><a class="btn pull-right edit_field"><i class="icon-gear"></i></a>');
				 });
			}
		}
	});
	
	/* Chaque field peut être draggué dans la zone
	 * Quand on commence à le déplacer, drag_type prend la valeur du type du champ pour identifier le type lors du drop
	 */
	$( '#dfields .field' ).draggable({
		revert: 'invalid',
		containment: 'document',
		cursor: 'move',
		connectToSortable: '#zone',
		helper: 'clone',
		start: function(event, ui) {
			drag_type = $(this).attr('rel');	
		}
	});
	
	/* Suppression d'un champ qui a été droppé, on l'efface complètement du DOM */
	$('.delete_field').live('click', function() {
		var obj = $(this);
		jConfirm('Voulez-vous effacer ce champ ?', 'Confirmation', function(r) {
			if (r) {
				obj.parent().parent().remove();
				jQnotice('Champ supprimé !');					
			}
		});
	});
	
	/* On ouvre la fenêtre d'édition, on efface les anciennes données du formulaires 
	 * @field prend la valeur du champ en cours
	 */
	$('.edit_field').live('click', function() {
		field = $(this).parent().parent();
				
		/* On récupère les valeurs du champ (label, requis ou non) */
		$('#fname').val( field.find('label:first').html() );
		if (field.find('.important').length) {
			
			$('#frequired_0').attr('checked', true);	
		} else {
			
			$('#frequired_0').attr('checked', false);
		}
		
		/* Dans le cas de cases à cocher ou d'un select, on récupère toutes les valeurs associées */
		var rel = field.attr('rel');
		$('#dvalues').hide();
		
		if (rel == 5 || rel == 6 || rel == 7) {
			$('#dvalues').show();
			$opt = $('#dvalues').find('.input-prepend:first').clone();
			$('#dvalues .input').remove();

			if (rel == 7) {
				
				field.find('option').each(function(i, o) 
				{		
					$clone = $opt.clone();
					$clone.find('.add-on').html((i+1));
					$clone.find('input').val($(o).html());
					$('#dvalues .clearfix').append($clone);
				});	
			}
			else {
				
				field.find('.inputs-list').find('span').each(function(i, o) 
				{		
					$clone = $opt.clone();
					$clone.find('.add-on').html((i+1));
					$clone.find('input').val($(o).html());
					$('#dvalues .clearfix').append($clone);
				});			
			}
		}
	
		$('#dfield').dialog('open');
	});
	
	/* On clone le premier champ et on le répète pour ajouter des options */
	$('#insert_option').click(function() {
		$clone = $('#dvalues').find('.input-prepend:first').clone();
		$clone.find('.add-on').html( ($('#dvalues').find('.input').length + 1) );
		$clone.find('input').val('');
		$('#dvalues .clearfix').append($clone);
	});
	
	/* On valide les modifications du champ
	 * Les modifications sont apportées sur @field
	 */
	$('#dvalid').click(function() {
		if ($('#frequired_0').attr('checked'))	{
			
			field.find('.input').append('<span class="label important">Champ requis</span>');	
		} else {
			
			field.find('.important').remove();
		}
		
		/* Dans le cas de cases à cocher ou d'un select, on attribue les nouvelles valeurs aux options */
		var rel = field.attr('rel');
		
		if (rel == 5 || rel == 6 || rel == 7) {

			if (rel == 7) {
				
				$opt = field.find('option:first').clone();
				field.find('select').html('');		
				
				$('#dvalues').find('input').each(function(i, o) 
				{		
					if ( $(o).val().trim().length ) {
						$clone = $opt.clone();
						$clone.html($(o).val());
						field.find('select').append($clone);
					}
				});	
			}
			else {
				
				$opt = field.find('.inputs-list li:first').clone();
				field.find('.inputs-list').html('');				
				
				$('#dvalues').find('input').each(function(i, o) 
				{		
					if ( $(o).val().trim().length ) {
						$clone = $opt.clone();
						$clone.find('span').html($(o).val());
						field.find('.inputs-list').append($clone);
					}
				});			
			}
		}
		
		field.find('label:first').html( $('#fname').val() );
		
		$('#dfield').dialog('close');			 
	});		
});
</script>
