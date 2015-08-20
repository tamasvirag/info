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
        
        
        // Set whole parent disctrict
        $(document).on('change', '.group-parent', function() {
            
            if ($(this).is(":checked")) {
                $('.group-'+$(this).val()).prop("checked",true);
            }
            else {
                $('.group-'+$(this).val()).prop("checked",false);
            }
            
            
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
        
        
        // invoice preview
        $(document).on('click', '.btn-invoice-preview', function() {
            if ( $( "form#news-invoice input[name='selection[]']:checked" ).length == 0) {
                bootbox.alert("Nincs kijelölve elem!");
                return false;
            }
            $( "form#news-invoice" ).submit();
        });
        
        
        // invoice execute
        $(document).on('click', '.btn-invoice-submit', function() {
            
            bootbox.confirm("Biztos benne, hogy számlázza?", function(result) {
            
                if (result && $( "form#news-invoice input[name='selection[]']:checked" ).length != 0) {
                    
                    /*
                        if ( $( "form#news-invoice input[name='selection[]']:checked" ).length == 0) {
                        bootbox.alert("Nincs kijelölve elem!");
                        return false;
                    }
                    */
                    
                    $("#btn-invoice-preview").remove();
                    $("#hidden-field").remove();
                    
                    $.ajax({
                        type:       "POST",
                        url:        baseUrl+'/invoice/execute',
                        data:       $("#news-invoice").serialize(),
                        dataType:   'json',
                        success:    function(data)
                        {
                            if (data.success)
                            {
                                $("#btn-invoice").remove();
                                $("#form-btn-group").append("<span>Számla: </span> ");
                                $("#form-btn-group").append("<a href='"+baseUrl+"/invoice/pdf?invoice_group_id="+data.invoice_group_id+"&copy=1' target='_blank'>1. példány</a> ");
                                $("#form-btn-group").append("<a href='"+baseUrl+"/invoice/pdf?invoice_group_id="+data.invoice_group_id+"&copy=2' target='_blank'>2. példány</a> ");
                            }
                        }
                    });
                    
                }
                
                else {
                    return;
                }
                
            }); 
            
            

        });
        
    });
})(jQuery);



// override yii default js alert confirm with bootbox

/*
yii.allowAction = function ($e) {
    var message = $e.data('confirm');
    return message === undefined || yii.confirm(message, $e);
};
yii.confirm = function (message, ok, cancel) {
 
    bootbox.confirm(
        {
            message: message,
            buttons: {
                confirm: {
                    label: "OK"
                },
                cancel: {
                    label: "Mégse"
                }
            },
            callback: function (confirmed) {
                if (confirmed) {
                    !ok || ok();
                } else {
                    !cancel || cancel();
                }
            }
        }
    );
    // confirm will always return false on the first call
    // to cancel click handler
    return false;
}
*/
