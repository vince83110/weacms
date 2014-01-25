<div class="buttons">    
    <?=button(NULL, 68, 'Créer ue nouvelle catégorie', 'action blue', 'create')?>
    
    <?=button('articles', 87, 'Articles', 'left')?>
    <?=button('articles/corbeille', 186, 'Corbeille', 'middle')?>
    <?=button('articles/categories', 138, 'Catégories', 'right on')?>
</div>

<p>Cette page contient la vue de l'arborescence des pages, il y a en tout <strong><?=$total?></strong> pages actives sur le site.</p>
    
<ul id="pages" class="filetree">
	<?=$categories?>
</ul>

<div id="dcreate" class="hide">
	<div class="loader hide"></div>
	<form id="fcreate">
    	<?=input('name', 'Nom de la catégorie')?>
        <?=input('url', 'URL de la catégorie')?>
        <?=select('parent', 'Parent', NULL, NULL, $categories_select)?>
    </form>
    
    <?=button('', 44, 'Valider', 'action green', 'dvalid')?>
    <?=button('', 44, 'Valider', 'action green hide', 'dupdate')?>
    <?=button('', 56, 'Annuler', 'fright', 'dcancel')?>
    <div class="clear"></div>
</div>

<script>
	$(function() { 
		var id = 0;
		$('.filetree').treeview();
		
		$('#dcreate').dialog({
			width:	460,
			modal: true,
			autoOpen: false,
			resizable: false,
			draggable: false,
			title: 'Nouvelle catégorie'
		});
		
		$('#create').click(function() {
			$('#dupdate').hide();
			$('#dvalid').show();
			$('#dcreate').dialog('open');		
		});
		
		$('#dcancel').click(function() {
			$('#dcreate').dialog('close');			 
		});
		
		$('.edit').click(function() {
			$('#dvalid').hide();
			$('#dupdate').show();
			$('#dcreate').dialog('open');									  
			$('.loader').show();
			id = $(this).attr('rel');
			
		});		
		
		$('#dvalid').click(function() {
			$('.loader').show();
			$.post('<?=base_url_admin()?>articles/creer_category/1', $('#fcreate').serialize(),
				function (d) {
					if (d.trim() === 'done') {									
						$('#dcreate').dialog('close');		
						location.reload(true);
					} else {
						jQnotice(d.trim());	
					}
					$('.loader').hide();
				});
		});
		
		$('#dupdate').click(function() {
			$('.loader').show();
			$.post('<?=base_url_admin()?>articles/creer_category/2/' + id, $('#fcreate').serialize(),
				function (d) {
					if (d.trim() === 'done') {									
						$('#dcreate').dialog('close');		
						location.reload(true);
					} else {
						jQnotice(d.trim());	
					}
					$('.loader').hide();
				});
		});
	});    
</script>