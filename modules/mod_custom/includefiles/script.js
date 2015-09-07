/**
 * Created by cuongnd on 7/16/14.
 */
jQuery(document).ready(function($){
    $("#author-rates-slider").ionRangeSlider({
        min: 0,
        max: 75000,
        from: 2000,
        postfix: " $",
        prettify: true,
        hasGrid: false,
        onChange:function()
        {
            rates=$('#author-rates-slider').val();
            $('.js-rates-volume').html(rates+' $');
            $rates_commission=getvaluepersen(rates);
            $('.js-rates-commission').html($rates_commission+'%');

        }
    });
    function getvaluepersen (e) {
        var t;
        t = e;
        switch (!0) {
            case t < 3750:
                return 50;
            case t < 7500:
                return 51;
            case t < 11250:
                return 52;
            case t < 15e3:
                return 53;
            case t < 18750:
                return 54;
            case t < 22500:
                return 55;
            case t < 26250:
                return 56;
            case t < 3e4:
                return 57;
            case t < 33750:
                return 58;
            case t < 37500:
                return 59;
            case t < 41250:
                return 60;
            case t < 45e3:
                return 61;
            case t < 48750:
                return 62;
            case t < 52500:
                return 63;
            case t < 56250:
                return 64;
            case t < 6e4:
                return 65;
            case t < 63750:
                return 66;
            case t < 67500:
                return 67;
            case t < 71250:
                return 68;
            case t < 75e3:
                return 69;
            default:
                return 70
        }
    }


});
window.scrollReveal = new scrollReveal();
//or
window.scrollReveal2 = new scrollReveal( {reset: true,elem: document.getElementById('srcontainer')} );

