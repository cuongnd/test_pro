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

$doc->addScript(JUri::root().'/administrator/components/com_virtuemart/assets/js/view_importcategoryvatgia.js');


?>
<div class="view-importcategoryvatgia">
    <div class="row">
        <div class="span12">
            <input type="button" class="btn btn-default importcategoryvatgia" value="Import categories from vatgia">
        </div>
    </div>
    <div class="row">
        <div class="span12">

        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('.view-importcategoryvatgia').view_importcategoryvatgia({

        });
    });
</script>