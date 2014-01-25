<?= button('forms/nouveau', 'Créer un formulaire', 'plus', 'btn-info') ?>
<hr />

<? if (count($forms)) : ?>

<table id="table-forms" class="table table-striped table-bordered table-hover flip-content dataTable">
    <thead>
        <tr>
            <th>Titre du formulaire</th>
            <th>Contacts</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <? 
        foreach ($forms as $form) 
        {
            echo '
            <tr id="'. $form->id .'">
                <td>'. $form->title .'</td>	
                <td>'. $form->nombre . (strtotime($form->last ) > (time() - 7200) ? '<span class="label label-info pull-right">1 récent</span>' : '').'</td>	
                <td>'. $form->description .'</td>
                <td>'.
                	button('forms/edition/'. $form->id, '', 'pencil') .
                	button('forms/entries/'. $form->id, '', 'comments', 'btn-primary') .'			
				</td>
            </tr>';
        } ?>
    </tbody>
</table>

<? else : ?>

<div class="well notice">
    <strong>Pas encore un seul formulaire...</strong><br/>N'hésitez pas à créer votre premier formulaire à intégrer sur votre site.
</div>

<? endif; ?>

<script>
	$(function() { 
		$('#table-forms').dataTable();
		
		$('.trash').click(function() {
			if (confirm('Etes-vous sur d\'effacer cette page ?'))
			{
				var id = $(this).attr('rel');
				$.post('<?=base_url()?>pages/basket', {id:id}, 
					function(data) {
						$('#' + id).fadeOut().delay(800).remove();
					});																					
			}
		});		
	});    
</script>