(function($) {
    $(function() {

        // ad client modal

        $(document).on('click', '.hideModalButton', function(){
            $('#modal').modal('hide');
        });

        $(document).on('click', '.showModalButton', function(){
            if ($('#modal').data('bs.modal').isShown) {
                $('#modal').find('#modalContent')
                        .load($(this).attr('value'));
                //dynamiclly set the header for the modal via title tag
                document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
            } else {
                //if modal isn't open; open it and load content
                $('#modal').modal('show')
                        .find('#modalContent')
                        .load($(this).attr('value'));
                 //dynamiclly set the header for the modal via title tag
                document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
            }
        });


        $(document).on('beforeSubmit', 'form#create-client', function () {
            var form = $(this);
            // return false if form still have some validation errors
            if (form.find('.has-error').length) {
                return false;
            }

            if (form.data('ajax') == true) {
                $.ajax({
                    url: form.attr('action'),
                    type: 'post',
                    data: form.serialize(),
                    success: function (response) {
                        if ( response.data == 'success') {
                            console.log(response.clientId);
                            var newClientData = [{
                                                    id:response.clientId,
                                                    text:response.clientNameWithAddress
                                                }];
                            refreshSelect($('#ad-client_id'), newClientData);
                            $('#ad-client_id').val(response.clientId).change();
                            $('#modal').modal('hide');
                        }
                    }
                });
                return false;
            }
        });

        function refreshSelect($input, data) {
            //$input.html($('<option />'));
            for (var key in data) {
                var $option = $('<option />')
                    .prop('value', data[key]['id'])
                    .text(data[key]['text'])
                ;
                $input.append($option)
            }
            $input.trigger('change');
        }


        $(document).on('change', '#ad-client_id', function () {
            //console.log( $('#ad-client_id').val() );

            $.pjax.reload({container:'#client-ads-pjax'}).done(function(){
                $('#client-ads-gridview-client-select').val($('#ad-client_id').val()).change();
            });

            // $('.grid-view-selector').yiiGridView('applyFilter');
            // https://github.com/defunkt/jquery-pjax

            //$('#ad-client_id').val()
            //$('#client-ads').load($('#client-ads').data('url'));
        });

    });
})(jQuery);
