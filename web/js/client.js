var client = {

    /**
     * üzleti ügyfélnél bővebb form mutatása
     */
    clientBusinessSwitch : function () {
        if ($("#client-business").prop('checked') == false) {
            $(".client-business-block").hide();
        }
        else {
            $(".client-business-block").show();
        }
    }

};

(function(){

    $(document).on('click', "#client-business", function( event ) {
        client.clientBusinessSwitch();
    });

    /*
     * form megjelenítésnél üzleti checkbox ellenőrzése
     */
    client.clientBusinessSwitch();

}());
