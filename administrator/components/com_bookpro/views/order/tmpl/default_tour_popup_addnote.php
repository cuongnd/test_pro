<script>
    jQuery(document).ready(function($){
        $(".add-note").click(function(){
            val=$(".info-textarea").text();
            $(".info-modal-notes").text(val);

            $(".save-info-modal-notes").click(function(){
                newval=$(".info-modal-notes").val();
                order_id="<?php echo JFactory::getApplication()->input->get('cid', 0); ?>";
                $.ajax({
                    method:"POST",
                    url: "index.php?option=com_bookpro&controller=order&task=ajax_updatenotes",
                    data: {
                        info_notes:newval,
                        id:order_id,
                    },
                    dataType: "text",
                    beforeSend: function() {
                        $("#waiting").css("display","block");
                    },
                    success: function() {
                       $(".cancel-notes").click();
                        $(".info-textarea").text(newval);
                        $("#waiting").css("display","none");
                    },
                    error: function() {
                        alert('Error');
                    }
                });
//                $(".info-textarea").text(newval);
            });
        });

    });
</script>
<!-- Small modal -->
<div class="bookpro-modal modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div id="waiting" style="display:none;width:100%;height:100%;position:absolute;z-index:100;background:black;opacity: 0.7;text-align:center;">
        <img src='<?php echo JUri::root(); ?>/administrator/components/com_bookpro/assets/images/waitting.gif' style="width:200px;height:200px;margin-top:10%" />
    </div>
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="col-md-span12" style="padding:10px ;margin:10px">
                <textarea class="info-modal-notes" style="width:100%;height:200px"></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default cancel-notes" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary save-info-modal-notes">Save changes</button>
            </div>
        </div>


    </div>
</div>