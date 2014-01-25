<div class="span8">
	<?= $portlet_pages ?>
	
	<div class="portlet">
		<div class="portlet-header">Dernières validations</div>
		<div class="portlet-content">

            <table id="pages-valid" class="zebra-striped<?=(count($pagesvalid) ? '' : ' hide')?>">
                <thead>
                    <tr>
                        <th class="header blue">Titre de la page</th>
                        <th class="header red">Validée le</th>
                        <th class="header" width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <? 
					if (count($pagesvalid)) :
						foreach ($pagesvalid as $o) 
						{
							echo '
							<tr>
								<td>'. $o->title .'</td>	
								<td>'. format_date_diff($o->edited) .'</td>
								<td>
									<a class="button left" href="'. base_url_admin() .'pages/edition/'. $o->id .'" title="Modifier la page"><span class="icon icon145"></span></a>
									<a class="button right" href="'. base_url_admin() .'pages/voir/'. $o->id .'" title="Visualiser la page" target="_blank"><span class="icon icon84"></span></a>		
								</td>
							</tr>';
						} 						
					endif; ?>
                </tbody>
            </table>
            
            <? if (!count($pagesvalid)) : ?>
            
            <div class="alert">
                <p><strong>Pas encore une seule page...</strong><br/>N'hésitez pas à créer vos pages pour lancer le site internet.</p>
                <div class="alert-actions">
                    <?=button('pages/nouvelle', 68, 'Créer une nouvelle page', 'action blue')?>
                    <div class="clear"></div>
                </div>
            </div>
            
            <? endif; ?>
        </div>
	</div>


	<div class="portlet">
		<div class="portlet-header">Dernières modifications</div>
		<div class="portlet-content">
       		<? if (count($logs)) : ?>
            
                <table id="pages" class="zebra-striped">
                    <thead>
                        <tr>
                            <th class="header blue">Action</th>
                            <th class="header green">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <? 
                        foreach ($logs as $o) 
                        {
                            echo '
                            <tr>
                                <td>'. $o->username . $o->action .'</td>	
                                <td>'. format_date_diff($o->created) .'</td>
                            </tr>';
                        } ?>
                    </tbody>
                </table>          
                  
            <? else : ?>
            
            <div class="alert">
                <p><strong>Pas encore une modification...</strong><br/>N'hésitez pas à créer vos pages pour lancer le site internet.</p>
            </div>      
            
            <? endif; ?>  
        </div>
	</div>

</div>

<div class="span8">
	<div class="portlet">
		<div class="portlet-header">Statistiques des visites</div>
		<div class="portlet-content">
        	<a class="button left stats on" rel="0"><span class="icon icon179"></span><span class="blabel">Jour</span></a>
        	<a class="button middle stats" rel="7"><span class="icon icon179"></span><span class="blabel">Semaine</span></a>
        	<a class="button right stats" rel="30"><span class="icon icon179"></span><span class="blabel">Mois</span></a>
      		<div class="clear"></div>
			<div id="ga" class="loader"></div>
		</div>
	</div>
    
</div>
<div class="clear"></div>
<script>
var i = 0;

	function valide($obj)
	{
		$('#pages-wait').show();
		$tr = $obj.parent().parent().clone();
		$tr.find('.validate').remove();
		var id = $obj.attr('data-id');
		
		$.ajax({
			mode: 'queue',
			type: 'POST',
			port: 'ajaxWhois',
			url: '<?=base_url_admin()?>' + $obj.attr('data-type') +'/valider',
			data: { 'id': id },
			success: function(data){
				if (data != 'done') {
					jQnotice(data);	
					return;
				}
				
				$('#' + id).remove();
				$('#pages-valid').show().prepend($tr);
			}
		});		
	}

	$(function() {
		$( ".span8" ).sortable({
			connectWith: ".span8"
		});
		$( ".span8" ).disableSelection();
		
		$('#pages').tablesorter();
		
		$.post('<?=base_url_admin()?>accueil/statistiques', function (data)
		{
			$('#ga').removeClass('loader').html(data);	
			$('.sorter').tablesorter();
		});
		
		$('.stats').click(function() {
			$('.stats').removeClass('on');
			$(this).addClass('on');
			$('#ga').html('');
			$('#ga').addClass('loader');				
			
			$.post('<?=base_url_admin()?>accueil/statistiques/' + $(this).attr('rel'), function (data)
			{
				$('#ga').removeClass('loader').html(data);	
				$('.sorter').tablesorter();
			});
		});
		
		// Valide toutes les pages en cours 
		$('#validate').click(function() 
		{
			jConfirm('Voulez-vous vraiment valider toutes les pages en attente et les afficher sur le site ?', 'Confirmation', function(r) {
				if (r) {
					$('.validate').each(function(i, e) {
						valide($(e));
					});
					
					$.ajax({ mode: 'dequeue', port: 'ajaxWhois' });
					$('#pages-wait').hide();
				}
			});	
		});
		
		// Valide une page par une page
		$('.validate').click(function() 
		{
			$obj = $(this);
			jConfirm('Voulez-vous vraiment valider cette page et l\'afficher sur le site ?', 'Confirmation', function(r) {
				if (r) {
					valide($obj);
					$.ajax({ mode: 'dequeue', port: 'ajaxWhois' });
					$('#pages-wait').hide();
				}
			});	
		});
	});
</script>