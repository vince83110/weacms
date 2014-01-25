<div class="tabbable tabbable-custom">     
	<ul class="nav nav-tabs nav-link">
	   <? foreach ($tabs as $index => $label) 
	   {			
			echo '<li', ($tab == $index ? ' class="active"' : '') ,'><a href="', base_url_admin('administration/index/'. $index) ,'">', $label ,'</a>';
		} ?>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active wrapper">
			<?= $content ?>
		</div>
	</div>
</div> 