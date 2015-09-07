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
            buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/callender.png',
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
            buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/callender.png'

        });
        $("#check_available").click(function(){
            if ($('#room_checkin_date').val() == '') {
                alert('<?php echo JText::_('COM_BOOKPRO_START_DATE_IS_REQUIRED_FIELD')?>');
                $('.hotel_search input[name="checkin"]').focus();
                return false;
            }
            if ($('#room_checkout_date').val() == '') {
                alert('<?php echo JText::_('COM_BOOKPRO_END_DATE_IS_REQUIRED_FIELD')?>');
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
                alert('<?php echo JText::_('COM_BOOKPRO_CHECKIN_DATE_MUST_SMALLLER_CHECKOUT_DATE')?>');
                return false;
            }
            $.ajax({
                url:'<?php echo JUri::base() ?>index.php',
                type: "GET",
                data:{
                    option:'com_bookpro',
                    controller:'widgethotelbooking',
                    id:joombookpro_account_id,
                    task:'showrooms',
                    checkin:$("#room_checkin_date").val(),
                    checkout:$("#room_checkout_date").val()
                },
                beforeSend: function() {
                    $('#widgetbookpro .widgetbookpro-loading').css({
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

                crossDomain: true,
                async: false,
                dataType: "jsonp",
                contentType: "application/json",
                success:function(data){
                    $('#widgetbookpro .widgetbookpro-loading').css({
                        display:"none"
                    });
                    $('#roomlist').html(data);
                    $('.roombookingdetail').html('');
                }
            });
        });
    });
</script>
<div class="date-select">
    <div class="control-group">
        <label><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKIN')?> </label>
        <div class="input-append">    
            <input type="text" name="checkin_date" id="room_checkin_date" class="inputbox" value="<?php echo $this->cart->checkin_date; ?>" />
        </div>
    </div>
    <div class="control-group">
        <label><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKOUT')?> </label>
        <div class="input-append">    
            <input type="text" name="checkout_date" class="inputbox" id="room_checkout_date" value="<?php echo $this->cart->checkout_date; ?>" />
        </div>
    </div>
    <button class="btn btn-primary " type="button" id="check_available"><?php echo JText::_('COM_BOOKPRO_ROOM_AVAILABEL_CHECK') ?></button>
</div>


