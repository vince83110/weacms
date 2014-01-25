	<div class="portlet">
		<div class="portlet-header">Dernières validations</div>
		<div class="portlet-content">

            <table id="pages-valid" class="table table-striped table-bordered table-hover flip-content<?=(count($pagesvalid) ? '' : ' hide')?>">
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
            
            <div class="well notice">
                <p><strong>Pas encore une seule page...</strong><br/>N'hésitez pas à créer vos pages pour lancer le site internet.</p>
                <div class="alert-actions">
                    <?=button('pages/nouvelle', 68, 'Créer une nouvelle page', 'action blue')?>
                    <div class="clear"></div>
                </div>
            </div>
            
            <? endif; ?>
        </div>
	</div>