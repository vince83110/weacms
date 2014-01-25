<div class="btn-group">
    <?= button('pages', 'Pages', 'file') ?>
    <?= button('pages/trash', 'Corbeille', 'trash', 'active') ?>
    <?= button('pages/treeview', 'Arborescence', 'list-ol') ?>
</div>
<?= button('', 'Vider la corbeille', 'remove', 'btn-danger', 'button-action-empty') ?>
<hr />
    
<? if (count($pages)) : ?>

<table id="pages" class="table table-striped table-bordered table-hover flip-content dataTable">
    <thead>
        <tr>
            <th>Titre de la page</th>
            <th>Menu</th>
            <th>Date d'ajout</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <? 
        foreach ($pages as $o) 
        {
            echo '
            <tr id="'. $o->id .'">
                <td>
					'. $o->title .'
					<span class="label important fright">Effacé</span>
				</td>	
                <td>Accueil</td>
                <td>'. $o->created .'</td>
                <td>
					<a class="button left restore" rel="'. $o->id .'"><span class="icon icon157"></span></a>
					<a class="button middle" href=""><span class="icon icon84"></span></a>
					<a class="button right trash" rel="'. $o->id .'"><span class="icon icon56"></span></a>				
				</td>
            </tr>';
        } ?>
    </tbody>
</table>

<? else : ?>

<div class="alert-message block-message warning">
    <p><strong>Aucune page dans la corbeille...</strong><br/>Toutes les pages supprimées se retrouvent ici, vous pouvez les supprimer définitivement.</p>
</div>

<? endif; ?>

<script>
	$(function() { 
		$('#pages').dataTable();
		
		$('.trash').click(function() {
			var id = $(this).attr('rel');
			jConfirm('Voulez-vous vraiment supprimer définitivement cette page ?', 'Confirmation', function(r) {
				if (r) {
					$.post('<?=base_url_admin()?>pages/delete', {id:id}, 
					function(data) {
						$('#' + id).fadeOut().delay(800).remove();
						jQnotice('Page définitivement supprimée !');
						if ($('#pages tbody tr').length === 0) {
							$('#pages').remove();
							
							$('.buttons').after('<div class="alert-message block-message warning"><p><strong>Aucune page dans la corbeille... </strong><br/>Toutes les pages supprimées se retrouvent ici, vous pouvez les supprimer définitivement.</p></div>');
						}
					});						
				}
			});
		});
		
		$('.restore').click(function() {
			var id = $(this).attr('rel');
			jConfirm('Voulez-vous restauré cette page ?', 'Confirmation', function(r) {
				if (r) {	
					$.post('<?=base_url_admin()?>pages/restore', {id:id}, 
					function(data) {
						$('#' + id).fadeOut().delay(800).remove();
						jQnotice('Page restaurée !');
						if ($('#pages tbody tr').length === 0) {
							$('#pages').remove();
							
							$('.buttons').after('<div class="alert-message block-message warning"><p><strong>Aucune page dans la corbeille... </strong><br/>Toutes les pages supprimées se retrouvent ici, vous pouvez les supprimer définitivement.</p></div>');
						}
					});	
				}
			});
		});
	});    
</script>
