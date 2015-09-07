<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 23 2012-07-08 02:20:56Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');
AImporter::helper('currency', 'date');
BookProHelper::setSubmenu($set);
JToolBarHelper::save();
JToolBarHelper::cancel();
JHtml::_('behavior.formvalidation');

?>
<script type="text/javascript">

    jQuery(document).ready(function($) {

        $(document).on('click', '.sendemailagain', function() {
            $.ajax({
                type: "GET",
                url: 'index.php?option=com_bookpro&controller=order&task=ajax_sendemail',
                data: {
                    "order_id":<?php echo $this->order->id ?>
                },
                //data: $.param($('.frontTourForm.trip_acommodaton.' + $trip_acommodaton).find(':input')),
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
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });

                    //$('.tripprice.' + $trip_acommodaton).html(result);
                    //getajax_form_totaltripprice();
                }
            });
        });
        $(document).on('change', '#pay_status', function() {
            return;
            $.ajax({
                type: "GET",
                url: 'index.php?option=com_bookpro&controller=order&task=updatepaymentstatus',
                data: {
                    "id":<?php echo $this->order->id ?>,
                    "pay_status": $(this).val()
                },
                //data: $.param($('.frontTourForm.trip_acommodaton.' + $trip_acommodaton).find(':input')),
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
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });

                    //$('.tripprice.' + $trip_acommodaton).html(result);
                    //getajax_form_totaltripprice();
                }
            });
        });
        $(document).on('change', '#order_status', function() {
            return;
            $.ajax({
                type: "GET",
                url: 'index.php?option=com_bookpro&controller=order&task=updatestateorder',
                data: {
                    "id":<?php echo $this->order->id ?>,
                    "order_status": $(this).val()
                },
                //data: $.param($('.frontTourForm.trip_acommodaton.' + $trip_acommodaton).find(':input')),
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
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });

                    //$('.tripprice.' + $trip_acommodaton).html(result);
                    //getajax_form_totaltripprice();
                }
            });
        });
        $(document).on('change', 'input.posttrip', function() {
            return;
            $.ajax({
                type: "GET",
                url: 'index.php?option=com_bookpro&controller=order&task=updatehotelposttrip',
                data: {
                    "id": $(this).attr('data_id'),
                    "text": $(this).val()
                },
                //data: $.param($('.frontTourForm.trip_acommodaton.' + $trip_acommodaton).find(':input')),
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
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });

                    //$('.tripprice.' + $trip_acommodaton).html(result);
                    //getajax_form_totaltripprice();
                }
            });
        });
        $(document).on('change', 'input.pretrip', function() {
            return;
            $.ajax({
                type: "GET",
                url: 'index.php?option=com_bookpro&controller=order&task=updatehotelpretrip',
                data: {
                    "id": $(this).attr('data_id'),
                    "text": $(this).val()
                },
                //data: $.param($('.frontTourForm.trip_acommodaton.' + $trip_acommodaton).find(':input')),
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
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });

                    //$('.tripprice.' + $trip_acommodaton).html(result);
                    //getajax_form_totaltripprice();
                }
            });
        });
    });
</script>
<div class="widgetbookpro-loading"></div>
<div class="span10">
    <form action="index.php" method="post" name="adminForm" id="adminForm" >
        <div class="form-horizontal">
            <?php echo $this->loadTemplate(strtolower($this->order->type)) ?>
            <?php
            //$this->addTemplatePath(JPATH_COMPONENT_BACK_END . DS . 'views' . DS . 'customer' . DS . 'tmpl');
            // echo $this->loadTemplate('customer');
            ?>
        </div>
    </form>
</div>

