<?=button('formulaires/export_csv/'. $id, 'Exporter en Excel', 'download', 'btn-success')?>
<hr />

<? if (count($entries)) : ?>
<h3>Il y a <strong><?=count($entries)?></strong> entrées.</h3>

<table id="forms" class="table table-striped table-bordered table-hover flip-content dataTable">
    <thead>
        <tr>
            <th>Date</th>
            <th>IP</th>
            <? 	
			foreach ($fields as $o) 
			{
				echo '<th>'. $o->name .'</th>';
			} ?>
        </tr>
    </thead>
    <tbody>
        <? 		
        foreach ($entries as $o) 
        {
            echo '
            <tr>
                <td>'. format_date_diff($o[0]->date) .'</td>
				<td>'. $o[0]->ip .'</td>';
				
				$i = 1;
				
				foreach ($o as $s) 
        		{
					if (strlen($s->real_value) == 0) 
					{
						echo '<td> - </td>';	
					} 
					else 
					{
						echo '<td>'. ($s->fdid == 7 ? $s->value : $s->real_value) .'</td>';
						
					}
				}
				
            echo '</tr>';
        } ?>
    </tbody>
</table>

<? else : ?>

<div class="alert-message block-message warning">
    <p><strong>Pas encore une seule entrée...</strong><br/>Dès qu'une personne remplira le formulaire, une ligne apparaitra dans cette partie.</p>
</div>

<? endif; ?>

<script>
	$(function() { 
		$('#forms').tablesorter();
		
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