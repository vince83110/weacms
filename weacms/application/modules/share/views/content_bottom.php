<div id="sharebox" class="hide-on-phones">
	<div id="shareme" data-url="<?=base_url()?>" data-title="partager cette page"></div>
</div>

<script>
$(function() {
	$('#sharrre').sharrre({
		share: {
		  googlePlus: true,
		  facebook: true,
		  twitter: true
		},
		buttons: {
			googlePlus: {size: 'tall', annotation:'bubble'},
			facebook: {layout: 'box_count'},
			twitter: {count: 'vertical'},
		},		
		hover: function(api, options){
			$(api.element).find('.buttons').show();
		},
		hide: function(api, options){
			$(api.element).find('.buttons').hide();
		},	
		url: '<?= base_url() ?>',
		urlCurl: '<?= base_url('share/share_curl') ?>'
	});
});
</script>