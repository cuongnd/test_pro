<?php
    defined('_JEXEC') or die('Restricted access');

    JHtml::_('behavior.framework');
    JHtmlBehavior::modal('a.modal_hotel');
    AImporter::helper('image','bookpro','currency','form');
    jimport( 'joomla.html.html.tabs' );
    JHtml::_('jquery.framework');
    $action=JURI::base().'index.php';
    $cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
    $cart->load();
    $lang=JFactory::getLanguage();
    $local=substr($lang->getTag(),0,2); 
?>




<?php 
    defined('_JEXEC') or die('Restricted access');
    $cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
    $cart->load();
    $config=AFactory::getConfig();
    $document=JFactory::getDocument();
    $document->addScript(JURI::root().'components/com_bookpro/assets/js/jquery.ui.datepicker.js');
    AImporter::helper('currency');
?>
<script type="text/javascript">

    jQuery(function($) {
        //set tabs
        $( "#tabs_booking" ).tabs();

       




        $(".checkroomandsubmit").click(function(){

            var room =0;
            $('.roomselect').each(function(index){
                room=room+$(this).val();
            });
            if(room<=0)
            {    
                var btns = {};
                btns['yes'] = function(){ 
                    $(this).dialog("close");
                };
                $("<div><?php echo JText::_('COM_BOOKPRO_ROOM_SELECT_WARN') ?></div>").dialog({
                    autoOpen: true,
                    title: '<?php echo JText::_('COM_BOOKPRO_ROOM_CONFIRM') ?>',
                    modal:true,
                    buttons:btns
                });

                return false;
            }

            if(!$('.customer_detail').validation())
            {
                var btns = {};
                btns['yes'] = function(){ 
                    $(this).dialog("close");
                };
                $("<div><?php echo JText::_('COM_BOOKPRO_REQUIRED_FIELD') ?></div>").dialog({
                    autoOpen: true,
                    title: '<?php echo JText::_('COM_BOOKPRO_WARNING') ?>',
                    modal:true,
                    buttons:btns
                });


                $( "#tabs_booking" ).tabs("option", "active", 1);
                return true;
            }

            var btns = {};
            btns['yes'] = function(){ 
                $(this).dialog("close");
                $.ajax({
                    url:'<?php echo JUri::base() ?>index.php?option=com_bookpro&controller=widgethotelbooking&task=savehotelbooking&curent_url='+$.base64('encode', document.URL),
                    type: "GET",
                    data:$("#booking").serialize(),
                    crossDomain: true,
                    async: false,
                    dataType: "jsonp",
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

                    contentType: "application/json",

                    success:function(data){
                        $('#widgetbookpro .widgetbookpro-loading').css({
                            display:"none"
                        });
                        link='<?php echo JUri::base() ?>index.php?option=com_bookpro&view=formpayment&order_id='+data.order_id+'&'+data.session+'=1';
                        window.location = link;
                        console.log(data);
                        $( "#tabs_booking" ).tabs("option", "active", 0);

                    }
                });


            };
            btns['no'] = function(){ 
                // Do nothing
                $(this).dialog("close");

            };
            $("<div><?php echo Jtext::_('COM_BOOKPRO_ROOM_CONFIRM_YES_NO') ?></div>").dialog({
                autoOpen: true,
                title: '<?php echo Jtext::_('COM_BOOKPRO_ROOM_CONFIRM') ?>',
                modal:true,
                buttons:btns
            });


        });
    });

</script>
<form id="booking" action="index.php">
    <div class="room">
        <?php echo $this->loadTemplate( 'search' ); ?>
        <?php echo $this->loadTemplate( 'rooms' ); ?>

    </div>

    <input type="hidden" id="hotel_id" name="hotel_id" value="<?php echo $this->cart->hotel_id ?>"> 
    <div class="row-fluid">
        <div >
            <div class="text-right">
                <button class="btn checkroomandsubmit btn-primary" type="button"><?php echo JText::_('COM_BOOKPRO_BOOK') ?></button>
            </div>
        </div>
    </div>

    <div id="tabs_booking">
        <ul>
            <li><a href="#tabs-1"><?php echo JText::_('COM_BOOKPRO_BOOKING_INFORMATION') ?></a></li>
            <li><a href="#tabs-2"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_INFO') ?></a></li>

        </ul>
        <div id="tabs-1">
            <div class="roombookingdetail">
                <?php echo $this->loadTemplate( 'roombookingdetail' ); ?>
            </div>
        </div>
        <div id="tabs-2">
            <div class="customer_detail">
                <?php echo $this->loadTemplate( 'customerinfo' ); ?>
            </div>
        </div>

    </div>
   
</form>






