jQuery(document).ready(function( $ ) {

    var button = $("#chi-enquete-submit");
    button.click( function() {

        validate();
        var outvars = {};
        outvars._ajax_nonce =  chi_enquete_chart_ajax_obj.nonce;
        outvars.action = chi_enquete_chart_ajax_obj.action;

        outvars.state = "start";
        var state_info = $(".chi-enquete-state-info:last");
        if (state_info.length > 0 && state_info.val()) {
            outvars.state = state_info.val();
        }

        var dob_info = $('#chi-enquete-dob');
        if (dob_info.length && dob_info.val()) {
            var dob = dob_info.val();
            outvars.dob_ts = new Date(dob).getTime()/1000;
        }

        var code_info = $('#chi-enquete-code');
        if (code_info.length && code_info.val()) {
            outvars.code = code_info.val();
        }else {
            var other_code_info = $('.chi-enquete-code:last');
            if (other_code_info.length && other_code_info.val()) {
                outvars.code = other_code_info.val();
            }
        }

        //see if there are any answers to be sent
        var number_questions = $("div.chi-enquete-answer-line").length;
        if (number_questions > 0) {
            //make sure they are all answered
            var checked = $("input.chi-enquete-radio:checked");
            if (checked.length < number_questions) {
                //signal error state and return early

                return;
            }
            var beer = [];
            checked.each(function (index) {
                var sel = $(this);
                beer.push({name: sel.attr('name'),value: sel.val()})
            });
            outvars.answers = beer;
        }

        $.ajax({
            type: 'POST',
            dataType: "json",
            url: chi_enquete_chart_ajax_obj.ajax_url,
            data: outvars,
            success: success_handler,
            error: error_handler
        });

        function success_handler(data) {

            if (data.is_valid) {
                switch (data.state) {
                    case 'finished_survey_page':
                        button.hide(); //no more submitting data once we get the chart for public
                    case 'survey_page':
                    case 'end_survey':
                        $(".chi-enquete-html").html(data.html);
                        let top_pos = jQuery("div.chi-enquete-custom-header").offset().top;
                        jQuery(document).scrollTop(top_pos);
                        $('.chi-enquete .chi-enquete-radio').on('click', function() {
                            $(this).closest('fieldset').removeClass('chi-error') ;
                        });
                        break;
                    default:
                        console.warn("Did not get a state that chi enquete recognized. The state was ",data.state);
                }



            } else {
                if (data.state === 'missing_dob') {
                    $(".chi-enquete-start-text").html(data.html);
                }
            }
            console.debug(data.message);
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

        function validate() {
            var count = 0;
             $('.chi-enquete fieldset').each(function(index, item) {
                if (($(item).find('input:radio').length > 0 && $(item).find('input:radio:checked').length === 0)) {
                    $(item).addClass('chi-error');
                    count++;
                }
                else{
                    $(item).removeClass('chi-error');
                }
            });
             if (count > 0) {
                 let top_pos = jQuery(".chi-error:first").offset().top - 80;
                 jQuery(document).scrollTop(top_pos);
             }

            return (count > 0) ? false : true;
        };
    });

});




