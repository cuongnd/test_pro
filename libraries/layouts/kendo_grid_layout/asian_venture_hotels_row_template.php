<script id="<?php echo str_replace('.php','', basename(__FILE__)) ?>" type="text/x-kendo-template">
    <tr data-uid="#: uid #">
        <td >
            #:id#
        </td>
        <td >
            <a href="<?php echo JUri::root().'index.php?option=com_bookpro&view=bookpro&Itemid='.$linkDetail.'&hotel_id=#:id#' ?>">#:title#</a>
        </td>
        <td >
            #:address1#
        </td>
        <td >
            #:rank#
        </td>
        <td >
            #:images#
        </td>
        <td >
            #:desc#
        </td>
    </tr>
</script>

<script id="alt_<?php echo str_replace('.php','', basename(__FILE__)) ?>" type="text/x-kendo-template">
    <tr data-uid="#: uid #">
        <td >
            #:id#
        </td>
        <td >
            <a href="<?php echo JUri::root().'index.php?option=com_bookpro&view=bookpro&Itemid='.$linkDetail.'&hotel_id=#:id#' ?>">#:title#</a>
        </td>
        <td >
            #:address1#
        </td>
        <td >
            #:rank#
        </td>
        <td >
            #:images#
        </td>
        <td >
            #:desc#
        </td>
    </tr>
</script>
