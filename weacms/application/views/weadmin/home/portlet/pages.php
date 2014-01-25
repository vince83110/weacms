<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">En attente de validation</div>
	</div>
	<div class="portlet-body">
		<?= button('', 'Valider toutes les pages', 'ok', 'btn-success', 'button-action-valid-all') ?>
		<div class="space7"></div>
        <div class="clear"></div>
			<? if (count($pages)) : ?>
            
            <table id="pages" class="table table-striped table-bordered table-hover flip-content">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Modifiée le</th>
                        <th width="140"></th>
                    </tr>
                </thead>
                <tbody>
                    <? 
                    foreach ($pages as $page) 
                    {
                        echo '
                        <tr id="'. $page->id .'">
                            <td>'. $page->title .'</td>	
                            <td>'. format_date_diff($page->edited) .'</td>
                            <td>'. 
                            	button('pages/edition/'. $page->id, '', 'pencil') .
                                button('pages/voir/'. $page->id, '', 'eye-open') .
                                button('', '', 'ok', 'btn-success button-valid', 'page-id-'. $page->id) .'		
                            </td>
                        </tr>';
                    } 					
					?>
                </tbody>
            </table>
            
            <? else:  ?>
            
            <div class="well notice">
                Rien n'est en attente.</strong><br/>N'hésitez pas à créer vos pages pour lancer le site internet.
            </div>
            
            <? endif; ?>
	</div>
</div>