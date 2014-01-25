<div class="nNote nInformation">
    <p>Il y a <strong><?= count($users) ?> sessions en cours</strong> sur l'intranet.</p>
</div>

<table class="table table-striped table-bordered table-advance table-hover dataTable">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Dernière activité</th>
            <th>Adresse IP</th>
            <th>Page en cours</th>
        </tr>
    </thead>
    <tbody>
    <?
    foreach ($users as $row): 
    
        echo '
        <tr>
            <td>'. $row->username .'</td>
            <td>'. $row->last_activity .'</td>
            <td>'. $row->ip .'</td>
            <td>'. $row->module .'</td>
        </tr>';
    
    endforeach;?>
    </tbody>
</table>