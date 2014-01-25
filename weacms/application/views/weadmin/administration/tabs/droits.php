<div class="nNote nInformation topMargin">
    <p>Veuillez choisir un module dans la liste ci-dessous, vous pourrez ensuite gérer les droits d'accès à ce module.</p>
</div>
<div class="row-fluid">
    <select id="main-select" onchange="window.location.href = '<?= base_url('administration/index/5') ?>/'+ this.value;">
        <option value="0">Choisir ...</option>
    <? foreach ($controllers as $row) {
        
        echo '<option'. ($this->uri->segment(4) && $this->uri->segment(4) == $row->id_sm ? ' selected="selected"' : '') .' value="'. $row->id_sm .'">'. $row->name .'</option>';
    } ?>
    </select>
</div>

<? if (! $controller) : ?>

<div class="nNote nWarning topMargin">
    <p>Aucun module n'a été sélectionné.</p>
</div>

<? else : ?>

<div class="wrapper topMargin">
    <div class="clearfix">
        <div class="leftPart">
            <div class="filter"><span>Filtrer: </span><input type="text" class="boxFilter" id="box1Filter"><input type="button" value="x" class="fBtn" id="box1Clear"><div class="clear"></div></div>
            
            <select style="height:300px;" class="multiple" multiple="multiple" id="box1View">
            <? foreach ($services as $row) {
                
                echo '<option style="margin:3px;" value="'. $row->id .'">'. $row->description .'</option>';
            }  ?>  
            </select>
        </div>
            
        <div class="dualControl">
            <button class="btn mr5" type="button" id="to2">&nbsp;&gt;&nbsp;</button>
            <button class="btn" type="button" id="allTo2">&nbsp;&gt;&gt;&nbsp;</button><br>
            <button class="btn mr5" type="button" id="to1">&nbsp;&lt;&nbsp;</button>
            <button class="btn" type="button" id="allTo1">&nbsp;&lt;&lt;&nbsp;</button>
        </div>
            
        <div class="rightPart">
            <div class="filter"><span>Filtrer: </span><input type="text" class="boxFilter" id="box2Filter"><input type="button" value="x" class="fBtn" id="box2Clear"><div class="clear"></div></div>
            
            <select style="height:300px;" class="multiple" multiple="multiple" id="box2View">
            <? foreach ($services_access as $row) {
                
                echo '<option style="margin:3px;" value="'. $row->id .'">'. $row->description .'</option>';
            }  ?>  
            </select>
                    
        </div>
    </div>
    <br />
    <a id="update" class="btn btn-info">Mettre à jour les accès</a>
</div>

<script language="javascript" type="text/javascript">
    $(function() {
        $.configureBoxes();
        
        $('#update').click(function() {
            $.post('<?= base_url('administration/droits_update/'. $controller->id_sm) ?>', 
                {'access': $('#box2View option').map(function(){ return this.value }).get().join('-')},
                function(data) {
                    $.pnotify('Mise à jour avec succès');
                });
        })
    });
</script>
    
<? endif; ?>
