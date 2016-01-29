<script id="<?php echo str_replace('.php','', basename(__FILE__)) ?>" type="text/x-kendo-template">
    <tr data-uid="#: uid #">
        <td >
            #:id#
        </td>
        <td >
            #:username#
        </td>
        <td >
            <a href="<?php echo JUri::root().'index.php?option=com_bookpro&view=bookpro&Itemid='.$linkDetail.'&oder_id=#:id#' ?>">#:tour_title#(#:id#)</a>
        </td>
        <td>
            <a class="k-button k-button-icontext k-grid-edit" href="javascript:void(0)"><span class="k-icon k-edit"></span>Edit</a>
            <a class="k-button k-button-icontext k-grid-delete" href="javascript:void(0)"><span class="k-icon k-delete"></span>Delete</a>
        </td>
    </tr>
</script>

<script id="alt_<?php echo str_replace('.php','', basename(__FILE__)) ?>" type="text/x-kendo-template">
    <tr data-uid="#: uid #">
        <td >
            #:id#
        </td>
        <td >
            #:username#
        </td>
        <td >
            <a href="<?php echo JUri::root().'index.php?option=com_bookpro&view=bookpro&Itemid='.$linkDetail.'&oder_id=#:id#' ?>">#:tour_title#(#:id#)</a>
        </td>
        <td>
            <a class="k-button k-button-icontext k-grid-edit" href="javascript:void(0)"><span class="k-icon k-edit"></span>Edit</a>
            <a class="k-button k-button-icontext k-grid-delete" href="javascript:void(0)"><span class="k-icon k-delete"></span>Delete</a>
        </td>
    </tr>
</script>
