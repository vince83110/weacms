<div class="buttons">
    <a class="button action red" href="<?=base_url_admin()?>pages/nouvelle">
        <span class="icon icon157"></span><span class="blabel">Vider la corbeille</span>
    </a>
    
    <a class="button left" href="<?=base_url_admin()?>pages"><span class="icon icon87"></span><span class="blabel">Pages</a>
    <a class="button middle on" href="<?=base_url_admin()?>pages/corbeille"><span class="icon icon186"></span><span class="blabel">Corbeille</a>
    <a class="button right" href="<?=base_url_admin()?>pages/arborescence"><span class="icon icon138"></span><span class="blabel">Arborescence</a>
</div>
    
<? if (count($pages)) : ?>

<table id="pages" class="zebra-striped">
    <thead>
        <tr>
            <th class="header blue">Titre de la page</th>
            <th class="header green">Menu</th>
            <th class="header red">Date d'ajout</th>
            <th class="header">Actions</th>
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
		$('#pages').tablesorter();
		
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
