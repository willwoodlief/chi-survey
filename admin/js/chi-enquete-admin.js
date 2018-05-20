(function( $ ) {
	'use strict';


})( jQuery );

function chi_enquete_talk_to_backend(method,server_options,success_callback,error_callback) {

    if (!server_options) {server_options = {};}

    // noinspection ES6ModulesDependencies
    var outvars = jQuery.extend({}, server_options);
    outvars._ajax_nonce =  chi_enquete_backend_ajax_obj.nonce;
    outvars.action = chi_enquete_backend_ajax_obj.action;
    outvars.method = method;

    // noinspection ES6ModulesDependencies
    jQuery.ajax({
        type: 'POST',
        dataType: "json",
        url: chi_enquete_backend_ajax_obj.ajax_url,
        data: outvars,
        success: success_handler,
        error: error_handler
    });

    function success_handler(data) {

        if (data.is_valid) {
            if (success_callback) {
                success_callback(data.data);
            } else {
                console.debug(data);
            }
        } else {
            if (error_callback) {
                error_callback(data.data);
            } else {
                console.debug(data);
            }

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
        var message = '';
        if (jqXHR && jqXHR.responseText) {
            try {
                what = jQuery.parseJSON(jqXHR.responseText);
                if (what !== null && typeof what === 'object') {
                    if (what.hasOwnProperty('message')) {
                        message =  what.message;
                    } else {
                        message = jqXHR.responseText;
                    }
                }
            } catch (err) {
                message = jqXHR.responseText;
            }
        } else {
            message = "textStatus"   ;
            console.info('Chi Enquete ajax failed but did not return json information, check below for details',what);
            console.error(jqXHR, textStatus, errorThrown);
        }

        if (error_callback) {
            error_callback(message);
        } else {
            console.warn(message);
        }


    }
}
