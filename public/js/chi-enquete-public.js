jQuery(document).ready(function( $ ) {

    var button = $("#submit-results");
    button.click( function() {

        var outvars = {};
        outvars._ajax_nonce =  chart_ajax_obj.nonce;
        outvars.action = chart_ajax_obj.action;
        outvars.test = "passing through :-)";
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: chart_ajax_obj.ajax_url,
            data: outvars,
            success: success_handler,
            error: error_handler
        });

        function success_handler(data) {

            if (data.is_valid) {
                console.log('valid',data);
            } else {
                console.log('not valid',data);
            }
        }

        /**
         *
         * @param {XMLHttpRequest} jqXHR
         * @param {Object} jqXHR.responseJSON
         * @param {string} textStatus
         * @param {string} errorThrown
         */
        function error_handler(jqXHR, textStatus, errorThrown) {
            var what = '';

            if (jqXHR && jqXHR.responseText) {
                try {
                    what = jQuery.parseJSON(jqXHR.responseText);
                    if (what !== null && typeof what === 'object') {
                        if (what.hasOwnProperty('message')) {
                            var server_error = what.message;
                            console.log("server error message is ",server_error);


                        } else {
                            console.log("server error is ",jqXHR.responseText);

                        }
                    }
                } catch (err) {
                    console.log("server error is not json and text is ",jqXHR.responseText);

                }
            } else {

                console.log('Chi Enquete ajax failed but did not return json information, check below for details',what);
                console.log(jqXHR, textStatus, errorThrown);
            }


        }
    });

});


