<?= button('widgets/refresh', 'Rafraichir les widgets', 'refresh', 'btn-success', 'button-refresh') ?>
<?= button('', 'Charger un widget', 'download', 'btn-info') ?>
<?= button('widgets/template', 'Gérer les widgets sur le template', 'file', 'btn-primary') ?>
<hr />

<? if (count($widgets)) : ?>

<table id="table-widgets" class="table table-striped table-bordered table-hover flip-content">
    <thead>
        <tr>
            <th>Titre du widget</th>
            <th>Description</th>
            <th>Pages</th>
            <th>Template</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <? 
        foreach ($widgets as $widget) 
        {
            echo '
            <tr id="'. $widget->id .'">
                <td>'. $widget->title .'</td>	
                <td>'. $widget->description .'</td>
                <td>'. ($widget->pages ? implode(' - ', $widget->pages) : '') .'</td>
                <td></td>
                <td>
					'. button('', '', 'refresh', 'btn', 'action-refresh-'. $widget->id) .'
					'. button('', '', 'remove', 'btn-danger', 'action-remove-'. $widget->id) .'
					'. ($widget->configure ? button('widgets/admin/'. $widget->id, '', 'cog') : '') .'
				</td>
            </tr>';
        } ?>
    </tbody>
</table>

<? else : ?>

<div class="well notice">
	Aucun widget détecté. Si vous avez ajouté un widget récemment, cliquez sur le bouton "Rafraichir" pour récupérer la liste des widgets installés.
	<i class="icon-info-sign"></i>
</div>

<? endif; ?>

<script>
	$(function() { 
		$('#table-widgets').dataTable();
		
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
		
		$('#button-refresh').click(function() {
			load_show();
		});
	});    
</script>