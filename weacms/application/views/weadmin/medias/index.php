<div id="elfinder"></div>

<script type="text/javascript" charset="utf-8">
	$().ready(function() {
		var elf = $('#elfinder').elfinder({
			url : '<?= base_url_admin('medias/elfinder') ?>',
			lang: 'fr',        
			height: 600,
			width: '100%'     
		}).elfinder('instance');
	});
</script>