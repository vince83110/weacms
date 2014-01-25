<form class="row-fluid" id="form<?=$fid?>">
	<div class="loader hide"></div>

	<?  
	$i = 0;
	$center = floor(count($fields) / 2);
	
	if ($form->column == 1) {
		echo '<div class="row">
			<div class="columns six">';
	}
	
	foreach ($fields as $o)
        {
		if ($form->column == 1) {
			if ($i++ == $center) {
				echo '</div><div class="columns six">';
			}
		}
        	echo $this->form->show_field($o->fdid, $o->name, count($o->options) ? $o->options : NULL, $o->required ? 'required' : FALSE, $o->id);
        }

	if ($form->column == 1) {
		echo '</div>
		</div>';
	}
	?>
	        
    <a class="btn btn-info btn-large right" id="submit<?=$fid?>"><i class="icon-ok"></i> Valider</a>
</form>

<script>
	$(document).ready(function() {
		var ajax_form = $( '#form<?=$fid?>' ).validVal();
		
		$( '#submit<?=$fid?>').click(function( event ) {
			event.preventDefault();
			
			form_data = ajax_form.submitform();
			
			if ( form_data ) {
				$('#submit<?=$fid?>').hide();
				$('#form<?=$fid?> .loader').show();
				
				$.post('<?=base_url()?>form',
					$('#form<?=$fid?>').serialize() + '&fid=' + <?=$fid?>,
					function( msg ) {
						$('#form<?=$fid?> .loader').hide();
						
						$('#form<?=$fid?>').html('<h3>Votre message a bien été envoyé.</h3><h5>Nous reviendrons vers vous dans les meilleurs délais.</h5>');
				});
			}
		});
	});
</script>