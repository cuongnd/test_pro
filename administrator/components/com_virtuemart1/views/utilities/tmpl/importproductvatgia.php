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

$doc->addScript(JUri::root().'/administrator/components/com_virtuemart/assets/js/view_importproductvatgia.js');


?>
<div class="view-importproductvatgia">
    <div class="row-fluid">
        <div class="span12">
            <input type="text" name="vatgia_category_id">
            <input type="button" class="btn btn-default importproductvatgia" value="Import product from vatgia">
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12 response">

        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('.view-importproductvatgia').view_importproductvatgia({

        });
    });
</script>