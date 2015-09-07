<?php
/**

 * @package 	Bookpro

 * @author 		Nguyen Dinh Cuong

 * @link 		http://ibookingonline.com

 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong

 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html

 * @version 	$Id: default.php  23-06-2012 23:33:14

 * */
// No direct access to this file

defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html.select');
JHtmlBehavior::modal('a.amodal');
AImporter::helper('form', 'currency');
AImporter::css('bookpro');

AImporter::model('order');
$modelorder = new BookProModelOrder();
$tour = $modelorder->getObjectTourByOrderID($this->order->id);
if ($tour->deposit) {
    $deposit_amount = ($tour->deposit_amount * $this->order->total) / 100;
}
?>
<form name="frontForm" method="post" action="index.php" id="paymentForm">
    <div class="row-fluid">
        <div class="form-inline">
            <label class="checkbox inline check_payment"> <input name="deposit"	type="radio" id="fullpayment" value="0"> <?php echo CurrencyHelper::formatprice($this->order->total); ?>
                ( Full Payment )
            </label> 
            <?php if ($tour->deposit) { ?>
                <label class="checkbox inline check_payment"> <inputname="deposit" type="radio" id="inlineCheckbox3" value="<?php echo $deposit_amount ?>"> <?php echo CurrencyHelper::formatprice($deposit_amount); ?>
                        ( Deposit )
                </label>
            <?php } ?>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span6">
            <h2 class="block_head">
                <span> <?php echo JText::_('COM_BOOKPRO_PAYMENT_SELECT') ?>
                </span>
            </h2>
            <?php
            if ($this->plugins) {
                foreach ($this->plugins as $plugin) {
                    ?>
                    <input value="<?php echo $plugin->element; ?>" class="payment_plugin"
                           onclick="getPaymentForm('<?php echo $plugin->element; ?>', 'payment_form_div');"
                           name="payment_plugin" type="radio"
                           <?php echo (!empty($plugin->checked)) ? "checked" : ""; ?> />

                    <?php
                    $params = new JRegistry;
                    $params->loadString($plugin->params);
                    $title = $params->get('display_name', '');
                    if (!empty($title)) {
                        echo $title;
                    } else {
                        echo JText::_($plugin->name);
                    }
                    ?>
                    <br />
                    <?php
                }
            }
            ?>

            <div id='payment_form_div' style="padding-top: 10px;">
                <?php
                if (!empty($this->payment_form_div)) {
                    echo $this->payment_form_div;
                }
                ?>

            </div>
            <div class="form-inline">
                <input type="checkbox" value="30" name="license_confirm"
                       checked="checked" id='license_confirm' class="controls"> <label
                       class="controls" for="term_condition"> <a
                        href="index.php?option=com_content&id=<?php echo $this->config->termContent ?>&view=article&tmpl=component&task=preview"
                        class='amodal' rel="{handler: 'iframe', size: {x: 680, y: 370}}"><?php echo JText::_("COM_BOOKPRO_ACCEPT_TERM") ?>
                    </a>
                </label>
            </div>
            <br />

            <div class="center-button">
                <input class="btn btn-primary" type="submit"
                       value="<?php echo JText::_('COM_BOOKPRO_PAYNOW') ?>"
                       name="btnSubmit" id="submitpayment" />
            </div>
            <?php echo FormHelper::bookproHiddenField(array('controller' => 'payment', 'task' => 'process', 'Itemid' => JRequest::getVar('Itemid'), 'order_id' => $this->order->id)) ?>
        </div>
        <!-- 
        <div class="span6">

            <?php ///echo $this->loadTemplate('cart') ?>
        </div>
         -->
    </div>
</form>

<script type="text/javascript">

                       function getPaymentForm(element, container) {

                           jQuery(document).ready(function($) {
                               container = '#' + container;
                               $.ajax({
                                   url: siteURL + 'index.php?option=com_bookpro&controller=payment&task=getPaymentForm&format=raw&payment_element=' + element,
                                   type: 'post',
                                   cache: false,
                                   contentType: 'application/json; charset=utf-8',
                                   dataType: 'json',
                                   beforeSend: function() {

                                   },
                                   complete: function() {

                                   },
                                   success: function(json) {
                                       $(container).html(json.msg);
                                       return true;
                                   }
                               });
                           });
                       }

                       jQuery(document).ready(function($) {




                           $("#submitpayment").click(function() {

                               if (jQuery("input:radio[name='payment_plugin']").is(":checked") == false)
                               {
                                   alert("<?php echo JText::_('COM_BOOKPRO_SELECT_PAYMENT_METHOD_WARN') ?>");
                                   return false;
                               }
                               if (jQuery('#license_confirm').is(':checked') == false) {
                                   alert("<?php echo JText::_('COM_BOOKPRO_ACCEPT_TERM_WARN') ?>");
                                   return false;
                               }
                               $("#paymentForm").submit();
                           });

                           $("#couponbt").click(function() {

                               if ($("input:text[name=coupon]").val()) {
                                   $("input:hidden[name=controller]").val('order');
                                   $("input:hidden[name=task]").val('applycoupon');
                                   $("#paymentForm").submit();
                               }
                               else {
                                   alert('Empty coupon code');
                                   return false;
                               }

                           });

                       });


</script>

