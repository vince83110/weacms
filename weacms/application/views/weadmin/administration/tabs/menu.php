<?=button('', 'Ajouter un menu', 'plus', 'btn-info', 'button-dialog-menu')?>
<hr />

<div class="tabbable tabbable-custom" style="border-bottom: 1px solid #ddd;">
	<ul class="nav nav-tabs nav-link">
	<? 
	foreach ($langs as $lang) 
	{
		echo '
			<li'. ($lang_main == $lang->id_dl ? ' class="active"' : '') .'>
				<a href="'. base_url_admin('administration/index/menu/list/'. $lang->id_dl) .'">'. $lang->name .'</a>
			</li>';
	} ?>
	</ul>
</div>

<div class="well notice">
	<strong>Pour gérer vos menus,</strong> vous devez d'abord créer des pages et indiquez si elles doivent être placées dans le menu.
	<br />
    Vous pouvez ici gérer l'ordre d'affichage du menu. Pour créer un sous-menu, vous devez créer des pages et indiquer une page parente.
    <i class="icon-info-sign"></i>
</div>
   
<ul id="menus" class="filetree">
	<?=$menu?>
</ul>

<div id="dialog-menu" class="hide">
	<div class="row-fluid show-grid">
        <div class="span5">
            <form id="form-menu">
                <?=input('label', 'Label dans le menu')?>
                <?=select('parent', 'Parent', NULL, $menu_parent)?>
                <?=input('class', 'Classe de l\'entrée')?>
                <?=select('position', 'Position', NULL, $positions)?>
				
				<input type="hidden" name="lang" value="<?=$lang_main?>" />
                <input type="hidden" name="id_page" value="1" id="id_page" />
            </form>    
        </div>
        <div class="span7">
            <h3 class="page-title lined">Conduit vers le lien</h3>
        	<?=select('id_page', 'Page du site', FALSE, $selectview, '', 'chosen')?>
            <?=input('href', 'Ou vers un lien classique', FALSE, FALSE, '', 'text', 'input-large')?>
				
			<label class="checkbox" style="width:360px;">
				<input type="checkbox" id="title"> Titre dans le menu, aucun lien
			</label>               
        </div>
	</div>   
    
    <?=button('', 'Valider', 'ok', 'btn-success', 'button-action-update')?>
    <?=button('', 'Annuler', 'remove', 'pull-right', 'button-dialog-menu-close')?>
    <div class="clear"></div>
</div>

<script>
var id_menu = 0;
var id_lang = <?=$lang_main?>;
	
	$(function() { 
		$('#menus').treeview();
		
		$('#dialog-menu').dialog({
			width:	820,
			title: 'Nouveau menu',
			open: function() { 		
				$('#href').keyup(function() {
					if ($(this).val().length) {
						$('#id_page').attr('disabled', 'disabled').trigger('chosen:updated');
					} else {
						$('#id_page').removeAttr('disabled').trigger('chosen:updated'); 
					}
				});
			}
		});
		
		$('#title').click(function() {
			if ($(this).is(':checked')) {
				$('#id_page, #href').attr('disabled', 'disabled').trigger('chosen:updated');
				
			} else {
				$('#id_page, #href').removeAttr('disabled').trigger('chosen:updated'); 
			}			
		});
		
		$('#menus a').click(function(e) {
			e.preventDefault();									  
			load_show();
			id_menu = $(this).attr('data-id');
			
			$.post(base_url +'administration/menu/get', {id:id_menu},
				function (data) {
					load_hide();
					
					$('#dialog-menu').dialog('open');	
					
					$.each(data.items, function(key, val) {
						$('#form-menu').find('#' + key).val( val );
					});
					$('#pages a[data-id="' + data.items.pid + '"]').parent().addClass('selected');
				}, 'json');
		});				
		
		$('#button-dialog-menu').click(function() {
			$('#form-menu')[0].reset();
			$('#dialog-menu').dialog('open');		
		});
		
		$('#button-dialog-menu-close').click(function() {
			$('#dialog-menu').dialog('close');			 
		});
		
		$('#button-action-update').click(function() {
			post_reload('administration/menu/update/'+ id_lang +'/' + id_menu, $('#form-menu').serialize());
		});		
	});    
</script>
<style>
	#pages {
		max-height: 200px;
		overflow: auto;		
	}
	#pages li a {
		padding: 5px 10px;
	}
	#pages li span i {
		display: none;
	}
</style>