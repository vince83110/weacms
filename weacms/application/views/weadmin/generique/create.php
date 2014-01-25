<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption"><i class="icon-edit"></i> <?= $params['title'] ?></div>
	</div>
	<div class="portlet-body form">
		<form id="fcreate" class="form-horizontal topMargin" method="post" action="<?= base_url($params['url'] . 'update') . ($data ? '/'. $data->$params['id'] : '') ?>" enctype="multipart/form-data">
			<? $is_fulltext = FALSE;
			
			foreach ($params['form'] as $form) {
				
				switch ($form[2]) {
					case 'input' : default :                    
						echo input($form[1], $form[0], NULL, $data, isset($form[3]) ? $form[3] : '');
					break;
					
					case 'input-date' : default :                    
						echo input($form[1], $form[0], NULL, $data, '', 'text', 'date');
					break;
					
					case 'textarea' :
						echo textarea($form[1], $form[0], $data);
					break;
										
					case 'select' :
						echo select($form[1], $form[0], $data, $form[3]);                     
					break;
					
					case 'file' :
						echo input_file($form[1], $form[0]);                     
					break;
					
					case 'fulltext' :
						echo textarea($form[1], $form[0], $data);       
						$is_fulltext = TRUE;
						
						echo '
						<script>
						tinyMCE.init(
						{
							document_base_url : "'. base_url() .'",
							mode :              "exact",
							elements :          "'. $form[1] .'",
							language :          "fr",
							width :             "100%",
							height :            600,
							convert_urls :      true,
							relative_urls :     false,
							valid_children :    "+body[style]",
							theme :             "advanced",
							content_css :       "'. base_url('theme/css/layout.css') . '",
							plugins : "jbimages,autolink,lists,style,layer,table,save,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,advlist,tinycimm",
						
							theme_advanced_buttons1 : "image,jbimages,media,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
							theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,forecolor,backcolor",
							theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,fullscreen,insertlayer,moveforward,movebackward,absolute,|,styleprops,|,attribs,|,template",
							theme_advanced_toolbar_location : "top",
							theme_advanced_toolbar_align : "left",
							theme_advanced_statusbar_location : "bottom",
							theme_advanced_resizing : true,
							paste_use_dialog : false,
							paste_convert_headers_to_strong : true,
							cleanup :  true,
							setup : function(ed) {
							  ed.addButton("template", {
								 title : "Insérer 2 colonnes",
								 onclick : function() {
									ed.setContent( ed.getContent() + \'<div class="row-fluid"><div class="span6"><h3>Colonne 1</h3></div><div class="span6"><h3>Colonne 2</h3></div></div>\' );
								 }
							  });
						   }
						});  
						</script>';              
					break;
				}
			} ?>
		</form>	
		<div class="form-actions">
			<?= button('', 'Valider les changements', 'ok', 'btn-success', 'button-valid') ?>
			<?= button($params['url'], 'Revenir', 'remove', 'btn-danger') ?>
		</div>
	</div>
</div>

<script>
    $(function() {        
        /* Clic sur le bouton de validation, si des champs sont manquants on bloque sinon la page est réchargée */
        $('#button-valid').click(function() {
            <? if ($is_fulltext) : ?>
            var ed = tinyMCE.get('content');
            tinyMCE.triggerSave();            
            <? endif; ?>
            
            $.post('<?= base_url($params['url'] . 'check') ?>', $('#fcreate').serialize(),
                function(data) {
                    if (data.success == 1) {                        
                        $('#fcreate').submit();
                    } else {                        
                        $.pnotify({text:data.message, icon:false, type:'danger'});
                    }
                }, 'json');
        });
    });    
</script>