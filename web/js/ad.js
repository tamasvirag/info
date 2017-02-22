var ad = {

    /**
     * nem jelenik meg addig a hirdetésfelvitel form amíg nincs ügyfél választva
     */
    clientIsSelectedSwitch : function () {
        if ($("#ad-client_id").val() == "") {
            $("#client-ads-pjax").hide();
            $("#ad-form").hide();
            $("#client-update-button").hide();
        }
        else {
            $("#client-ads-pjax").show();
            $("#ad-form").show();
            $("#client-update-button").show();
        }
    },

    /**
     * client választó frissítése, miután mentésre került az új client, vagy szerkesztve lett egy meglévő
     */
    updateClientSelect : function ( input, data, status ) {
        // szerkesztett elem eltávolítása
        if (status == 'updated') {
            $(input+" option[value='"+data[0].id+"']").remove();
        }
        // új elem hozzáadása
        for (var key in data) {
            var $option = $('<option />')
                .prop('value', data[key]['id'])
                .text(data[key]['text'])
            ;
            $(input).append($option);
        }
        $(input).trigger('change');
    },

    /**
     * hirdetés betöltése az új hirdetés formba
     */
    loadAdToForm : function ( formId, data ) {
        $(formId + ' #ad-description').val(data.description);
        $(formId + ' #ad-category_id').val(data.category_id).trigger("change");
        $(formId + ' #ad-user_id').val(data.user_id).trigger("change");
        $(formId + ' #ad-highlight_type_id').val(data.highlight_type_id).trigger("change");
        $(formId + ' #ad-discount').val(data.discount);
        $(formId + ' #ad-net_price').val(data.net_price);
        $(formId + ' #ad-gross_price').val(data.gross_price);
        $(formId + ' #ad-vat_price').val(data.vat_price);
        $(formId + ' #ad-vat').val(data.vat);
        $(formId + ' #ad-words').val(data.words);
        $(formId + ' #ad-letters').val(data.letters);
        $(formId + ' #ad-motto').val(data.motto);
        if (data.business) {
            $(formId + ' #ad-business').prop('checked',true);
        }
        else {
            $(formId + ' #ad-business').prop('checked',false);
        }
    },

    /*
     * karakter és szószám
     */
    countWords : function ( field ) {
        var charCount = field.value.length;
        var fullStr = field.value + " ";

        fullStr = fullStr.replace( /([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+/g,'email' );
        fullStr = fullStr.replace( /(([a-zA-Z0-9_\.\-])+\.)+([a-zA-Z0-9]{2,4})+/g,'domain' );

        var initial_whitespace_rExp = /^[^A-ZÉÁŰŐÚÖÜÓÍa-záéűőúöüóí0-9@/_-]+/gi;
        var left_trimmedStr = fullStr.replace(initial_whitespace_rExp, "");
        var non_alphanumerics_rExp = rExp = /[^A-ZÉÁŰŐÚÖÜÓÍa-záéűőúöüóí0-9@/_-]+/gi;
        var cleanedStr = left_trimmedStr.replace(non_alphanumerics_rExp, " ");
        var splitString = cleanedStr.split(" ");
        var wordCount = splitString.length -1;
        if (fullStr.length <2) {
            wordCount = 0;
        }
        return wordCount;
    },

    /*
     *
     */
    calculatePrices : function () {
        var hossz = szoszam(document.hirdetesform.tartalom, false, false);

        $("#szoszam").val(hossz);

        if ( hossz < 10 ) {
            hossz = 10;
        }

        var hirdetesenkenti_ar  = 0;
        var teljes_ar           = 0;
        var jelige_felar        = 0;
        var kep_felar           = 0;
        var kiemeles_felar      = 0;
        var szoar               = szo_ar;
        var per_alkalom         = 0;

        if ($("#jelige_valaszto").prop('checked') == true) {
            jelige_felar        = jelige_ar;
        }

        if ($("#kep_valaszto").prop('checked') == true) {
            kep_felar           = kep_ar;
        }

        var megjelenesek_szama  = $("#megjelenesek_szama").val();
        hirdetesenkenti_ar = (szoar * hossz) + (jelige_felar + kep_felar);
        per_alkalom = (szoar * hossz);

        if ($("#kiemeles").prop('checked') == true) {
            switch( $("input[name='kiemeles_type']:checked").val() )
            {
                case '1':
                hirdetesenkenti_ar = hirdetesenkenti_ar * kiemelt_szorzo;
                kiemeles_felar = Math.round( per_alkalom * (kiemelt_szorzo - 1) );
                break;

                case '2':
                hirdetesenkenti_ar = hirdetesenkenti_ar + 305;
                kiemeles_felar = 305;
                break;

                case '3':
                hirdetesenkenti_ar = hirdetesenkenti_ar + 500;
                kiemeles_felar = 500;
                break;

                case '4':
                hirdetesenkenti_ar = hirdetesenkenti_ar + 405;
                kiemeles_felar = 405;
                break;

                case '5':
                hirdetesenkenti_ar = hirdetesenkenti_ar + 500;
                kiemeles_felar = 500;
                break;
            }
        }

        if ( $("#akcio").is(':checked') ) {
            teljes_ar = 3 * hirdetesenkenti_ar;
        }
        else {
            teljes_ar = megjelenesek_szama * hirdetesenkenti_ar;
        }

        $("#alkalmak").val(megjelenesek_szama);
        $("#per_alkalom").val(per_alkalom);
        $("#jelige_felar").val(jelige_felar);
        $("#kep_felar").val(kep_felar);
        $("#kiemeles_felar").val(kiemeles_felar);
        $("#szumma").val(teljes_ar);
    }

};


(function(){

    /**
     * ad client modal megjelenítés/bezárás
     */
    $(document).on('click', '.hideModalButton', function(){
        $('#modal').modal('hide');
    });

    $(document).on('click', '.showModalButton', function(){
        if ($('#modal').data('bs.modal').isShown) {
            $('#modal').find('#modalContent')
                    .load($(this).attr('value'));
            document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
        } else {
            //if modal isn't open; open it and load content
            $('#modal').modal('show')
                    .find('#modalContent')
                    .load($(this).attr('value'));
            document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
        }
    });


    /**
     * ad client modal mentés
     */
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
                        var newClientData = [{
                                                id:response.clientId,
                                                text:response.clientNameWithAddress
                                            }];
                        // client select frissítése
                        ad.updateClientSelect('#ad-client_id', newClientData, response.status); // added / updated
                        $('#ad-client_id').val(response.clientId).change();
                        $('#modal').modal('hide');

                        // ha nincs korábbi hirdetése a partnernek, akkor ne jelenjen meg a lista sem
                        if ( response.adsCount == 0 ) {
                            $("#client-ads-pjax").hide();
                        }
                    }
                }
            });
            return false;
        }
    });

    /**
     * client hirdetéseinek betöltése a listába, ha változik a kiválasztott client
     */
    $(document).on('change', '#ad-client_id', function () {

        ad.clientIsSelectedSwitch();

        if ($("#ad-client_id").val() != "") {
            $("#client-update-button").attr({value: baseUrl+'/client/update?id='+$("#ad-client_id").val()});
        }

        $.pjax.reload({container:'#client-ads-pjax'}).done(function(){
            $('#client-ads-gridview-client-select').val($('#ad-client_id').val()).change();
        });

    });

    /**
     * ad kattintásra betölteni a hirdetést a formba újként
     */
    $(document).on('click', '.create-ad-from-this', function(event){
        event.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            type: 'get',
            data: {},
            success: function (response) {
                if ( response.id ) {
                    console.log(response);
                    ad.loadAdToForm( "#new-ad-form", response );
                }
            }
        });
    });


    /*
     * oldal betöltésénél kiválasztott client ellenőrzése, ha nincs kiválasztva, akkor nem jelennek meg a hirdetésfelvitel elemei
     */
    ad.clientIsSelectedSwitch();

}());
