<? if (isset($page)) : ?>
<blockquote class="pull-right">
	<small>Modifiée par <?=$page->username?>, le <?=date_month_short($page->edited)?>, créée le <?= date_month_short($page->created) ?></small>
</blockquote>
<? endif; ?>

<?=button('', 'Enregistrer la page', 'download', 'btn-info update', NULL, 'Enregistrer la page')?>
<?=button('', 'Voir la page', 'eye-open', 'btn-success view', NULL, 'Enregistrer et visualiser')?>
<hr />

<div class="tabbable tabbable-custom" style="border-bottom: 1px solid #ddd;">
	<ul class="nav nav-tabs">
        <li class="active"><a href="#tab_content">Contenu</a></li>
        <li><a href="#tab_source">Source</a></li>
        <li><a href="#tab_tree">Ressources</a></li>
        <li><a href="#tab_seo">Référencement</a></li>
        <li><a href="#tab_template">Template</a></li>
        <li><a href="#tab_widgets">Widgets</a></li>
        <li class="pull-right"><a href="#tab_builder"><i class="icon-gear"></i> Page builder</a></li>
    </ul>
</div>

<div class="tab-content">
	<div id="tab_content" class="active tab-pane">		
		<?= button('', 'Insérer un lien vers une page', 'link', 'btn-warning btn-small', 'button-dialog-link') ?>
		<?= button('', 'Insérer un média', 'picture', 'btn-success btn-small', 'button-dialog-media') ?>
		<?= button('', 'Insérer un widget', 'link', 'btn-inverse btn-small',  'button-dialog-widget') ?>
		<?= button('', 'Insérer un formulaire', 'check', 'btn-primary btn-small', 'button-dialog-form') ?>
		<div class="space7"></div>
		
		<textarea id="content" name="content"><?= (isset($page) ? $page->content : '') ?></textarea>
	</div>
	
	<div id="tab_source" class="tab-pane"> 	
		<div id="editor"></div>
	</div>

	<div id="tab_tree" class="tab-pane">    
		<form class="form-horizontal post-form-data">
			<?= input('name', 'Nom de la page', 'N', $page, 'Le nom de la page est uniquement visible par vous', 'text', 'input-xxlarge') ?>
			
			<?= input('css', 'Fichier CSS associé', 'CSS', $page, 'Vous pouvez mettre plusieurs fichiers en les séparant par une virgule', 'text', 'input-xxlarge') ?>
			<?= input('js', 'Fichier JS associé', 'JS', $page, 'Vous pouvez mettre plusieurs fichiers en les séparant par une virgule', 'text', 'input-xxlarge') ?>    
			<?= input('class', 'Classe CSS de la page', 'CLASS', $page, 'Classe CSS apposée sur l\'élément &lt;body&gt;') ?>  
		</form>   
	</div>

	<div id="tab_seo" class="tab-pane">
		<form class="form-horizontal post-form-data">	
			<?= input('title', 'Titre de la page', 'T', $page, 'Le titre de la page est visible par les internautes', 'text', 'input-xxlarge') ?>
			<?= select('parent', 'Page parente', FALSE, $selectview, '', 'chosen') ?>
			<? // Disable page url for homepage and 404 page
			if (isset($page) && ($page->homepage == 1)) 
			{
				echo input('url', 'Adresse de la page', '@', $page, 'Cette valeur est écrasée pour la page d\'accueil', 'text', 'input-xxlarge');
			}
			else 
			{
				echo input('url', 'Adresse de la page', '@', $page, 'Adresse de la page, influe sur le référencement', 'text', 'input-xxlarge');
			} ?>
			<?= textarea('description', 'Meta-description', $page, 'Balise meta-description de la page', 160) ?>      
			<input type="hidden" name="lang" value="<?= $lang ?>" />
		</form>
	</div>

	<div id="tab_template" class="tab-pane">
		<? 
		$template_selected = isset($page) ? $page->template : 1;
		
		foreach ($templates as $key => $template) 
		{
			echo '
				<div id="template-'. $key .'" class="template'. ($key == $template_selected ? ' template-selected ' : '') .' portlet solid" data-hooks="' . $template[2] .'">
					<div class="portlet-title">
						<div class="caption">'. $template[0] .'</div>
						<span class="label label-info pull-right hide"><i class="icon-ok"></i> Choisi</span>
					</div>
					<div class="template-html row-fluid">
						'. $template[1] .'
					</div>
				</div>
			';
		} ?>
		<div class="clear"></div>
	</div>
	
	<div id="tab_widgets" class="tab-pane">
		<div class="row-fluid">
			<div class="span9">
				<h3>Widgets disponibles</h3>
				<div id="dwidgets" class="wcontent">
					<? 
					foreach ($widgets as $widget) 
					{
						$options = '';
						foreach ($widget->hooks as $hook)
						{
							$options .= '<option value="'. $hook->hook .'">'. $widgets_hooks[$hook->hook][1] .'</option>';
						}
						
						echo '
							<div id="widget-'. $widget->id .'" class="widget portlet solid purple">
								<div class="portlet-title">
									<div class="caption">'. $widget->title .'</div>
								</div>
								<div class="portlet-body">
									<p>'. $widget->description .'</p>
									<div class="widget-footer">
										<select id="widget-select-'. $widget->id .'" class="pull-left">
											'. $options .'
										</select>
										'. button('', 'Ajouter', 'plus', 'btn-info pull-right btn-widget-add', 'widget-id-'. $widget->id) .'
									</div>
								</div>
							</div>';
					} ?>
				</div>
			</div>
			<div class="span3">
				<h3>Zones</h3>
				<div id="widgets-hooks">
					<?
					foreach ($widgets_hooks as $k => $hook) 
					{
						echo '
							<div id="hook-'. $k .'" class="portlet box blue widget-hook">
								<div class="portlet-title">
									<div class="caption">'. $hook[1] .'</div>
								</div>
								<div class="portlet-body">
									<p>Aucun widget</p>
								</div>
							</div>';       
					} ?>   
				</div>
			</div>
		</div>
	</div>
	
	<div id="tab_builder" class="tab-pane">
		<iframe id="iframe-page-builder" src="<?= base_url_admin('pages/page_builder/'. ($page ? $page->id : 0)) ?>"></iframe>
	</div>
</div>

<!-- Dialog link -->
<div id="dialog-link" class="hide">
	<ul id="folders" class="filetree">
		<?= $treeview ?>
	</ul> 
</div>

<!-- Dialog image -->
<div id="dialog-media" class="hide">
	<div id="elfinder_div"></div>
	<hr />
	<?= button('', 'Insérer un lien vers le documents sélectionné', 'link', 'btn-warning', 'button-insert-media-link') ?>
	<?= button('', 'Insérer les images sélectionnées', 'ok', 'btn-info pull-right', 'button-insert-image') ?>
</div>

<script type="text/javascript">
var id 		= <?= ($page ? $page->id : 0) ?>;
var parent 	= <?= ($page ? $page->parent : 0) ?>;
var lang	= <?= $lang ?>

//	Get 2 global vars for ckeditor and ace editors
var ckcontent = null;
var editor = ace.edit("editor");
editor.setTheme("ace/theme/monokai");
editor.getSession().setMode("ace/mode/html");
var is_ckeditor = true;

/**
 *	Save the full page with AJAX call
 *  @param	int		callback of the page update
 */
function save(type)
{
	load_show();
	
	// Get template selected key
	var template = $('.template-selected').attr('id').replace('template-', '');
	
	// Get widgets list
	var widgets = '';
	
	$('.widget-hook').each(function(i, hook) {
		var id_hook = $(hook).attr('id').replace('hook-', '');
		$(hook).find('.widget-hooked').each(function(i, widget) {
			var id_widget = $(widget).attr('id').replace('widget-hooked-', '');
			
			// Build string to post
			widgets += id_widget + '|'+ id_hook +'-';
		});
	});
	
	// Get content from Ace of Ckeditor considering last tab opened
	if (is_ckeditor) {
		content = ckcontent.getData()
	}
	else {
		content = editor.getSession().getValue();		
	}
	
	 // Find disabled inputs, and remove the "disabled" attribute to handle serialize
	var disabled = $('.post-form-data').find(':input:disabled').removeAttr('disabled');
	
	$.post(base_url +'pages/update/'+ lang, 'id=' + id + '&content='+ encodeURIComponent(content) +'&template=' + template + '&' + $('.post-form-data').serialize() +'&widgets='+ widgets, 
		function(data)
		{
			load_hide();
			
			 // re-disabled the set of inputs that you previously enabled
			disabled.attr('disabled','disabled');
			
			if (data.success == false) {
				$.pnotify(data.message);
				return;
			} 
			else {	
				switch(type) {
					case 0: 	
						$.pnotify('La page a bien été enregistrée');
					break;
					case 1:
						$('.topbar, .container').hide();
						$('#visu').show().find('iframe').attr('src', base_url +'pages/voir/'+ id); 
					break;
					case 2:
						$('body').attr('onBeforeUnload', '');
						window.location.href= base_url +'pages/index/'+ lang; 
					break;
				}
			}
		}, 'json')
		.error(function(xhr, ajaxOptions, thrownError) 
		{
			loadHide();
			alert(xhr.status);		
		});	
}
		
/**
 *	Close visualisation mode
 */
function closeVisu()
{
	$('#visu').hide().find('iframe').html(''); 		
	$('.topbar, .container').show();
}

/**
 *	Perform actions when DOM is loaded
 */
$(function() {
	/* JS trick to get visualisation view */
	//$('body').append('<div id="visu" class="hide"><a class="close" onclick="closeVisu();">Fermer</a><iframe height="100%" width="100%" frameborder="0"></iframe></div>').attr('onBeforeUnload', 'return(\'Les données non-sauvegardées seront perdues\')');

	$('textarea#content').ckeditor();
	ckcontent 						= CKEDITOR.instances.content;
	CKEDITOR.config.height 			='500px';
	CKEDITOR.config.removePlugins 	= 'forms,sourcearea';
	
	// Beautify HTML code for Ace editor
	editor.getSession().setValue(vkbeautify.xml(ckcontent.getData()));
	
	// When clicking on content tab, get ace editor content
	$('a[href="#tab_content"]').click(function() {
		ckcontent.setData(editor.getSession().getValue());
		is_ckeditor = true;
	});	
	
	// When clicking on source tab, get ckeditor content
	$('a[href="#tab_source"]').click(function() {
		editor.getSession().setValue(vkbeautify.xml(ckcontent.getData()));
		is_ckeditor = false;
	});
	
	$('.update').click(function() {
		save(0);
	});
	
	$('.view').click(function() {
		save(1);
	});
	
	$('.save').click(function() {
		save(2);
	});
	
	// Build a tree view for pages 
	$('.filetree').treeview();	
	
	$('#folders a').click(function(e) {
		e.preventDefault();
		
		parent = $(this).attr('data-id');
		$('#parent-name').html($(this).attr('data-name'));
		
		// Rewrite url
		$('#url').val($(this).attr('data-url') +'/'+ format_url($('#title').val()));
	});
	
	// Format title in URL format on the fly
	$('#title, #url').keyup(function() {
		$('#url').val(format_url($(this).val()));
	});
	
	$('.template').click(function() {
		$('.template-selected').removeClass('template-selected');
		$(this).addClass('template-selected');							  
	});

	/**
	 *	Dialog box for image files
	 */
	$('#dialog-media').dialog({
		width: 820,
		height: 560,
		title:'Choisir un média dans la bibliothèque',
		open: function() {
			var elf = $('#elfinder_div').elfinder({
				url : base_url + 'medias/elfinder',
				lang: 'fr',         
				height: 420, 
				dragUploadAllow: true,
				resizable: false,
				uiOptions : {
					toolbar : [
						['back', 'forward'],
						['open'],
						['mkdir', 'mkfile', 'upload'],
						['info'],
						['search']
					]
				}                             
			}).elfinder('instance');                
		}
	});	
	$('#button-dialog-media').click(function() { $('#dialog-media').dialog('open'); });
	
	// Click event on join images button
	$('#button-insert-image').click(function() {
		var id = '';
		
		$('.ui-selected:not(.directory)').each(function(i, elmt) {
			id += $(elmt).attr('id') + '|';
		});            
		
		$.post(base_url + 'medias/get_attachs', {attachs: id}, function(data) {
			if (data.success == 1) {
				$.each(data.items, function(i, item) {					
					// Check if the file is an image or not
					ckcontent.insertHtml('<img src="'+ item.url +'" alt="" />');
				})
				
				$('#dialog-media').dialog('close');
			} 
			else {
				$.pnotify({text: data.message});
			}
		}, 'json');
	});	
	
	/**
	 *	Dialog box for link pages
	 */	
	$('#dialog-link').dialog({
		width: 600,
		title: 'Insérer un lien vers une page du site',
	});	
	$('#button-dialog-link').click(function() { $('#dialog-link').dialog('open'); });
	
	$('#dialog-link .filetree a').click(function() {
		if (CKEDITOR.env.ie) {
			selection = ckcontent.getSelection().document.$.selection.createRange().text;
		} 
		else {
			selection  = ckcontent.getSelection().getSelectedText();
		}
	   
		ckcontent.insertHtml('<a href="'+ site_url + $(this).attr('data-url') +'">'+ (selection ? selection : $(this).find('span').text()) +'</a>');
		
		$('#dialog-link').dialog('close');
	});
	
	/**
	 *	Manage widgets drag and drop tab
	 */
	$( '.btn-widget-add').click(function() {
		var id_widget = $(this).attr('id').replace('widget-id-', '');
		var id_hook = $('#widget-select-'+ id_widget).val();
		$element = $('#widget-'+ id_widget);
		
		$('html, body').css('overflowX', 'hidden');
		
		var $clone = $element.clone();
		var element_offset = $element.offset();

		$clone.css({'position': 'absolute', 'top': element_offset.top, 'left': element_offset.left});

		$hook = $('#hook-'+ id_hook);
		var hook_offset = $hook.offset();

		// Animate widget to hook
		$clone.appendTo('body');
		$clone.css({ 'position': 'absolute', 'top': $clone.css('top'), 'left': $clone.css('left') })
			.animate({ 'width': $element.attr('width')*0.5, 'height': $element.attr('height')*0.5, 'opacity': 0.2, 'top': hook_offset.top + 30, 'left': hook_offset.left + 15 }, 700)
			.fadeOut(100, function() {
				insert_widget($element, $hook, id_widget);
		});	
		
		$('html, body').css('overflowX', 'auto');
	});
	
	<? 
	// Show page widgets
	if (count($pages_widgets)) 
	{
		foreach ($pages_widgets as $page_widget) 
		{	
			echo "insert_widget($('#widget-". $page_widget->id_widget ."'), $('#hook-". $page_widget->hook ."'), ". $page_widget->id_widget .");";
				
		}		
	} ?>
	
	function insert_widget($widget, $hook, id_widget) 
	{
		$item = $widget.clone();
		
		// Hide default message 
		$hook.find('p').hide();
		
		// Remove inlined styles
		$item.removeAttr('style').removeClass('widget');
		$item.find('.portlet-body').remove();
		$item.removeAttr('id').attr('id', 'widget-hooked-'+ id_widget).addClass('widget-hooked relative');
		
		// Append to hook box
		$hook.find('.portlet-body').append($item);
		$item.append('<a class="btn btn-danger" style="position: absolute; right: -2px; top: -2px;"><i class="icon-remove icon-2x"></i></a>');
		
		$item.find('.btn').click(function() {
			delete_widget($(this).parent(), $hook);
		});
	}

	function delete_widget($item, $hook) 
	{
		$item.fadeOut(function() {
			$item.remove();
			
			if (! $hook.find('.widget-hooked').length) {
				$hook.find('p').show();	
			}
		});
	}
});
</script>
<style type="text/css" media="screen">
    #editor { 
		position: relative;
        width: 100%;
		height: 500px;
    }
</style>