<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">Dernières modifications</div>
	</div>
	<div class="portlet-body">
		<? if (count($logs)) : ?>
		
			<table class="table table-striped table-bordered table-hover flip-content">
				<thead>
					<tr>
						<th>Action</th>
						<th>Date</th>
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
			<p><strong>Pas encore une modification...</strong><br/>N'hésitez pas créer vos pages pour lancer le site internet.</p>
		</div>      
		
		<? endif; ?>  
	</div>
</div>