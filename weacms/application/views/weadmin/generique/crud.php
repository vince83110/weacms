<h4><?= $params['title'] ?></h4>
<?= button('', $params['button'], 'plus', 'btn-info', 'button-dialog-form') ?>
<?= (isset($params['other_buttons']) ? $params['other_buttons'] : '') ?>
<hr />
       
<table id="table" class="table table-striped table-bordered table-hover flip-content dataTable">
	<thead>
		<tr>
			<? foreach ($params['fields'] as $label => $field) {
			   
			   echo '<th>', $label ,'</th>'; 
			} ?>
			<th width="100">Actions</th>
		</tr>
	</thead>
	<tbody>
	<? foreach ($params['data'] as $data) {
	
		echo '<tr>';
		
			foreach ($params['fields'] as $label => $field) {
				echo '<td>', $data->$field, '</td>';
			}
			
			echo '
				<td>'.
					button('', '', 'wrench', 'btn-info button-action-update', 'update-'. $data->$params['id']) .
					button('', '', 'remove', 'btn-danger button-action-delete', 'delete-'. $data->$params['id']) .'
				</td>';
			
		echo '</tr>';
	} ?>
	</tbody>
</table>
    
<div id="dialog-form" class="hide">
    <form id="form-crud" class="form-horizontal">
        <? foreach ($params['form'] as $form) {
                if (! isset($form[2])) {
                    $form[2] = 'input';
                }
                switch ($form[2]) {
                    case 'input' : default :                    
                        echo input($form[1], $form[0]);
                    break;
                    
                    case 'input-date' : default :                    
                        echo input($form[1], $form[0], NULL, NULL, '', 'text', 'date');
                    break;
                    
                    case 'textarea' :
                        echo textarea($form[1], $form[0]);
                    break;
                                        
                    case 'select' :
                        echo select($form[1], $form[0], NULL, $form[3]);                     
                    break;
                    
                    case 'file' :
                        echo input_file($form[1], $form[0]);                     
                    break;
                }
        } ?>
    </form>
    
    <div class="modal-footer">
        <?=button('', 'Valider les changements', 'ok', 'btn-info', 'button-action-valid')?>
        <?=button('', 'Annuler', 'remove', '', 'dialog-form-close')?>
    </div>
</div>

<script>
    /* Variables globales */
    var id = 0;

    $(function() {
        $('#table').dataTable();
        
        $('#dialog-form').dialog({
            title:'<?= $params['button'] ?>',
            width:560        
        });
        
        $('#button-dialog-form').click(function() {
            id = 0;
            $('#form-crud')[0].reset();
            $('#dialog-form').dialog('option', 'title', '<?= $params['button'] ?>');
            $('#dialog-form').dialog('open'); 
        });
        
        $('#dialog-form-close').click(function() { 
            $('#dialog-form').dialog('close') 
        });
        
        /* On récupère les données en AJAX pour les afficher pour les mettre à jour */
        $('.button-action-update').click(function() {
            $('#dialog-form').dialog('option', 'title', 'Mise à jour');
			load_show();
            id = $(this).attr('id').replace('update-', '');
            
            $.post('<?=base_url_admin($params['url'] . 'get')?>/'+ id, {},
                function (data) {
                    load_hide();
                    
					$('#dialog-form').dialog('open');   
                    $.each(data.items, function(key, val) {
                        $('#form-crud').find('#' + key).val( val );
                    });
                }, 'json')
               .error(function(xhr) {
                    load_hide();
                    
               		alert(xhr.responseText);
               });
        });
        
        /* Clic sur le bouton suppression d'une ligne */
		$('.button-action-delete').click(function() {           
            if (confirm('Voulez-vous vraiment supprimer définitivement cette entrée ?')) {             
				post_reload('<?= $params['url'] ?>delete/' + $(this).attr('id').replace('delete-', ''));
			} 
		});
        
        /* Clic sur le bouton de validation, si des champs sont manquants on bloque sinon la page est réchargée */
        $('#button-action-valid').click(function() {
			post_reload('<?= $params['url'] ?>update/' + (id ? id : 0), $('#form-crud').serialize());
        });
    });    
</script>