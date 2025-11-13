<script>
    function ShowTakeTable(id) {
        $('a.show_table[data-id=' + id + ']').addClass('d-none');
        $('a.hide_table[data-id=' + id + ']').removeClass('d-none');
        $('table.take_table[data-id=' + id + ']').removeClass('d-none');
    }
    
    function HideTakeTable(id) {
        $('a.hide_table[data-id=' + id + ']').addClass('d-none');
        $('a.show_table[data-id=' + id + ']').removeClass('d-none');
        $('table.take_table[data-id=' + id + ']').addClass('d-none');
    }
    
    function ShowNotTakeTable() {
        $('a.show_not_take_table').addClass('d-none');
        $('a.hide_not_take_table').removeClass('d-none');
        $('table.not_take_table').removeClass('d-none');
    }
            
    function HideNotTakeTable() {
        $('a.hide_not_take_table').addClass('d-none');
        $('a.show_not_take_table').removeClass('d-none');
        $('table.not_take_table').addClass('d-none');
    }
    
    $('#cut_remove').on('shown.bs.modal', function() {
        $('input:text:visible:first').focus();
    });
            
    $('#cut_remove').on('hidden.bs.modal', function() {
        $('input#status_comment').val('');
    });
    
    $('#edit_take_stream').on('shown.bs.modal', function() {
        $('input#take_stream_weight').focus();
    });
            
    $('#edit_take_stream').on('hidden.bs.modal', function() {
        $('input#take_stream_weight').val('');
        $('input#take_stream_length').val('');
        $('input#take_stream_radius').val('');
        $('#edit_take_stream_alert').addClass('d-none');
    });
            
    $('#add_not_take_stream').on('shown.bs.modal', function() {
        $('select#calculation_stream_id').focus();
    });
            
    $('#add_not_take_stream').on('hidden.bs.modal', function() {
        $('select#calculation_stream_id').val('');
        $('input#add_not_take_stream_weight').val('');
        $('input#add_not_take_stream_length').val('');
        $('input#add_not_take_stream_radius').val('');
        $('#add_not_take_stream_alert').addClass('d-none');
    });
    
    $('#edit_not_take_stream').on('shown.bs.modal', function() {
        $('input#not_take_stream_weight').focus();
    });
            
    $('#edit_not_take_stream').on('hidden.bs.modal', function() {
        $('input#not_take_stream_weight').val('');
        $('input#not_take_stream_length').val('');
        $('input#not_take_stream_radius').val('');
        $('#edit_not_take_stream_alert').addClass('d-none');
    });
    
    <?php if(IsInRole(CUTTER_USERS)): ?>
    function ShowCutterName() {
        $('span#top_user_name').load("<?=APPLICATION ?>/cut/_get_user.php");
    }
    
    ShowCutterName();
    setInterval(ShowCutterName, 60000);
    <?php endif; ?>
</script>