(function($) {
    $(function() {
        
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
    });
})(jQuery);
