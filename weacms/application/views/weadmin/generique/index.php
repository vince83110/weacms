<a class="btn btn-info" href="<?= base_url_admin($params['url'] . 'edition') ?>">
	<i class="icon-plus"></i> <?= $params['button'] ?>
</a>
<?= (isset($params['other_buttons']) ? $params['other_buttons'] : '') ?>
<hr />

<? if (! count($params['data'])) : ?>
<div class="alert">
	<p>Aucune entrée enregistrée. Utilisez le bouton ci-dessus pour créer des entrées.</p>
</div>
<? endif; ?>

<table id="table" class="table table-striped table-bordered table-advance table-hover dataTable">
	<thead>
		<tr>
			<? foreach ($params['fields'] as $label => $field) 
			{			   
			   echo '<th>', $label ,'</th>'; 
			} ?>
			<th width="100">Actions</th>
		</tr>
	</thead>
	<tbody>
	<? foreach ($params['data'] as $data) 
	{	
		echo '<tr>';
		
			foreach ($params['fields'] as $label => $field) 
			{
				echo '<td>'. ($field == 'image' ? '<img width="50" class="img-polaroid" src="'. $params['image_url'] . $data->$field .'" />' : $data->$field) .'</td>';
			} 
			
			echo '
				<td>
					<a class="btn btn-success update" href="'. base_url_admin($params['url'] .'edition/'. $data->$params['id']) .'"><i class="icon-wrench"></i></a>
					<a class="btn btn-danger delete" data-id="'. $data->$params['id'] .'"><i class="icon-remove"></i></a>
				</td>';
			
		echo '</tr>';
	} ?>
	</tbody>
</table>

<script>
    /* Variables globales */
    var id = 0;

    $(function() {
        $('#table').dataTable();
        
        /* Clic sur le bouton suppression d'une ligne */
       $('.delete').click(function() {           
            if (confirm('Voulez-vous vraiment supprimer définitivement cette entrée ?')) {
              
                $.post('<?= base_url($params['url'] . 'delete') ?>/' + $(this).attr('data-id'),
                    function(data) {
                        if (data.success == 1) {
                            
                            window.location.reload();
                        } else {
                            
                            $.pnotify({text:data.message, icon:false, type:'danger'});
                        }
                    }, 'json');
          } 
       });
    });    
</script>