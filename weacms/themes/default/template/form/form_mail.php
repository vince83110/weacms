<div style="font-family:Arial, Helvetica, sans-serif;">
    <h4>Message reçu depuis le formulaire : <strong><?=$title?></strong></h4>
    <hr />
    <p>Une personne a rempli le formulaire <u><?=$title?></u> le <i><?=date('d/m/Y à H:i', strtotime($data[0]->date))?></i>.</p>
    
    <table cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif;">
        <tbody>
            <? 		
            foreach ($data as $o) 
            {
				if (isset($o->real_value) && strlen($o->real_value)) {

					echo '
					<tr style="padding:3px;">
						<th width="250" style="text-align:left;">'. $o->name .'</th>
						<td>'. ($o->fdid == 7 ? $o->value : $o->real_value) .'</td>
					</tr>';
				}
            } ?>
        </tbody>
    </table>
    
    <br />
</div>