<? 
foreach ($notes as $row) {
    echo '
    <div id="jnote'. $row->id.'" class="jnote alert alert-'. $row->type .'" style="left: '. $row->x .'%; top: '. $row->y .'%;">
        <p>'. $row->content .'</p>
        <span>par '. $row->username .'</span>
        '. ($this->ion_auth->is_admin() || $this->session->userdata('user_id') == $row->id_user ? '<i class="icon-remove" data-id="'. $row->id.'"></i>' : '') .'
    </div>';
	
}  ?>

<div id="dnote" class="hide">
    <p>Vous pouvez ajouter une note visible par tous concernant la page sur laquelle vous êtes.</p>

    <div class="row-fluid">
        <div class="span8 form-horizontal">
            <?= select('jnote_type', 'Type de note', NULL, '', array('info' => 'Information', 'success' => 'Succés', 'danger' => 'Alerte')) ?>
            <?= textarea('jnote_content', 'Contenu') ?>
        </div>
        <div class="span4">
            <div id="jnote" class="jnote alert alert-info">
                <p></p>
                <span>par <?=$this->session->userdata('username')?></span>
            </div>
        </div>
    </div>
    <a id="jnote-input" class="btn btn-info"><i class="icon-ok"></i> Créer ma note</a>
</div>
<script>
    $(function() {
        $('.jnote').draggable({ scroll: false,
            stop: function(event, ui) {
                var id = $(this).attr('id').replace('jnote', '');
                var x = $(this).position().left / $(window).width() * 100;
                var y = $(this).position().top / $(window).height() * 100;
                
                $.post('<?= base_url('services/update_note') ?>', {x: x, y: y, id: id},
                    function(data) {}
                );
            } 
        });
        
        $('#dnote').dialog({
            title: 'Ajouter une nouvelle note',
            width: 700,
            height: 290
        });    
        
        $('#jnote_content').keyup(function() {
            $('#jnote p').text($(this).val());
        });
        
        $('#jnote_type').change(function() {
            $('#jnote').removeClass('alert-info alert-danger alert-success').addClass('alert-' + $(this).val());
        });
        
        $('#jnote-input').click(function() {
           $.post('<?= base_url('services/add_note') ?>', {content: $('#jnote_content').val(), type: $('#jnote_type').val(), url: "<?= $this->uri->uri_string() ?>"},
           function(data) {
               if (data.success) {
                   $jnote = $('#jnote').clone();
                   $jnote.css({left: 20, top: 20});
                   $('body').append($jnote);
                   $jnote.draggable({ scroll: false });
                   $('#dnote').dialog('close');
               } else {
                   alert(data.message);
               }
           }, 'json');            
        });
        
        $('.jnote .icon-remove').live('click', function() {
            var id = $(this).attr('data-id');
            $.post('<?= base_url('services/remove_note') ?>', {id:id},
               function(data) {
                   if (data.success) {
                       $('#jnote' + id).fadeOut().remove();
                   } else {
                       alert(data.message);
                   }
               }, 'json');    
        });
    });
</script>