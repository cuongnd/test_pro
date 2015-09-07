<?php
JHtml::_('bootstrap.framework');
$doc=JFactory::getDocument();
$doc->addStyleSheet(JUri::root().'/media/jui/bootstrap-3.3.0/dist/css/bootstrap.css');
$doc->addScript(JUri::root().'/media/system/js/jquery.bookproside/js/jquery.bookproside.js');

$lessInput=JPATH_ROOT.'/media/system/js/jquery.bookproside/less/jquery.bookproside.less';
$cssOutput=JPATH_ROOT.'/media/system/js/jquery.bookproside/css/jquery.bookproside.css';
BookProHelper::compileLess($lessInput,$cssOutput);
$doc->addStyleSheet(JUri::root().'/media/system/js/jquery.bookproside/css/jquery.bookproside.css');

?>
<script>
    jQuery(document).ready(function($){
        $('bookpro-side').bookproside({});
    });
</script>
<div class="bookpro-side row">

    <div class="big-images col-md-9">
        <div class="show-image">show-image</div>
        <div class="show-map">show-map</div>
        <div class="main-big-image">
            <div class="control-left"><</div>
            <div class="control-right"></div>
            <div class="image"><img src="http://api.jqueryui.com/jquery-wp-content/themes/jquery/images/logo-jquery-ui.png"></div>
            <div class="image"><img src="http://api.jqueryui.com/jquery-wp-content/themes/jquery/images/logo-jquery-ui.png"></div>
            <div class="image"><img src="http://api.jqueryui.com/jquery-wp-content/themes/jquery/images/logo-jquery-ui.png"></div>
            <div class="image"><img src="http://api.jqueryui.com/jquery-wp-content/themes/jquery/images/logo-jquery-ui.png"></div>
            <div class="image"><img src="http://api.jqueryui.com/jquery-wp-content/themes/jquery/images/logo-jquery-ui.png"></div>
            <div class="image"><img src="http://api.jqueryui.com/jquery-wp-content/themes/jquery/images/logo-jquery-ui.png"></div>
        </div>
        <div class="map">
            <div class="this-map">
                hello this map
            </div>
        </div>
    </div>
    <div class="small-image col-md-3">
        <div class="image-item"><img src="http://api.jqueryui.com/jquery-wp-content/themes/jquery/images/logo-jquery-ui.png"></div>
        <div class="image-item"><img src="http://api.jqueryui.com/jquery-wp-content/themes/jquery/images/logo-jquery-ui.png"></div>
        <div class="image-item"><img src="http://api.jqueryui.com/jquery-wp-content/themes/jquery/images/logo-jquery-ui.png"></div>
        <div class="image-item"><img src="http://api.jqueryui.com/jquery-wp-content/themes/jquery/images/logo-jquery-ui.png"></div>
        <div class="image-item"><img src="http://api.jqueryui.com/jquery-wp-content/themes/jquery/images/logo-jquery-ui.png"></div>
        <div class="image-item"><img src="http://api.jqueryui.com/jquery-wp-content/themes/jquery/images/logo-jquery-ui.png"></div>
        <div class="control-image">
            <div class="control-left"><</div>
            <div class="control-right"></div>
        </div>
    </div>
</div>