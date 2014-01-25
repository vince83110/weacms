<div class="buttons">
    <?=button('pages/edition/'. $lang_main, 'Créer une nouvelle page', 'ok', 'btn-info')?>
    
    <div class="btn-group">
	    <?= button('pages', 'Pages', 'file', 'active') ?>
	    <?= button('pages/corbeille', 'Corbeille', 'trash') ?>
	    <?= button('pages/arborescence', 'Arborescence', 'tree') ?>
    </div>
    
    <?=button(NULL, 'Regénérer toutes les pages', 'repeat', 'btn-success', 'validate')?>
</div>

<div class="buttons">
	<h5 style="display:inline; margin-right:20px;" class="fleft">Définir l'article principal :</h5>
	<select class="fleft" id="dossier" style="width:300px;">
        <? 
        foreach ($articles as $o) 
        {
            echo '
            <option value="'. $o->id_ar .'"'. ($o->is_dossier == 1 ? ' selected="selected"' : '') .'>'. $o->title .'</option>';
        } ?>
	</select>
	
	<a id="valid-dossier" class="button action green fright" style="margin:0;"><span class="blabel">Valider</span></a>
</div>
    
<? if (count($articles)) : ?>

<table id="articles" class="zebra-striped">
    <thead>
        <tr>
            <th class="header blue">Titre de la page</th>
            <th class="header green">Catégorie</th>
            <th class="header red">Date de modification</th>
            <th class="header">Actions</th>
        </tr>
    </thead>
    <tbody>
        <? 
		$states = array(
			0 => array('warning', 'En attente'),
			1 => array('success', 'Validé'),
		);
		
        foreach ($articles as $o) 
        {
            echo '
            <tr id="'. $o->id_ar .'">
                <td>
					'. $o->title .'
					'. (strtotime( $o->edited ) > (time() - 86400 * 1) ? '<span class="label label-info pull-right marged">New</span>' : '') .'
					<span class="label label-'. $states[ $o->state ][0] .' pull-right">'. $states[ $o->state ][1] .'</span>
				</td>	
                <td>'. $o->category .'</td>
                <td>'. format_date_diff($o->edited) .'</td>
                <td>
					'. button('articles/edition/'. $o->id_ar, 145, NULL, 'left', NULL, 'Modifier la page') .'
					'. button('articles/voir/'. $o->id_ar, 84, NULL, 'middle', NULL, 'Visualiser la page dans le site', '_blank') .'
					'. button(NULL, 186, NULL, 'right trash', NULL, 'Envoyer dans la corbeille') .'				
				</td>
            </tr>';
        } ?>
    </tbody>
</table>

<div id="pager" class="pager"></div>

<? else : ?>

<div class="alert-message block-message warning">
    <p><strong>Pas encore un seul article</strong><br/>N'hésitez pas à créer vos articles pour lancer le site internet.</p>
    <div class="alert-actions">
        <a class="button" href="<?=base_url_admin()?>articles/nouveau">
            <span class="icon icon68"></span><span class="blabel">Créer un nouvel article</span>
        </a>
        <div class="clear"></div>
    </div>
</div>

<? endif; ?>

<script>
	$(function() { 
		$('#articles').tablesorter();
		
		$('.trash').click(function() {
			if (confirm('Etes-vous sur d\'effacer cet article ?'))
			{
				var id = $(this).parent().parent().attr('id');
				$.post('<?=base_url_admin()?>articles/basket', {id:id}, 
					function(data) {
						$('#' + id).fadeOut().delay(800).remove();
					});																					
			}
		});	
		
		// Valide toutes les articles en cours 
		$('#validate').click(function() 
		{
			jConfirm('Voulez-vous vraiment valider toutes les articles et les afficher sur le site ?', 'Confirmation', function(r) {
				if (r) {
					$('.loader').show();
					
					$.post('<?=base_url_admin()?>articles/valider_toutes',
						function(data) {
							if (data == 'done') {
								window.location = '<?=base_url_admin()?>articles';
								
							} else {
								$('.loader').hide();
								jQnotice(data);	
							}
						});		
				}
			});	
		});		
		
		$('#valid-dossier').click(function() {
			var id = $('#dossier').val();
			jConfirm('Voulez-vous mettre cet article en dossier principal ?', 'Confirmation', function(r) {
				if (r) {
					$.post('<?=base_url_admin()?>articles/valider_dossier', {id:id},
						function(data) {
							jQnotice('Dossier spécial mis à jour !');	
						});		
				}
			});			
		});
	});    
</script>
