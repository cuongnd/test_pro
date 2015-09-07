<?php 
    defined('_JEXEC') or die('Restricted access');
    $cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
    $cart->load();
    $config=AFactory::getConfig();
    $document=JFactory::getDocument();
    $document->addScript(JURI::root().'components/com_bookpro/assets/js/jquery.ui.datepicker.js');
    AImporter::helper('currency','room');
?>
<script>

    jQuery(document).ready(function($) {

        $( "#room_checkin_date" ).datepicker({
            dateFormat:"dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel:false,
            minDate: new Date(),
            showOn: "button",
            onSelect : function() {

                var selected = $('#room_checkin_date').datepicker('getDate');
                selected.setDate(selected.getDate()+1);
                $('#room_checkout_date').datepicker('setDate', selected);
                $( "#room_checkout_date" ).datepicker("option", {
                    minDate: selected
                });
            },
            buttonText:'<i class="icon-calendar"></i>'
        });
        $( "#room_checkout_date" ).datepicker({
            dateFormat:"dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel:false,
            showOn: "button",
            minDate: new Date(),
            buttonText:'<i class="icon-calendar"></i>'
        });
        $("#check_available").click(function(){
            loadAvailableRoom();
        });
        $(".ui-datepicker-trigger").addClass(' btn-small');

        loadAvailableRoom();

        function loadAvailableRoom(){
            var id = $("#hotel_id").val();
            var checkin = $("#room_checkin_date").val();
            var checkout = $("#room_checkout_date").val();
            $.ajax({
                url:'index.php?option=com_bookpro&controller=hotel&task=checkAvailable&hotel_id='+id+'&checkin='+checkin+'&checkout='+checkout+'&tmpl=component&format=raw',
                beforeSend: function(){
                    jQuery("#roomlist").html('<div align="center"><img src="components/com_bookpro/assets/images/loader.gif" /><div>');
                },
                success:function(data){
                    $('#roomlist').html(data);
                }
            });
        }	


    });
</script>
<div class="room">
    <h2>
        <span style="text-transform:capitalize;"><?php echo JText::_("COM_BOOKPRO_ROOM_SELECT_AND_BOOK")?>
        </span>
    </h2>
    <div class="breadcrumb searchcheckincheckout bookbar form-inline">

        <label style="padding: 0 5px;" ><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKIN')?> </label>
        <div id="calendar_checkin" class="input-append">	
            <input type="text" name="checkin_date" id="room_checkin_date" class="input-small" value="<?php echo $cart->checkin_date; ?>" />
        </div>

        <label style="padding: 0 5px;"><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKOUT')?> </label>
        <input type="text" name="checkout_date" class="input-small" id="room_checkout_date" value="<?php echo $cart->checkout_date; ?>" />

        <button class="btn btn-primary " type="button" id="check_available"><?php echo JText::_('COM_BOOKPRO_ROOM_AVAILABLE_CHECK') ?></button>
    </div>
    <!-- Display rooms -->
    <div id="roomlist" class="roomlist">



    </div>
    <div id="facility_hotel">
        <div class="pull-right form-horizontal">
            <?php /* ?>
                <div class="control-group">
                <label class="control-label"><?php echo JText::_('COM_BOOKPRO_HOTEL_SELECT_FACILITY') ?>:</label>
                <div class="controls form-inline">
                <?php echo $this->facilitybox; ?>
                </div>

                </div>
            <?php */ ?>


        </div>

    </div>

</div>
<style type="text/css">
 
</style>

