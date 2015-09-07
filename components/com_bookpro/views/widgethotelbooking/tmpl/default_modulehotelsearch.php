
<script type="text/javascript">
    jQuery(function($) {
        $(".hotel_search #checkin").datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            minDate: new Date(),
            showOn: "button",
            buttonImageOnly: true, 
            buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/callender.png',
            onSelect : function(selected) {
                var selected = $('.hotel_search #checkin').datepicker('getDate');
                selected.setDate(selected.getDate()+1);
                $('.hotel_search #checkout').datepicker('setDate', selected);
                $( ".hotel_search #checkout" ).datepicker("option", {
                    minDate: selected
                });
            }
        });
        $(".hotel_search #checkout").datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,

            minDate: new Date(),
            showOn: "button",
            buttonImageOnly: true, 
            buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/callender.png',

        });

        $('body').delegate('.hotel_search_button', 'click', function() {

            if ($('.hotel_search input[name="checkin"]').val() == '') {
                alert('<?php echo JText::_('COM_BOOKPRO_START_DATE_IS_REQUIRED_FIELD')?>');
                $('.hotel_search input[name="checkin"]').focus();
                return false;
            }
            if ($('.hotel_search input[name="checkout"]').val() == '') {
                alert('<?php echo JText::_('COM_BOOKPRO_END_DATE_IS_REQUIRED_FIELD')?>');
                $('.hotel_search input[name="checkout"]').focus();
                return false;
            }
            var dateObject = $('.hotel_search #checkin').datepicker("getDate");
            var dateString = $.datepicker.formatDate("yy-mm-dd", dateObject);

            var room_checkin_date_timestamp=new Date(dateString).getTime();

            var dateObject = $('.hotel_search #checkout').datepicker("getDate");
            var dateString = $.datepicker.formatDate("yy-mm-dd", dateObject);

            var room_checkout_date_timestamp=new Date(dateString).getTime();
            if(room_checkin_date_timestamp>=room_checkout_date_timestamp )
            {
                alert('<?php echo JText::_('COM_BOOKPRO_CHECKIN_DATE_MUST_SMALLLER_CHECKOUT_DATE')?>');
                return false;
            }

            var a_url = "<?php echo JUri::base() ?>index.php";
            $.ajax({
                type: "GET",
                url: a_url,
                data:{
                    option:'com_bookpro',
                    id:joombookpro_account_id,
                    curent_url:document.domain,
                    controller:'widgethotelbooking',
                    task:'showwidgetform',
                    checkin:$("#checkin").val(),
                    checkout:$("#checkout").val(),
                    joombookpro_account_id:'<?php echo JRequest::getVar('joombookpro_account_id ') ?>'

                },
                crossDomain: true,
                async: false,
                dataType: "jsonp",
                contentType: "application/json",
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
                success: function(data) {
                    $.when($.getScript("<?php echo JUri::base() ?>components/com_bookpro/assets/js/widgetbookpro/widgethotelbooking_script.js"), $.Deferred(function (deferred) {
                        $(deferred.resolve);
                    })).done(function () { 
                        $('#widgetbookpro .widgetbookpro-loading').css({
                            display:"none"
                        });
                        $("#iDivdialog").html(data);
                        $("#iDivdialog").dialog({
                            modal: true,
                            width: 900,
                            height: 500,
                            title:'<?php echo JText::_("COM_BOOKPRO_ROOM_SELECT_AND_BOOK")?>',
                            open: function(event, ui) {

                            }
                        });
                    });
                }
            });


        });

    });
</script>
<div class="row-fluid hotel_search">
    <div class="control-group" >
        <label>
            <?php echo JText::_( 'COM_BOOKPRO_TRAVEL_SEARCH_CHECKIN' ); ?>
        </label>
        <div class="input-prepend">
            <input type="text" class="inputbox input_span12" name="checkin" id="checkin"
                value="<?php echo $this->cart->checkin_date ?>" size="13" maxlength="10" />
        </div>
    </div>
    <div  class="control-group">
        <label>
            <?php echo JText::_( 'COM_BOOKPRO_TRAVEL_SEARCH_CHECKOUT'); ?>
        </label>
        <div class="input-prepend">
            <input type="text" class="inputbox input_span12" name="checkout" id="checkout"
                value="<?php echo  $this->cart->checkout_date ?>" size="13" maxlength="10" />
        </div>
    </div>

    <div class="row-fluid">
        <button type="submit" class="hotel_search_button btn btn-large span4 offset7">
            <?php echo JText::_( 'COM_BOOKPRO_TRAVEL_SEARCH_SEARCH')?>
        </button>
    </div>
</div>
