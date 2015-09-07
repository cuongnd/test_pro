<?php
$doc=JFactory::getDocument();



$doc->addScript(JUri::root().'/media/system/js/jQuery-Amazing-Scrolling-Presentation-Plugin-scrolldeck/js/jquery.scrollTo-1.4.3.1.min.js');
$doc->addScript(JUri::root().'/media/system/js/jQuery-Amazing-Scrolling-Presentation-Plugin-scrolldeck/js/jquery.easing.1.3.js');
$doc->addScript(JUri::root().'/media/system/js/jQuery-Amazing-Scrolling-Presentation-Plugin-scrolldeck/js/jquery.scrollorama.js');
$doc->addScript(JUri::root().'/media/system/js/jQuery-Amazing-Scrolling-Presentation-Plugin-scrolldeck/js/jquery.scrolldeck.js');
$doc->addStyleSheet(JUri::root().'/media/system/css/ionicons.min.css');
$user=JFactory::getUser();
?>
<div id="get_more_row" class="slide">
    <div data-scroll-reveal="enter bottom and move 50px after 0.15s"   class="s5_width_wrap s5_row_paddings" id="get_more_row_inner" data-scroll-reveal-id="13" data-scroll-reveal-initialized="true" data-scroll-reveal-complete="true">
        <h3 class="row_title"><strong>Get</strong> More With A Membership</h3>
        <div id="s5_get_more_left" class="animate-build" data-build="1">
            <span style="font-size:1.3em;display:block;"><strong>1.</strong> Access all themes or templates for only <strong><span style="color:#FA8870;">one low price</span></strong></span>
            <br>
            Why pay a premium price for a single Joomla template or WordPress theme from other providers when you can access dozens of Joomla templates and Wordpress themes from us at the same price!
            <br><br>
            That's right, gain access to all our designs and extensions of one club for just one low price. This means if you decide you don't like a theme or you want to change one later, you can do this without having to purchase a brand new design every time.
        </div>
        <div id="s5_get_more_right"  class="animate-build" data-build="1">
            <span style="font-size:1.3em;display:block;"><strong>2.</strong> Professional web design done on a <strong><span style="color:#FA8870;">small budget</span></strong></span>
            <br>
            What if you could setup three websites for only $27 each? That is cheaper than most developers charge for a single theme or template. Our Best Value Membership does just this! For just $79.99 you can configure up to three sites using our designs, which equals to an incredibly low price of only $27 per website!
            <br><br>
            Don't forget if you change your mind, you download and install any other design of your choice during your membership period.
        </div>
        <div style="clear:both;"></div>
        <div id="get_more_button_wrap">
            <a class="button" href="http://www.shape5.com/join-now.html" style="float:none;margin-left:auto;margin-right:auto;display:inline-block;">View Our Pricing</a>
            <div style="clear:both;"></div>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {



    });
</script>
<style type="text/css">
    /* get more row */
    #get_more_row
    {
        position: relative !important;
        top: 0px !important;
    }
    #get_more_row {background-attachment: fixed;background-image: url(modules/mod_custom/includefiles/images/get_more_bg.jpg);background-position: center top;background-repeat: no-repeat;background-size: cover;}
    #s5_get_more_left {float:left;width:47%;color:#FFFFFF;line-height: 28px;}
    #s5_get_more_right {float:right;width:47%;color:#FFFFFF;line-height: 28px;}
    #get_more_row_inner {padding-bottom:82px;}
    #get_more_button_wrap {text-align:center;margin-top:66px;}

    @media screen and (max-width: 750px){
        #s5_get_more_left, #s5_get_more_right {width:100%;}
        #s5_get_more_right {margin-top:50px;}
    }
</style>
