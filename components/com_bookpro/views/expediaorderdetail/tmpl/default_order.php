<?php
    defined( '_JEXEC' ) or die( 'Restricted access' );
    $RateInfos=$this->itin['Itinerary']['HotelConfirmation']['RateInfos'];

?>
<h2>
    <?php echo JText::_('COM_BOOKPRO_ORDER_SUMARY'); ?>
</h2>



<table class="table table-bordered">
    <tr>
        <th><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER'); ?>:
        </th>
        <td><span class="total"><?php echo $this->itin['Itinerary']['itineraryId']; ?></span></td>

    </tr>
    <tr>
        <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_NAME'); ?>:
        </th>
        <td><?php echo $this->itin['Itinerary']['Customer']['firstName']. ' '.$this->itin['Itinerary']['Customer']['lastName']; ?></td>

    </tr>

    <tr>
        <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>:
        </th>
        <td><?php echo $this->itin['Itinerary']['Customer']['email']	?></td>
    </tr>

    <tr>
        <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE'); ?>:
        </th>
        <td><?php echo  $this->itin['Itinerary']['Customer']['homePhone'];?></td>

    </tr>


    <tr>
        <th><?php echo JText::_('Booking Advance'); ?>:
        </th>
        <td><?php echo CurrencyHelper::formatprice($RateInfos[RateInfo]['ChargeableRateInfo']['@total']); ?></td>
    </tr>
    <tr>
        <th><?php echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_STATUS'); ?>:
        </th>
        <!--  -->
        <td><?php
                echo '<span class="label label-warning">'.JText::_('COM_BOOKPRO_PAYMENT_STATUS_'.$this->order->pay_status).'</span>&nbsp;';

                if($this->order->pay_status!='SUCCESS' ) {
                    echo JHtml::link(JURI::base().'index.php?option=com_bookpro&view=formpayment&order_id='.$this->order->id.'&'.JSession::getFormToken().'=1', JText::_('COM_BOOKPRO_MAKE_PAYMENT'),'class="btn"');
            }?>

        </td>
    </tr>

    <tr>

        <th><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_TIME'); ?>:
        </th>
        <td><?php echo JHtml::_('date',$this->order->created); ?></td>
    </tr>
</table>
