<div class="span6">
	<?= $portlet_pages ?>
	
	<?= $portlet_logs ?>
</div>

<div class="span6">
	<?= (strlen($hook_home) ? $hook_home : '<div class="well notice">Aucun hook implanté sur la partie Home.</div>') ?>
</div>

<script>
var i = 0;

	function valid_page($obj) {
		$tr = $obj.parent().parent().clone();
		$tr.find('.validate').remove();
		var id = $obj.attr('data-id');
		
		$.ajax({
			mode: 'queue',
			type: 'POST',
			port: 'ajaxWhois',
			url: '<?=base_url_admin()?>' + $obj.attr('data-type') +'/valider',
			data: { 'id': id },
			success: function(data){
				if (data.succes) {
					$('#' + id).remove();					
				} else {
					$.pnotify(data.message);
				}
				
			}
		});		
	}

	$(function() {		
		// Valid all pages
		$('#button-action-valid-all').click(function() {
			if confirm('Voulez-vous vraiment valider toutes les pages en attente et les afficher sur le site ?') {
				load_show();
				
				$('.button-valid').each(function(i, element) {
					valid_page($(element));
				});
				
				$.ajax({ mode: 'dequeue', port: 'ajaxWhois' });
				load_hide();
			}
		});
		
		// Valid one page
		$('.validate').click(function() {
			if confirm('Voulez-vous vraiment valider cette page et l\'afficher sur le site ?') {
				load_show();
				
				valid_page($(this));
				
				$.ajax({ mode: 'dequeue', port: 'ajaxWhois' });
				
				load_hide();
			}
		});
	});
</script>