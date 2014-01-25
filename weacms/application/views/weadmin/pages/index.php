<div class="buttons">
    <?=button('pages/edition/'. $lang_main, 'Créer une nouvelle page', 'ok', 'btn-info')?>
    
    <div class="btn-group">
	    <?= button('pages', 'Pages', 'file', 'active') ?>
	    <?= button('pages/trash', 'Corbeille', 'trash') ?>
	    <?= button('pages/treeview', 'Arborescence', 'list-ol') ?>
    </div>
    
    <?=button(NULL, 'Regénérer toutes les pages', 'repeat', 'btn-success', 'validate')?>
</div>

<hr />

<div class="tabbable tabbable-custom" style="border-bottom: 1px solid #ddd;">
	<ul class="nav nav-tabs nav-link">
	<? 
	foreach ($langs as $lang) {
		echo '
			<li'. ($lang_main == $lang->id_dl ? ' class="active"' : '') .'>
				<a href="'. base_url_admin('pages/index/'. $lang->id_dl) .'">'. $lang->name .'</a>
			</li>';
	} ?>
	</ul>
</div>

<? if (count($pages)) : ?>

<?= button('', 'Définir la page d\'accueil', 'home', 'btn-primary pull-right', 'button-dialog-homepage') ?>

<table id="table-pages" class="table table-striped table-bordered table-hover flip-content dataTable">
    <thead>
        <tr>
            <th>Nom de la page</th>
            <th>Page parente</th>
            <th>Date de modification</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <? 
		$states = array(
			0 => array('label-warning', 'En attente'),
			1 => array('label-success', 'En ligne'),
		);
		
        foreach ($pages as $page) 
        {
            echo '
            <tr id="'. $page->id .'">
                <td>
					'. $page->name .'
					'. ($page->homepage ? '<span class="label label-info pull-right"><i class="icon-home"></i></span>' : '') .'
				</td>	
                <td>'. $page->category .'</td>
                <td>'. format_date_diff($page->edited) .'</td>
				<td>		
					'. (strtotime( $page->edited ) > (time() - 21600) ? '<span class="label label-info">Récent</span>' : '') .'
					<span class="label '. $states[ $page->state ][0] .'">'. $states[ $page->state ][1] .'</span>				
				</td>
                <td>
					'. button('pages/edition/'. $page->id .'/'. $lang_main, '', 'pencil', false, false, 'Modifier la page') .'
					'. button('pages/view/'. $page->id .'/'. $lang_main, '', 'eye-open', false, false, 'Visualiser la page dans le site', '_blank') .'
					'. button(false, '', 'trash', 'btn-action-trash', 'trash-page-id-' . $page->id, 'Envoyer dans la corbeille') .'		
					'. button('', '', 'refresh', 'btn-success button-action-valid', 'valid-page-id-' . $page->id, 'Valider la page pour l\'afficher') .'		
				</td>
            </tr>';
        } ?>
    </tbody>
</table>

<div id="pager" class="pager"></div>

<? else : ?>

<div class="well danger">
    <strong>Pas encore une seule page...</strong><br/>N'hésitez pas à créer vos pages pour lancer le site internet.
</div>

<? endif; ?>

<!-- Dialog link -->
<div id="dialog-homepage" class="hide form-horizontal">
	<p>Vous pouvez choisir ici la page qui sera affichée en tant que page d'accueil de votre site.</p>
	<?= select('select-homepage', 'Choisir parmi les pages', FALSE, $selectview, '', 'chosen') ?>
	
    <?= button('', 'Valider', 'ok', 'btn-info', 'button-action-set-homepage') ?>
</div>

<script>
	$(function() { 
		$('#table-pages').dataTable();
	
		/**
		 *	Dialog box for link pages
		 */	
		$('#dialog-homepage').dialog({
			width: 600,
			title: 'Page d\'accueil du site'
		});	
		$('#button-dialog-homepage').click(function() { $('#dialog-homepage').dialog('open'); });		


		/**				
		/* Confirm command if you want to delete a page
		 */		
		$('.button-action-trash').click(function() {
			if (confirm('Voulez-vous vraiment mettre cette page à la corbeille ?')) {
				post_reload('pages/basket', {id:$(this).attr('id').replace('trash-page-id-', '')});
			}
		});	
		
		
		/* Validate all pages 
         * Usefull if you have edited your template 
		 */
		$('#validate').click(function() 
		{
			if (confirm('Voulez-vous validez toutes les pages ? Cette opération peut prendre quelques instants...')) {
				post_reload('pages/valid_all/');
			}
		});		
		
		/**		
		/* Valid one page
		 */
		$('.button-action-valid').click(function() {
			if (confirm('Voulez-vous vraiment valider cette page et l\'afficher sur le site ?')) {
				post_reload('pages/valid_one/'+ $(this).attr('id').replace('valid-page-id-', ''));
			}
		});			
	});    
</script>
