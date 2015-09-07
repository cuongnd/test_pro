<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.framework');
JHtmlBehavior::modal('a.modal_hotel');
AImporter::helper('image', 'bookpro', 'currency', 'form');
JHtml::_('jquery.framework');
$action = JURI::base() . 'index.php';
$cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
$cart->load();
$lang = JFactory::getLanguage();
$local = substr($lang->getTag(), 0, 2);
?>




<?php
defined('_JEXEC') or die('Restricted access');
$cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
$cart->load();
$config = AFactory::getConfig();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/css/jquery-ui.css');
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/css/view-supplierbooking.css');
$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/jquery-ui.js');
$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/jquery.ui.datepicker.js');
$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/jquery-validate-supplierbooking.js');

AImporter::helper('currency');
?>
<script type="text/javascript">

    jQuery(function($) {
        //set tabs



        $(".checkroomandsubmit").click(function() {

            var room = 0;
            $('.roomselect').each(function(index) {
                room = room + $(this).val();
            });
            if (room <= 0)
            {
                var btns = {};
                btns['yes'] = function() {
                    $(this).dialog("close");
                };
                $("<div><?php echo JText::_('COM_BOOKPRO_ROOM_SELECT_WARN') ?></div>").dialog({
                    autoOpen: true,
                    title: '<?php echo JText::_('COM_BOOKPRO_ROOM_CONFIRM') ?>',
                    modal: true,
                    buttons: btns
                });

                return false;
            }

            if (!$('.customer_detail').validation())
            {
                var btns = {};
                btns['yes'] = function() {
                    $(this).dialog("close");
                };
                $("<div><?php echo JText::_('COM_BOOKPRO_REQUIRED_FIELD') ?></div>").dialog({
                    autoOpen: true,
                    title: '<?php echo JText::_('COM_BOOKPRO_WARNING') ?>',
                    modal: true,
                    buttons: btns
                });

                $('#tabs_bookingTabs li:eq(1) a').tab('show');
                return true;
            }

            var btns = {};
            btns['yes'] = function() {
                $(this).dialog("close");
                $.ajax({
                    type: "GET",
                    url: 'index.php?option=com_bookpro&controller=supplierbooking&task=savehotelbooking',
                    data: $("#booking").serialize(),
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
                    success: function(result) {

                        console.log(result);
                        $('.widgetbookpro-loading').css({
                            display: "none"
                        });
                        var btns = {};
                        btns['yes'] = function() {
                            $(this).dialog("close");
                        };
                        $("<div><?php echo JText::_('COM_BOOKPRO_BOOKING_SUCCESS') ?></div>").dialog({
                            autoOpen: true,
                            title: '<?php echo JText::_('COM_BOOKPRO_MESAGER') ?>',
                            modal: true,
                            buttons: btns
                        });
                        $('#tabs_bookingTabs li:eq(0) a').tab('show');
                        //$( "#tabs_booking" ).tabs("option", "active", 0);
                    }
                });





            };
            btns['no'] = function() {
                // Do nothing
                $(this).dialog("close");

            };
            $("<div><?php echo Jtext::_('COM_BOOKPRO_ROOM_CONFIRM_YES_NO') ?></div>").dialog({
                autoOpen: true,
                title: '<?php echo Jtext::_('COM_BOOKPRO_ROOM_CONFIRM') ?>',
                modal: true,
                buttons: btns
            });


        });
    });

</script>
<div class="row-fluid">
    <?php
    $layout = new JLayoutFile('suppliermenu', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
    $html = $layout->render(array());
    echo $html;
    ?>
</div>
<div class="widgetbookpro-loading"></div>
<form id="booking" action="index.php">
    <div class="room">
        <?php echo $this->loadTemplate('search'); ?>
        <h3><?php echo Jtext::_('COM_BOOKPRO_ROOM_AVAILABILITY') ?></h3>
        <?php echo $this->loadTemplate('rooms'); ?>

    </div>

    <input type="hidden" id="hotel_id" name="hotel_id"
           value="<?php echo $this->cart->hotel_id ?>">
    <div class="row-fluid">
        <div>
            <div class="text-right" style="padding-bottom: 10px;">
                <button class="btn checkroomandsubmit btn-primary" type="button">
                    <?php echo JText::_('COM_BOOKPRO_CREATE_BOOKING') ?>
                </button>
            </div>
        </div>
    </div>
    <?php echo JHtml::_('bootstrap.startTabSet', 'tabs_booking', array('active' => 'tab1')); ?> 
    <?php echo JHtml::_('bootstrap.addTab', 'tabs_booking', 'tab1', JText::_('COM_BOOKPRO_BOOKING_INFORMATION')); ?> 
    <div class="roombookingdetail">
        <?php echo $this->loadTemplate('roombookingdetail'); ?>
    </div>
    <?php echo JHtml::_('bootstrap.endTab'); ?> 
    <?php echo JHtml::_('bootstrap.addTab', 'tabs_booking', 'tab2', JText::_('COM_BOOKPRO_CUSTOMER_INFO')); ?> 
    <div class="customer_detail">
        <?php echo $this->loadTemplate('customerinfo'); ?>
    </div>
    <?php echo JHtml::_('bootstrap.endTab'); ?>
    <?php echo JHtml::_('bootstrap.endTabSet'); ?>


</form>






