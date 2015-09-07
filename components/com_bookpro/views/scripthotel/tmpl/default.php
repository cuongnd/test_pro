<?php

    defined ( '_JEXEC' ) or die ( 'Restricted access' );
    jimport ( 'joomla.html.html' );
    AImporter::helper ( 'date', 'bookpro', 'currency', 'hotel' );
    JHTML::_ ( 'behavior.tooltip' );
    $doc=JFactory::getDocument();
    $doc->addStyleSheet(JUri::root().'components/com_bookpro/assets/css/view-scripthotel.css');
    $itemsCount = count ( $this->items );

?>
<script type="text/javascript">            
    var script='';
    script+='<script type="text/javascript">';

    script+=' var joombookpro_account_id = "hotel_id";';
    script+='var remote_url="<?php echo JUri::root() ?>";';
    script+='(function() {';
    script+='var joombookpro = document.createElement("script");';
    script+='joombookpro.type = "text/javascript";';
    script+='joombookpro.async = true;';
    script+='joombookpro.src =';
    script+='remote_url+';
    script+='"components/com_bookpro/assets/js/widgetbookpro/widgetbookpro.js";';
    script+='var script_node = document.getElementsByTagName("script")[0];';
    script+=' script_node.parentNode.insertBefore(joombookpro, script_node);';
    script+=' })();';
    script+=' <\/script>';
    script+=' <div id="widgetbookpro"></div>';




    jQuery(document).ready(
        function ($) {
            $('body').delegate('.genera_script', 'click', function() {

                 hotel_id=  $('#hotel').val();
                if($('#hotel').val()!=0)
                {
                    $.ajax({
                        url:'<?php echo JUri::base() ?>index.php?option=com_bookpro&controller=widgethotelbooking&task=checkexistsroom',
                        type: "GET",
                        data:{
                            hotel_id:hotel_id
                        },
                        crossDomain: true,
                        async: false,
                        dataType: "json",
                        beforeSend: function() {
                            $('.bookpro-loading').css({
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

                        success:function(data_json){
                            $('.bookpro-loading').css({
                                display:"none"
                            });
                            $('.roombookingdetail').html(data_json);
                            if(data_json.toInt()>=1)
                                $('#output_script').val(script.replace("hotel_id",hotel_id)).select();
                            else
                                alert('<?php echo Jtext::_('COM_BOOKPRO_INPUT_ROOM') ?>');

                        }
                    });

                }
                else
                    alert('<?php echo  JText::_('COM_BOOKPRO_SELECT_HOTEL') ?>');
            });
        }
    );
</script>

<div class="bookpro-loading"></div>
<div class="row-fluid">
    <?php
        $layout = new JLayoutFile('suppliermenu', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
        $html = $layout->render(array());
        echo $html;
    ?>

    <fieldset>
        <legend><?php echo JText::_('COM_BOOKPRO_SCRIPT_HOTEL_MANAGER'); ?></legend>

        <?php
            echo $this->getHotelSelect();
        ?>


        <button class="genera_script btn btn-medium btn-success" style="margin-bottom: 10px;" type="button">
            <span class="icon-new  icon-white"></span><?php echo JText::_('COM_BOOKPRO_HOTEL_GENERATE'); ?>
        </button>
        <div  style="padding: 10px;">
            <?php echo JText::_('COM_BOOKPRO_HOTEL_HELP_GENERATE'); ?>
        </div>
        <textarea  style="width: 100%; height: 150px;" id="output_script">
        </textarea>
    </fieldset>
</div>

