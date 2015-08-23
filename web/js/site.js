(function($) {
    $(function() {
        
        setTimeout(function() { $('.alert').slideUp(); }, 3000);
        
        function isNumeric(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }
        
        // News district edit
        function checkNewsDistrictRows() {
            $.each( $('.child input:checkbox'), function() {
                $('#row-'+$(this).val()).removeClass('highlight');
                $('#newscount-'+$(this).val()).html('');
            });
            
            newscountAll = 0;
            $.each( $('.child input:checked'), function() {
                $('#row-'+$(this).val()).addClass('highlight');
                
                block = 0;
                blockPrice = $('#block-price-'+$(this).val()).val();
                blockPricePH = $('#block-price-'+$(this).val()).attr('placeholder');
                if ( isNumeric( blockPrice ) && blockPrice > 0 || blockPrice == "" && isNumeric( blockPricePH ) && blockPricePH > 0 ) {
                    if ( isNumeric( $('#block-'+$(this).val()).val() ) ) {
                        block = $('#block-'+$(this).val()).val();
                    }
                    else if ( isNumeric( $('#block-'+$(this).val()).attr('placeholder') ) ) {
                        block = $('#block-'+$(this).val()).attr('placeholder');
                    }
                }
                
                house = 0;
                housePrice = $('#house-price-'+$(this).val()).val();
                housePricePH = $('#house-price-'+$(this).val()).attr('placeholder');
                if ( isNumeric( housePrice ) && housePrice > 0 || housePrice == "" && isNumeric( housePricePH ) && housePricePH > 0 ) {
                    if ( isNumeric( $('#house-'+$(this).val()).val() ) ) {
                        house = $('#house-'+$(this).val()).val();
                    }
                    else if ( isNumeric( $('#house-'+$(this).val()).attr('placeholder') ) ) {
                        house = $('#house-'+$(this).val()).attr('placeholder');
                    }
                }
                
                $('#newscount-'+$(this).val()).html(parseInt(block)+parseInt(house));
                newscountAll += (parseInt(block)+parseInt(house));
            });
            $('#newscount-all').html(newscountAll);
        }
        
        checkNewsDistrictRows();
        
        $('#edit-news-districts table .child').toggle();
        
        $(document).on('click', '#edit-news-districts table .accordion', function() {
            $('#edit-news-districts table .child-'+$(this).data('id')).toggle();
        });
        
        $(document).on('change', '#edit-news-districts table input:checkbox', function(){
            checkNewsDistrictRows();
        });
        $(document).on('change', '.newscount-trigger', function(){
            checkNewsDistrictRows();
        });
        $(document).on('keyup', '.newscount-trigger', function(){
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
                                $("#form-btn-group").append("<a href='"+baseUrl+"/invoice/pdf?invoice_group_id="+data.invoice_group_id+"&type=normal' target='_blank'>Számlák megnyitása</a> ");
                                $("#form-btn-group").append(" <a href='"+baseUrl+"/invoiceenvelope?invoice_group_id="+data.invoice_group_id+"' target='_blank'>Borítékok</a> ");
                            }
                        }
                    });
                    
                }
                
                else {
                    return;
                }
                
            }); 
            
        });

        if ( typeof disableNews !== 'undefined' ) {
            $("input").attr('disabled', 'disabled');
            $("input").attr('readonly', 'true');
            $("select").attr('disabled', 'disabled');
            $("select").attr('readonly', 'true');
            $("textarea").attr('disabled', 'disabled');
            $("textarea").attr('readonly', 'true');
        }
        
    });
})(jQuery);
