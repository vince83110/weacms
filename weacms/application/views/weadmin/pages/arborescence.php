<div class="buttons">    
    <?=button('pages', 87, 'Pages', 'left')?>
    <?=button('pages/corbeille', 186, 'Corbeille', 'middle')?>
    <?=button('pages/arborescence', 138, 'Arborescence', 'right on')?>
</div>
<div class="buttons">
	<? 
	foreach ($langs as $lang) 
	{
		echo button('pages/arborescence/'. $lang->id_dl, 138, $lang->letters, $lang_main == $lang->id_dl ? 'on' : '');
	} ?>
</div>

<p>Cette page contient la vue de l'arborescence des pages, il y a en tout <strong><?=$total?></strong> pages actives sur le site.</p>
    
<ul id="pages" class="filetree">
	<?= $treeview ?>
</ul>

<script>
	$(function() { 
		$('#pages').treeview();
	});    
</script>