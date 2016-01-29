<script id="<?php echo str_replace('.php','', basename(__FILE__)) ?>" type="text/x-kendo-template">
    <tr data-uid="#: uid #">
        <td >
            #:tour_package_id#
        </td>
        <td >
            #:group#
        </td>
        <td >
            #:start_date#
        </td>
        <td >
            #:tour_class#
        </td>
        <td >
            <a href="<?php echo JUri::root().'index.php?option=com_bookpro&view=bookpro&Itemid='.$linkDetail.'&tour_package_id=#:tour_package_id#' ?>">#:tour_package_id#(#:tour_package_id#)</a>
        </td>
    </tr>
</script>
<script id="alt_<?php echo str_replace('.php','', basename(__FILE__)) ?>" type="text/x-kendo-template">
    <tr data-uid="#: uid #">
        <td >
            #:tour_package_id#
        </td>
        <td >
            #:group#
        </td>
        <td >
            #:start_date#
        </td>
        <td >
            #:tour_class#
        </td>
        <td >
            <a href="<?php echo JUri::root().'index.php?option=com_bookpro&view=bookpro&Itemid='.$linkDetail.'&tour_package_id=#:tour_package_id#' ?>">#:tour_package_id#(#:tour_package_id#)</a>
        </td>
    </tr>
</script>


