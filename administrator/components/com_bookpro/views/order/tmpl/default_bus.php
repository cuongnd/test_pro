<?php
defined('_JEXEC') or die('Restricted access');
require_once JPATH_ROOT.'/administrator/components/com_bookpro/helpers/paystatus.php';
include_once(JPATH_LIBRARIES . DS . 'joomla' . DS . 'html' . DS . 'html' . DS . 'select.php');
AImporter::model('bustrips', 'bustrip', 'orderinfos', 'airport','passengers');
AImporter::css('transport');
$infoModel = new BookProModelOrderinfos();
$param = array('order_id' => $this->order->id);
$infoModel->init($param);
$orderInfo = $infoModel->getData();

$passengersModel = new BookProModelPassengers();
$passengersModel->init(array(
    'order_id' => $this->order->id,
    'order_Dir' => 'ASC'
));
$passengers = $passengersModel->getData();
$passenger=$passengers[0];
$params=$passenger->params;
$params=json_decode($params);
$passenger->params=$params;
$config			= JComponentHelper::getParams('com_bookpro');
$company_name	= $config->get('company_name');
$logo			= $config->get('company_logo');
$address		= $config->get('company_address');
?>


<div class="bpblock">
    <h2>
        <span><?php echo JText::_("COM_BOOKPRO_BOOKING_INFORMATION") ?> </span>
    </h2>

    <form id="tourBookForm" name="tourBookForm" action="index.php"
          method="post" onSubmit="return validateForm()">
        <div  id="booking_detail">
            <div class="row-fluid">
                <div class="span4">
                    <div>
                        <?php if($logo){?>
                            <img alt="" src="<?php echo JUri::root().$logo; ?>" width="220px;">
                        <?php }?>
                    </div>
                    <div><?php echo $company_name; ?></div>
                    <div><?php echo $address; ?></div>
                    <div>
                        <h4><?php echo JText::_("COM_BOOKPRO_BOOKING_BILL_TO")?></h4>
                        <table class="table">
                            <tr>
                                <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_NAME'); ?></th>
                                <td><?php echo $this->customer->lastname. ' '.$this->customer->firstname; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?></th>
                                <td><?php echo $this->customer->email	?></td>
                            </tr>
                            <tr>
                                <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE'); ?></th>
                                <td><?php echo $this->customer->email	?></td>
                            </tr>
                            <tr>
                                <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE'); ?></th>
                                <td><?php echo $this->customer->mobile;?></td>
                            </tr>
                        </table>
                    </div>

                </div>
                <div class="span3 offset5">
                    <table  class="table table-bordered">
                        <tr>
                            <td ><?php echo JText::_('COM_BOOKPRO_INVOICE_NUMBER'); ?>:</td>
                            <td ><?php echo $this->order->order_number; ?>
                            </td>
                        </tr>
                        <tr>
                            <td ><?php echo JText::_('COM_BOOKPRO_INVOICE_DATE')?>:</td>
                            <td ><?php echo JHtml::_('date',$this->order->created,'d-m-Y'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td ><?php echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_STATUS')?>:</td>
                            <td ><span
                                    class="btn btn-success"> <?php echo PayStatus::format($this->order->pay_status) ?>
								</span></td>
                        </tr>
                    </table>

                </div>
            </div>
            <h2>
                <span><?php echo JText::_("Booking detail") ?> </span>
            </h2>

            <div class="row-fluid">
                <div class="span6">
                    <table class="table" style="width: 100%">
                        <tr>
                            <th><?php echo JText::_('Pickup location') ?></th>
                            <td><?php echo $passenger->params->pickupplace ?>-<?php echo $passenger->params->bustrip->dest_from_title ?>(<?php echo $passenger->params->bustrip->dest_from_parent_title ?>)</td>
                        </tr>
                        <tr>
                            <th><?php echo JText::_('Pickup Date time') ?></th>
                            <td><?php echo $passenger->params->pickup_time ?> <?php echo JFactory::getDate($passenger->start)->format('Y-m-d') ?></td>
                        </tr>
                        <tr>
                            <th><?php echo JText::_('drop location') ?></th>
                            <td><?php echo $passenger->params->dropoffplace ?> -<?php echo $passenger->params->bustrip->dest_to_title ?>(<?php echo $passenger->params->bustrip->dest_to_parent_title ?>)</td>
                        </tr>
                        <tr>
                            <th><?php echo JText::_('Drop Date time') ?></th>
                            <td><?php echo $passenger->params->drop_off_time ?> <?php echo JFactory::getDate($passenger->start)->format('Y-m-d') ?></td>
                        </tr>
                    </table>
                </div>
                <div class="span6">
                    <h4 style="text-align: center"><?php echo $passenger->params->bustrip->bus_title ?></h4>
                    <div style="text-align: center">
                        <img src="<?php echo JUri::root().$passenger->params->bustrip->bus_image ?>">
                    </div>
                </div>
            </div>






        </div>
        <!--
	<div class="center-button">
		<input type="submit" name="update" <?php echo $disable ?>
			value="<?php echo JText::_('COM_BOOKPRO_SAVE') ?>" class="button" /> <a
			href="index.php?option=com_bookpro&view=mypage"><input type="button"
			name="Close" value="<?php echo JText::_('Back') ?>" class="button" />
		</a>
	</div>
 -->

        <input type="hidden" name="option" value="com_bookpro"/> <input
            type="hidden" name="controller" value="order"/> <input type="hidden"
                                                                   name="task" value="updateorder"/> <input
            type="hidden"
            name="order_id" value="<?php echo $this->order->id; ?>"/>


    </form>
</div>

<script type="text/javascript">


    function validateForm() {

        var form = document.tourBookForm;
        var countday = parseInt('<?php echo $count_day ?>');
        var adult = form.adult;
        var children = form.children;
        var pax = adult.options[adult.selectedIndex].value + children.options[children.selectedIndex].value;
        if (pax < form.pax_min.value) {
            alert('<?php echo JText::_('COM_BOOKPRO_PACKAGE_PAX_WARN')?>');
            return false;
        }

        if (form.depart.value == "") {
            alert('<?php echo JText::_('COM_BOOKPRO_DEPART_DATE_WARN')?>');
            form.depart.focus();
            return false;

        }
        if (countday <= 2) {
            alert('<?php echo JText::_('COM_BOOKPRO_DEPART_DATE_OVER')?>');
            return false;
        }

        return true;
    }
</script>
<style type="text/css">
    #booking_detail
    {
        border: 1px #ccc solid;
        border-radius: 3px;
        padding: 20px;
    }
    #booking_detail table th
    {
        background: none;
        color: #000;
    }
</style>
