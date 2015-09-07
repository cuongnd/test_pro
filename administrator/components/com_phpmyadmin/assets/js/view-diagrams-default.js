jQuery(document).ready(function ($) {

    $(document).on('click','a.preview',function(){
        var windowFeatures = 'menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes';
        var windowName ='png_output';

        var imageWindow = window.open('', windowName, windowFeatures);
        imageWindow.document.write('<img src="' + $(this).attr('data-png-image') + '"/>');

    });

});
