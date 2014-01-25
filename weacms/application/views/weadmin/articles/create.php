<? if (isset($article)) : ?>
<blockquote>
	<small>Modifiée par <?=$article->username?>, le <?=date_month_short($article->edited)?>, créée le <?=date_month_short($article->created)?></small>
</blockquote>
<? endif; ?>
<div id="test"></div>
<div class="buttons">
	<div id="nav">
        <a class="button left on" rel="contenu"><span class="icon icon120"></span><span class="blabel">Contenu</span></a>
        <a class="button middle" rel="menu"><span class="icon icon140"></span><span class="blabel">Rangement</span></a>
        <a class="button middle" rel="amorce"><span class="icon icon131"></span><span class="blabel">Présentation</span></a>
        <a class="button right" rel="referencement"><span class="icon icon179"></span><span class="blabel">Référencement</span></a> 
    </div>
    
    <?=button('', 67, '', 'left update', NULL, 'Enregistrer l\'article')?>
    <?=button('', 84, '', 'middle view', NULL, 'Enregistrer et visualiser')?>
</div>

<div id="contenu" class="opened">
	<form id="pcontent">	
        <?=input('title', 'Titre de l\'article', 'N', $article)?>
        
        <div class="clearfix">
            <label for="content">Contenu de la article</label>
            <div class="input">
                <textarea id="content" name="content" style="width:700px;"><?=(isset($article) ? $article->content : '')?></textarea>
            </div>
        </div>
        
        <?=input('css', 'Fichier CSS associé', 'CSS', $article)?>
        <?=input('js', 'Fichier JS associé', 'JS', $article)?>    
        <?=input('class', 'Classe CSS de l\'article', '', $article)?>        
    </form>
</div>

<div id="menu" class="hide">
    
    <div id="menu-folder">
        <div class="alert-message block-message warning">
            <p>L'article sera rangée dans la catégorie : <span id="parent-name"></span></p>
        </div>	    
    	<ul id="categories" class="filetree">
			<?=$categories?>
		</ul>
    </div>
    
</div>

<form id="amorce" class="hide">	
        <div class="clearfix">
            <label for="presentation">Texte de présentation</label>
            <div class="input">
                <textarea id="presentation" name="presentation" style="width:500px;"><?=(isset($article) ? $article->presentation : '')?></textarea>
            </div>
        </div>
        <div class="clearfix">
            <label for="content">Image de présentation</label>
            <div class="input">
                <?=button(NULL, 0, 'Charger une image de présentation', NULL, 'fileupload')?>
        		<div id="image-div" class="clear"><?=(isset($article) ? '<img src="'. base_url('theme/assets/images/'. $article->image) .'" />' : '')?></div>
            </div>
        </div>   
</form>

<form id="referencement" class="hide">	
	<?=input('url', 'Adresse de la article', '@', $article, 'Adresse de la article, influe sur le référencement')?>
    <?=textarea('description', 'Meta-description', $article, 'Balise meta-description de la article', 160)?>
</form>

<div class="clear"></div>
<hr />
<a class="button action blue save"><span class="icon icon67"></span><span class="blabel">Enregistrer et quitter</span></a>
<a class="button action" id="next"><span class="icon icon64"></span><span class="blabel">Etape suivante</span></a>

<script type="text/javascript">
var id = <?=($article ? $article->id_ar : 0)?>;
var parent = <?=($article ? $article->id_ac : 0)?>;

/*
*	This part create the rich text editor
*/
tinyMCE.init(
{
	document_base_url : '<?=base_url_admin();?>',
	mode : 				'exact',
	elements : 			'content',
	language : 			'fr',
	convert_urls : 		false,
	relative_urls : 	true,
	theme : 			'advanced',
	content_css :		'<?=cdn_url()?>stylesheets/foundation.css, <?=cdn_url()?>stylesheets/app.css',
	plugins : "autolink,lists,style,layer,table,save,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,tinycimm",

	// Theme options
	theme_advanced_buttons1 : "image,tinycimm-image,media,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,fullscreen,insertlayer,moveforward,movebackward,absolute,|,styleprops,|,attribs,|,template",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	paste_use_dialog : false,
	paste_convert_headers_to_strong : true,
	cleanup : 					true,
	apply_source_formatting : 	true,
	force_hex_style_colors : 	true,
	button_tile_map : 			true,
	file_browser_callback : 'tinycimm',
	tinycimm_controller : '<?=$this->config->item('tinycimm_controller');?>',
	tinycimm_assets_path : '<?=local_url($this->config->item('tinycimm_asset_path'))?>',
	tinycimm_resize_default_intial_width : '<?=$this->config->item('default_initial_width', 'tinycimm_image_resize_config');?>',
	tinycimm_thumb_width : '<?=$this->config->item('tinycimm_image_thumbnail_default_width');?>',
	tinycimm_thumb_height : '<?=$this->config->item('tinycimm_image_thumbnail_default_height');?>',
	tinycimm_thumb_lightbox_class : '<?=$this->config->item('tinycimm_image_thumbnail_default_lightbox_class');?>',
	tinycimm_thumb_lightbox_gallery : '<?=$this->config->item('tinycimm_image_thumbnail_default_lightbox_gallery');?>'
});

tinyMCE.init(
{
	document_base_url : '<?=base_url_admin();?>',
	mode : 				'exact',
	elements : 			'presentation',
	language : 			'fr',
	convert_urls : 		true,
	relative_urls : 	false,
	theme : 			'advanced',
	content_css :		'<?=cdn_url()?>stylesheets/foundation.css, <?=cdn_url()?>stylesheets/app.css',
	plugins : "autolink,lists,style,layer,save,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,tinycimm",

	// Theme options
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,help,code,|,forecolor,backcolor",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
});

function tinycimm(field_name, url, type, win) {
	var url = win.tinyMCE.baseURI.relative+'/plugins/tinycimm/'+type+'.html';

	tinyMCE.activeEditor.windowManager.open({
		file : url,
		width : 600,
		height : 480,
		resizable : "yes",
		inline : "yes",  
		close_previous : "no"
	}, {
		window : win,
		tinyMCEPopup : win.tinyMCEPopup,
		input : field_name
	});
	return false;
}

var step = 0;

/*
*	Manage the main tab menu
*   param - @obj : jquery Object clicked
*/
function openTab(obj) {
	var id = obj.attr('rel');
	$('#nav a').removeClass('on');
	obj.addClass('on');
	step = obj.index();
	$('.opened').hide();
	$('#' + id).show().addClass('opened');
	
	if (step === 3)
	{
		$('#next').hide();
	} else {
		$('#next').show();	
	}	
}

/*
*	Save the full article with AJAX call
*   param - @type : callback of the article update
*/
function saveDraft(type)
{
	var ed = tinyMCE.get('content');
	tinyMCE.triggerSave();
	ed.setProgressState(1); 	
	
	$.post('<?=base_url_admin()?>articles/update', 'id_ar=' + id + '&' + $('#pcontent').serialize() + '&presentation=' + encodeURIComponent($('#presentation').val()) + '&' + $('#referencement').serialize() + '&id_ac=' + parent,
		function(data)
		{
			ed.setProgressState(0);
			if (data.length > 5) {
				
				jQnotice(data);
				return;
				
			} else {				
				
				id = parseInt(data);
				switch(type) {
					case 0: 	
						jQnotice('L\'article a bien été enregistrée');
					break;
					case 1:
						$('.topbar, .container').hide();
						$('#visu').show().find('iframe').attr('src', '<?=base_url_admin()?>articles/voir/'+id); 
					break;
					case 2:
						window.location.href= '<?=base_url_admin()?>articles'; 
					break;
				}
			}
		});	
}

/*
*	Close the visualisation mode
*/
function closeVisu()
{
	$('#visu').hide().find('iframe').html(''); 		
	$('.topbar, .container').show();
}

/*
*	Perform some actions when DOM is loaded
*/
$(function() {
	/* JS trick to get visualisation view */
	$('body').append('<div id="visu" class="hide"><a class="close" onclick="closeVisu();">Fermer</a><iframe height="100%" width="100%" frameborder="0"></iframe></div>');
	//.attr('onBeforeUnload', 'return(\'Les données non-sauvegardées seront perdues\')');

	$('.update').click(function() {
		saveDraft(0);
	});
	
	$('.view').click(function() {
		saveDraft(1);
	});
	
	$('.save').click(function() {
		saveDraft(2);
	});
	
	$('#categories').treeview();	
	
	$('#nav a').click(function() {
		openTab($(this));
	});
	
	$('#next').click(function() {
		openTab($('#nav a:eq(' + (step+1) + ')'));
	});
	
	$('#categories a').click(function(e) {
		e.preventDefault();
		
		parent = $(this).attr('data-id');
		$('#parent-name').html( $(this).html() );
		$('#url').val( $(this).attr('data-url') + '/' + $('#title').val().replace(/ /g, '-').toLowerCase().replace(/[ÈÉÊËèéêë]/g,'e').replace(/[âà]/g,'a').replace(/[ûùü]/g,'u').replace(/d'/g, '').replace(/l'/g, '') );
	});
	
	$('#title, #url').keyup(function() {
		var url = $(this).val();
		$('#url').val(url.replace(/ /g, '-').toLowerCase().replace(/[ÈÉÊËèéêë]/g,'e').replace(/[âà]/g,'a').replace(/[ûùü]/g,'u').replace(/d'/g, '').replace(/l'/g, ''));
	});
	
	$('#fileupload').uploadify({
		swf  			: '<?=base_url()?>theme/uploadify.swf',
		buttonText      : 'Charger une image',
		queueID         : 'queue',
		'checkExisting' : '<?=base_url_admin()?>medias/hcheck',
		uploader    	: '<?=base_url()?>uploadify.php', 
		postData        : { type:1 },
		'cancelImage' 	: '<?=base_url()?>theme/css/icon/close.png',
		'auto'      	: true,
		'multi'			: false,
		'fileExt'		: 'jpg|png|gif|jpeg',
		onUploadSuccess : function(file,data,response) {
			if (id == 0) {
				jQnotice('Merci d\'enregistrer la page avant de charger une image');
				return;
			}
			
			if (data.trim() == 'error') {
				jQnotice('Erreur d\'envoi du fichier');
				return;	
			}
			
			d = data.split('|');
			$.post('<?=base_url_admin()?>articles/save_image', {id:id, name:d[0], filename:d[1], description:d[2], extension:d[3], filesize:d[4]}, 
				function(data) 
				{	
					$img = $('<img src="<?=base_url( $this->config->item('tinycimm_asset_path') . 'images' )?>/'+ d[1] +'" />');
					$('#image-div').html($img);	
				}).error(function() {
					alert('Une erreur est survenue !');
				});				
		}
	});	
});
</script>
<style>
.swfupload {
	left: 0;
	top: 5px;
	width: 130px;
}
</style>