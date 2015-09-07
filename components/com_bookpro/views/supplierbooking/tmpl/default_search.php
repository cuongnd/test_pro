<script type="text/javascript">
    jQuery(function($) {


        //set calender select
        $( "#room_checkin_date" ).datepicker({
            dateFormat:"dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel:false,
            minDate: new Date(),
            showOn: "button",
            buttonImageOnly: true, 
            buttonImage: 'components/com_bookpro/assets/images/callender.png',
            onSelect : function() {
                
                var selected = $('#room_checkin_date').datepicker('getDate');
                selected.setDate(selected.getDate()+1);
                $('#room_checkout_date').datepicker('setDate', selected);
                $( "#room_checkout_date" ).datepicker("option", {
                    minDate: selected
                });
            }
        });
        $( "#room_checkout_date" ).datepicker({
            dateFormat:"dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel:false,
            minDate: new Date(),
            showOn: "button",
            buttonImageOnly: true,

            buttonImage: 'components/com_bookpro/assets/images/callender.png',


        });
        $('.room').delegate('#check_available', 'click', function() {
            if ($('#hotel').val() ==0) {
                var btns = {};
                btns['yes'] = function(){ 
                    $(this).dialog("close");
                };
                $("<div><?php echo JText::_('COM_BOOKPRO_SELECT_HOTEL') ?></div>").dialog({
                    autoOpen: true,
                    title: '<?php echo JText::_('COM_BOOKPRO_MESAGER') ?>',
                    modal:true,
                    buttons:btns
                });

                $('.hotel_search input[name="hotel"]').focus();
                return false;
            }
            if ($('#room_checkin_date').val() == '') {
                var btns = {};
                btns['yes'] = function(){ 
                    $(this).dialog("close");
                };
                $("<div><?php echo JText::_('COM_BOOKPRO_START_DATE_IS_REQUIRED_FIELD') ?></div>").dialog({
                    autoOpen: true,
                    title: '<?php echo JText::_('COM_BOOKPRO_MESAGER') ?>',
                    modal:true,
                    buttons:btns
                });

                $('.hotel_search input[name="checkin"]').focus();
                return false;
            }
            if ($('#room_checkout_date').val() == '') {
                var btns = {};
                btns['yes'] = function(){ 
                    $(this).dialog("close");
                };
                $("<div><?php echo JText::_('COM_BOOKPRO_END_DATE_IS_REQUIRED_FIELD') ?></div>").dialog({
                    autoOpen: true,
                    title: '<?php echo JText::_('COM_BOOKPRO_MESAGER') ?>',
                    modal:true,
                    buttons:btns
                });
                $('.hotel_search input[name="checkout"]').focus();
                return false;
            }
            var dateObject = $('#room_checkin_date').datepicker("getDate");
            var dateString = $.datepicker.formatDate("yy-mm-dd", dateObject);

            var room_checkin_date_timestamp=new Date(dateString).getTime();

            var dateObject = $('#room_checkout_date').datepicker("getDate");
            var dateString = $.datepicker.formatDate("yy-mm-dd", dateObject);

            var room_checkout_date_timestamp=new Date(dateString).getTime();

            if(room_checkin_date_timestamp>=room_checkout_date_timestamp )
            {
                var btns = {};
                btns['yes'] = function(){ 
                    $(this).dialog("close");
                };
                $("<div><?php echo JText::_('COM_BOOKPRO_CHECKIN_DATE_MUST_SMALLLER_CHECKOUT_DATE') ?></div>").dialog({
                    autoOpen: true,
                    title: '<?php echo JText::_('COM_BOOKPRO_SELECT_HOTEL') ?>',
                    modal:true,
                    buttons:btns
                });

                return false;
            }

            $.ajax({
                type : "GET",
                url : "index.php?option=com_bookpro&controller=flight&task=findStatebycountry",
                data:{
                    option:'com_bookpro',
                    controller:'supplierbooking',
                    task:'showrooms',
                    checkin:$("#room_checkin_date").val(),
                    checkout:$("#room_checkout_date").val(),
                    hotel_id:$("#hotel").val()
                },
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "block",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success : function(result) {

                    $('.widgetbookpro-loading').css({
                        display:"none"
                    });

                    $('#roomlist').html(result);
                    $('.roombookingdetail').html('');
                }
            });



        });
    });
</script>
<div class="date-select container-fluid form-horizontal" style="padding-top: 10px;">
    <div class="control-group span2">
        <label class="control-label"><?php echo JText::_('COM_BOOKPRO_HOTEL')?> </label>
        <div class="controls">    
            <?php  echo $this->getHotelSelect(); ?>
        </div>
    </div>
    <div class="control-group span3">
        <label class="control-label"><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKIN')?>: </label>
        <div class="controls">    
            <input type="text" name="checkin_date" id="room_checkin_date" class="inputbox" value="<?php echo $this->cart->checkin_date; ?>" />
        </div>
    </div>


    <div class="control-group span3">
        <label class="control-label"><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKOUT')?>: </label>
        <div class="controls">    
            <input type="text" name="checkout_date" class="inputbox" id="room_checkout_date" value="<?php echo $this->cart->checkout_date; ?>" />
        </div>
    </div>
    <div class="control-group span3">
        <button class="btn btn-primary " type="button" id="check_available"><?php echo JText::_('COM_BOOKPRO_ROOM_AVAILABEL_CHECK') ?></button>
    </div>
</div>


