<?php
/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 1/6/2016
 * Time: 8:13 AM
 *
 */
JHtml::_('jquery.framework');
$doc=JFactory::getDocument();

$doc->addScript(JUri::root().'/administrator/components/com_virtuemart/assets/js/view_setcategoryproduct.js');


?>
<div class="view-setcategoryproduct">
    <div class="row-fluid">
        <div class="span12">
            <input type="button" class="btn btn-default setcategoryproduct" value="Set category product vatgia">
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12 loading">

        </div>
    </div>
    <div class="row-fluid">
        <div class="span12 response">

        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('.view-setcategoryproduct').view_setcategoryproduct({

        });
    });
</script>