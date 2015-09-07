(function($) {

    $.fn.validation = function() {        

        var error = 0;
		
        $('.required-field', this).each(function() {
            var a_error=0;
            var this_input=$(':input', this);
            var input_value = this_input.val();
            if (input_value == "") {
                a_error++;
            }
            if(this_input.attr('datatype')=='email')
            {
                 var regex = new RegExp("^[\\w-_\.]*[\\w-_\.]\@[\\w]\.+[\\w]+[\\w]$");
                 if(!regex.test(input_value))
                 {
                    a_error++; 
                 }
            }
            
            if (a_error != 0) {
                $('span.error-message', this).remove();
                $(this).append('<span class="error-message"><span class="error"></span></span>');
                $('span.error', this).html('Field is required.');
                $(':input', this).addClass("error-highlight");
                error++;
            } else {
                $('span.error-message', this).remove();
                $(':input', this).removeClass("error-highlight");
            }
        });

        if (error == 0) {
            return true;
        } else {
            return false;
        }
    };

})(jQuery);