
jQuery(document).ready(function ($) {
    $("#category_id" +
        ",#category_extension_id" +
        ",#website_template_id" +
        ",#cms_template_id" +
        ",#e_commerce_templates_id" +
        ",#vendor_id" +
        ",#flash_media_id" +
        ",#sorting" +

        "").select2({
        placeholder: "Select a destination",
        width: 'resolve',
        allowClear: true,
        width: '100%'
    });

    $("#price-rates-slider").ionRangeSlider({
        type: 'double',
        min: 0,
        max: 500,
        postfix: " $",

        prettify: false,
        hasGrid: false,
        onChange:function()
        {
        }
    });
    $('.btn.type').click(function(){
        inputValue=$(this).find('input').val();
        $('input[name="Itemid"]').val(inputValue==1?itemid_extension:itemid_template);
    });



});
