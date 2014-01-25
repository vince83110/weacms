<?= button('widgets', 'Configuration des widgets', 'cog') ?>
<?= button('', 'Enregistrer les changements', 'ok', 'btn-success', 'button-action-save') ?>
<hr />

<div class="row-fluid">
	<div class="span9">
		<h3>Widgets disponibles</h3>
		<div id="dwidgets" class="wcontent">
			<? 
			if (! count($widgets))
			{
				echo '<div class="well notice">Aucun widget disponible pour votre template.</div>';
			}
			else 
			{
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
				} 
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

<script>
	$(function() {
		/**
		 *	Save widgets list
		 */
		$('#button-action-save').click(function() {
			load_show();
			
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
			
			$.post(base_url +'widgets/update_widgets_template/', {widgets: widgets}, 
				function(data)
				{
					load_hide();
					
					if (data.success == true) {
						$.pnotify('Les widgets ont bien été sauvegardés');
					} 
					else {	
						$.pnotify(data.message);
					}
				}, 'json')
				.error(function(xhr, ajaxOptions, thrownError) 
				{
					load_hide();
					alert(xhr.status);		
				});	
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
		// Show template widgets
		if (count($template_widgets)) 
		{
			foreach ($template_widgets as $page_widget) 
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