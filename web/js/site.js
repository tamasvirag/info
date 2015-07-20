(function($) {
    $(function() {
        
        
        // News district edit
        function checkNewsDistrictRows() {
            $.each( $('.child input:checkbox'), function() {
                $('#row-'+$(this).val()).removeClass('highlight');
            });
            
            $.each( $('.child input:checked'), function() {
                $('#row-'+$(this).val()).addClass('highlight');
            });
        }
        
        checkNewsDistrictRows();
        
        $('#edit-news-districts table .child').toggle();
        
        $(document).on('click', '#edit-news-districts table .accordion', function() {
            $('#edit-news-districts table .child-'+$(this).data('id')).toggle();
        });
        
        $(document).on('change', '#edit-news-districts table input:checkbox', function(){
            checkNewsDistrictRows();
        });
        
        
        
        // new News, get payment_method_id from Client
        $(document).on('change', '#news-client_id', function(){
            $.ajax({
                url:        baseUrl+'/client/getclientjsonbyid?id='+$("#news-client_id").val(),
                method:     'POST',
                dataType:   'json',
                })
                .done(function(result) {
                    $("#news-payment_method_id").val(result.payment_method_id);
                });
        });
        
        
        $(document).on('click', '.btn-invoice-preview', function() {
            var client_id = $(this).data('client-id');
            
            if ( $( "form#news-invoice-"+client_id+" input:checked" ).length == 0) {
                alert("Nincs kijelölve elem!");
                return false;
            }
            
            $( "form#news-invoice-"+client_id ).submit();
        });
        
        
        // invoice execute
        $(document).on('click', '.btn-invoice-submit', function() {
            var client_id = $(this).data('client-id');
            
            if ( $( "form#news-invoice-"+client_id+" input:checked" ).length == 0) {
                alert("Nincs kijelölve elem!");
                return false;
            }
            
            $("#btn-invoice-preview-"+client_id).remove();
            $("#hidden-field-"+client_id).remove();
            
            $.ajax({
                type:       "POST",
                url:        baseUrl+'/invoice/execute',
                data:       $("#news-invoice-"+client_id).serialize(),
                dataType:   'json',
                success:    function(data)
                {
                    if (data.success)
                    {
                        $("#btn-invoice-"+client_id).remove();
                        $("#form-btn-group-"+client_id).append("<span>Számla: </span> ");
                        $("#form-btn-group-"+client_id).append("<a href='"+baseUrl+"/invoice/pdf?id="+data.invoice_id+"&copy=1' target='_blank'>1. példány</a> ");
                        $("#form-btn-group-"+client_id).append("<a href='"+baseUrl+"/invoice/pdf?id="+data.invoice_id+"&copy=2' target='_blank'>2. példány</a> ");
                    }
                }
            });

        });
        
    });
})(jQuery);
