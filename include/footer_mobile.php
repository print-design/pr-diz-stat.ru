<script>
    function AddClearListener() {
        $('button#clear').click(function() {
            $('input#id').val('');
            $('input#id').change();
            $('input#id').focus();
        });
    }

    function SetClearVisibility(obj) {
        if(obj.val() === '') {
            $('button#clear').addClass('d-none');
        }
        else {
            $('button#clear').removeClass('d-none');
        }
    }
    
    $(document).ready(function() {
        SetClearVisibility($('input#id'));
        AddClearListener();
    });
     
    $('input#id').keyup(function(e) {
        SetClearVisibility($(e.target));
    });
    
    $('input#id').keypress(function(e) {
        SetClearVisibility($(e.target));
    });
    
    $('input#id').change(function(e) {
        SetClearVisibility($(e.target));
    });    
</script>